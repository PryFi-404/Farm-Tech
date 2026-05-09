<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemeApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id', 'scheme_id', 'applied_date', 'status',
        'approved_by', 'approved_date', 'subsidy_amount', 'remarks',
    ];

    protected $casts = [
        'applied_date'   => 'date',
        'approved_date'  => 'date',
        'subsidy_amount' => 'decimal:2',
    ];

    // Which farmer applied
    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    // Which scheme
    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }

    // Who approved/rejected (admin/officer user)
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Badge color helper for status
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            default    => 'bg-yellow-100 text-yellow-800',
        };
    }
}
