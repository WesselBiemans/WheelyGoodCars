@extends('layouts.main')

@section('title', 'WheelyGoodCars - Add New Car')

@section('main-class', 'py-12')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-900">Auto Verkopen</h2>
        <p class="mt-2 text-gray-600">Vul de onderstaande gegevens in om je auto te verkopen.</p>
    </div>

    <div class="bg-white overflow-hidden shadow-lg rounded-lg">
        <div class="p-6 sm:p-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('cars.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="license_plate" class="block font-medium text-sm text-gray-700">
                        Kenteken <span class="text-red-500">*</span>
                    </label>
                    <input id="license_plate"
                           name="license_plate"
                           type="text"
                           value="{{ old('license_plate') }}"
                           class="mt-1 block w-full border-gray-300 focus:border-cyan-500 focus:ring-cyan-500 rounded-md shadow-sm"
                           required>
                    @error('license_plate')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="brand" class="block font-medium text-sm text-gray-700">
                        Merk <span class="text-red-500">*</span>
                    </label>
                    <input id="brand"
                           name="brand"
                           type="text"
                           value="{{ old('brand') }}"
                           class="mt-1 block w-full border-gray-300 focus:border-cyan-500 focus:ring-cyan-500 rounded-md shadow-sm"
                           required>
                    @error('brand')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="model" class="block font-medium text-sm text-gray-700">
                        Model <span class="text-red-500">*</span>
                    </label>
                    <input id="model"
                           name="model"
                           type="text"
                           value="{{ old('model') }}"
                           class="mt-1 block w-full border-gray-300 focus:border-cyan-500 focus:ring-cyan-500 rounded-md shadow-sm"
                           required>
                    @error('model')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="block font-medium text-sm text-gray-700">
                        Prijs (€) <span class="text-red-500">*</span>
                    </label>
                    <input id="price"
                           name="price"
                           type="number"
                           step="0.01"
                           value="{{ old('price') }}"
                           class="mt-1 block w-full border-gray-300 focus:border-cyan-500 focus:ring-cyan-500 rounded-md shadow-sm"
                           required>
                    @error('price')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="mileage" class="block font-medium text-sm text-gray-700">
                        Kilometerstand <span class="text-red-500">*</span>
                    </label>
                    <input id="mileage"
                           name="mileage"
                           type="number"
                           value="{{ old('mileage') }}"
                           class="mt-1 block w-full border-gray-300 focus:border-cyan-500 focus:ring-cyan-500 rounded-md shadow-sm"
                           required>
                    @error('mileage')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="seats" class="block font-medium text-sm text-gray-700">
                            Aantal Stoelen
                        </label>
                        <input id="seats"
                               name="seats"
                               type="number"
                               value="{{ old('seats') }}"
                               class="mt-1 block w-full border-gray-300 focus:border-cyan-500 focus:ring-cyan-500 rounded-md shadow-sm">
                        @error('seats')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="doors" class="block font-medium text-sm text-gray-700">
                            Aantal Deuren
                        </label>
                        <input id="doors"
                               name="doors"
                               type="number"
                               value="{{ old('doors') }}"
                               class="mt-1 block w-full border-gray-300 focus:border-cyan-500 focus:ring-cyan-500 rounded-md shadow-sm">
                        @error('doors')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="production_year" class="block font-medium text-sm text-gray-700">
                            Bouwjaar
                        </label>
                        <input id="production_year"
                               name="production_year"
                               type="number"
                               value="{{ old('production_year') }}"
                               class="mt-1 block w-full border-gray-300 focus:border-cyan-500 focus:ring-cyan-500 rounded-md shadow-sm">
                        @error('production_year')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="weight" class="block font-medium text-sm text-gray-700">
                            Gewicht (kg)
                        </label>
                        <input id="weight"
                               name="weight"
                               type="number"
                               value="{{ old('weight') }}"
                               class="mt-1 block w-full border-gray-300 focus:border-cyan-500 focus:ring-cyan-500 rounded-md shadow-sm">
                        @error('weight')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="color" class="block font-medium text-sm text-gray-700">
                        Kleur
                    </label>
                    <input id="color"
                           name="color"
                           type="text"
                           value="{{ old('color') }}"
                           class="mt-1 block w-full border-gray-300 focus:border-cyan-500 focus:ring-cyan-500 rounded-md shadow-sm">
                    @error('color')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="image" class="block font-medium text-sm text-gray-700">
                        Afbeelding URL
                    </label>
                    <input id="image"
                           name="image"
                           type="text"
                           value="{{ old('image') }}"
                           class="mt-1 block w-full border-gray-300 focus:border-cyan-500 focus:ring-cyan-500 rounded-md shadow-sm">
                    @error('image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tags -->
                @if($tags->count() > 0)
                <div>
                    <label class="block font-medium text-sm text-gray-700 mb-2">
                        Tags
                    </label>
                    <div class="space-y-2">
                        @foreach($tags as $tag)
                            <div class="flex items-center">
                                <input id="tag-{{ $tag->id }}"
                                       name="tags[]"
                                       type="checkbox"
                                       value="{{ $tag->id }}"
                                       {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-cyan-600 shadow-sm focus:ring-cyan-500">
                                <label for="tag-{{ $tag->id }}" class="ml-2 text-sm text-gray-700">
                                    <span class="inline-block px-2 py-1 rounded text-white" style="background-color: {{ $tag->color ?? '#6B7280' }}">
                                        {{ $tag->name }}
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('tags')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <div class="flex items-center justify-end">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-cyan-700 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-cyan-800 focus:bg-cyan-800 active:bg-cyan-900 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Auto Toevoegen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
