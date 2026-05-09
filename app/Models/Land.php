<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Land extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id', 'survey_number', 'area_acres', 'soil_type',
        'irrigation_type', 'ownership_type', 'khasra_number', 'document',
    ];

    protected $casts = [
        'area_acres' => 'decimal:2',
    ];

    // A land parcel belongs to a farmer
    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    // A land parcel has many crop histories
    public function cropHistories()
    {
        return $this->hasMany(CropHistory::class);
    }
}
