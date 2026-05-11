<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSchemeRequest;
use App\Models\Scheme;
use App\Models\SchemeApplication;
use Illuminate\Http\Request;

class SchemeController extends Controller
{
    /**
     * List all government schemes
     */
    public function index(Request $request)
    {
        $schemes = Scheme::withCount('applications')
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->when($request->status === 'active',   fn($q) => $q->where('is_active', true))
            ->when($request->status === 'inactive', fn($q) => $q->where('is_active', false))
            ->when($request->search, fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
            )
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $stats = [
            'total'    => Scheme::count(),
            'active'   => Scheme::where('is_active', true)->count(),
            'inactive' => Scheme::where('is_active', false)->count(),
            'applied'  => SchemeApplication::count(),
        ];

        return view('schemes.index', compact('schemes', 'stats'));
    }

    /**
     * Show create form (admin only)
     */
    public function create()
    {
        return view('schemes.create');
    }

    /**
     * Store new scheme
     */
    public function store(StoreSchemeRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        Scheme::create($data);

        return redirect()->route('schemes.index')
            ->with('success', 'Government scheme added successfully!');
    }

    /**
     * Scheme detail + its applications
     * 🔒 Farmers only see their own application for this scheme
     */
    public function show(Scheme $scheme)
    {
        $scheme->loadCount('applications');

        if (auth()->user()->isFarmer()) {
            // Farmer sees ONLY their own application for this scheme
            $farmerId     = auth()->user()->farmer?->id;
            $applications = $scheme->applications()
                ->with('farmer.user')
                ->where('farmer_id', $farmerId)
                ->latest()
                ->paginate(10);

            // Personal stats only
            $stats = [
                'total'    => $farmerId ? $scheme->applications()->where('farmer_id', $farmerId)->count() : 0,
                'approved' => $farmerId ? $scheme->applications()->where('farmer_id', $farmerId)->where('status', 'approved')->count() : 0,
                'pending'  => $farmerId ? $scheme->applications()->where('farmer_id', $farmerId)->where('status', 'pending')->count() : 0,
                'rejected' => $farmerId ? $scheme->applications()->where('farmer_id', $farmerId)->where('status', 'rejected')->count() : 0,
                'disbursed'=> $farmerId ? $scheme->applications()->where('farmer_id', $farmerId)->sum('subsidy_amount') : 0,
            ];
        } else {
            // Admin / Officer see all applications
            $applications = $scheme->applications()
                ->with('farmer.user')
                ->latest()
                ->paginate(10);

            $stats = [
                'total'    => $scheme->applications()->count(),
                'approved' => $scheme->applications()->where('status', 'approved')->count(),
                'pending'  => $scheme->applications()->where('status', 'pending')->count(),
                'rejected' => $scheme->applications()->where('status', 'rejected')->count(),
                'disbursed'=> $scheme->applications()->sum('subsidy_amount'),
            ];
        }

        return view('schemes.show', compact('scheme', 'applications', 'stats'));
    }

    /**
     * Edit form
     */
    public function edit(Scheme $scheme)
    {
        return view('schemes.edit', compact('scheme'));
    }

    /**
     * Update scheme
     */
    public function update(StoreSchemeRequest $request, Scheme $scheme)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $scheme->update($data);

        return redirect()->route('schemes.show', $scheme->id)
            ->with('success', 'Scheme updated!');
    }

    /**
     * Toggle active status (quick action)
     */
    public function toggleStatus(Scheme $scheme)
    {
        $scheme->update(['is_active' => !$scheme->is_active]);

        $status = $scheme->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Scheme {$status} successfully.");
    }

    /**
     * Delete scheme
     */
    public function destroy(Scheme $scheme)
    {
        $scheme->delete();
        return redirect()->route('schemes.index')
            ->with('success', 'Scheme removed.');
    }
}
