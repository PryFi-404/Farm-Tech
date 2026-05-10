<x-app-layout header="Edit Farmer" breadcrumb="Farmers / {{ $farmer->user?->name }} / Edit">

    <div class="max-w-4xl mx-auto">
        <form method="POST" action="{{ route('farmers.update', $farmer->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- ── Section 1: Personal Info ─────────────────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-5">
                <h2 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-farm-600 text-white rounded-lg flex items-center justify-center text-xs font-bold">1</span>
                    Personal Details
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $farmer->user?->name) }}" class="form-input">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Phone <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone', $farmer->phone) }}" class="form-input">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Aadhaar Number <span class="text-red-500">*</span></label>
                        <input type="text" name="aadhaar" value="{{ old('aadhaar', $farmer->aadhaar) }}" class="form-input" maxlength="12">
                        @error('aadhaar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Voter ID</label>
                        <input type="text" name="voter_id" value="{{ old('voter_id', $farmer->voter_id) }}" class="form-input">
                        @error('voter_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="dob" value="{{ old('dob', $farmer->dob?->format('Y-m-d')) }}" class="form-input">
                        @error('dob') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Gender <span class="text-red-500">*</span></label>
                        <select name="gender" class="form-input">
                            @foreach(['Male','Female','Other'] as $g)
                            <option value="{{ $g }}" {{ old('gender', $farmer->gender) == $g ? 'selected' : '' }}>{{ $g }}</option>
                            @endforeach
                        </select>
                        @error('gender') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- ── Section 2: Photo ────────────────────────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-5">
                <h2 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-farm-600 text-white rounded-lg flex items-center justify-center text-xs font-bold">2</span>
                    Profile Photo
                </h2>
                <div class="flex items-center gap-5">
                    {{-- Current Photo --}}
                    @if($farmer->photo)
                    <img src="{{ Storage::url($farmer->photo) }}"
                         id="photo-preview"
                         class="w-20 h-20 rounded-full object-cover border-2 border-farm-200">
                    @else
                    <div class="w-20 h-20 rounded-full bg-farm-100 flex items-center justify-center" id="avatar-placeholder">
                        <span class="text-2xl font-bold text-farm-600">{{ strtoupper(substr($farmer->user?->name ?? 'F', 0, 1)) }}</span>
                    </div>
                    <img id="photo-preview" class="w-20 h-20 rounded-full object-cover border-2 border-farm-200 hidden">
                    @endif

                    <label for="photo" class="cursor-pointer btn-secondary text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Change Photo
                    </label>
                    <input type="file" name="photo" id="photo" accept="image/*" class="hidden" onchange="previewPhoto(this)">
                </div>
                @error('photo') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- ── Section 3: Address ───────────────────────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-5">
                <h2 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-farm-600 text-white rounded-lg flex items-center justify-center text-xs font-bold">3</span>
                    Address Details
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="form-label">Full Address <span class="text-red-500">*</span></label>
                        <textarea name="address" rows="2" class="form-input">{{ old('address', $farmer->address) }}</textarea>
                        @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    @foreach(['village' => 'Village', 'block' => 'Block', 'district' => 'District', 'state' => 'State', 'pincode' => 'Pincode'] as $field => $label)
                    <div>
                        <label class="form-label">{{ $label }} <span class="text-red-500">*</span></label>
                        <input type="text" name="{{ $field }}" value="{{ old($field, $farmer->$field) }}" class="form-input">
                        @error($field) <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ── Section 4: Bank Details ──────────────────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-5">
                <h2 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-farm-600 text-white rounded-lg flex items-center justify-center text-xs font-bold">4</span>
                    Bank Details
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">Bank Name</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', $farmer->bank_name) }}" class="form-input">
                        @error('bank_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Account Number</label>
                        <input type="text" name="bank_account" value="{{ old('bank_account', $farmer->bank_account) }}" class="form-input">
                        @error('bank_account') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">IFSC Code</label>
                        <input type="text" name="ifsc" value="{{ old('ifsc', $farmer->ifsc) }}" class="form-input" style="text-transform:uppercase">
                        @error('ifsc') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-3">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Changes
                </button>
                <a href="{{ route('farmers.show', $farmer->id) }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                const preview = document.getElementById('photo-preview');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                const placeholder = document.getElementById('avatar-placeholder');
                if (placeholder) placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>
    @endpush

</x-app-layout>
