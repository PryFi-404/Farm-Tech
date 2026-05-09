<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crop extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category', 'season', 'description'];

    // A crop type appears in many crop histories
    public function cropHistories()
    {
        return $this->hasMany(CropHistory::class);
    }
}
