<x-app-layout header="Application Detail" breadcrumb="Applications / #{{ $application->id }}">

    <div class="max-w-3xl mx-auto space-y-5">

        {{-- Status Banner --}}
        @php
        $bannerClass = match($application->status) {
            'approved' => 'bg-gradient-to-r from-green-700 to-green-500',
            'rejected' => 'bg-gradient-to-r from-red-700 to-red-500',
            default    => 'bg-gradient-to-r from-yellow-600 to-yellow-400',
        };
        $statusIcon = match($application->status) {
            'approved' => '✅', 'rejected' => '❌', default => '⏳',
        };
        @endphp
        <div class="{{ $bannerClass }} rounded-xl p-5 text-white shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/70 text-sm mb-1">Application #{{ $application->id }}</p>
                    <h2 class="text-lg font-bold">
                        {{ $statusIcon }} {{ ucfirst($application->status) }}
                    </h2>
                    <p class="text-white/80 text-sm mt-1">
                        Applied: {{ $application->applied_date?->format('d M Y') }}
                        @if($application->approved_date)
                        &nbsp;·&nbsp; Reviewed: {{ $application->approved_date->format('d M Y') }}
                        @endif
                    </p>
                </div>
                @if($application->subsidy_amount)
                <div class="text-right">
                    <p class="text-white/70 text-xs">Benefit Assigned</p>
                    <p class="text-3xl font-bold">₹{{ number_format($application->subsidy_amount) }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            {{-- Farmer Info --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">👨‍🌾 Farmer Details</h3>
                <dl class="space-y-2.5 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Name</dt>
                        <dd>
                            <a href="{{ route('farmers.show', $application->farmer_id) }}"
                               class="font-medium text-farm-600 hover:underline">
                                {{ $application->farmer?->user?->name }}
                            </a>
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Village</dt>
                        <dd class="font-medium">{{ $application->farmer?->village }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">District</dt>
                        <dd class="font-medium">{{ $application->farmer?->district }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Aadhaar</dt>
                        <dd class="font-mono text-xs">****-****-{{ substr($application->farmer?->aadhaar, -4) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Phone</dt>
                        <dd class="font-medium">{{ $application->farmer?->phone }}</dd>
                    </div>
                    @if($application->farmer?->bank_account)
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Bank Account</dt>
                        <dd class="font-mono text-xs">****{{ substr($application->farmer?->bank_account, -4) }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            {{-- Scheme Info --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">📋 Scheme Details</h3>
                <dl class="space-y-2.5 text-sm">
                    <div>
                        <dt class="text-gray-500 mb-1">Scheme Name</dt>
                        <dd class="font-medium">
                            <a href="{{ route('schemes.show', $application->scheme_id) }}"
                               class="text-farm-600 hover:underline">
                                {{ $application->scheme?->name }}
                            </a>
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Category</dt>
                        <dd class="font-medium">{{ $application->scheme?->category }}</dd>
                    </div>
                    @if($application->scheme?->benefit_amount)
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Max Benefit</dt>
                        <dd class="font-semibold text-green-600">₹{{ number_format($application->scheme?->benefit_amount) }}</dd>
                    </div>
                    @endif
                </dl>
                @if($application->remarks)
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <p class="text-xs text-gray-400 mb-1">Applicant Remarks</p>
                    <p class="text-sm text-gray-600">{{ $application->remarks }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- ── Approve / Reject Panel (pending only) ────────────────────── --}}
        @if($application->status === 'pending' && (auth()->user()->isAdmin() || auth()->user()->isOfficer()))
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            {{-- Approve Form --}}
            <div class="bg-green-50 border border-green-200 rounded-xl p-5">
                <h3 class="text-sm font-semibold text-green-800 mb-4">✅ Approve Application</h3>
                <form method="POST" action="{{ route('applications.approve', $application->id) }}">
                    @csrf
                    <div class="space-y-3">
                        <div>
                            <label class="form-label text-green-800">Benefit Amount (₹) <span class="text-red-500">*</span></label>
                            <input type="number" name="subsidy_amount" class="form-input" step="0.01"
                                   placeholder="{{ $application->scheme?->benefit_amount ?? 'Enter amount' }}"
                                   value="{{ old('subsidy_amount', $application->scheme?->benefit_amount) }}" required>
                            @error('subsidy_amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label text-green-800">Approval Remarks</label>
                            <textarea name="remarks" rows="2" class="form-input"
                                      placeholder="Optional approval notes...">{{ old('remarks') }}</textarea>
                        </div>
                        <button type="submit" class="w-full justify-center py-2.5 btn-primary bg-green-600 hover:bg-green-700">
                            ✅ Approve & Assign Benefit
                        </button>
                    </div>
                </form>
            </div>

            {{-- Reject Form --}}
            <div class="bg-red-50 border border-red-200 rounded-xl p-5">
                <h3 class="text-sm font-semibold text-red-800 mb-4">❌ Reject Application</h3>
                <form method="POST" action="{{ route('applications.reject', $application->id) }}"
                      onsubmit="return confirm('Reject this application?')">
                    @csrf
                    <div class="space-y-3">
                        <div>
                            <label class="form-label text-red-800">Rejection Reason <span class="text-red-500">*</span></label>
                            <textarea name="remarks" rows="4" class="form-input" required
                                      placeholder="Explain why this application is being rejected...">{{ old('remarks') }}</textarea>
                            @error('remarks') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit" class="w-full justify-center py-2.5 btn-primary bg-red-600 hover:bg-red-700">
                            ❌ Reject Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        {{-- Review Info (if already reviewed) --}}
        @if($application->status !== 'pending' && $application->approvedBy)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">👤 Review Information</h3>
            <div class="flex items-center justify-between text-sm">
                <div>
                    <p class="text-gray-500 text-xs">Reviewed by</p>
                    <p class="font-medium">{{ $application->approvedBy?->name }}</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-500 text-xs">Review Date</p>
                    <p class="font-medium">{{ $application->approved_date?->format('d M Y') }}</p>
                </div>
            </div>
            @if($application->remarks)
            <div class="mt-3 pt-3 border-t border-gray-100">
                <p class="text-xs text-gray-400 mb-1">Officer Remarks</p>
                <p class="text-sm text-gray-700">{{ $application->remarks }}</p>
            </div>
            @endif
        </div>
        @endif

        {{-- Back button --}}
        <div>
            <a href="{{ route('applications.index') }}" class="btn-secondary">← Back to Applications</a>
        </div>
    </div>

</x-app-layout>
