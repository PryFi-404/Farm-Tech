<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    use HasFactory;

    protected $fillable = ['farmer_id', 'name', 'relation', 'age', 'occupation'];

    // A family member belongs to a farmer
    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }
}
