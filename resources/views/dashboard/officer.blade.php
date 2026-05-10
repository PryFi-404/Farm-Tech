<x-app-layout header="Field Officer Dashboard" breadcrumb="Your activity overview">

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
        <x-stat-card title="Total Farmers" :value="number_format($totalFarmers)" color="green" :link="route('farmers.index')"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>' />
        <x-stat-card title="Pending Reviews" :value="number_format($pendingApps)" color="orange" :link="route('applications.index')"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' />
        <x-stat-card title="Approved" :value="number_format($approvedApps)" color="blue"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' />
        <x-stat-card title="SHG / FPG Groups" :value="number_format($totalSHGs)" color="purple" :link="route('shgs.index')"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>' />
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">🕐 Recent Applications to Review</h3>
        <table class="w-full data-table">
            <thead><tr><th>Farmer</th><th>Scheme</th><th>Date</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
                @foreach($recentApplications as $app)
                <tr>
                    <td class="font-medium">{{ $app->farmer?->user?->name ?? '—' }}</td>
                    <td>{{ $app->scheme?->name ?? '—' }}</td>
                    <td>{{ $app->applied_date?->format('d M Y') }}</td>
                    <td><span class="badge-{{ $app->status }}">{{ ucfirst($app->status) }}</span></td>
                    <td><a href="{{ route('applications.show', $app->id) }}" class="text-farm-600 hover:underline text-xs font-medium">Review</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</x-app-layout>
