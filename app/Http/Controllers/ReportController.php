<?php

namespace App\Http\Controllers;

use App\Models\CropHistory;
use App\Models\Farmer;
use App\Models\Land;
use App\Models\Scheme;
use App\Models\SchemeApplication;
use App\Models\Shg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Main analytics dashboard
     */
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));

        // ── 1. TOP-LEVEL SUMMARY STATS ─────────────────────────────────
        $summary = [
            'total_farmers'    => Farmer::count(),
            'total_land_acres' => Land::sum('area_acres'),
            'total_schemes'    => Scheme::where('is_active', true)->count(),
            'total_applied'    => SchemeApplication::count(),
            'total_approved'   => SchemeApplication::where('status', 'approved')->count(),
            'total_disbursed'  => SchemeApplication::where('status', 'approved')->sum('subsidy_amount'),
            'total_shgs'       => Shg::count(),
            'total_production' => CropHistory::sum('production_kg'),
        ];

        // ── 2. FARMERS BY DISTRICT (Bar Chart) ─────────────────────────
        $farmersByDistrict = Farmer::select('district', DB::raw('COUNT(*) as count'))
            ->groupBy('district')
            ->orderByDesc('count')
            ->limit(8)
            ->get();

        // ── 3. APPLICATION STATUS BREAKDOWN (Donut Chart) ──────────────
        $applicationStatus = SchemeApplication::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // ── 4. CROP PRODUCTION BY CROP (Bar Chart) ─────────────────────
        $cropProduction = CropHistory::with('crop')
            ->select('crop_id', DB::raw('SUM(production_kg) as total_kg'), DB::raw('SUM(production_kg * selling_price) as total_revenue'))
            ->groupBy('crop_id')
            ->orderByDesc('total_kg')
            ->limit(8)
            ->get();

        // ── 5. MONTHLY APPLICATIONS TREND (Line Chart) ─────────────────
        $monthlyApplications = SchemeApplication::select(
                DB::raw('MONTH(applied_date) as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status="approved" THEN 1 ELSE 0 END) as approved')
            )
            ->whereYear('applied_date', $year)
            ->groupBy(DB::raw('MONTH(applied_date)'))
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // Build full 12-month array (0 for missing months)
        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[$m] = [
                'total'    => $monthlyApplications[$m]->total    ?? 0,
                'approved' => $monthlyApplications[$m]->approved ?? 0,
            ];
        }

        // ── 6. GENDER DISTRIBUTION (Donut Chart) ───────────────────────
        $genderData = Farmer::select('gender', DB::raw('COUNT(*) as count'))
            ->groupBy('gender')
            ->get()
            ->keyBy('gender');

        // ── 7. LAND OWNERSHIP TYPE (Donut Chart) ───────────────────────
        $ownershipData = Land::select('ownership_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(area_acres) as total_acres'))
            ->groupBy('ownership_type')
            ->get();

        // ── 8. SCHEME PERFORMANCE TABLE ────────────────────────────────
        $schemePerformance = Scheme::select('schemes.*')
            ->withCount('applications')
            ->withCount(['applications as approved_count' => fn($q) => $q->where('status', 'approved')])
            ->withSum(['applications as total_disbursed' => fn($q) => $q->where('status', 'approved')], 'subsidy_amount')
            ->orderByDesc('applications_count')
            ->limit(6)
            ->get();

        // ── 9. TOP BENEFICIARY FARMERS ─────────────────────────────────
        $topBeneficiaries = SchemeApplication::with('farmer.user', 'scheme')
            ->where('status', 'approved')
            ->select('farmer_id', DB::raw('SUM(subsidy_amount) as total_benefit'), DB::raw('COUNT(*) as schemes_count'))
            ->groupBy('farmer_id')
            ->orderByDesc('total_benefit')
            ->limit(5)
            ->get();

        // ── 10. CROP SEASON SPLIT (Donut) ──────────────────────────────
        $seasonData = CropHistory::select('season', DB::raw('COUNT(*) as count'), DB::raw('SUM(production_kg) as total_kg'))
            ->groupBy('season')
            ->get();

        // Available years for filter dropdown
        $availableYears = SchemeApplication::selectRaw('YEAR(applied_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        return view('reports.index', compact(
            'summary', 'farmersByDistrict', 'applicationStatus',
            'cropProduction', 'monthlyData', 'genderData',
            'ownershipData', 'schemePerformance', 'topBeneficiaries',
            'seasonData', 'year', 'availableYears'
        ));
    }
}
