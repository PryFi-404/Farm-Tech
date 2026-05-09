<?php

namespace Database\Seeders;

use App\Models\Crop;
use App\Models\CropHistory;
use App\Models\Farmer;
use App\Models\FamilyMember;
use App\Models\Land;
use App\Models\Scheme;
use App\Models\SchemeApplication;
use App\Models\User;
use Illuminate\Database\Seeder;

class FarmerSeeder extends Seeder
{
    public function run(): void
    {
        $farmerUsers = User::where('role', 'farmer')->get();
        $officer     = User::where('role', 'officer')->first();

        $villages = ['Rampur', 'Sunderpur', 'Krishnapur', 'Laxmipur', 'Ganeshpur'];
        $blocks   = ['Block A', 'Block B', 'Block C'];
        $district = 'Raipur';
        $state    = 'Chhattisgarh';
        $soils    = ['Loamy', 'Clay', 'Sandy', 'Black Cotton', 'Red'];
        $irr      = ['Canal', 'Borewell', 'Rainwater', 'Drip', 'Sprinkler'];
        $genders  = ['Male', 'Female'];

        $aadhaarBase = 100000000001;
        $cropIds  = Crop::pluck('id')->toArray();
        $schemes  = Scheme::all();

        foreach ($farmerUsers as $index => $user) {
            // Create Farmer profile
            $farmer = Farmer::create([
                'user_id'      => $user->id,
                'aadhaar'      => (string)($aadhaarBase + $index),
                'voter_id'     => 'VT' . str_pad($index + 1, 8, '0', STR_PAD_LEFT),
                'phone'        => '9' . rand(100000000, 999999999),
                'dob'          => now()->subYears(rand(25, 60))->subDays(rand(1, 365))->toDateString(),
                'gender'       => $genders[$index % 2],
                'address'      => (10 + $index) . ', Main Road',
                'village'      => $villages[$index % count($villages)],
                'block'        => $blocks[$index % count($blocks)],
                'district'     => $district,
                'state'        => $state,
                'pincode'      => '49200' . $index,
                'bank_account' => '3' . rand(1000000000, 9999999999),
                'bank_name'    => ['SBI', 'Bank of India', 'Canara Bank'][$index % 3],
                'ifsc'         => ['SBIN0001234', 'BKID0002345', 'CNRB0003456'][$index % 3],
                'created_by'   => $officer?->id,
            ]);

            // Family members
            FamilyMember::create(['farmer_id' => $farmer->id, 'name' => 'Spouse of ' . $user->name, 'relation' => 'Spouse', 'age' => rand(22, 55), 'occupation' => 'Homemaker']);
            FamilyMember::create(['farmer_id' => $farmer->id, 'name' => 'Child of ' . $user->name,  'relation' => 'Son/Daughter', 'age' => rand(5, 20), 'occupation' => 'Student']);

            // Land parcels (1–2 per farmer)
            $land1 = Land::create([
                'farmer_id'      => $farmer->id,
                'survey_number'  => 'SY-' . rand(100, 999),
                'area_acres'     => rand(1, 5) + 0.5,
                'soil_type'      => $soils[$index % count($soils)],
                'irrigation_type'=> $irr[$index % count($irr)],
                'ownership_type' => 'Owned',
                'khasra_number'  => 'KH-' . rand(1000, 9999),
            ]);

            if ($index % 3 === 0) {
                $land2 = Land::create([
                    'farmer_id'      => $farmer->id,
                    'survey_number'  => 'SY-' . rand(100, 999),
                    'area_acres'     => rand(1, 3) + 0.25,
                    'soil_type'      => $soils[($index + 1) % count($soils)],
                    'irrigation_type'=> $irr[($index + 1) % count($irr)],
                    'ownership_type' => 'Leased',
                    'khasra_number'  => 'KH-' . rand(1000, 9999),
                ]);
            }

            // Crop histories (2 records per farmer)
            $cropId1 = $cropIds[$index % count($cropIds)];
            $cropId2 = $cropIds[($index + 2) % count($cropIds)];

            CropHistory::create([
                'farmer_id'    => $farmer->id,
                'land_id'      => $land1->id,
                'crop_id'      => $cropId1,
                'season'       => 'Kharif',
                'year'         => 2024,
                'area_used'    => $land1->area_acres,
                'production_kg'=> rand(500, 3000),
                'selling_price'=> rand(10, 50),
                'notes'        => 'Good yield this season.',
            ]);

            CropHistory::create([
                'farmer_id'    => $farmer->id,
                'land_id'      => $land1->id,
                'crop_id'      => $cropId2,
                'season'       => 'Rabi',
                'year'         => 2025,
                'area_used'    => $land1->area_acres * 0.75,
                'production_kg'=> rand(300, 2000),
                'selling_price'=> rand(15, 60),
                'notes'        => 'Moderate yield, some pest damage.',
            ]);

            // Scheme applications (2 per farmer)
            foreach ($schemes->take(2) as $si => $scheme) {
                $status = ['pending', 'approved', 'rejected'][$index % 3];
                SchemeApplication::create([
                    'farmer_id'     => $farmer->id,
                    'scheme_id'     => $scheme->id,
                    'applied_date'  => now()->subDays(rand(10, 90))->toDateString(),
                    'status'        => $status,
                    'approved_by'   => $status !== 'pending' ? $officer?->id : null,
                    'approved_date' => $status !== 'pending' ? now()->subDays(rand(1, 9))->toDateString() : null,
                    'subsidy_amount'=> $status === 'approved' ? $scheme->benefit_amount : null,
                    'remarks'       => $status === 'rejected' ? 'Does not meet eligibility criteria.' : null,
                ]);
            }
        }
    }
}
