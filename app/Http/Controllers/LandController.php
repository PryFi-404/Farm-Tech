<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLandRequest;
use App\Models\Farmer;
use App\Models\Land;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandController extends Controller
{
    /**
     * Show all land records (optionally filtered by farmer)
     */
    public function index(Request $request)
    {
        $query = Land::with('farmer.user')
            ->when($request->farmer_id, fn($q) => $q->where('farmer_id', $request->farmer_id))
            ->when($request->soil_type, fn($q) => $q->where('soil_type', $request->soil_type))
            ->when($request->ownership_type, fn($q) => $q->where('ownership_type', $request->ownership_type))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $farmers      = Farmer::with('user')->get();
        $soilTypes    = Land::distinct()->pluck('soil_type')->filter()->sort()->values();
        $totalArea    = Land::sum('area_acres');

        return view('lands.index', compact('query', 'farmers', 'soilTypes', 'totalArea'));
    }

    /**
     * Show form to add a new land parcel
     */
    public function create(Request $request)
    {
        // Pre-select farmer if coming from farmer profile
        $farmers       = Farmer::with('user')->get();
        $selectedFarmer = $request->farmer_id
            ? Farmer::with('user')->find($request->farmer_id)
            : null;

        return view('lands.create', compact('farmers', 'selectedFarmer'));
    }

    /**
     * Store new land record
     */
    public function store(StoreLandRequest $request)
    {
        $data = $request->validated();

        // Handle document upload (PDF or image)
        if ($request->hasFile('document')) {
            $data['document'] = $request->file('document')->store('land-documents', 'public');
        }

        Land::create($data);

        return redirect()->route('farmers.show', $request->farmer_id)
            ->with('success', 'Land record added successfully!');
    }

    /**
     * Edit land record
     */
    public function edit(Land $land)
    {
        $farmers = Farmer::with('user')->get();
        return view('lands.edit', compact('land', 'farmers'));
    }

    /**
     * Update land record
     */
    public function update(StoreLandRequest $request, Land $land)
    {
        $data = $request->validated();

        if ($request->hasFile('document')) {
            // Remove old document
            if ($land->document) {
                Storage::disk('public')->delete($land->document);
            }
            $data['document'] = $request->file('document')->store('land-documents', 'public');
        }

        $land->update($data);

        return redirect()->route('farmers.show', $land->farmer_id)
            ->with('success', 'Land record updated!');
    }

    /**
     * Delete land record
     */
    public function destroy(Land $land)
    {
        $farmerId = $land->farmer_id;

        if ($land->document) {
            Storage::disk('public')->delete($land->document);
        }

        $land->delete();

        return redirect()->route('farmers.show', $farmerId)
            ->with('success', 'Land record deleted.');
    }
}
