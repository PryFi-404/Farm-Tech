<x-app-layout header="Farmers" breadcrumb="Manage all registered farmers">

    {{-- ── Top Bar: Add Button + Search ────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <p class="text-sm text-gray-500">
                Total: <span class="font-semibold text-gray-800">{{ $query->total() }}</span> farmers
            </p>
        </div>
        @if(auth()->user()->isAdmin() || auth()->user()->isOfficer())
        <a href="{{ route('farmers.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Farmer
        </a>
        @endif
    </div>

    {{-- ── Search & Filter Bar ──────────────────────────────────────────── --}}
    <form method="GET" action="{{ route('farmers.index') }}"
          class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <label class="form-label">Search by Name</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Farmer name..." class="form-input">
            </div>
            <div>
                <label class="form-label">Village</label>
                <select name="village" class="form-input">
                    <option value="">All Villages</option>
                    @foreach($villages as $v)
                    <option value="{{ $v }}" {{ request('village') == $v ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">District</label>
                <select name="district" class="form-input">
                    <option value="">All Districts</option>
                    @foreach($districts as $d)
                    <option value="{{ $d }}" {{ request('district') == $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Gender</label>
                <select name="gender" class="form-input">
                    <option value="">All</option>
                    <option value="Male"   {{ request('gender') == 'Male'   ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other"  {{ request('gender') == 'Other'  ? 'selected' : '' }}>Other</option>
                </select>
            </div>
        </div>
        <div class="flex gap-2 mt-3">
            <button type="submit" class="btn-primary text-xs px-3 py-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Search
            </button>
            @if(request()->hasAny(['search','village','district','gender']))
            <a href="{{ route('farmers.index') }}" class="btn-secondary text-xs px-3 py-1.5">Clear Filters</a>
            @endif
        </div>
    </form>

    {{-- ── Farmers Table ─────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Farmer</th>
                        <th>Contact</th>
                        <th>Location</th>
                        <th>Aadhaar</th>
                        <th>Gender</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($query as $farmer)
                    <tr>
                        {{-- Serial Number --}}
                        <td class="text-gray-400 text-xs">
                            {{ ($query->currentPage() - 1) * $query->perPage() + $loop->iteration }}
                        </td>

                        {{-- Farmer Name + Photo --}}
                        <td>
                            <div class="flex items-center gap-3">
                                @if($farmer->photo)
                                <img src="{{ Storage::url($farmer->photo) }}"
                                     class="w-9 h-9 rounded-full object-cover border-2 border-farm-200"
                                     alt="{{ $farmer->user?->name }}">
                                @else
                                <div class="w-9 h-9 rounded-full bg-farm-100 flex items-center justify-center">
                                    <span class="text-farm-600 text-sm font-bold">
                                        {{ strtoupper(substr($farmer->user?->name ?? 'F', 0, 1)) }}
                                    </span>
                                </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-800 text-sm">{{ $farmer->user?->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $farmer->user?->email }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Phone --}}
                        <td class="text-sm text-gray-600">{{ $farmer->phone ?? '—' }}</td>

                        {{-- Location --}}
                        <td>
                            <p class="text-sm text-gray-700">{{ $farmer->village }}</p>
                            <p class="text-xs text-gray-400">{{ $farmer->district }}, {{ $farmer->state }}</p>
                        </td>

                        {{-- Aadhaar (masked) --}}
                        <td class="text-sm text-gray-600 font-mono">
                            ****-****-{{ substr($farmer->aadhaar, -4) }}
                        </td>

                        {{-- Gender Badge --}}
                        <td>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $farmer->gender === 'Female' ? 'bg-pink-100 text-pink-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $farmer->gender }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('farmers.show', $farmer->id) }}"
                                   class="text-farm-600 hover:text-farm-800 transition-colors" title="View Profile">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                @if(auth()->user()->isAdmin() || auth()->user()->isOfficer())
                                <a href="{{ route('farmers.edit', $farmer->id) }}"
                                   class="text-yellow-500 hover:text-yellow-700 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @endif
                                @if(auth()->user()->isAdmin())
                                <form method="POST" action="{{ route('farmers.destroy', $farmer->id) }}"
                                      onsubmit="return confirm('Delete this farmer permanently?')">
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
                        <td colspan="7" class="text-center py-12 text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <p class="font-medium">No farmers found</p>
                            <p class="text-xs mt-1">Try adjusting your filters or <a href="{{ route('farmers.create') }}" class="text-farm-600 hover:underline">add a farmer</a>.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($query->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $query->links() }}
        </div>
        @endif
    </div>

</x-app-layout>
