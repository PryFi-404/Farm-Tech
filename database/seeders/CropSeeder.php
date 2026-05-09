<?php

namespace Database\Seeders;

use App\Models\Crop;
use Illuminate\Database\Seeder;

class CropSeeder extends Seeder
{
    public function run(): void
    {
        $crops = [
            // Kharif (monsoon) crops
            ['name' => 'Paddy (Rice)',    'category' => 'Cereal',     'season' => 'Kharif',  'description' => 'Staple food crop grown in flooded fields.'],
            ['name' => 'Maize',           'category' => 'Cereal',     'season' => 'Kharif',  'description' => 'Multipurpose crop used for food and fodder.'],
            ['name' => 'Soybean',         'category' => 'Oilseed',    'season' => 'Kharif',  'description' => 'Rich protein oilseed crop.'],
            ['name' => 'Cotton',          'category' => 'Cash Crop',  'season' => 'Kharif',  'description' => 'White gold — major fiber crop.'],
            ['name' => 'Sugarcane',       'category' => 'Cash Crop',  'season' => 'Kharif',  'description' => 'Long duration sugar crop.'],
            ['name' => 'Groundnut',       'category' => 'Oilseed',    'season' => 'Kharif',  'description' => 'Major oilseed and protein source.'],

            // Rabi (winter) crops
            ['name' => 'Wheat',           'category' => 'Cereal',     'season' => 'Rabi',    'description' => 'Primary winter food grain.'],
            ['name' => 'Gram (Chickpea)', 'category' => 'Pulse',      'season' => 'Rabi',    'description' => 'Important winter pulse crop.'],
            ['name' => 'Mustard',         'category' => 'Oilseed',    'season' => 'Rabi',    'description' => 'Major Rabi oilseed crop.'],
            ['name' => 'Lentil (Masoor)', 'category' => 'Pulse',      'season' => 'Rabi',    'description' => 'Nutritious winter pulse.'],

            // Zaid (summer) crops
            ['name' => 'Watermelon',      'category' => 'Vegetable',  'season' => 'Zaid',    'description' => 'Summer fruit crop.'],
            ['name' => 'Cucumber',        'category' => 'Vegetable',  'season' => 'Zaid',    'description' => 'Summer vegetable with high water content.'],
            ['name' => 'Moong (Green Gram)', 'category' => 'Pulse',   'season' => 'Zaid',    'description' => 'Short-duration summer pulse.'],

            // Perennial / year-round
            ['name' => 'Banana',          'category' => 'Fruit',      'season' => 'Year Round', 'description' => 'High-value perennial fruit crop.'],
            ['name' => 'Tomato',          'category' => 'Vegetable',  'season' => 'Year Round', 'description' => 'High-demand vegetable crop.'],
        ];

        foreach ($crops as $crop) {
            Crop::create($crop);
        }
    }
}
