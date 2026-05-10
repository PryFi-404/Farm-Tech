<x-app-layout header="Record Crop Production" breadcrumb="Crop History / Create">
<div class="max-w-2xl mx-auto">
    <form method="POST" action="{{ route('crop-history.store') }}">
        @csrf
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
            <h2 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">
                Record New Crop Season
            </h2>

            {{-- Farmer --}}
            <div>
                <label class="form-label">Farmer <span class="text-red-500">*</span></label>
                <select name="farmer_id" id="farmer_select" class="form-input" required>
                    <option value="">Select Farmer</option>
                    @foreach($farmers as $f)
                    <option value="{{ $f->id }}"
                        data-lands="{{ $f->lands->map(fn($l) => ['id'=>$l->id,'label'=>$l->survey_number.' ('.$l->area_acres.' ac)'])->toJson() }}"
                        {{ old('farmer_id', $selectedFarmer?->id) == $f->id ? 'selected' : '' }}>
                        {{ $f->user?->name }}
                    </option>
                    @endforeach
                </select>
                @error('farmer_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Land (dynamically populated via JS) --}}
            <div>
                <label class="form-label">Land Parcel <span class="text-red-500">*</span></label>
                <select name="land_id" id="land_select" class="form-input" required>
                    <option value="">Select farmer first</option>
                    @if($selectedFarmer)
                        @foreach($selectedFarmer->lands as $l)
                        <option value="{{ $l->id }}" {{ old('land_id') == $l->id ? 'selected' : '' }}>
                            {{ $l->survey_number }} ({{ $l->area_acres }} acres)
                        </option>
                        @endforeach
                    @endif
                </select>
                @error('land_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Crop --}}
            <div>
                <label class="form-label">Crop Type <span class="text-red-500">*</span></label>
                <select name="crop_id" class="form-input" required>
                    <option value="">Select Crop</option>
                    @foreach($crops->groupBy('season') as $season => $groupCrops)
                    <optgroup label="{{ $season }}">
                        @foreach($groupCrops as $c)
                        <option value="{{ $c->id }}" {{ old('crop_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }} ({{ $c->category }})
                        </option>
                        @endforeach
                    </optgroup>
                    @endforeach
                </select>
                @error('crop_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Season <span class="text-red-500">*</span></label>
                    <select name="season" class="form-input" required>
                        @foreach(['Kharif','Rabi','Zaid','Year Round'] as $s)
                        <option value="{{ $s }}" {{ old('season') == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                    @error('season') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Year <span class="text-red-500">*</span></label>
                    <input type="number" name="year" value="{{ old('year', date('Y')) }}"
                           class="form-input" min="2000" max="{{ date('Y') + 1 }}">
                    @error('year') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Area Used (acres) <span class="text-red-500">*</span></label>
                    <input type="number" name="area_used" value="{{ old('area_used') }}"
                           class="form-input" step="0.01" min="0.01" placeholder="e.g. 2.00">
                    @error('area_used') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Production (kg)</label>
                    <input type="number" name="production_kg" value="{{ old('production_kg') }}"
                           class="form-input" step="0.01" min="0" placeholder="e.g. 1500">
                    @error('production_kg') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Selling Price (₹/kg)</label>
                    <input type="number" name="selling_price" value="{{ old('selling_price') }}"
                           class="form-input" step="0.01" min="0" placeholder="e.g. 25">
                    @error('selling_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="form-label">Notes / Remarks</label>
                <textarea name="notes" rows="2" class="form-input"
                          placeholder="Any observations about this crop season...">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="flex items-center gap-3 mt-5">
            <button type="submit" class="btn-primary">Save Crop Record</button>
            <a href="{{ route('crop-history.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Dynamically populate lands when farmer is selected
document.getElementById('farmer_select').addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    const lands  = JSON.parse(option.dataset.lands || '[]');
    const select = document.getElementById('land_select');

    select.innerHTML = lands.length
        ? '<option value="">Select Land Parcel</option>'
        : '<option value="">No land parcels found</option>';

    lands.forEach(l => {
        const opt = document.createElement('option');
        opt.value = l.id;
        opt.textContent = l.label;
        select.appendChild(opt);
    });
});
</script>
@endpush
</x-app-layout>
