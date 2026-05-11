<x-app-layout header="Apply for Scheme" breadcrumb="Applications / New">
<div class="max-w-2xl mx-auto">
    <form method="POST" action="{{ route('applications.store') }}">
        @csrf
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-5">
            <h2 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">
                New Scheme Application
            </h2>

            {{-- Farmer --}}
            <div>
                <label class="form-label">Farmer <span class="text-red-500">*</span></label>

                @if(auth()->user()->isFarmer())
                {{-- Farmers see only their own name (locked) --}}
                <div class="form-input bg-gray-50 text-gray-700 flex items-center gap-2 cursor-not-allowed">
                    <svg class="w-4 h-4 text-farm-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="font-medium">{{ $selectedFarmer?->user?->name }}</span>
                    <span class="text-gray-400 text-xs ml-1">— {{ $selectedFarmer?->village }}, {{ $selectedFarmer?->district }}</span>
                </div>
                <input type="hidden" name="farmer_id" value="{{ $selectedFarmer?->id }}">
                <p class="text-xs text-gray-400 mt-1">You are applying as yourself.</p>

                @else
                {{-- Admin / Officer see full dropdown --}}
                <select name="farmer_id" class="form-input" required>
                    <option value="">Select Farmer</option>
                    @foreach($farmers as $f)
                    <option value="{{ $f->id }}"
                        {{ old('farmer_id', $selectedFarmer?->id) == $f->id ? 'selected' : '' }}>
                        {{ $f->user?->name }} — {{ $f->village }}, {{ $f->district }}
                    </option>
                    @endforeach
                </select>
                @endif

                @error('farmer_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Scheme --}}
            <div>
                <label class="form-label">Scheme <span class="text-red-500">*</span></label>
                <select name="scheme_id" id="scheme_select" class="form-input" required>
                    <option value="">Select Active Scheme</option>
                    @foreach($schemes->groupBy('category') as $cat => $group)
                    <optgroup label="{{ $cat }}">
                        @foreach($group as $s)
                        <option value="{{ $s->id }}"
                            data-amount="{{ $s->benefit_amount }}"
                            data-desc="{{ Str::limit($s->description, 100) }}"
                            {{ old('scheme_id', $selectedScheme?->id) == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                            @if($s->benefit_amount) (₹{{ number_format($s->benefit_amount) }}) @endif
                        </option>
                        @endforeach
                    </optgroup>
                    @endforeach
                </select>
                @error('scheme_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                {{-- Scheme preview (JS populated) --}}
                <div id="scheme-preview" class="hidden mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg text-xs text-blue-700">
                    <p id="scheme-desc"></p>
                    <p id="scheme-amount" class="font-semibold mt-1"></p>
                </div>
            </div>

            {{-- Application Date --}}
            <div>
                <label class="form-label">Application Date <span class="text-red-500">*</span></label>
                <input type="date" name="applied_date" value="{{ old('applied_date', date('Y-m-d')) }}"
                       class="form-input" required>
                @error('applied_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Remarks --}}
            <div>
                <label class="form-label">Remarks / Additional Info</label>
                <textarea name="remarks" rows="3" class="form-input"
                          placeholder="Any special circumstances, land details, or supporting information...">{{ old('remarks') }}</textarea>
            </div>

            {{-- Duplicate warning --}}
            @if(session('error'))
            <div class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                ⚠️ {{ session('error') }}
            </div>
            @endif
        </div>

        <div class="flex items-center gap-3 mt-5">
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Submit Application
            </button>
            <a href="{{ route('applications.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('scheme_select').addEventListener('change', function() {
    const opt     = this.options[this.selectedIndex];
    const preview = document.getElementById('scheme-preview');
    const desc    = document.getElementById('scheme-desc');
    const amount  = document.getElementById('scheme-amount');

    if (opt.value) {
        desc.textContent   = opt.dataset.desc || '';
        amount.textContent = opt.dataset.amount
            ? '💵 Benefit up to ₹' + Number(opt.dataset.amount).toLocaleString('en-IN')
            : '';
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
    }
});
</script>
@endpush
</x-app-layout>
