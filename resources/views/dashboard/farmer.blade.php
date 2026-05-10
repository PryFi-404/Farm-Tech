<x-app-layout header="My Dashboard" breadcrumb="Welcome, {{ auth()->user()->name }}">

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <x-stat-card title="My Land Parcels" :value="$myLands" color="green" :link="route('lands.index')"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4"/></svg>' />
        <x-stat-card title="Crop Records" :value="$myCrops" color="blue"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/></svg>' />
        <x-stat-card title="My Applications" :value="$myApplications->count()" color="orange" :link="route('applications.index')"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>' />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- My Applications --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">📋 My Scheme Applications</h3>
            @forelse($myApplications as $app)
            <div class="flex items-center justify-between py-2.5 border-b border-gray-50 last:border-0">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $app->scheme?->name }}</p>
                    <p class="text-xs text-gray-400">Applied: {{ $app->applied_date?->format('d M Y') }}</p>
                </div>
                <span class="badge-{{ $app->status }}">{{ ucfirst($app->status) }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-6">No applications yet. <a href="{{ route('schemes.index') }}" class="text-farm-600 hover:underline">Browse Schemes →</a></p>
            @endforelse
        </div>

        {{-- Available Schemes --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-700">🏛️ Available Schemes</h3>
                <a href="{{ route('schemes.index') }}" class="text-xs text-farm-600 hover:underline">View all →</a>
            </div>
            @foreach($availableSchemes as $scheme)
            <div class="p-3 mb-2 bg-green-50 rounded-lg border border-green-100">
                <p class="text-sm font-medium text-gray-800">{{ $scheme->name }}</p>
                <p class="text-xs text-gray-500 mt-0.5">₹{{ number_format($scheme->benefit_amount, 0) }} benefit</p>
                <a href="{{ route('applications.create', ['scheme_id' => $scheme->id]) }}"
                   class="mt-2 inline-block text-xs text-farm-600 font-semibold hover:underline">Apply Now →</a>
            </div>
            @endforeach
        </div>
    </div>

</x-app-layout>
