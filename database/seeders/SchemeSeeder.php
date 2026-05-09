<?php

namespace Database\Seeders;

use App\Models\Scheme;
use App\Models\User;
use Illuminate\Database\Seeder;

class SchemeSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        $schemes = [
            [
                'name'                => 'PM Kisan Samman Nidhi',
                'description'         => 'Direct income support of ₹6000 per year to small and marginal farmers in three equal installments.',
                'department'          => 'Ministry of Agriculture',
                'scheme_type'         => 'Income Support',
                'eligibility_criteria'=> 'Small and marginal farmers with cultivable land up to 2 hectares.',
                'benefit_amount'      => 6000.00,
                'start_date'          => '2024-01-01',
                'end_date'            => '2026-12-31',
                'is_active'           => true,
            ],
            [
                'name'                => 'Pradhan Mantri Fasal Bima Yojana',
                'description'         => 'Crop insurance scheme providing financial support to farmers in case of crop failure due to natural calamities.',
                'department'          => 'Ministry of Agriculture',
                'scheme_type'         => 'Insurance',
                'eligibility_criteria'=> 'All farmers growing notified crops in notified areas.',
                'benefit_amount'      => 50000.00,
                'start_date'          => '2024-04-01',
                'end_date'            => '2026-03-31',
                'is_active'           => true,
            ],
            [
                'name'                => 'Kisan Credit Card (KCC)',
                'description'         => 'Provides short-term credit requirements for crop cultivation, post-harvest expenses, and maintenance.',
                'department'          => 'Ministry of Finance',
                'scheme_type'         => 'Credit',
                'eligibility_criteria'=> 'All farmers — individual or joint borrowers who are owner cultivators.',
                'benefit_amount'      => 300000.00,
                'start_date'          => '2024-01-01',
                'end_date'            => null,
                'is_active'           => true,
            ],
            [
                'name'                => 'PM Krishi Sinchayee Yojana',
                'description'         => 'Achieve convergence of investments in irrigation at the field level, expand cultivable area under assured irrigation.',
                'department'          => 'Ministry of Jal Shakti',
                'scheme_type'         => 'Infrastructure',
                'eligibility_criteria'=> 'Farmers in water-stressed areas with land records.',
                'benefit_amount'      => 25000.00,
                'start_date'          => '2024-06-01',
                'end_date'            => '2025-12-31',
                'is_active'           => true,
            ],
            [
                'name'                => 'SHG Promotion & Linkage Scheme',
                'description'         => 'Financial support to Self Help Groups for formation, training, and bank linkage.',
                'department'          => 'State Rural Livelihoods Mission',
                'scheme_type'         => 'SHG Support',
                'eligibility_criteria'=> 'Women SHG members from BPL households in rural areas.',
                'benefit_amount'      => 15000.00,
                'start_date'          => '2024-04-01',
                'end_date'            => '2025-03-31',
                'is_active'           => true,
            ],
            [
                'name'                => 'Soil Health Card Scheme',
                'description'         => 'Provides soil health cards to farmers with crop-wise recommendations of nutrients and fertilizers.',
                'department'          => 'Department of Agriculture',
                'scheme_type'         => 'Technical Support',
                'eligibility_criteria'=> 'All registered farmers.',
                'benefit_amount'      => 500.00,
                'start_date'          => '2024-01-01',
                'end_date'            => null,
                'is_active'           => true,
            ],
        ];

        foreach ($schemes as $scheme) {
            Scheme::create(array_merge($scheme, ['created_by' => $admin?->id]));
        }
    }
}
