<x-app-layout header="Reports & Analytics" breadcrumb="Comprehensive farm data insights">

    {{-- Year Filter --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Analytics Overview</h2>
            <p class="text-sm text-gray-400">Data-driven insights across all modules</p>
        </div>
        <form method="GET" action="{{ route('reports.index') }}" class="flex items-center gap-2">
            <label class="text-sm text-gray-500 font-medium">Year:</label>
            <select name="year" class="form-input text-sm py-1.5 w-28" onchange="this.form.submit()">
                @foreach($availableYears->merge([date('Y')]) ->unique()->sort()->reverse() as $y)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- ── TOP SUMMARY CARDS ──────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['label' => 'Total Farmers',    'value' => number_format($summary['total_farmers']),                  'icon' => '👨‍🌾', 'color' => 'bg-farm-50 border-farm-200 text-farm-700'],
            ['label' => 'Land Area (ac)',    'value' => number_format($summary['total_land_acres'], 1),            'icon' => '🌍', 'color' => 'bg-blue-50 border-blue-200 text-blue-700'],
            ['label' => 'Crop Production',  'value' => number_format($summary['total_production']) . ' kg',       'icon' => '🌾', 'color' => 'bg-amber-50 border-amber-200 text-amber-700'],
            ['label' => 'Active Schemes',   'value' => number_format($summary['total_schemes']),                  'icon' => '📋', 'color' => 'bg-purple-50 border-purple-200 text-purple-700'],
            ['label' => 'Total Applied',    'value' => number_format($summary['total_applied']),                  'icon' => '📝', 'color' => 'bg-gray-50 border-gray-200 text-gray-700'],
            ['label' => 'Approved',         'value' => number_format($summary['total_approved']),                 'icon' => '✅', 'color' => 'bg-green-50 border-green-200 text-green-700'],
            ['label' => 'Disbursed (₹)',    'value' => '₹' . number_format($summary['total_disbursed']),         'icon' => '💰', 'color' => 'bg-emerald-50 border-emerald-200 text-emerald-700'],
            ['label' => 'SHG / FPG Groups', 'value' => number_format($summary['total_shgs']),                    'icon' => '👥', 'color' => 'bg-orange-50 border-orange-200 text-orange-700'],
        ] as $card)
        <div class="rounded-xl border p-4 {{ $card['color'] }}">
            <div class="text-2xl mb-1">{{ $card['icon'] }}</div>
            <p class="text-xl font-bold">{{ $card['value'] }}</p>
            <p class="text-xs font-medium mt-0.5 opacity-80">{{ $card['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ── ROW 1: Monthly Trend + Application Status ──────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">

        {{-- Monthly Application Trend (Line) --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">
                📈 Monthly Application Trend
                <span class="text-xs font-normal text-gray-400 ml-1">({{ $year }})</span>
            </h3>
            <canvas id="monthlyTrendChart" height="120"></canvas>
        </div>

        {{-- Application Status Donut --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">🍩 Application Status</h3>
            <canvas id="applicationStatusChart" height="180"></canvas>
            <div class="mt-4 space-y-2">
                @foreach(['pending'=>['🟡','yellow'], 'approved'=>['🟢','green'], 'rejected'=>['🔴','red']] as $status => [$dot, $color])
                <div class="flex justify-between text-xs">
                    <span class="text-gray-500">{{ $dot }} {{ ucfirst($status) }}</span>
                    <span class="font-semibold">{{ $applicationStatus[$status]->count ?? 0 }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── ROW 2: Farmers by District + Crop Production ───────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">

        {{-- Farmers by District (Horizontal Bar) --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">👨‍🌾 Farmers by District</h3>
            <canvas id="farmerDistrictChart" height="200"></canvas>
        </div>

        {{-- Crop Production Bar --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">🌾 Crop Production (kg)</h3>
            <canvas id="cropProductionChart" height="200"></canvas>
        </div>
    </div>

    {{-- ── ROW 3: Gender + Ownership + Season Donuts ──────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-5">

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">👤 Gender Distribution</h3>
            <canvas id="genderChart" height="200"></canvas>
            <div class="mt-3 flex justify-center gap-4 text-xs text-gray-500">
                <span>🔵 Male: <strong>{{ $genderData['Male']->count ?? 0 }}</strong></span>
                <span>🟣 Female: <strong>{{ $genderData['Female']->count ?? 0 }}</strong></span>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">🏡 Land Ownership</h3>
            <canvas id="ownershipChart" height="200"></canvas>
            <div class="mt-3 space-y-1 text-xs text-gray-500">
                @foreach($ownershipData as $o)
                <div class="flex justify-between">
                    <span>{{ $o->ownership_type }}</span>
                    <span class="font-semibold">{{ number_format($o->total_acres, 1) }} ac</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">🗓️ Crop Season Split</h3>
            <canvas id="seasonChart" height="200"></canvas>
            <div class="mt-3 space-y-1 text-xs text-gray-500">
                @foreach($seasonData as $s)
                <div class="flex justify-between">
                    <span>{{ $s->season }}</span>
                    <span class="font-semibold">{{ $s->count }} records</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── ROW 4: Scheme Performance Table + Top Beneficiaries ─────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Scheme Performance Table --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">📊 Scheme Performance</h3>
            </div>
            <table class="w-full data-table">
                <thead>
                    <tr>
                        <th>Scheme</th>
                        <th class="text-center">Applied</th>
                        <th class="text-center">Approved</th>
                        <th class="text-right">Disbursed</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schemePerformance as $s)
                    <tr>
                        <td>
                            <a href="{{ route('schemes.show', $s->id) }}"
                               class="text-sm font-medium text-farm-600 hover:underline line-clamp-1">
                                {{ Str::limit($s->name, 30) }}
                            </a>
                            <p class="text-xs text-gray-400">{{ $s->category }}</p>
                        </td>
                        <td class="text-center text-sm font-medium">{{ $s->applications_count }}</td>
                        <td class="text-center">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                                {{ $s->approved_count > 0 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $s->approved_count }}
                            </span>
                        </td>
                        <td class="text-right text-sm font-semibold text-green-600">
                            {{ $s->total_disbursed ? '₹' . number_format($s->total_disbursed) : '—' }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-8 text-gray-400 text-sm">No scheme data yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Top Beneficiary Farmers --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">🏆 Top Beneficiary Farmers</h3>
            </div>
            @forelse($topBeneficiaries as $i => $b)
            <div class="flex items-center gap-3 px-5 py-3.5 {{ !$loop->last ? 'border-b border-gray-50' : '' }}
                {{ $loop->first ? 'bg-amber-50' : '' }}">
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm shrink-0
                    {{ $loop->first ? 'bg-amber-400 text-white' :
                       ($loop->index === 1 ? 'bg-gray-300 text-gray-700' :
                       ($loop->index === 2 ? 'bg-amber-700 text-white' : 'bg-farm-100 text-farm-700')) }}">
                    {{ $i + 1 }}
                </div>
                <div class="flex-1 min-w-0">
                    <a href="{{ route('farmers.show', $b->farmer_id) }}"
                       class="text-sm font-semibold text-gray-800 hover:text-farm-600 transition-colors">
                        {{ $b->farmer?->user?->name }}
                    </a>
                    <p class="text-xs text-gray-400">{{ $b->farmer?->village }}, {{ $b->farmer?->district }}
                        &nbsp;·&nbsp; {{ $b->schemes_count }} scheme{{ $b->schemes_count > 1 ? 's' : '' }}
                    </p>
                </div>
                <div class="text-right shrink-0">
                    <p class="text-sm font-bold text-green-600">₹{{ number_format($b->total_benefit) }}</p>
                    <p class="text-xs text-gray-400">total benefit</p>
                </div>
            </div>
            @empty
            <div class="text-center py-10 text-gray-400 text-sm">No approved applications yet.</div>
            @endforelse
        </div>
    </div>

</x-app-layout>

@push('scripts')
<script>
// ── Shared color palette ─────────────────────────────────────────────────
const palette = ['#16a34a','#2563eb','#9333ea','#ea580c','#0891b2','#dc2626','#ca8a04','#059669'];
const months  = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

// ── 1. Monthly Trend Line Chart ─────────────────────────────────────────
@php
    $monthTotal    = array_column($monthlyData, 'total');
    $monthApproved = array_column($monthlyData, 'approved');
@endphp
new Chart(document.getElementById('monthlyTrendChart'), {
    type: 'line',
    data: {
        labels: months,
        datasets: [
            {
                label: 'Total Applied',
                data: @json($monthTotal),
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37,99,235,0.08)',
                fill: true, tension: 0.4, pointRadius: 4,
            },
            {
                label: 'Approved',
                data: @json($monthApproved),
                borderColor: '#16a34a',
                backgroundColor: 'rgba(22,163,74,0.08)',
                fill: true, tension: 0.4, pointRadius: 4,
            },
        ]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero: true } } }
});

// ── 2. Application Status Donut ─────────────────────────────────────────
new Chart(document.getElementById('applicationStatusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Approved', 'Rejected'],
        datasets: [{
            data: [
                {{ $applicationStatus['pending']->count  ?? 0 }},
                {{ $applicationStatus['approved']->count ?? 0 }},
                {{ $applicationStatus['rejected']->count ?? 0 }},
            ],
            backgroundColor: ['#fbbf24', '#16a34a', '#dc2626'],
            borderWidth: 2, borderColor: '#fff',
        }]
    },
    options: { responsive: true, cutout: '65%', plugins: { legend: { display: false } } }
});

// ── 3. Farmers by District (Horizontal Bar) ─────────────────────────────
new Chart(document.getElementById('farmerDistrictChart'), {
    type: 'bar',
    data: {
        labels: @json($farmersByDistrict->pluck('district')),
        datasets: [{
            label: 'Farmers',
            data: @json($farmersByDistrict->pluck('count')),
            backgroundColor: palette,
            borderRadius: 6,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});

// ── 4. Crop Production Bar ──────────────────────────────────────────────
new Chart(document.getElementById('cropProductionChart'), {
    type: 'bar',
    data: {
        labels: @json($cropProduction->map(fn($c) => $c->crop?->name ?? 'Unknown')),
        datasets: [
            {
                label: 'Production (kg)',
                data: @json($cropProduction->pluck('total_kg')),
                backgroundColor: '#16a34a',
                borderRadius: 6,
                yAxisID: 'y',
            },
            {
                label: 'Revenue (₹)',
                data: @json($cropProduction->pluck('total_revenue')),
                backgroundColor: '#2563eb',
                borderRadius: 6,
                yAxisID: 'y1',
                type: 'line',
                tension: 0.4,
                pointRadius: 5,
            },
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        scales: {
            y:  { beginAtZero: true, title: { display: true, text: 'kg' } },
            y1: { beginAtZero: true, position: 'right', title: { display: true, text: '₹' }, grid: { drawOnChartArea: false } },
        }
    }
});

// ── 5. Gender Donut ──────────────────────────────────────────────────────
new Chart(document.getElementById('genderChart'), {
    type: 'doughnut',
    data: {
        labels: ['Male', 'Female', 'Other'],
        datasets: [{
            data: [
                {{ $genderData['Male']->count   ?? 0 }},
                {{ $genderData['Female']->count ?? 0 }},
                {{ $genderData['Other']->count  ?? 0 }},
            ],
            backgroundColor: ['#2563eb', '#9333ea', '#ea580c'],
            borderWidth: 2, borderColor: '#fff',
        }]
    },
    options: { responsive: true, cutout: '60%', plugins: { legend: { position: 'bottom', labels: { boxWidth: 10 } } } }
});

// ── 6. Land Ownership Donut ──────────────────────────────────────────────
new Chart(document.getElementById('ownershipChart'), {
    type: 'doughnut',
    data: {
        labels: @json($ownershipData->pluck('ownership_type')),
        datasets: [{
            data: @json($ownershipData->pluck('count')),
            backgroundColor: ['#16a34a', '#2563eb', '#9333ea'],
            borderWidth: 2, borderColor: '#fff',
        }]
    },
    options: { responsive: true, cutout: '60%', plugins: { legend: { position: 'bottom', labels: { boxWidth: 10 } } } }
});

// ── 7. Crop Season Donut ─────────────────────────────────────────────────
new Chart(document.getElementById('seasonChart'), {
    type: 'doughnut',
    data: {
        labels: @json($seasonData->pluck('season')),
        datasets: [{
            data: @json($seasonData->pluck('count')),
            backgroundColor: ['#0891b2', '#ca8a04', '#ea580c', '#16a34a'],
            borderWidth: 2, borderColor: '#fff',
        }]
    },
    options: { responsive: true, cutout: '60%', plugins: { legend: { position: 'bottom', labels: { boxWidth: 10 } } } }
});
</script>
@endpush
