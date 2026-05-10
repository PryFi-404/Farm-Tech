<x-app-layout header="Add Land Record" breadcrumb="Land Records / Create">
<div class="max-w-2xl mx-auto">
    <form method="POST" action="{{ route('lands.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
            <h2 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">Land Parcel Details</h2>

            {{-- Farmer selection --}}
            <div>
                <label class="form-label">Farmer <span class="text-red-500">*</span></label>
                <select name="farmer_id" id="farmer_id" class="form-input" required>
                    <option value="">Select Farmer</option>
                    @foreach($farmers as $f)
                    <option value="{{ $f->id }}"
                        {{ (old('farmer_id', $selectedFarmer?->id) == $f->id) ? 'selected' : '' }}>
                        {{ $f->user?->name }} — {{ $f->village }}
                    </option>
                    @endforeach
                </select>
                @error('farmer_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Survey Number <span class="text-red-500">*</span></label>
                    <input type="text" name="survey_number" value="{{ old('survey_number') }}"
                           class="form-input" placeholder="e.g. SY-204">
                    @error('survey_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Khasra Number</label>
                    <input type="text" name="khasra_number" value="{{ old('khasra_number') }}"
                           class="form-input" placeholder="e.g. KH-1045">
                    @error('khasra_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Area (acres) <span class="text-red-500">*</span></label>
                    <input type="number" name="area_acres" value="{{ old('area_acres') }}"
                           class="form-input" step="0.01" min="0.01" placeholder="e.g. 2.50">
                    @error('area_acres') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Ownership Type <span class="text-red-500">*</span></label>
                    <select name="ownership_type" class="form-input">
                        @foreach(['Owned','Leased','Shared'] as $o)
                        <option value="{{ $o }}" {{ old('ownership_type') == $o ? 'selected' : '' }}>{{ $o }}</option>
                        @endforeach
                    </select>
                    @error('ownership_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Soil Type</label>
                    <select name="soil_type" class="form-input">
                        <option value="">Select Soil Type</option>
                        @foreach(['Loamy','Clay','Sandy','Black Cotton','Red','Alluvial'] as $s)
                        <option value="{{ $s }}" {{ old('soil_type') == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                    @error('soil_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Irrigation Type</label>
                    <select name="irrigation_type" class="form-input">
                        <option value="">Select Irrigation</option>
                        @foreach(['Canal','Borewell','Rainwater','Drip','Sprinkler','Pond','Tube Well'] as $i)
                        <option value="{{ $i }}" {{ old('irrigation_type') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endforeach
                    </select>
                    @error('irrigation_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Document Upload --}}
            <div>
                <label class="form-label">Land Document (optional — PDF/Image, max 4MB)</label>
                <input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png"
                       class="block w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-4
                              file:rounded-lg file:border-0 file:bg-farm-50 file:text-farm-700
                              file:font-medium hover:file:bg-farm-100 cursor-pointer">
                @error('document') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center gap-3 mt-5">
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save Land Record
            </button>
            <a href="{{ route('lands.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</x-app-layout>
