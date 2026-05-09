<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shg extends Model
{
    use HasFactory;

    protected $table = 'shgs';

    protected $fillable = [
        'name', 'type', 'registration_number', 'formation_date',
        'village', 'block', 'district', 'leader_farmer_id',
        'total_members', 'bank_account',
    ];

    protected $casts = [
        'formation_date' => 'date',
    ];

    // The leader of this SHG group
    public function leader()
    {
        return $this->belongsTo(Farmer::class, 'leader_farmer_id');
    }

    // All members of this SHG
    public function shgMembers()
    {
        return $this->hasMany(ShgMember::class);
    }

    // Direct access to farmer records via pivot
    public function farmers()
    {
        return $this->belongsToMany(Farmer::class, 'shg_members', 'shg_id', 'farmer_id');
    }
}
