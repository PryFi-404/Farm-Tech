<x-app-layout header="Scheme Details" breadcrumb="Schemes / {{ $scheme->name }}">

    @php
    $categoryColors = [
        'Subsidy'   => 'from-green-700 to-green-500',
        'Insurance' => 'from-blue-700 to-blue-500',
        'Loan'      => 'from-purple-700 to-purple-500',
        'Training'  => 'from-yellow-600 to-yellow-400',
        'Equipment' => 'from-orange-700 to-orange-500',
        'Other'     => 'from-gray-700 to-gray-500',
    ];
    $gradient = $categoryColors[$scheme->category] ?? $categoryColors['Other'];
    @endphp

    {{-- Scheme Header --}}
    <div class="bg-gradient-to-r {{ $gradient }} rounded-xl p-6 text-white shadow mb-6">
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs px-2.5 py-1 rounded-full bg-white/20 font-semibold">{{ $scheme->category }}</span>
                    <span class="text-xs px-2.5 py-1 rounded-full {{ $scheme->is_active ? 'bg-green-400/30' : 'bg-red-400/30' }} font-semibold">
                        {{ $scheme->is_active ? '✅ Active' : '⛔ Inactive' }}
                    </span>
                </div>
                <h1 class="text-xl font-bold leading-snug">{{ $scheme->name }}</h1>
                @if($scheme->ministry)
                <p class="text-white/70 text-sm mt-1">{{ $scheme->ministry }}</p>
                @endif
                <div class="flex flex-wrap gap-4 mt-3 text-sm text-white/80">
                    @if($scheme->benefit_amount)
                    <span>💵 Benefit: ₹{{ number_format($scheme->benefit_amount) }}</span>
                    @endif
                    @if($scheme->end_date)
                    <span>📅 Ends: {{ $scheme->end_date->format('d M Y') }}</span>
                    @endif
                    @if($scheme->max_beneficiaries)
                    <span>👥 Max: {{ $scheme->max_beneficiaries }} beneficiaries</span>
                    @endif
                </div>
            </div>
            @if(auth()->user()->isFarmer())
            {{-- Farmer: show apply button if scheme is active --}}
            @if($scheme->is_active)
            <div class="shrink-0">
                <a href="{{ route('applications.create', ['scheme_id' => $scheme->id]) }}"
                   class="btn-primary text-sm bg-white/20 hover:bg-white/30 border border-white/30">
                    Apply for This Scheme
                </a>
            </div>
            @endif
            @else
            {{-- Admin / Officer actions --}}
            <div class="flex gap-2 shrink-0">
                <a href="{{ route('applications.create', ['scheme_id' => $scheme->id]) }}"
                   class="btn-primary text-sm bg-white/20 hover:bg-white/30 border border-white/30">
                    Apply for Farmer
                </a>
                <a href="{{ route('pdf.scheme', $scheme->id) }}"
                   class="btn-secondary text-sm bg-white/15 hover:bg-white/25 border border-white/30 text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Download PDF
                </a>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('schemes.edit', $scheme->id) }}" class="btn-secondary text-sm">Edit</a>
                @endif
            </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        {{-- Scheme Info --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">📋 Scheme Info</h3>
            <dl class="space-y-2.5 text-sm">
                <div><dt class="text-gray-400 text-xs mb-0.5">Description</dt>
                <dd class="text-gray-700">{{ $scheme->description }}</dd></div>
                @if($scheme->eligibility)
                <div class="pt-2 border-t border-gray-50">
                    <dt class="text-gray-400 text-xs mb-0.5">Eligibility</dt>
                    <dd class="text-gray-700">{{ $scheme->eligibility }}</dd>
                </div>
                @endif
                @if($scheme->start_date)
                <div class="flex justify-between pt-2 border-t border-gray-50">
                    <dt class="text-gray-400">Start Date</dt>
                    <dd class="font-medium">{{ $scheme->start_date->format('d M Y') }}</dd>
                </div>
                @endif
            </dl>
        </div>

        {{-- Application Stats --}}
        <div class="lg:col-span-2 grid grid-cols-2 sm:grid-cols-4 gap-4">
            @foreach([
                ['label'=>'Total Applied', 'value'=>$stats['total'],    'color'=>'text-gray-800',   'bg'=>'bg-gray-50'],
                ['label'=>'Approved',      'value'=>$stats['approved'],  'color'=>'text-green-700',  'bg'=>'bg-green-50'],
                ['label'=>'Pending',       'value'=>$stats['pending'],   'color'=>'text-yellow-700', 'bg'=>'bg-yellow-50'],
                ['label'=>'Rejected',      'value'=>$stats['rejected'],  'color'=>'text-red-700',    'bg'=>'bg-red-50'],
            ] as $stat)
            <div class="{{ $stat['bg'] }} rounded-xl p-4 text-center">
                <p class="text-2xl font-bold {{ $stat['color'] }}">{{ $stat['value'] }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $stat['label'] }}</p>
            </div>
            @endforeach
            <div class="col-span-2 sm:col-span-4 bg-farm-50 rounded-xl p-4 text-center border border-farm-200">
                <p class="text-2xl font-bold text-farm-700">₹{{ number_format($stats['disbursed']) }}</p>
                <p class="text-xs text-gray-500 mt-1">Total Subsidy Disbursed</p>
            </div>
        </div>
    </div>

    {{-- Applications Table --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700">
                {{ auth()->user()->isFarmer() ? 'My Application Status' : 'All Applications' }}
                <span class="ml-2 text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">
                    {{ $applications->total() }}
                </span>
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full data-table">
                <thead>
                    <tr>
                        <th>Farmer</th>
                        <th>Applied On</th>
                        <th>Status</th>
                        <th>Benefit Assigned</th>
                        <th>Remarks</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($applications as $app)
                    <tr>
                        @if(!auth()->user()->isFarmer())
                        {{-- Admin/Officer see farmer name --}}
                        <td>
                            <a href="{{ route('farmers.show', $app->farmer_id) }}"
                               class="font-medium text-farm-600 hover:underline text-sm">
                                {{ $app->farmer?->user?->name }}
                            </a>
                            <p class="text-xs text-gray-400">{{ $app->farmer?->village }}</p>
                        </td>
                        @endif
                        <td class="text-sm text-gray-600">{{ $app->applied_date?->format('d M Y') }}</td>
                        <td>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $app->status_badge }}">
                                {{ ucfirst($app->status) }}
                            </span>
                        </td>
                        <td class="font-semibold {{ $app->subsidy_amount ? 'text-green-600' : 'text-gray-400' }}">
                            {{ $app->subsidy_amount ? '₹' . number_format($app->subsidy_amount) : '—' }}
                        </td>
                        <td class="text-xs text-gray-500 max-w-xs truncate">{{ $app->remarks ?? '—' }}</td>
                        <td class="text-right">
                            <a href="{{ route('applications.show', $app->id) }}"
                               class="text-farm-600 hover:text-farm-800 text-xs font-medium">View →</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-10 text-gray-400">
                        {{ auth()->user()->isFarmer() ? "You haven't applied for this scheme yet." : 'No applications yet.' }}
                    </td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($applications->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">{{ $applications->links() }}</div>
        @endif
    </div>

</x-app-layout>
