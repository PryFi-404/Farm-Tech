<x-app-layout header="Land Records" breadcrumb="All land parcels across farmers">

    {{-- Stats bar --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <x-stat-card title="Total Land Parcels" :value="$query->total()" color="green"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4"/></svg>' />
        <x-stat-card title="Total Area (acres)" :value="number_format($totalArea, 2)" color="blue"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>' />
        <x-stat-card title="Avg Area / Parcel" :value="$query->total() > 0 ? number_format($totalArea / $query->total(), 2) . ' ac' : '—'" color="orange"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10"/></svg>' />
    </div>

    {{-- Action + Filter bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
        <form method="GET" action="{{ route('lands.index') }}" class="flex flex-wrap gap-2">
            <select name="farmer_id" class="form-input text-sm py-1.5 w-48">
                <option value="">All Farmers</option>
                @foreach($farmers as $f)
                <option value="{{ $f->id }}" {{ request('farmer_id') == $f->id ? 'selected' : '' }}>{{ $f->user?->name }}</option>
                @endforeach
            </select>
            <select name="soil_type" class="form-input text-sm py-1.5 w-36">
                <option value="">All Soils</option>
                @foreach($soilTypes as $s)
                <option value="{{ $s }}" {{ request('soil_type') == $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
            <select name="ownership_type" class="form-input text-sm py-1.5 w-36">
                <option value="">All Ownership</option>
                @foreach(['Owned','Leased','Shared'] as $o)
                <option value="{{ $o }}" {{ request('ownership_type') == $o ? 'selected' : '' }}>{{ $o }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary text-xs px-3 py-1.5">Filter</button>
            @if(request()->hasAny(['farmer_id','soil_type','ownership_type']))
            <a href="{{ route('lands.index') }}" class="btn-secondary text-xs px-3 py-1.5">Clear</a>
            @endif
        </form>
        @if(auth()->user()->isAdmin() || auth()->user()->isOfficer())
        <a href="{{ route('lands.create') }}" class="btn-primary shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Land Record
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
                        <th>Survey No.</th>
                        <th>Area (acres)</th>
                        <th>Soil Type</th>
                        <th>Irrigation</th>
                        <th>Ownership</th>
                        <th>Khasra No.</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($query as $land)
                    <tr>
                        <td>
                            <a href="{{ route('farmers.show', $land->farmer_id) }}"
                               class="text-farm-600 hover:underline font-medium text-sm">
                                {{ $land->farmer?->user?->name }}
                            </a>
                        </td>
                        <td class="font-mono text-xs">{{ $land->survey_number }}</td>
                        <td class="font-semibold">{{ $land->area_acres }}</td>
                        <td>{{ $land->soil_type ?? '—' }}</td>
                        <td>{{ $land->irrigation_type ?? '—' }}</td>
                        <td>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs
                                {{ $land->ownership_type === 'Owned'  ? 'bg-green-100 text-green-700' :
                                  ($land->ownership_type === 'Leased' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">
                                {{ $land->ownership_type }}
                            </span>
                        </td>
                        <td class="font-mono text-xs">{{ $land->khasra_number ?? '—' }}</td>
                        <td class="text-right">
                            <div class="flex justify-end gap-2">
                                @if(auth()->user()->isAdmin() || auth()->user()->isOfficer())
                                <a href="{{ route('lands.edit', $land->id) }}"
                                   class="text-yellow-500 hover:text-yellow-700 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @endif
                                @if(auth()->user()->isAdmin())
                                <form method="POST" action="{{ route('lands.destroy', $land->id) }}"
                                      onsubmit="return confirm('Delete this land record?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600 transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-10 text-gray-400">
                            <p class="font-medium">No land records found.</p>
                        </td>
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
