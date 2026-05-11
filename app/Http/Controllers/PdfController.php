<?php

namespace App\Http\Controllers;

use App\Models\CropHistory;
use App\Models\Farmer;
use App\Models\Scheme;
use App\Models\SchemeApplication;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PdfController extends Controller
{
    /**
     * Download individual Farmer Profile PDF
     */
    public function farmerProfile(Farmer $farmer)
    {
        $farmer->load([
            'user',
            'lands',
            'cropHistories.crop',
            'cropHistories.land',
            'schemeApplications.scheme',
            'shgMembers.shg',
        ]);

        $totalLandArea = $farmer->lands->sum('area_acres');
        $totalProduction = $farmer->cropHistories->sum('production_kg');
        $totalRevenue = $farmer->cropHistories->sum(
            fn($ch) => $ch->production_kg * $ch->selling_price
        );
        $approvedSchemes = $farmer->schemeApplications
            ->where('status', 'approved');
        $totalBenefit = $approvedSchemes->sum('subsidy_amount');

        $pdf = Pdf::loadView('pdf.farmer-profile', compact(
            'farmer', 'totalLandArea', 'totalProduction',
            'totalRevenue', 'totalBenefit', 'approvedSchemes'
        ))->setPaper('a4', 'portrait');

        $filename = 'farmer-profile-' . str($farmer->user->name)->slug() . '-' . date('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Download Scheme Summary PDF for a specific scheme
     */
    public function schemeSummary(Scheme $scheme)
    {
        $applications = $scheme->applications()
            ->with('farmer.user')
            ->latest()
            ->get();

        $stats = [
            'total'    => $applications->count(),
            'approved' => $applications->where('status', 'approved')->count(),
            'pending'  => $applications->where('status', 'pending')->count(),
            'rejected' => $applications->where('status', 'rejected')->count(),
            'disbursed'=> $applications->where('status', 'approved')->sum('subsidy_amount'),
        ];

        $pdf = Pdf::loadView('pdf.scheme-summary', compact('scheme', 'applications', 'stats'))
            ->setPaper('a4', 'landscape');

        $filename = 'scheme-' . str($scheme->name)->slug() . '-' . date('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Download full Analytics Summary PDF
     */
    public function analyticsSummary()
    {
        $summary = [
            'total_farmers'    => Farmer::count(),
            'total_land_acres' => \App\Models\Land::sum('area_acres'),
            'total_schemes'    => Scheme::where('is_active', true)->count(),
            'total_applied'    => SchemeApplication::count(),
            'total_approved'   => SchemeApplication::where('status', 'approved')->count(),
            'total_disbursed'  => SchemeApplication::where('status', 'approved')->sum('subsidy_amount'),
            'total_production' => CropHistory::sum('production_kg'),
        ];

        $farmersByDistrict = Farmer::select('district', DB::raw('COUNT(*) as count'))
            ->groupBy('district')->orderByDesc('count')->get();

        $schemePerformance = Scheme::withCount('applications')
            ->withCount(['applications as approved_count' => fn($q) => $q->where('status', 'approved')])
            ->withSum(['applications as total_disbursed' => fn($q) => $q->where('status', 'approved')], 'subsidy_amount')
            ->orderByDesc('applications_count')
            ->get();

        $topBeneficiaries = SchemeApplication::with('farmer.user')
            ->where('status', 'approved')
            ->select('farmer_id', DB::raw('SUM(subsidy_amount) as total_benefit'), DB::raw('COUNT(*) as schemes_count'))
            ->groupBy('farmer_id')
            ->orderByDesc('total_benefit')
            ->limit(10)
            ->get();

        $pdf = Pdf::loadView('pdf.analytics-summary', compact(
            'summary', 'farmersByDistrict', 'schemePerformance', 'topBeneficiaries'
        ))->setPaper('a4', 'portrait');

        $filename = 'farmtech-analytics-' . date('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}
