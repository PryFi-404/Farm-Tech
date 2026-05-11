<x-app-layout header="Add New Scheme" breadcrumb="Schemes / Create">
<div class="max-w-2xl mx-auto">
    <form method="POST" action="{{ route('schemes.store') }}">
        @csrf
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-5 space-y-4">
            <h2 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">Scheme Information</h2>

            <div>
                <label class="form-label">Scheme Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-input"
                       placeholder="e.g. Pradhan Mantri Kisan Samman Nidhi">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Category <span class="text-red-500">*</span></label>
                    <select name="category" class="form-input">
                        <option value="">Select Category</option>
                        @foreach(['Subsidy','Insurance','Loan','Training','Equipment','Other'] as $c)
                        <option value="{{ $c }}" {{ old('category') == $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                    @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Benefit Amount (₹)</label>
                    <input type="number" name="benefit_amount" value="{{ old('benefit_amount') }}"
                           class="form-input" step="0.01" placeholder="e.g. 6000">
                    @error('benefit_amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" class="form-input">
                </div>
                <div>
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" class="form-input">
                    @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Max Beneficiaries</label>
                    <input type="number" name="max_beneficiaries" value="{{ old('max_beneficiaries') }}"
                           class="form-input" placeholder="Leave blank for unlimited">
                </div>
                <div>
                    <label class="form-label">Ministry / Department</label>
                    <input type="text" name="ministry" value="{{ old('ministry') }}" class="form-input"
                           placeholder="e.g. Ministry of Agriculture">
                </div>
            </div>

            <div>
                <label class="form-label">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4" class="form-input"
                          placeholder="Describe what this scheme offers, who it's for...">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">Eligibility Criteria</label>
                <textarea name="eligibility" rows="3" class="form-input"
                          placeholder="Who can apply? e.g. Small and marginal farmers with land < 2 acres...">{{ old('eligibility') }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" value="1" id="is_active"
                       {{ old('is_active', true) ? 'checked' : '' }}
                       class="w-4 h-4 text-farm-600 rounded border-gray-300">
                <label for="is_active" class="text-sm font-medium text-gray-700">
                    Mark as Active (farmers can apply immediately)
                </label>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save Scheme
            </button>
            <a href="{{ route('schemes.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</x-app-layout>
