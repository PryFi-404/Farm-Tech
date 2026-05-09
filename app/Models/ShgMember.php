<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShgMember extends Model
{
    use HasFactory;

    protected $table = 'shg_members';

    protected $fillable = ['shg_id', 'farmer_id', 'role', 'joined_date', 'status'];

    protected $casts = [
        'joined_date' => 'date',
    ];

    // Which SHG group
    public function shg()
    {
        return $this->belongsTo(Shg::class);
    }

    // Which farmer
    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }
}
