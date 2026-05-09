<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CropHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id', 'land_id', 'crop_id', 'season', 'year',
        'area_used', 'production_kg', 'selling_price', 'notes',
    ];

    protected $casts = [
        'area_used'     => 'decimal:2',
        'production_kg' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];

    // Which farmer grew this crop
    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    // On which land
    public function land()
    {
        return $this->belongsTo(Land::class);
    }

    // Which crop type
    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }

    // Calculate estimated revenue
    public function getEstimatedRevenueAttribute()
    {
        return $this->production_kg * $this->selling_price;
    }
}
