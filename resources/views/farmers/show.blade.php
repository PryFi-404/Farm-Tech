<x-app-layout header="Farmer Profile" breadcrumb="Farmers / {{ $farmer->user?->name }}">

    <div class="max-w-5xl mx-auto space-y-5">

        {{-- ── Profile Header Card ─────────────────────────────────────────── --}}
        <div class="bg-gradient-to-r from-farm-700 to-farm-500 rounded-xl p-6 text-white shadow">
            <div class="flex flex-col sm:flex-row sm:items-center gap-5">

                {{-- Photo --}}
                <div class="shrink-0">
                    @if($farmer->photo)
                    <img src="{{ Storage::url($farmer->photo) }}"
                         class="w-24 h-24 rounded-2xl object-cover border-4 border-white/30 shadow-lg"
                         alt="{{ $farmer->user?->name }}">
                    @else
                    <div class="w-24 h-24 rounded-2xl bg-white/20 flex items-center justify-center border-4 border-white/30">
                        <span class="text-3xl font-bold text-white">
                            {{ strtoupper(substr($farmer->user?->name ?? 'F', 0, 1)) }}
                        </span>
                    </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1">
                    <h1 class="text-2xl font-bold">{{ $farmer->user?->name }}</h1>
                    <p class="text-green-200 text-sm mt-0.5">{{ $farmer->user?->email }}</p>
                    <div class="flex flex-wrap gap-3 mt-3">
                        <span class="flex items-center gap-1 text-sm bg-white/15 px-3 py-1 rounded-full">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            {{ $farmer->village }}, {{ $farmer->district }}
                        </span>
                        <span class="flex items-center gap-1 text-sm bg-white/15 px-3 py-1 rounded-full">
                            📱 {{ $farmer->phone ?? '—' }}
                        </span>
                        <span class="flex items-center gap-1 text-sm bg-white/15 px-3 py-1 rounded-full">
                            {{ $farmer->gender }}
                        </span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                @if(auth()->user()->isAdmin() || auth()->user()->isOfficer())
                <div class="flex gap-2 shrink-0 flex-wrap">
                    <a href="{{ route('farmers.edit', $farmer->id) }}" class="btn-secondary text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('lands.create', ['farmer_id' => $farmer->id]) }}" class="btn-primary text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Land
                    </a>
                    <a href="{{ route('crop-history.create', ['farmer_id' => $farmer->id]) }}" class="btn-primary text-sm bg-blue-600 hover:bg-blue-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Record Crop
                    </a>
                </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- ── Left Column ─────────────────────────────────────────────── --}}
            <div class="space-y-5">

                {{-- Personal Details --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">👤 Personal Details</h3>
                    <dl class="space-y-2.5 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Aadhaar</dt>
                            <dd class="font-mono font-medium">****-****-{{ substr($farmer->aadhaar, -4) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Voter ID</dt>
                            <dd class="font-medium">{{ $farmer->voter_id ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Date of Birth</dt>
                            <dd class="font-medium">{{ $farmer->dob?->format('d M Y') ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">State</dt>
                            <dd class="font-medium">{{ $farmer->state }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Pincode</dt>
                            <dd class="font-medium">{{ $farmer->pincode }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Bank Details --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">🏦 Bank Details</h3>
                    <dl class="space-y-2.5 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Bank</dt>
                            <dd class="font-medium">{{ $farmer->bank_name ?? '—' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Account No</dt>
                            <dd class="font-mono text-xs">{{ $farmer->bank_account ? '****' . substr($farmer->bank_account, -4) : '—' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">IFSC</dt>
                            <dd class="font-mono">{{ $farmer->ifsc ?? '—' }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Family Members --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">👨‍👩‍👧 Family Members</h3>
                    @forelse($farmer->familyMembers as $member)
                    <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $member->name }}</p>
                            <p class="text-xs text-gray-400">{{ $member->relation }} · Age {{ $member->age }}</p>
                        </div>
                        <span class="text-xs text-gray-500">{{ $member->occupation }}</span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400 text-center py-2">No family members recorded.</p>
                    @endforelse
                </div>
            </div>

            {{-- ── Right Column (2/3 width) ─────────────────────────────────── --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Land Records --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">🌍 Land Records</h3>
                    @if($farmer->lands->isEmpty())
                    <p class="text-sm text-gray-400 text-center py-4">No land records added.</p>
                    @else
                    <div class="overflow-x-auto">
                        <table class="w-full data-table">
                            <thead><tr><th>Survey No.</th><th>Area (acres)</th><th>Soil Type</th><th>Irrigation</th><th>Ownership</th></tr></thead>
                            <tbody>
                                @foreach($farmer->lands as $land)
                                <tr>
                                    <td class="font-mono text-xs">{{ $land->survey_number }}</td>
                                    <td>{{ $land->area_acres }}</td>
                                    <td>{{ $land->soil_type ?? '—' }}</td>
                                    <td>{{ $land->irrigation_type ?? '—' }}</td>
                                    <td>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs
                                            {{ $land->ownership_type === 'Owned' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                            {{ $land->ownership_type }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>

                {{-- Crop History --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">🌾 Crop History</h3>
                    @if($farmer->cropHistories->isEmpty())
                    <p class="text-sm text-gray-400 text-center py-4">No crop history recorded.</p>
                    @else
                    <div class="overflow-x-auto">
                        <table class="w-full data-table">
                            <thead><tr><th>Crop</th><th>Season</th><th>Year</th><th>Area Used</th><th>Production (kg)</th><th>Price/kg</th></tr></thead>
                            <tbody>
                                @foreach($farmer->cropHistories as $ch)
                                <tr>
                                    <td class="font-medium">{{ $ch->crop?->name }}</td>
                                    <td>{{ $ch->season }}</td>
                                    <td>{{ $ch->year }}</td>
                                    <td>{{ $ch->area_used }} acres</td>
                                    <td>{{ number_format($ch->production_kg) }}</td>
                                    <td>₹{{ number_format($ch->selling_price) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>

                {{-- Scheme Applications --}}
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">📋 Scheme Applications</h3>
                    @forelse($farmer->schemeApplications as $app)
                    <div class="flex items-center justify-between py-2.5 border-b border-gray-50 last:border-0">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $app->scheme?->name }}</p>
                            <p class="text-xs text-gray-400">Applied: {{ $app->applied_date?->format('d M Y') }}</p>
                        </div>
                        <div class="text-right">
                            <span class="badge-{{ $app->status }}">{{ ucfirst($app->status) }}</span>
                            @if($app->subsidy_amount)
                            <p class="text-xs text-green-600 font-medium mt-1">₹{{ number_format($app->subsidy_amount) }}</p>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400 text-center py-4">No applications yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
