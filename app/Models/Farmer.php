<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'aadhaar', 'voter_id', 'phone', 'dob', 'gender',
        'address', 'village', 'block', 'district', 'state', 'pincode',
        'photo', 'bank_account', 'bank_name', 'ifsc', 'created_by',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    // A farmer belongs to a user account
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Who created this farmer record
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // A farmer can own multiple land parcels
    public function lands()
    {
        return $this->hasMany(Land::class);
    }

    // A farmer has a history of crops grown
    public function cropHistories()
    {
        return $this->hasMany(CropHistory::class);
    }

    // A farmer has family members
    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class);
    }

    // A farmer can apply for multiple government schemes
    public function schemeApplications()
    {
        return $this->hasMany(SchemeApplication::class);
    }

    // A farmer can be a member of multiple SHGs
    public function shgMembers()
    {
        return $this->hasMany(ShgMember::class);
    }

    // Shortcut to get SHGs the farmer belongs to
    public function shgs()
    {
        return $this->belongsToMany(Shg::class, 'shg_members', 'farmer_id', 'shg_id');
    }

    // Full name accessor for convenience
    public function getFullAddressAttribute()
    {
        return collect([$this->village, $this->block, $this->district, $this->state])
            ->filter()
            ->implode(', ');
    }
}
