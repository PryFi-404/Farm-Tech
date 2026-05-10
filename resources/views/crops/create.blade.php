<x-app-layout header="Add New Crop" breadcrumb="Crops / Create">
<div class="max-w-lg mx-auto">
    <form method="POST" action="{{ route('crops.store') }}">
        @csrf
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
            <h2 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">Add Crop to Master List</h2>
            <div>
                <label class="form-label">Crop Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-input" placeholder="e.g. Jowar">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="form-label">Category <span class="text-red-500">*</span></label>
                <select name="category" class="form-input">
                    <option value="">Select Category</option>
                    @foreach(['Cereal','Pulse','Oilseed','Cash Crop','Vegetable','Fruit','Spice','Fiber'] as $c)
                    <option value="{{ $c }}" {{ old('category') == $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
                @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="form-label">Growing Season <span class="text-red-500">*</span></label>
                <select name="season" class="form-input">
                    <option value="">Select Season</option>
                    @foreach(['Kharif','Rabi','Zaid','Year Round'] as $s)
                    <option value="{{ $s }}" {{ old('season') == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
                @error('season') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-input"
                          placeholder="Brief description of the crop...">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <div class="flex items-center gap-3 mt-5">
            <button type="submit" class="btn-primary">Add to Master List</button>
            <a href="{{ route('crops.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</x-app-layout>
