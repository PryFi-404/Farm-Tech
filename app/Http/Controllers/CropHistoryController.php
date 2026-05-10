<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCropHistoryRequest;
use App\Models\Crop;
use App\Models\CropHistory;
use App\Models\Farmer;
use Illuminate\Http\Request;

class CropHistoryController extends Controller
{
    /**
     * List all crop histories with filters
     */
    public function index(Request $request)
    {
        $query = CropHistory::with(['farmer.user', 'crop', 'land'])
            ->when($request->farmer_id, fn($q) => $q->where('farmer_id', $request->farmer_id))
            ->when($request->crop_id,   fn($q) => $q->where('crop_id', $request->crop_id))
            ->when($request->season,    fn($q) => $q->where('season', $request->season))
            ->when($request->year,      fn($q) => $q->where('year', $request->year))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $farmers = Farmer::with('user')->get();
        $crops   = Crop::orderBy('name')->get();
        $years   = CropHistory::distinct()->pluck('year')->sortDesc()->values();

        // Summary stats for top of page
        $totalProduction = CropHistory::sum('production_kg');
        $totalRevenue    = CropHistory::selectRaw('SUM(production_kg * selling_price) as rev')->value('rev');

        return view('crops.history', compact(
            'query', 'farmers', 'crops', 'years', 'totalProduction', 'totalRevenue'
        ));
    }

    /**
     * Show form to record a new crop season
     */
    public function create(Request $request)
    {
        $farmers = Farmer::with(['user', 'lands'])->get();
        $crops   = Crop::orderBy('name')->get();

        $selectedFarmer = $request->farmer_id
            ? Farmer::with(['user', 'lands'])->find($request->farmer_id)
            : null;

        return view('crops.create_history', compact('farmers', 'crops', 'selectedFarmer'));
    }

    /**
     * Save crop history record
     */
    public function store(StoreCropHistoryRequest $request)
    {
        CropHistory::create($request->validated());

        return redirect()->route('farmers.show', $request->farmer_id)
            ->with('success', 'Crop record saved successfully!');
    }

    /**
     * Delete crop history record
     */
    public function destroy(CropHistory $cropHistory)
    {
        $farmerId = $cropHistory->farmer_id;
        $cropHistory->delete();

        return redirect()->route('farmers.show', $farmerId)
            ->with('success', 'Crop record deleted.');
    }
}
