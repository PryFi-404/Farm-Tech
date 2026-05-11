<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Models\Farmer;
use App\Models\Scheme;
use App\Models\SchemeApplication;
use Illuminate\Http\Request;

class SchemeApplicationController extends Controller
{
    /**
     * All applications with filters + approve/reject actions
     */
    public function index(Request $request)
    {
        $query = SchemeApplication::with(['farmer.user', 'scheme'])
            // 🔒 Farmers only see their OWN applications
            ->when(auth()->user()->isFarmer(), fn($q) =>
                $q->where('farmer_id', auth()->user()->farmer?->id)
            )
            ->when($request->status,    fn($q) => $q->where('status', $request->status))
            ->when($request->scheme_id, fn($q) => $q->where('scheme_id', $request->scheme_id))
            ->when($request->farmer_id && !auth()->user()->isFarmer(), fn($q) =>
                $q->where('farmer_id', $request->farmer_id)
            )
            ->when($request->search, fn($q) =>
                $q->whereHas('farmer.user', fn($u) =>
                    $u->where('name', 'like', "%{$request->search}%")
                )
            )
            ->latest()
            ->paginate(12)
            ->withQueryString();

        // Stats also scoped for farmers
        $baseQuery = auth()->user()->isFarmer()
            ? SchemeApplication::where('farmer_id', auth()->user()->farmer?->id)
            : new SchemeApplication;

        $stats = [
            'total'    => (clone $baseQuery)->count(),
            'pending'  => (clone $baseQuery)->where('status', 'pending')->count(),
            'approved' => (clone $baseQuery)->where('status', 'approved')->count(),
            'rejected' => (clone $baseQuery)->where('status', 'rejected')->count(),
            'disbursed'=> (clone $baseQuery)->sum('subsidy_amount'),
        ];

        $schemes = Scheme::orderBy('name')->get();
        $farmers = Farmer::with('user')->get();

        return view('applications.index', compact('query', 'stats', 'schemes', 'farmers'));
    }

    /**
     * Farmer/officer applies for a scheme
     */
    public function create(Request $request)
    {
        $schemes = Scheme::where('is_active', true)->orderBy('name')->get();

        // If logged-in user IS a farmer → force-select their own profile
        if (auth()->user()->isFarmer()) {
            $ownFarmer      = auth()->user()->farmer;
            $farmers        = collect([$ownFarmer]); // Only themselves
            $selectedFarmer = $ownFarmer;
        } else {
            // Admin / Officer can apply on behalf of any farmer
            $farmers        = Farmer::with('user')->get();
            $selectedFarmer = $request->farmer_id
                ? Farmer::with('user')->find($request->farmer_id)
                : null;
        }

        $selectedScheme = $request->scheme_id
            ? Scheme::find($request->scheme_id)
            : null;

        return view('applications.create', compact('schemes', 'farmers', 'selectedScheme', 'selectedFarmer'));
    }

    /**
     * Submit application
     */
    public function store(StoreApplicationRequest $request)
    {
        // 🔒 Security: If farmer role, force their own farmer_id (ignore submitted value)
        $farmerId = auth()->user()->isFarmer()
            ? auth()->user()->farmer->id
            : $request->farmer_id;

        // Prevent duplicate applications for same scheme
        $alreadyApplied = SchemeApplication::where('farmer_id', $farmerId)
            ->where('scheme_id', $request->scheme_id)
            ->exists();

        if ($alreadyApplied) {
            return back()
                ->withInput()
                ->with('error', 'You have already applied for this scheme.');
        }

        SchemeApplication::create([
            'farmer_id'    => $farmerId,
            'scheme_id'    => $request->scheme_id,
            'remarks'      => $request->remarks,
            'applied_date' => $request->applied_date,
            'status'       => 'pending',
        ]);

        return redirect()->route('applications.index')
            ->with('success', 'Application submitted successfully!');
    }

    /**
     * Application detail view
     */
    public function show(SchemeApplication $application)
    {
        $application->load(['farmer.user', 'farmer.lands', 'scheme', 'reviewedBy']);

        return view('applications.show', compact('application'));
    }

    /**
     * ✅ APPROVE application
     */
    public function approve(Request $request, SchemeApplication $application)
    {
        $request->validate([
            'subsidy_amount' => ['required', 'numeric', 'min:1'],
            'remarks'        => ['nullable', 'string', 'max:500'],
        ]);

        $application->update([
            'status'         => 'approved',
            'subsidy_amount' => $request->subsidy_amount,
            'remarks'        => $request->remarks,
            'approved_by'    => auth()->id(),
            'approved_date'  => now()->toDateString(),
        ]);

        return back()->with('success', '✅ Application approved! ₹' . number_format($request->subsidy_amount) . ' benefit assigned.');
    }

    /**
     * ❌ REJECT application
     */
    public function reject(Request $request, SchemeApplication $application)
    {
        $request->validate([
            'remarks' => ['required', 'string', 'max:500'],
        ]);

        $application->update([
            'status'       => 'rejected',
            'remarks'      => $request->remarks,
            'approved_by'  => auth()->id(),
            'approved_date'=> now()->toDateString(),
        ]);

        return back()->with('success', 'Application rejected with remarks.');
    }

    /**
     * Delete application
     */
    public function destroy(SchemeApplication $application)
    {
        $application->delete();
        return redirect()->route('applications.index')
            ->with('success', 'Application deleted.');
    }
}
