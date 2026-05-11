<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShgRequest;
use App\Models\Farmer;
use App\Models\Shg;
use App\Models\ShgMember;
use Illuminate\Http\Request;

class ShgController extends Controller
{
    /**
     * List all SHG / FPG groups
     */
    public function index(Request $request)
    {
        $query = Shg::withCount('shgMembers')
            ->when($request->type,     fn($q) => $q->where('type', $request->type))
            ->when($request->district, fn($q) => $q->where('district', $request->district))
            ->when($request->search,   fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('registration_number', 'like', "%{$request->search}%")
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $districts = Shg::distinct()->pluck('district')->filter()->sort()->values();
        $stats = [
            'total'  => Shg::count(),
            'shg'    => Shg::where('type', 'SHG')->count(),
            'fpg'    => Shg::where('type', 'FPG')->count(),
            'members'=> ShgMember::where('status', 'active')->count(),
        ];

        return view('shgs.index', compact('query', 'districts', 'stats'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $farmers = Farmer::with('user')->get();
        return view('shgs.create', compact('farmers'));
    }

    /**
     * Store new SHG/FPG
     */
    public function store(StoreShgRequest $request)
    {
        $shg = Shg::create($request->validated());

        // Auto-add leader as member (President) if selected
        if ($request->leader_farmer_id) {
            ShgMember::create([
                'shg_id'      => $shg->id,
                'farmer_id'   => $request->leader_farmer_id,
                'role'        => 'President',
                'joined_date' => $request->formation_date ?? now()->toDateString(),
                'status'      => 'active',
            ]);
        }

        return redirect()->route('shgs.show', $shg->id)
            ->with('success', 'SHG/FPG group created successfully!');
    }

    /**
     * Show group profile with members
     */
    public function show(Shg $shg)
    {
        $shg->load(['leader.user', 'shgMembers.farmer.user']);

        // Farmers NOT already in this group (for add member dropdown)
        $existingFarmerIds = $shg->shgMembers->pluck('farmer_id')->toArray();
        $availableFarmers  = Farmer::with('user')
            ->whereNotIn('id', $existingFarmerIds)
            ->get();

        return view('shgs.show', compact('shg', 'availableFarmers'));
    }

    /**
     * Show edit form
     */
    public function edit(Shg $shg)
    {
        $farmers = Farmer::with('user')->get();
        return view('shgs.edit', compact('shg', 'farmers'));
    }

    /**
     * Update group
     */
    public function update(StoreShgRequest $request, Shg $shg)
    {
        $shg->update($request->validated());

        return redirect()->route('shgs.show', $shg->id)
            ->with('success', 'Group updated successfully!');
    }

    /**
     * Delete group (also cascades members)
     */
    public function destroy(Shg $shg)
    {
        $shg->shgMembers()->delete();
        $shg->delete();

        return redirect()->route('shgs.index')
            ->with('success', 'Group deleted.');
    }

    /**
     * Add a farmer as a member to this group
     */
    public function addMember(Request $request, Shg $shg)
    {
        $request->validate([
            'farmer_id'   => ['required', 'exists:farmers,id'],
            'role'        => ['required', 'string', 'max:50'],
            'joined_date' => ['required', 'date'],
        ]);

        // Prevent duplicate membership
        $alreadyMember = ShgMember::where('shg_id', $shg->id)
            ->where('farmer_id', $request->farmer_id)
            ->exists();

        if ($alreadyMember) {
            return back()->with('error', 'This farmer is already a member of this group.');
        }

        ShgMember::create([
            'shg_id'      => $shg->id,
            'farmer_id'   => $request->farmer_id,
            'role'        => $request->role,
            'joined_date' => $request->joined_date,
            'status'      => 'active',
        ]);

        // Update total_members count
        $shg->update(['total_members' => $shg->shgMembers()->count()]);

        return back()->with('success', 'Member added to group!');
    }

    /**
     * Remove a member from this group
     */
    public function removeMember(Shg $shg, ShgMember $member)
    {
        $member->delete();
        $shg->update(['total_members' => $shg->shgMembers()->count()]);

        return back()->with('success', 'Member removed from group.');
    }
}
