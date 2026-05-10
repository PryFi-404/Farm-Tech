<x-app-layout header="Add New Farmer" breadcrumb="Farmers / Create">

    <div class="max-w-4xl mx-auto">
        <form method="POST" action="{{ route('farmers.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- ── Section 1: Personal Info ─────────────────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-5">
                <h2 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-farm-600 text-white rounded-lg flex items-center justify-center text-xs font-bold">1</span>
                    Account & Personal Details
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-input" placeholder="e.g. Ramesh Patel">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="farmer@example.com">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Login Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" class="form-input" placeholder="Min 8 characters">
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Phone Number <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="10-digit mobile number">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="dob" value="{{ old('dob') }}" class="form-input">
                        @error('dob') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Gender <span class="text-red-500">*</span></label>
                        <select name="gender" class="form-input">
                            <option value="">Select Gender</option>
                            @foreach(['Male','Female','Other'] as $g)
                            <option value="{{ $g }}" {{ old('gender') == $g ? 'selected' : '' }}>{{ $g }}</option>
                            @endforeach
                        </select>
                        @error('gender') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- ── Section 2: Identity Documents ───────────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-5">
                <h2 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-farm-600 text-white rounded-lg flex items-center justify-center text-xs font-bold">2</span>
                    Identity Documents
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Aadhaar Number <span class="text-red-500">*</span></label>
                        <input type="text" name="aadhaar" value="{{ old('aadhaar') }}" class="form-input" placeholder="12-digit Aadhaar" maxlength="12">
                        @error('aadhaar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Voter ID</label>
                        <input type="text" name="voter_id" value="{{ old('voter_id') }}" class="form-input" placeholder="Optional">
                        @error('voter_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Photo Upload --}}
                    <div class="sm:col-span-2">
                        <label class="form-label">Farmer Photo</label>
                        <div class="flex items-center gap-4">
                            <div id="preview-wrap" class="hidden">
                                <img id="photo-preview" class="w-20 h-20 rounded-full object-cover border-2 border-farm-200">
                            </div>
                            <label for="photo" class="cursor-pointer flex items-center gap-2 px-4 py-2 border-2 border-dashed border-gray-300 rounded-lg hover:border-farm-400 transition-colors">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span id="photo-label" class="text-sm text-gray-500">Click to upload photo (JPG/PNG, max 2MB)</span>
                            </label>
                            <input type="file" name="photo" id="photo" accept="image/*" class="hidden"
                                   onchange="previewPhoto(this)">
                        </div>
                        @error('photo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
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
                        <textarea name="address" rows="2" class="form-input" placeholder="House No, Street, Area...">{{ old('address') }}</textarea>
                        @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Village <span class="text-red-500">*</span></label>
                        <input type="text" name="village" value="{{ old('village') }}" class="form-input" placeholder="Village name">
                        @error('village') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Block <span class="text-red-500">*</span></label>
                        <input type="text" name="block" value="{{ old('block') }}" class="form-input" placeholder="Block / Tehsil">
                        @error('block') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">District <span class="text-red-500">*</span></label>
                        <input type="text" name="district" value="{{ old('district') }}" class="form-input" placeholder="District">
                        @error('district') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">State <span class="text-red-500">*</span></label>
                        <input type="text" name="state" value="{{ old('state') }}" class="form-input" placeholder="State">
                        @error('state') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Pincode <span class="text-red-500">*</span></label>
                        <input type="text" name="pincode" value="{{ old('pincode') }}" class="form-input" placeholder="6-digit pincode" maxlength="6">
                        @error('pincode') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- ── Section 4: Bank Details ──────────────────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-5">
                <h2 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-farm-600 text-white rounded-lg flex items-center justify-center text-xs font-bold">4</span>
                    Bank Details <span class="text-xs text-gray-400 font-normal ml-1">(for subsidy transfer)</span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">Bank Name</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name') }}" class="form-input" placeholder="e.g. State Bank of India">
                        @error('bank_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Account Number</label>
                        <input type="text" name="bank_account" value="{{ old('bank_account') }}" class="form-input" placeholder="Account number">
                        @error('bank_account') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">IFSC Code</label>
                        <input type="text" name="ifsc" value="{{ old('ifsc') }}" class="form-input" placeholder="e.g. SBIN0001234" style="text-transform:uppercase">
                        @error('ifsc') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- ── Submit Buttons ───────────────────────────────────────────── --}}
            <div class="flex items-center gap-3">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Register Farmer
                </button>
                <a href="{{ route('farmers.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('photo-preview').src = e.target.result;
                document.getElementById('preview-wrap').classList.remove('hidden');
                document.getElementById('photo-label').textContent = input.files[0].name;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>
    @endpush

</x-app-layout>
