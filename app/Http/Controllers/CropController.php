<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use Illuminate\Http\Request;

class CropController extends Controller
{
    /**
     * Master crop list
     */
    public function index(Request $request)
    {
        $crops = Crop::when($request->season, fn($q) => $q->where('season', $request->season))
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->orderBy('season')->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $seasons    = Crop::distinct()->pluck('season')->filter()->sort()->values();
        $categories = Crop::distinct()->pluck('category')->filter()->sort()->values();

        return view('crops.index', compact('crops', 'seasons', 'categories'));
    }

    /**
     * Show create form (admin only)
     */
    public function create()
    {
        return view('crops.create');
    }

    /**
     * Store new crop type
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:100', 'unique:crops,name'],
            'category'    => ['required', 'string', 'max:50'],
            'season'      => ['required', 'in:Kharif,Rabi,Zaid,Year Round'],
            'description' => ['nullable', 'string', 'max:300'],
        ]);

        Crop::create($request->only('name', 'category', 'season', 'description'));

        return redirect()->route('crops.index')->with('success', 'Crop added to master list!');
    }

    /**
     * Delete crop (admin only, careful — cascades to crop_histories)
     */
    public function destroy(Crop $crop)
    {
        $crop->delete();
        return redirect()->route('crops.index')->with('success', 'Crop removed from master list.');
    }
}
