<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFarmerRequest;
use App\Http\Requests\UpdateFarmerRequest;
use App\Models\Farmer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class FarmerController extends Controller
{
    /**
     * LIST all farmers with search + filters
     */
    public function index(Request $request)
    {
        $query = Farmer::with('user')
            ->when($request->search, fn($q) =>
                $q->whereHas('user', fn($u) =>
                    $u->where('name', 'like', "%{$request->search}%")
                )
            )
            ->when($request->village, fn($q) =>
                $q->where('village', $request->village)
            )
            ->when($request->district, fn($q) =>
                $q->where('district', $request->district)
            )
            ->when($request->gender, fn($q) =>
                $q->where('gender', $request->gender)
            )
            ->latest()
            ->paginate(10)
            ->withQueryString(); // keep filters across pages

        // Dropdown options
        $villages  = Farmer::distinct()->pluck('village')->filter()->sort()->values();
        $districts = Farmer::distinct()->pluck('district')->filter()->sort()->values();

        return view('farmers.index', compact('query', 'villages', 'districts'));
    }

    /**
     * SHOW add farmer form
     */
    public function create()
    {
        return view('farmers.create');
    }

    /**
     * STORE new farmer in DB
     */
    public function store(StoreFarmerRequest $request)
    {
        DB::transaction(function () use ($request) {
            // 1. Create a user account for the farmer
            $user = User::create([
                'name'              => $request->name,
                'email'             => $request->email,
                'password'          => Hash::make($request->password),
                'role'              => 'farmer',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]);

            // 2. Handle photo upload
            $photoPath = null;
            if ($request->hasFile('photo')) {
                // Stores in storage/app/public/farmers/ → accessible via /storage/farmers/
                $photoPath = $request->file('photo')->store('farmers', 'public');
            }

            // 3. Create the farmer profile linked to the user
            Farmer::create([
                'user_id'      => $user->id,
                'aadhaar'      => $request->aadhaar,
                'voter_id'     => $request->voter_id,
                'phone'        => $request->phone,
                'dob'          => $request->dob,
                'gender'       => $request->gender,
                'address'      => $request->address,
                'village'      => $request->village,
                'block'        => $request->block,
                'district'     => $request->district,
                'state'        => $request->state,
                'pincode'      => $request->pincode,
                'photo'        => $photoPath,
                'bank_account' => $request->bank_account,
                'bank_name'    => $request->bank_name,
                'ifsc'         => $request->ifsc,
                'created_by'   => auth()->id(),
            ]);
        });

        return redirect()->route('farmers.index')
            ->with('success', 'Farmer registered successfully!');
    }

    /**
     * SHOW farmer profile
     */
    public function show(Farmer $farmer)
    {
        $farmer->load([
            'user', 'lands', 'familyMembers',
            'cropHistories.crop', 'cropHistories.land',
            'schemeApplications.scheme', 'shgMembers.shg',
        ]);

        return view('farmers.show', compact('farmer'));
    }

    /**
     * SHOW edit form
     */
    public function edit(Farmer $farmer)
    {
        $farmer->load('user');
        return view('farmers.edit', compact('farmer'));
    }

    /**
     * UPDATE farmer record
     */
    public function update(UpdateFarmerRequest $request, Farmer $farmer)
    {
        // Update the linked user name
        $farmer->user->update(['name' => $request->name]);

        // Handle new photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($farmer->photo) {
                Storage::disk('public')->delete($farmer->photo);
            }
            $farmer->photo = $request->file('photo')->store('farmers', 'public');
        }

        $farmer->fill($request->except(['name', 'photo']))->save();

        return redirect()->route('farmers.show', $farmer->id)
            ->with('success', 'Farmer details updated successfully!');
    }

    /**
     * DELETE farmer record (Admin only)
     */
    public function destroy(Farmer $farmer)
    {
        // Delete photo from disk
        if ($farmer->photo) {
            Storage::disk('public')->delete($farmer->photo);
        }

        // Deleting farmer also deletes user (cascade on farmer side handled in DB)
        $farmer->user->delete(); // This cascades to farmer via DB foreign key

        return redirect()->route('farmers.index')
            ->with('success', 'Farmer record deleted.');
    }
}
