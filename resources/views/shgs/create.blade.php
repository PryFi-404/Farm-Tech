<x-app-layout header="Create New Group" breadcrumb="SHG / FPG / Create">
<div class="max-w-2xl mx-auto">
    <form method="POST" action="{{ route('shgs.store') }}">
        @csrf
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4 mb-5">
            <h2 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">Group Information</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="form-label">Group Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-input"
                           placeholder="e.g. Jai Kisan Mahila Self Help Group">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Group Type <span class="text-red-500">*</span></label>
                    <select name="type" class="form-input">
                        <option value="">Select Type</option>
                        @foreach(['SHG' => 'Self Help Group (SHG)', 'FPG' => 'Farmer Producer Group (FPG)', 'FPC' => 'Farmer Producer Company (FPC)', 'JLG' => 'Joint Liability Group (JLG)'] as $val => $label)
                        <option value="{{ $val }}" {{ old('type') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Registration Number</label>
                    <input type="text" name="registration_number" value="{{ old('registration_number') }}"
                           class="form-input" placeholder="e.g. SHG/RPR/2024/001">
                    @error('registration_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Formation Date</label>
                    <input type="date" name="formation_date" value="{{ old('formation_date') }}" class="form-input">
                    @error('formation_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Leader / President</label>
                    <select name="leader_farmer_id" class="form-input">
                        <option value="">Select Group Leader</option>
                        @foreach($farmers as $f)
                        <option value="{{ $f->id }}" {{ old('leader_farmer_id') == $f->id ? 'selected' : '' }}>
                            {{ $f->user?->name }} — {{ $f->village }}
                        </option>
                        @endforeach
                    </select>
                    @error('leader_farmer_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4 mb-5">
            <h2 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">Location Details</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Village <span class="text-red-500">*</span></label>
                    <input type="text" name="village" value="{{ old('village') }}" class="form-input">
                    @error('village') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Block <span class="text-red-500">*</span></label>
                    <input type="text" name="block" value="{{ old('block') }}" class="form-input">
                    @error('block') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">District <span class="text-red-500">*</span></label>
                    <input type="text" name="district" value="{{ old('district') }}" class="form-input">
                    @error('district') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Bank Account</label>
                    <input type="text" name="bank_account" value="{{ old('bank_account') }}" class="form-input"
                           placeholder="Group savings account number">
                    @error('bank_account') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Create Group
            </button>
            <a href="{{ route('shgs.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</x-app-layout>
