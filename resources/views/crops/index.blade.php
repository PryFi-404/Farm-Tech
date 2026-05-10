<x-app-layout header="Crop Master List" breadcrumb="All crop types available in the system">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        {{-- Filters --}}
        <form method="GET" action="{{ route('crops.index') }}" class="flex flex-wrap gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search crop name..." class="form-input text-sm py-1.5 w-44">
            <select name="season" class="form-input text-sm py-1.5 w-36">
                <option value="">All Seasons</option>
                @foreach($seasons as $s)
                <option value="{{ $s }}" {{ request('season') == $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
            <select name="category" class="form-input text-sm py-1.5 w-36">
                <option value="">All Categories</option>
                @foreach($categories as $c)
                <option value="{{ $c }}" {{ request('category') == $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary text-xs px-3 py-1.5">Search</button>
            @if(request()->hasAny(['search','season','category']))
            <a href="{{ route('crops.index') }}" class="btn-secondary text-xs px-3 py-1.5">Clear</a>
            @endif
        </form>

        @if(auth()->user()->isAdmin())
        <a href="{{ route('crops.create') }}" class="btn-primary shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Crop
        </a>
        @endif
    </div>

    {{-- Season tabs / Grid --}}
    @php
        $seasonColors = [
            'Kharif'     => ['bg' => 'bg-blue-50',   'badge' => 'bg-blue-100 text-blue-700',   'border' => 'border-blue-200'],
            'Rabi'       => ['bg' => 'bg-amber-50',  'badge' => 'bg-amber-100 text-amber-700',  'border' => 'border-amber-200'],
            'Zaid'       => ['bg' => 'bg-orange-50', 'badge' => 'bg-orange-100 text-orange-700','border' => 'border-orange-200'],
            'Year Round' => ['bg' => 'bg-green-50',  'badge' => 'bg-green-100 text-green-700',  'border' => 'border-green-200'],
        ];
        $categoryIcons = [
            'Cereal'    => '🌾', 'Pulse' => '🫘', 'Oilseed' => '🌻',
            'Cash Crop' => '💰', 'Vegetable' => '🥦', 'Fruit' => '🍌',
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($crops as $crop)
        @php $sc = $seasonColors[$crop->season] ?? $seasonColors['Year Round']; @endphp
        <div class="bg-white rounded-xl border {{ $sc['border'] }} shadow-sm hover:shadow-md transition-shadow p-4">
            <div class="flex items-start justify-between mb-2">
                <span class="text-2xl">{{ $categoryIcons[$crop->category] ?? '🌿' }}</span>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $sc['badge'] }}">
                    {{ $crop->season }}
                </span>
            </div>
            <h3 class="font-semibold text-gray-800 text-sm">{{ $crop->name }}</h3>
            <p class="text-xs text-gray-500 mt-0.5">{{ $crop->category }}</p>
            @if($crop->description)
            <p class="text-xs text-gray-400 mt-2 line-clamp-2">{{ $crop->description }}</p>
            @endif
            @if(auth()->user()->isAdmin())
            <form method="POST" action="{{ route('crops.destroy', $crop->id) }}"
                  class="mt-3" onsubmit="return confirm('Remove {{ $crop->name }} from master list?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-xs text-red-400 hover:text-red-600 transition-colors">
                    Remove
                </button>
            </form>
            @endif
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-gray-400">
            <p class="font-medium">No crops found matching your filters.</p>
        </div>
        @endforelse
    </div>

    @if($crops->hasPages())
    <div class="mt-5">{{ $crops->links() }}</div>
    @endif

</x-app-layout>
