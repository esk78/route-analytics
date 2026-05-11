<div>
    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
        Назва
    </label>

    <input
        id="name"
        type="text"
        name="name"
        value="{{ old('name', $checkpoint->name ?? '') }}"
        class="w-full border-gray-300 rounded-md shadow-sm"
        required
    >

    @error('name')
        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
    @enderror
</div>

<div>
    <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">
        Широта
    </label>

    <input
        id="latitude"
        type="number"
        step="0.0000001"
        min="-90"
        max="90"
        name="latitude"
        value="{{ old('latitude', $checkpoint->latitude ?? '') }}"
        class="w-full border-gray-300 rounded-md shadow-sm"
        required
    >

    @error('latitude')
        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
    @enderror
</div>

<div>
    <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">
        Довгота
    </label>

    <input
        id="longitude"
        type="number"
        step="0.0000001"
        min="-180"
        max="180"
        name="longitude"
        value="{{ old('longitude', $checkpoint->longitude ?? '') }}"
        class="w-full border-gray-300 rounded-md shadow-sm"
        required
    >

    @error('longitude')
        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
    @enderror
</div>
