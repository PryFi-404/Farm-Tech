<x-app-layout header="Notifications" breadcrumb="All your notifications">

    <div class="max-w-2xl mx-auto">

        {{-- Header with mark all read --}}
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-base font-bold text-gray-800">Notification Center</h2>
                <p class="text-sm text-gray-400">
                    {{ auth()->user()->unreadNotifications->count() }} unread
                    &nbsp;·&nbsp; {{ $notifications->total() }} total
                </p>
            </div>
            @if(auth()->user()->unreadNotifications->count() > 0)
            <form method="POST" action="{{ route('notifications.markAllRead') }}">
                @csrf
                <button type="submit"
                        class="text-xs font-medium text-farm-600 hover:text-farm-800 border border-farm-200 px-3 py-1.5 rounded-lg transition-colors">
                    ✅ Mark all as read
                </button>
            </form>
            @endif
        </div>

        {{-- Notification Cards --}}
        <div class="space-y-2">
            @forelse($notifications as $notif)
            @php
                $data    = $notif->data;
                $isRead  = $notif->read_at !== null;
                $color   = $data['color'] ?? 'blue';
                $colorMap = [
                    'blue'  => ['ring'=>'ring-blue-200',  'bg'=>'bg-blue-50',  'dot'=>'bg-blue-500',  'badge'=>'bg-blue-100 text-blue-700'],
                    'green' => ['ring'=>'ring-green-200', 'bg'=>'bg-green-50', 'dot'=>'bg-green-500', 'badge'=>'bg-green-100 text-green-700'],
                    'red'   => ['ring'=>'ring-red-200',   'bg'=>'bg-red-50',   'dot'=>'bg-red-500',   'badge'=>'bg-red-100 text-red-700'],
                ];
                $c = $colorMap[$color] ?? $colorMap['blue'];
            @endphp
            <div class="bg-white rounded-xl border {{ !$isRead ? 'border-l-4 border-l-farm-500 shadow-sm' : 'border-gray-100' }} p-4
                         flex items-start gap-4 transition-all
                         {{ !$isRead ? 'shadow-sm' : 'opacity-75' }}">

                {{-- Icon --}}
                <div class="w-10 h-10 rounded-full {{ $c['bg'] }} ring-2 {{ $c['ring'] }} flex items-center justify-center text-lg shrink-0">
                    {{ $data['icon'] ?? '🔔' }}
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2 mb-1">
                        <p class="text-sm font-semibold text-gray-800 leading-snug">{{ $data['title'] ?? 'Notification' }}</p>
                        <div class="flex items-center gap-2 shrink-0">
                            @if(!$isRead)
                            <span class="inline-block w-2 h-2 rounded-full {{ $c['dot'] }}"></span>
                            @endif
                            <span class="text-xs text-gray-400 whitespace-nowrap">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 leading-relaxed">{{ $data['message'] ?? '' }}</p>

                    {{-- Actions --}}
                    <div class="flex items-center gap-3 mt-2.5">
                        @if(isset($data['url']))
                        <a href="{{ route('notifications.read', $notif->id) }}"
                           class="text-xs font-medium text-farm-600 hover:text-farm-800 transition-colors">
                            View Details →
                        </a>
                        @endif
                        <form method="POST"
                              action="{{ route('notifications.destroy', $notif->id) }}"
                              onsubmit="return confirm('Remove this notification?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-gray-400 hover:text-red-500 transition-colors">
                                Remove
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-16 text-gray-400">
                <div class="text-5xl mb-4">🔔</div>
                <p class="font-medium text-gray-500">You're all caught up!</p>
                <p class="text-sm mt-1">No notifications yet.</p>
            </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
        <div class="mt-5">{{ $notifications->links() }}</div>
        @endif

    </div>

</x-app-layout>
