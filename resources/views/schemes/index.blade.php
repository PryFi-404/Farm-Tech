<x-app-layout header="Government Schemes" breadcrumb="All available beneficiary schemes">

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <x-stat-card title="Total Schemes" :value="$stats['total']" color="blue"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>'/>
        <x-stat-card title="Active Schemes" :value="$stats['active']" color="green"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'/>
        <x-stat-card title="Inactive Schemes" :value="$stats['inactive']" color="orange"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'/>
        <x-stat-card title="Total Applications" :value="$stats['applied']" color="purple"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857"/></svg>'/>
    </div>

    {{-- Filters + Add --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <form method="GET" action="{{ route('schemes.index') }}" class="flex flex-wrap gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search scheme name..." class="form-input text-sm py-1.5 w-52">
            <select name="category" class="form-input text-sm py-1.5 w-36">
                <option value="">All Categories</option>
                @foreach(['Subsidy','Insurance','Loan','Training','Equipment','Other'] as $c)
                <option value="{{ $c }}" {{ request('category') == $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>
            <select name="status" class="form-input text-sm py-1.5 w-32">
                <option value="">All Status</option>
                <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="btn-primary text-xs px-3 py-1.5">Filter</button>
            @if(request()->hasAny(['search','category','status']))
            <a href="{{ route('schemes.index') }}" class="btn-secondary text-xs px-3 py-1.5">Clear</a>
            @endif
        </form>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('schemes.create') }}" class="btn-primary shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Scheme
        </a>
        @endif
    </div>

    {{-- Scheme Cards Grid --}}
    @php
    $categoryColors = [
        'Subsidy'   => ['border'=>'border-green-200',  'badge'=>'bg-green-100 text-green-700',   'icon'=>'💰'],
        'Insurance' => ['border'=>'border-blue-200',   'badge'=>'bg-blue-100 text-blue-700',     'icon'=>'🛡️'],
        'Loan'      => ['border'=>'border-purple-200', 'badge'=>'bg-purple-100 text-purple-700', 'icon'=>'🏦'],
        'Training'  => ['border'=>'border-yellow-200', 'badge'=>'bg-yellow-100 text-yellow-700', 'icon'=>'📚'],
        'Equipment' => ['border'=>'border-orange-200', 'badge'=>'bg-orange-100 text-orange-700', 'icon'=>'🚜'],
        'Other'     => ['border'=>'border-gray-200',   'badge'=>'bg-gray-100 text-gray-600',     'icon'=>'📋'],
    ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($schemes as $scheme)
        @php $cc = $categoryColors[$scheme->category] ?? $categoryColors['Other']; @endphp
        <div class="bg-white rounded-xl border {{ $cc['border'] }} shadow-sm hover:shadow-md transition-shadow flex flex-col">
            <div class="p-5 flex-1">
                {{-- Header --}}
                <div class="flex items-start justify-between mb-3">
                    <span class="text-2xl">{{ $cc['icon'] }}</span>
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $cc['badge'] }}">
                            {{ $scheme->category }}
                        </span>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full
                            {{ $scheme->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $scheme->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <h3 class="font-bold text-gray-800 text-sm leading-snug mb-1">{{ $scheme->name }}</h3>

                @if($scheme->ministry)
                <p class="text-xs text-gray-400 mb-2">{{ $scheme->ministry }}</p>
                @endif

                <p class="text-xs text-gray-500 line-clamp-3 mb-3">{{ $scheme->description }}</p>

                {{-- Meta --}}
                <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-500">
                    @if($scheme->benefit_amount)
                    <span>💵 Up to ₹{{ number_format($scheme->benefit_amount) }}</span>
                    @endif
                    @if($scheme->end_date)
                    <span>📅 Ends: {{ $scheme->end_date->format('d M Y') }}</span>
                    @endif
                    <span>📝 {{ $scheme->applications_count }} applications</span>
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between gap-2">
                <a href="{{ route('schemes.show', $scheme->id) }}"
                   class="text-xs font-medium text-farm-600 hover:text-farm-800 transition-colors">
                    View Details →
                </a>
                <div class="flex gap-2">
                    {{-- Apply button --}}
                    @if($scheme->is_active && (auth()->user()->isAdmin() || auth()->user()->isOfficer()))
                    <a href="{{ route('applications.create', ['scheme_id' => $scheme->id]) }}"
                       class="text-xs font-medium text-green-600 hover:text-green-800 transition-colors">
                        Apply
                    </a>
                    @endif
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('schemes.edit', $scheme->id) }}"
                       class="text-xs font-medium text-yellow-600 hover:text-yellow-800 transition-colors">
                        Edit
                    </a>
                    {{-- Quick Toggle --}}
                    <form method="POST" action="{{ route('schemes.toggle', $scheme->id) }}">
                        @csrf
                        <button type="submit"
                                class="text-xs font-medium {{ $scheme->is_active ? 'text-orange-500 hover:text-orange-700' : 'text-green-500 hover:text-green-700' }} transition-colors">
                            {{ $scheme->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-14 text-gray-400">
            <p class="font-medium text-gray-500">No schemes found.</p>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('schemes.create') }}" class="text-farm-600 hover:underline text-sm mt-1 inline-block">Add first scheme →</a>
            @endif
        </div>
        @endforelse
    </div>

    @if($schemes->hasPages())
    <div class="mt-5">{{ $schemes->links() }}</div>
    @endif

</x-app-layout>
