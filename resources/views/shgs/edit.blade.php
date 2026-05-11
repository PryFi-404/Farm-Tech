<x-app-layout header="Edit Group" breadcrumb="SHG / FPG / {{ $shg->name }} / Edit">
    <div class="max-w-2xl mx-auto">
        <form method="POST" action="{{ route('shgs.update', $shg->id) }}">
            @csrf @method('PUT')
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4 mb-5">
                <h2 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">
                    Editing: <span class="text-farm-600">{{ $shg->name }}</span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="form-label">Group Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $shg->name) }}" class="form-input">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Group Type <span class="text-red-500">*</span></label>
                        <select name="type" class="form-input">
                            @foreach(['SHG', 'FPG', 'FPC', 'JLG'] as $t)
                                <option value="{{ $t }}" {{ old('type', $shg->type) == $t ? 'selected' : '' }}>{{ $t }}
                                </option>
                            @endforeach
                        </select>
                        @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Registration Number</label>
                        <input type="text" name="registration_number"
                            value="{{ old('registration_number', $shg->registration_number) }}" class="form-input">
                        @error('registration_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Formation Date</label>
                        <input type="date" name="formation_date"
                            value="{{ old('formation_date', $shg->formation_date?->format('Y-m-d')) }}"
                            class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Leader / President</label>
                        <select name="leader_farmer_id" class="form-input">
                            <option value="">None</option>
                            @foreach($farmers as $f)
                                <option value="{{ $f->id }}" {{ old('leader_farmer_id', $shg->leader_farmer_id) == $f->id ? 'selected' : '' }}>
                                    {{ $f->user?->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Village <span class="text-red-500">*</span></label>
                        <input type="text" name="village" value="{{ old('village', $shg->village) }}"
                            class="form-input">
                        @error('village') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Block <span class="text-red-500">*</span></label>
                        <input type="text" name="block" value="{{ old('block', $shg->block) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">District <span class="text-red-500">*</span></label>
                        <input type="text" name="district" value="{{ old('district', $shg->district) }}"
                            class="form-input">
                        @error('district') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Bank Account</label>
                        <input type="text" name="bank_account" value="{{ old('bank_account', $shg->bank_account) }}"
                            class="form-input">
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" class="btn-primary">Save Changes</button>
                <a href="{{ route('shgs.show', $shg->id) }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>