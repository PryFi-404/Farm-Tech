<x-app-layout header="Admin Dashboard" breadcrumb="Overview of FarmTech MIS">

    {{-- ═══ STAT CARDS ══════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 mb-8">

        <x-stat-card
            title="Total Farmers"
            :value="number_format($totalFarmers)"
            color="green"
            :link="route('farmers.index')"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>'
        />

        <x-stat-card
            title="Active Schemes"
            :value="number_format($totalSchemes)"
            color="blue"
            :link="route('schemes.index')"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>'
        />

        <x-stat-card
            title="Approved Beneficiaries"
            :value="number_format($totalApproved)"
            color="green"
            change="Scheme applications approved"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        />

        <x-stat-card
            title="Pending Applications"
            :value="number_format($totalPending)"
            color="orange"
            :link="route('applications.index')"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        />

        <x-stat-card
            title="SHG / FPG Groups"
            :value="number_format($totalSHGs)"
            color="purple"
            :link="route('shgs.index')"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'
        />

        <x-stat-card
            title="Total Subsidy Distributed"
            :value="'₹' . number_format($totalSubsidy, 0)"
            color="blue"
            change="Approved subsidy amount"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        />
    </div>

    {{-- ═══ CHARTS ROW ══════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        {{-- Crop Production Bar Chart --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">🌾 Crop Production (kg)</h3>
            <canvas id="cropChart" height="220"></canvas>
        </div>

        {{-- Application Status Pie Chart --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">📋 Application Status Breakdown</h3>
            <div class="flex items-center justify-center">
                <canvas id="appStatusChart" height="220" style="max-width:280px"></canvas>
            </div>
        </div>
    </div>

    {{-- ═══ VILLAGE STATS + RECENT APPLICATIONS ══════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Village Farmer Distribution --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">📍 Farmers by Village</h3>
            <div class="space-y-3">
                @foreach($villageStats as $v)
                @php $pct = $totalFarmers > 0 ? round(($v->count / $totalFarmers) * 100) : 0; @endphp
                <div>
                    <div class="flex justify-between text-xs text-gray-600 mb-1">
                        <span class="font-medium">{{ $v->village }}</span>
                        <span>{{ $v->count }} farmers</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-farm-500 h-2 rounded-full transition-all duration-500"
                             style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Recent Applications Table --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-700">🕐 Recent Applications</h3>
                <a href="{{ route('applications.index') }}"
                   class="text-xs text-farm-600 hover:underline font-medium">View all →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full data-table">
                    <thead>
                        <tr>
                            <th>Farmer</th>
                            <th>Scheme</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentApplications as $app)
                        <tr>
                            <td class="font-medium">{{ $app->farmer?->user?->name ?? '—' }}</td>
                            <td class="text-gray-500 max-w-[160px] truncate">{{ $app->scheme?->name ?? '—' }}</td>
                            <td class="text-gray-500">{{ $app->applied_date?->format('d M Y') }}</td>
                            <td>
                                <span class="badge-{{ $app->status }}">
                                    {{ ucfirst($app->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                        @if($recentApplications->isEmpty())
                        <tr><td colspan="4" class="text-center text-gray-400 py-6">No applications yet.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Chart.js CDN + Data Injection --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    // ── Crop Production Bar Chart ─────────────────────────────────
    new Chart(document.getElementById('cropChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($cropProduction->pluck('crop')) !!},
            datasets: [{
                label: 'Production (kg)',
                data:  {!! json_encode($cropProduction->pluck('total')) !!},
                backgroundColor: [
                    '#52B788','#2D6A4F','#74C69D','#B7E4C7',
                    '#40916C','#1B4332'
                ],
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
                x: { grid: { display: false } }
            }
        }
    });

    // ── Application Status Pie Chart ──────────────────────────────
    new Chart(document.getElementById('appStatusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Approved', 'Pending', 'Rejected'],
            datasets: [{
                data: [
                    {{ $applicationStats['approved'] }},
                    {{ $applicationStats['pending'] }},
                    {{ $applicationStats['rejected'] }}
                ],
                backgroundColor: ['#52B788', '#F4A261', '#E76F51'],
                borderWidth: 0,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: { position: 'bottom', labels: { padding: 16, font: { size: 12 } } }
            }
        }
    });
    </script>
    @endpush

</x-app-layout>
