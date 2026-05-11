<x-app-layout header="Edit Scheme" breadcrumb="Schemes / {{ $scheme->name }} / Edit">
<div class="max-w-2xl mx-auto">
    <form method="POST" action="{{ route('schemes.update', $scheme->id) }}">
        @csrf @method('PUT')
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-5 space-y-4">
            <h2 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">
                Editing: <span class="text-farm-600">{{ $scheme->name }}</span>
            </h2>
            <div>
                <label class="form-label">Scheme Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $scheme->name) }}" class="form-input">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Category <span class="text-red-500">*</span></label>
                    <select name="category" class="form-input">
                        @foreach(['Subsidy','Insurance','Loan','Training','Equipment','Other'] as $c)
                        <option value="{{ $c }}" {{ old('category', $scheme->category) == $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Benefit Amount (₹)</label>
                    <input type="number" name="benefit_amount" value="{{ old('benefit_amount', $scheme->benefit_amount) }}" class="form-input" step="0.01">
                </div>
                <div>
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date', $scheme->start_date?->format('Y-m-d')) }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" value="{{ old('end_date', $scheme->end_date?->format('Y-m-d')) }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Max Beneficiaries</label>
                    <input type="number" name="max_beneficiaries" value="{{ old('max_beneficiaries', $scheme->max_beneficiaries) }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">Ministry</label>
                    <input type="text" name="ministry" value="{{ old('ministry', $scheme->ministry) }}" class="form-input">
                </div>
            </div>
            <div>
                <label class="form-label">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4" class="form-input">{{ old('description', $scheme->description) }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="form-label">Eligibility Criteria</label>
                <textarea name="eligibility" rows="3" class="form-input">{{ old('eligibility', $scheme->eligibility) }}</textarea>
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" value="1" id="is_active"
                       {{ old('is_active', $scheme->is_active) ? 'checked' : '' }}
                       class="w-4 h-4 text-farm-600 rounded border-gray-300">
                <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button type="submit" class="btn-primary">Save Changes</button>
            <a href="{{ route('schemes.show', $scheme->id) }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</x-app-layout>
