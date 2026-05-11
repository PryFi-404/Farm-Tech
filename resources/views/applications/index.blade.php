<x-app-layout header="Scheme Applications" breadcrumb="All farmer scheme applications">

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mb-6">
        <div class="bg-gray-50 rounded-xl p-4 text-center border border-gray-200">
            <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Total</p>
        </div>
        <div class="bg-yellow-50 rounded-xl p-4 text-center border border-yellow-200">
            <p class="text-2xl font-bold text-yellow-700">{{ $stats['pending'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Pending</p>
        </div>
        <div class="bg-green-50 rounded-xl p-4 text-center border border-green-200">
            <p class="text-2xl font-bold text-green-700">{{ $stats['approved'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Approved</p>
        </div>
        <div class="bg-red-50 rounded-xl p-4 text-center border border-red-200">
            <p class="text-2xl font-bold text-red-700">{{ $stats['rejected'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Rejected</p>
        </div>
        <div class="bg-farm-50 rounded-xl p-4 text-center border border-farm-200 col-span-2 sm:col-span-1">
            <p class="text-xl font-bold text-farm-700">₹{{ number_format($stats['disbursed']) }}</p>
            <p class="text-xs text-gray-500 mt-1">Disbursed</p>
        </div>
    </div>

    {{-- Filters + Add Button --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <form method="GET" action="{{ route('applications.index') }}" class="flex flex-wrap gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search farmer name..." class="form-input text-sm py-1.5 w-48">
            <select name="scheme_id" class="form-input text-sm py-1.5 w-44">
                <option value="">All Schemes</option>
                @foreach($schemes as $s)
                <option value="{{ $s->id }}" {{ request('scheme_id') == $s->id ? 'selected' : '' }}>{{ Str::limit($s->name, 30) }}</option>
                @endforeach
            </select>
            <select name="status" class="form-input text-sm py-1.5 w-32">
                <option value="">All Status</option>
                @foreach(['pending','approved','rejected'] as $st)
                <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary text-xs px-3 py-1.5">Filter</button>
            @if(request()->hasAny(['search','scheme_id','status','farmer_id']))
            <a href="{{ route('applications.index') }}" class="btn-secondary text-xs px-3 py-1.5">Clear</a>
            @endif
        </form>
        @if(auth()->user()->isAdmin() || auth()->user()->isOfficer())
        <a href="{{ route('applications.create') }}" class="btn-primary shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Application
        </a>
        @endif
    </div>

    {{-- Applications Table --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Farmer</th>
                        <th>Scheme</th>
                        <th>Applied On</th>
                        <th>Status</th>
                        <th>Benefit</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($query as $app)
                    <tr>
                        <td class="text-gray-400 text-xs">{{ ($query->currentPage()-1)*$query->perPage()+$loop->iteration }}</td>
                        <td>
                            <a href="{{ route('farmers.show', $app->farmer_id) }}"
                               class="font-medium text-farm-600 hover:underline text-sm">
                                {{ $app->farmer?->user?->name }}
                            </a>
                            <p class="text-xs text-gray-400">{{ $app->farmer?->village }}, {{ $app->farmer?->district }}</p>
                        </td>
                        <td>
                            <a href="{{ route('schemes.show', $app->scheme_id) }}"
                               class="text-sm text-gray-700 hover:text-farm-600 font-medium">
                                {{ Str::limit($app->scheme?->name, 35) }}
                            </a>
                            <p class="text-xs text-gray-400">{{ $app->scheme?->category }}</p>
                        </td>
                        <td class="text-sm text-gray-600">{{ $app->applied_date?->format('d M Y') }}</td>
                        <td>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $app->status_badge }}">
                                {{ ucfirst($app->status) }}
                            </span>
                        </td>
                        <td class="{{ $app->subsidy_amount ? 'text-green-600 font-semibold' : 'text-gray-400' }}">
                            {{ $app->subsidy_amount ? '₹'.number_format($app->subsidy_amount) : '—' }}
                        </td>
                        <td class="text-right">
                            <a href="{{ route('applications.show', $app->id) }}"
                               class="text-farm-600 hover:text-farm-800 font-medium text-xs">
                                View →
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-400">
                            <p class="font-medium">No applications found.</p>
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
