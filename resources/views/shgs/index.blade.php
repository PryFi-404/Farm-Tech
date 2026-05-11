<x-app-layout header="SHG / FPG Groups" breadcrumb="Self Help Groups & Farmer Producer Groups">

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <x-stat-card title="Total Groups" :value="$stats['total']" color="green"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'/>
        <x-stat-card title="SHG Groups" :value="$stats['shg']" color="blue"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>'/>
        <x-stat-card title="FPG Groups" :value="$stats['fpg']" color="purple"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>'/>
        <x-stat-card title="Active Members" :value="$stats['members']" color="orange"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>'/>
    </div>

    {{-- Filters + Add --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <form method="GET" action="{{ route('shgs.index') }}" class="flex flex-wrap gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search group name / reg no..." class="form-input text-sm py-1.5 w-56">
            <select name="type" class="form-input text-sm py-1.5 w-32">
                <option value="">All Types</option>
                @foreach(['SHG','FPG','FPC','JLG'] as $t)
                <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
            <select name="district" class="form-input text-sm py-1.5 w-36">
                <option value="">All Districts</option>
                @foreach($districts as $d)
                <option value="{{ $d }}" {{ request('district') == $d ? 'selected' : '' }}>{{ $d }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary text-xs px-3 py-1.5">Filter</button>
            @if(request()->hasAny(['search','type','district']))
            <a href="{{ route('shgs.index') }}" class="btn-secondary text-xs px-3 py-1.5">Clear</a>
            @endif
        </form>
        @if(auth()->user()->isAdmin() || auth()->user()->isOfficer())
        <a href="{{ route('shgs.create') }}" class="btn-primary shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Group
        </a>
        @endif
    </div>

    {{-- Group Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($query as $shg)
        @php
        $typeColors = [
            'SHG' => ['bg'=>'bg-blue-50', 'badge'=>'bg-blue-100 text-blue-700', 'border'=>'border-blue-200'],
            'FPG' => ['bg'=>'bg-purple-50','badge'=>'bg-purple-100 text-purple-700','border'=>'border-purple-200'],
            'FPC' => ['bg'=>'bg-amber-50', 'badge'=>'bg-amber-100 text-amber-700', 'border'=>'border-amber-200'],
            'JLG' => ['bg'=>'bg-green-50', 'badge'=>'bg-green-100 text-green-700', 'border'=>'border-green-200'],
        ];
        $tc = $typeColors[$shg->type] ?? $typeColors['SHG'];
        @endphp

        <div class="bg-white rounded-xl border {{ $tc['border'] }} shadow-sm hover:shadow-md transition-shadow p-5">
            {{-- Header --}}
            <div class="flex items-start justify-between mb-3">
                <div class="flex-1 min-w-0">
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $tc['badge'] }} mb-2 inline-block">
                        {{ $shg->type }}
                    </span>
                    <h3 class="font-bold text-gray-800 text-sm leading-tight">{{ $shg->name }}</h3>
                    @if($shg->registration_number)
                    <p class="text-xs text-gray-400 mt-0.5 font-mono">{{ $shg->registration_number }}</p>
                    @endif
                </div>
                <div class="w-12 h-12 rounded-xl {{ $tc['bg'] }} flex items-center justify-center shrink-0 ml-3">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>

            {{-- Info --}}
            <div class="space-y-1.5 text-xs text-gray-500 mb-4">
                <div class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                    {{ $shg->village }}, {{ $shg->block }}, {{ $shg->district }}
                </div>
                @if($shg->formation_date)
                <div class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Formed: {{ $shg->formation_date->format('d M Y') }}
                </div>
                @endif
                <div class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="font-semibold text-gray-700">{{ $shg->shg_members_count }}</span> members
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-2 pt-3 border-t border-gray-100">
                <a href="{{ route('shgs.show', $shg->id) }}"
                   class="flex-1 text-center text-xs font-medium text-farm-600 hover:text-farm-800 py-1.5 rounded-lg hover:bg-farm-50 transition-colors">
                    View Group →
                </a>
                @if(auth()->user()->isAdmin() || auth()->user()->isOfficer())
                <a href="{{ route('shgs.edit', $shg->id) }}"
                   class="text-xs font-medium text-yellow-600 hover:text-yellow-800 py-1.5 px-3 rounded-lg hover:bg-yellow-50 transition-colors">
                    Edit
                </a>
                @endif
                @if(auth()->user()->isAdmin())
                <form method="POST" action="{{ route('shgs.destroy', $shg->id) }}"
                      onsubmit="return confirm('Delete {{ $shg->name }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs font-medium text-red-400 hover:text-red-600 py-1.5 px-2 rounded-lg hover:bg-red-50 transition-colors">
                        Delete
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-16 text-gray-400">
            <svg class="w-14 h-14 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="font-medium text-gray-500">No groups found.</p>
            <p class="text-sm mt-1">
                <a href="{{ route('shgs.create') }}" class="text-farm-600 hover:underline">Create your first SHG/FPG group →</a>
            </p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($query->hasPages())
    <div class="mt-5">{{ $query->links() }}</div>
    @endif

</x-app-layout>
