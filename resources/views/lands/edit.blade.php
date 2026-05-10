<x-app-layout header="Edit Land Record" breadcrumb="Land Records / Edit">
<div class="max-w-2xl mx-auto">
    <form method="POST" action="{{ route('lands.update', $land->id) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
            <h2 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">
                Editing land for: <span class="text-farm-600">{{ $land->farmer?->user?->name }}</span>
            </h2>

            <input type="hidden" name="farmer_id" value="{{ $land->farmer_id }}">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Survey Number <span class="text-red-500">*</span></label>
                    <input type="text" name="survey_number" value="{{ old('survey_number', $land->survey_number) }}" class="form-input">
                    @error('survey_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Khasra Number</label>
                    <input type="text" name="khasra_number" value="{{ old('khasra_number', $land->khasra_number) }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Area (acres) <span class="text-red-500">*</span></label>
                    <input type="number" name="area_acres" value="{{ old('area_acres', $land->area_acres) }}" class="form-input" step="0.01">
                    @error('area_acres') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Ownership Type <span class="text-red-500">*</span></label>
                    <select name="ownership_type" class="form-input">
                        @foreach(['Owned','Leased','Shared'] as $o)
                        <option value="{{ $o }}" {{ old('ownership_type', $land->ownership_type) == $o ? 'selected' : '' }}>{{ $o }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Soil Type</label>
                    <select name="soil_type" class="form-input">
                        <option value="">None</option>
                        @foreach(['Loamy','Clay','Sandy','Black Cotton','Red','Alluvial'] as $s)
                        <option value="{{ $s }}" {{ old('soil_type', $land->soil_type) == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Irrigation Type</label>
                    <select name="irrigation_type" class="form-input">
                        <option value="">None</option>
                        @foreach(['Canal','Borewell','Rainwater','Drip','Sprinkler','Pond','Tube Well'] as $i)
                        <option value="{{ $i }}" {{ old('irrigation_type', $land->irrigation_type) == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if($land->document)
            <div class="p-3 bg-blue-50 rounded-lg text-sm text-blue-700 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Existing document uploaded. Upload new to replace.
            </div>
            @endif
            <div>
                <label class="form-label">Replace Document (optional)</label>
                <input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png"
                       class="block w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-4
                              file:rounded-lg file:border-0 file:bg-farm-50 file:text-farm-700 cursor-pointer">
            </div>
        </div>

        <div class="flex items-center gap-3 mt-5">
            <button type="submit" class="btn-primary">Save Changes</button>
            <a href="{{ route('lands.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</x-app-layout>
