<x-app-layout header="Crop Production History" breadcrumb="All crop records across farmers">

    {{-- Summary Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <x-stat-card title="Total Records" :value="$query->total()" color="green"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>' />
        <x-stat-card title="Total Production (kg)" :value="number_format($totalProduction)" color="blue"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>' />
        <x-stat-card title="Est. Total Revenue" :value="'₹' . number_format($totalRevenue)" color="purple"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2"/></svg>' />
    </div>

    {{-- Filters + Add Button --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <form method="GET" action="{{ route('crop-history.index') }}" class="flex flex-wrap gap-2">
            <select name="farmer_id" class="form-input text-sm py-1.5 w-44">
                <option value="">All Farmers</option>
                @foreach($farmers as $f)
                <option value="{{ $f->id }}" {{ request('farmer_id') == $f->id ? 'selected' : '' }}>{{ $f->user?->name }}</option>
                @endforeach
            </select>
            <select name="crop_id" class="form-input text-sm py-1.5 w-36">
                <option value="">All Crops</option>
                @foreach($crops as $c)
                <option value="{{ $c->id }}" {{ request('crop_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
            <select name="season" class="form-input text-sm py-1.5 w-32">
                <option value="">All Seasons</option>
                @foreach(['Kharif','Rabi','Zaid','Year Round'] as $s)
                <option value="{{ $s }}" {{ request('season') == $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
            <select name="year" class="form-input text-sm py-1.5 w-28">
                <option value="">All Years</option>
                @foreach($years as $y)
                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary text-xs px-3 py-1.5">Filter</button>
            @if(request()->hasAny(['farmer_id','crop_id','season','year']))
            <a href="{{ route('crop-history.index') }}" class="btn-secondary text-xs px-3 py-1.5">Clear</a>
            @endif
        </form>

        @if(auth()->user()->isAdmin() || auth()->user()->isOfficer())
        <a href="{{ route('crop-history.create') }}" class="btn-primary shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Record Crop
        </a>
        @endif
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full data-table">
                <thead>
                    <tr>
                        <th>Farmer</th>
                        <th>Crop</th>
                        <th>Season / Year</th>
                        <th>Land</th>
                        <th>Area Used</th>
                        <th>Production (kg)</th>
                        <th>Price/kg</th>
                        <th>Est. Revenue</th>
                        @if(auth()->user()->isAdmin())<th></th>@endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($query as $ch)
                    <tr>
                        <td>
                            <a href="{{ route('farmers.show', $ch->farmer_id) }}"
                               class="text-farm-600 hover:underline font-medium text-sm">
                                {{ $ch->farmer?->user?->name }}
                            </a>
                        </td>
                        <td class="font-medium">{{ $ch->crop?->name }}</td>
                        <td>
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">
                                {{ $ch->season }}
                            </span>
                            <span class="text-xs text-gray-500 ml-1">{{ $ch->year }}</span>
                        </td>
                        <td class="font-mono text-xs">{{ $ch->land?->survey_number ?? '—' }}</td>
                        <td>{{ $ch->area_used }} ac</td>
                        <td class="font-semibold">{{ number_format($ch->production_kg) }}</td>
                        <td>₹{{ number_format($ch->selling_price) }}</td>
                        <td class="text-green-600 font-semibold">
                            ₹{{ number_format($ch->production_kg * $ch->selling_price) }}
                        </td>
                        @if(auth()->user()->isAdmin())
                        <td>
                            <form method="POST" action="{{ route('crop-history.destroy', $ch->id) }}"
                                  onsubmit="return confirm('Delete this crop record?')">
                                @csrf @method('DELETE')
                                <button class="text-red-400 hover:text-red-600 transition-colors" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-10 text-gray-400">No crop history records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($query->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">{{ $query->links() }}</div>
        @endif
    </div>

</x-app-layout>
