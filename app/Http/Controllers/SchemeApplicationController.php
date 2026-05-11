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
            ->when($request->status,    fn($q) => $q->where('status', $request->status))
            ->when($request->scheme_id, fn($q) => $q->where('scheme_id', $request->scheme_id))
            ->when($request->farmer_id, fn($q) => $q->where('farmer_id', $request->farmer_id))
            ->when($request->search,    fn($q) =>
                $q->whereHas('farmer.user', fn($u) =>
                    $u->where('name', 'like', "%{$request->search}%")
                )
            )
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total'    => SchemeApplication::count(),
            'pending'  => SchemeApplication::where('status', 'pending')->count(),
            'approved' => SchemeApplication::where('status', 'approved')->count(),
            'rejected' => SchemeApplication::where('status', 'rejected')->count(),
            'disbursed'=> SchemeApplication::sum('subsidy_amount'),
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
        // Pre-select scheme or farmer from URL
        $schemes = Scheme::where('is_active', true)->orderBy('name')->get();
        $farmers = Farmer::with('user')->get();

        $selectedScheme = $request->scheme_id
            ? Scheme::find($request->scheme_id)
            : null;

        $selectedFarmer = $request->farmer_id
            ? Farmer::with('user')->find($request->farmer_id)
            : null;

        return view('applications.create', compact('schemes', 'farmers', 'selectedScheme', 'selectedFarmer'));
    }

    /**
     * Submit application
     */
    public function store(StoreApplicationRequest $request)
    {
        // Prevent duplicate applications for same scheme
        $alreadyApplied = SchemeApplication::where('farmer_id', $request->farmer_id)
            ->where('scheme_id', $request->scheme_id)
            ->exists();

        if ($alreadyApplied) {
            return back()
                ->withInput()
                ->with('error', 'This farmer has already applied for this scheme.');
        }

        SchemeApplication::create([
            'farmer_id'    => $request->farmer_id,
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
