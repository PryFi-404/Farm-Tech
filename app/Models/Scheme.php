<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'department', 'scheme_type',
        'eligibility_criteria', 'benefit_amount', 'start_date',
        'end_date', 'is_active', 'created_by',
    ];

    protected $casts = [
        'start_date'     => 'date',
        'end_date'       => 'date',
        'is_active'      => 'boolean',
        'benefit_amount' => 'decimal:2',
    ];

    // Who created this scheme (admin user)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // All farmer applications for this scheme
    public function applications()
    {
        return $this->hasMany(SchemeApplication::class);
    }

    // Only approved applications
    public function approvedApplications()
    {
        return $this->hasMany(SchemeApplication::class)->where('status', 'approved');
    }

    // Check if scheme is currently active and within dates
    public function getIsCurrentlyActiveAttribute()
    {
        $today = now()->toDateString();
        return $this->is_active
            && ($this->start_date === null || $this->start_date <= $today)
            && ($this->end_date === null || $this->end_date >= $today);
    }
}
