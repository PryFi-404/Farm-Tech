<x-app-layout header="Group Profile" breadcrumb="SHG / FPG / {{ $shg->name }}">

    @php
    $typeColors = [
        'SHG' => ['gradient'=>'from-blue-700 to-blue-500',   'badge'=>'bg-blue-100 text-blue-700'],
        'FPG' => ['gradient'=>'from-purple-700 to-purple-500','badge'=>'bg-purple-100 text-purple-700'],
        'FPC' => ['gradient'=>'from-amber-700 to-amber-500',  'badge'=>'bg-amber-100 text-amber-700'],
        'JLG' => ['gradient'=>'from-farm-700 to-farm-500',    'badge'=>'bg-green-100 text-green-700'],
    ];
    $tc = $typeColors[$shg->type] ?? $typeColors['SHG'];
    @endphp

    {{-- ── Group Header Banner ──────────────────────────────────────────── --}}
    <div class="bg-gradient-to-r {{ $tc['gradient'] }} rounded-xl p-6 text-white shadow mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-white/20 flex items-center justify-center shrink-0 border-4 border-white/30">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <h1 class="text-xl font-bold">{{ $shg->name }}</h1>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-white/20 font-semibold">{{ $shg->type }}</span>
                </div>
                @if($shg->registration_number)
                <p class="text-white/70 text-sm font-mono">{{ $shg->registration_number }}</p>
                @endif
                <div class="flex flex-wrap gap-4 mt-2 text-sm text-white/80">
                    <span>📍 {{ $shg->village }}, {{ $shg->block }}, {{ $shg->district }}</span>
                    @if($shg->formation_date)
                    <span>📅 Formed: {{ $shg->formation_date->format('d M Y') }}</span>
                    @endif
                    <span>👥 {{ $shg->shgMembers->count() }} Members</span>
                </div>
            </div>
            @if(auth()->user()->isAdmin() || auth()->user()->isOfficer())
            <div class="flex gap-2 shrink-0">
                <a href="{{ route('shgs.edit', $shg->id) }}" class="btn-secondary text-sm">Edit Group</a>
            </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ── Left: Group Details ─────────────────────────────────────────── --}}
        <div class="space-y-5">

            {{-- Group Info --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">ℹ️ Group Details</h3>
                <dl class="space-y-2.5 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Type</dt>
                        <dd><span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $tc['badge'] }}">{{ $shg->type }}</span></dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Leader</dt>
                        <dd class="font-medium text-right">
                            @if($shg->leader)
                            <a href="{{ route('farmers.show', $shg->leader->id) }}"
                               class="text-farm-600 hover:underline">{{ $shg->leader->user?->name }}</a>
                            @else
                            <span class="text-gray-400">—</span>
                            @endif
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Block</dt>
                        <dd class="font-medium">{{ $shg->block }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">District</dt>
                        <dd class="font-medium">{{ $shg->district }}</dd>
                    </div>
                    @if($shg->bank_account)
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Bank Account</dt>
                        <dd class="font-mono text-xs">****{{ substr($shg->bank_account, -4) }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            {{-- Add Member Form --}}
            @if(auth()->user()->isAdmin() || auth()->user()->isOfficer())
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">➕ Add Member</h3>
                @if($availableFarmers->isEmpty())
                <p class="text-xs text-gray-400 text-center py-3">All registered farmers are already members.</p>
                @else
                <form method="POST" action="{{ route('shgs.members.add', $shg->id) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="form-label">Select Farmer</label>
                        <select name="farmer_id" class="form-input" required>
                            <option value="">Choose farmer...</option>
                            @foreach($availableFarmers as $f)
                            <option value="{{ $f->id }}">{{ $f->user?->name }} — {{ $f->village }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Role in Group</label>
                        <select name="role" class="form-input" required>
                            <option value="Member">Member</option>
                            <option value="President">President</option>
                            <option value="Secretary">Secretary</option>
                            <option value="Treasurer">Treasurer</option>
                            <option value="Chairman">Chairman</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Joining Date</label>
                        <input type="date" name="joined_date" value="{{ date('Y-m-d') }}" class="form-input" required>
                    </div>
                    <button type="submit" class="btn-primary w-full justify-center text-sm">
                        Add to Group
                    </button>
                </form>
                @endif
            </div>
            @endif
        </div>

        {{-- ── Right: Members Table ─────────────────────────────────────────── --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700">
                        👥 Members
                        <span class="ml-2 text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">
                            {{ $shg->shgMembers->count() }}
                        </span>
                    </h3>
                </div>

                @if($shg->shgMembers->isEmpty())
                <div class="text-center py-12 text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-2 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <p class="text-sm font-medium">No members yet.</p>
                    <p class="text-xs mt-1">Add farmers from the panel on the left.</p>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Farmer</th>
                                <th>Role</th>
                                <th>Village</th>
                                <th>Joined</th>
                                <th>Status</th>
                                @if(auth()->user()->isAdmin() || auth()->user()->isOfficer())
                                <th></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shg->shgMembers as $i => $member)
                            <tr>
                                <td class="text-gray-400 text-xs">{{ $i + 1 }}</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-farm-100 flex items-center justify-center shrink-0">
                                            <span class="text-farm-600 text-xs font-bold">
                                                {{ strtoupper(substr($member->farmer?->user?->name ?? 'F', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <a href="{{ route('farmers.show', $member->farmer_id) }}"
                                               class="text-farm-600 hover:underline font-medium text-sm">
                                                {{ $member->farmer?->user?->name }}
                                            </a>
                                            <p class="text-xs text-gray-400">{{ $member->farmer?->phone }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if(in_array($member->role, ['President','Chairman','Secretary','Treasurer']))
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">
                                        {{ $member->role }}
                                    </span>
                                    @else
                                    <span class="text-xs text-gray-500">{{ $member->role }}</span>
                                    @endif
                                </td>
                                <td class="text-sm text-gray-600">{{ $member->farmer?->village ?? '—' }}</td>
                                <td class="text-sm text-gray-500">{{ $member->joined_date?->format('d M Y') ?? '—' }}</td>
                                <td>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        {{ $member->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ ucfirst($member->status) }}
                                    </span>
                                </td>
                                @if(auth()->user()->isAdmin() || auth()->user()->isOfficer())
                                <td>
                                    <form method="POST"
                                          action="{{ route('shgs.members.remove', [$shg->id, $member->id]) }}"
                                          onsubmit="return confirm('Remove {{ $member->farmer?->user?->name }} from this group?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="text-red-400 hover:text-red-600 transition-colors" title="Remove">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h16v-1a6 6 0 00-6-6h-4zm8-4h6m0 0l-3-3m3 3l-3 3"/>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

</x-app-layout>
