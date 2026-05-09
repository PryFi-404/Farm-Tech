<?php

namespace Database\Seeders;

use App\Models\Farmer;
use App\Models\Shg;
use App\Models\ShgMember;
use Illuminate\Database\Seeder;

class ShgSeeder extends Seeder
{
    public function run(): void
    {
        $farmers = Farmer::all();

        // SHG Group 1 — Women's Self Help Group
        $shg1 = Shg::create([
            'name'                => 'Jai Kisan Mahila SHG',
            'type'                => 'SHG',
            'registration_number' => 'SHG/RPR/2022/001',
            'formation_date'      => '2022-06-15',
            'village'             => 'Rampur',
            'block'               => 'Block A',
            'district'            => 'Raipur',
            'leader_farmer_id'    => $farmers->first()?->id,
            'total_members'       => 5,
            'bank_account'        => '123456789012',
        ]);

        // Add first 5 farmers as SHG 1 members
        foreach ($farmers->take(5) as $i => $farmer) {
            ShgMember::create([
                'shg_id'      => $shg1->id,
                'farmer_id'   => $farmer->id,
                'role'        => $i === 0 ? 'President' : ($i === 1 ? 'Secretary' : 'Member'),
                'joined_date' => '2022-06-15',
                'status'      => 'active',
            ]);
        }

        // FPG Group — Farmer Producer Group
        $fpg1 = Shg::create([
            'name'                => 'Krishak Pragati FPG',
            'type'                => 'FPG',
            'registration_number' => 'FPG/RPR/2023/001',
            'formation_date'      => '2023-03-20',
            'village'             => 'Krishnapur',
            'block'               => 'Block B',
            'district'            => 'Raipur',
            'leader_farmer_id'    => $farmers->skip(5)->first()?->id,
            'total_members'       => 5,
            'bank_account'        => '987654321098',
        ]);

        // Add next 5 farmers as FPG members
        foreach ($farmers->skip(5)->take(5) as $i => $farmer) {
            ShgMember::create([
                'shg_id'      => $fpg1->id,
                'farmer_id'   => $farmer->id,
                'role'        => $i === 0 ? 'Chairman' : ($i === 1 ? 'Treasurer' : 'Member'),
                'joined_date' => '2023-03-20',
                'status'      => 'active',
            ]);
        }
    }
}
