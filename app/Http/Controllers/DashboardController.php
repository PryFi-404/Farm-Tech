<?php

namespace App\Http\Controllers;

use App\Models\CropHistory;
use App\Models\Farmer;
use App\Models\Scheme;
use App\Models\SchemeApplication;
use App\Models\Shg;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin()
    {
        // ── Stat card numbers ───────────────────────────────────────
        $totalFarmers    = Farmer::count();
        $totalSchemes    = Scheme::where('is_active', true)->count();
        $totalApproved   = SchemeApplication::where('status', 'approved')->count();
        $totalPending    = SchemeApplication::where('status', 'pending')->count();
        $totalSHGs       = Shg::count();
        $totalSubsidy    = SchemeApplication::where('status', 'approved')->sum('subsidy_amount');

        // ── Chart data: crop production by name (top 6) ─────────────
        $cropProduction = CropHistory::with('crop')
            ->selectRaw('crop_id, SUM(production_kg) as total_kg')
            ->groupBy('crop_id')
            ->orderByDesc('total_kg')
            ->limit(6)
            ->get()
            ->map(fn($ch) => [
                'crop'  => $ch->crop?->name ?? 'Unknown',
                'total' => round($ch->total_kg, 0),
            ]);

        // ── Chart data: application status breakdown ─────────────────
        $applicationStats = [
            'approved' => SchemeApplication::where('status', 'approved')->count(),
            'pending'  => SchemeApplication::where('status', 'pending')->count(),
            'rejected' => SchemeApplication::where('status', 'rejected')->count(),
        ];

        // ── Chart data: village-wise farmer count ────────────────────
        $villageStats = Farmer::selectRaw('village, COUNT(*) as count')
            ->whereNotNull('village')
            ->groupBy('village')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // ── Recent applications ──────────────────────────────────────
        $recentApplications = SchemeApplication::with(['farmer.user', 'scheme'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.admin', compact(
            'totalFarmers', 'totalSchemes', 'totalApproved', 'totalPending',
            'totalSHGs', 'totalSubsidy', 'cropProduction', 'applicationStats',
            'villageStats', 'recentApplications'
        ));
    }

    public function officer()
    {
        $totalFarmers  = Farmer::count();
        $pendingApps   = SchemeApplication::where('status', 'pending')->count();
        $approvedApps  = SchemeApplication::where('status', 'approved')->count();
        $totalSHGs     = Shg::count();

        $recentApplications = SchemeApplication::with(['farmer.user', 'scheme'])
            ->latest()->limit(5)->get();

        return view('dashboard.officer', compact(
            'totalFarmers', 'pendingApps', 'approvedApps',
            'totalSHGs', 'recentApplications'
        ));
    }

    public function farmer()
    {
        $farmer = auth()->user()->farmer;

        $myApplications = $farmer
            ? SchemeApplication::with('scheme')
                ->where('farmer_id', $farmer->id)
                ->latest()->get()
            : collect();

        $availableSchemes = Scheme::where('is_active', true)->limit(3)->get();
        $myLands  = $farmer?->lands()->count() ?? 0;
        $myCrops  = $farmer?->cropHistories()->count() ?? 0;

        return view('dashboard.farmer', compact(
            'farmer', 'myApplications', 'availableSchemes', 'myLands', 'myCrops'
        ));
    }
}
