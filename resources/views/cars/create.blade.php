@extends('layouts.main')

@section('title', 'WheelyGoodCars - Add New Car')

@section('main-class', 'py-12')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-900">Auto Verkopen</h2>
        <p class="mt-2 text-gray-600">Voer uw kenteken in. Na validatie van het kenteken kan je de rest van je gegevens invullen.</p>
    </div>

    <div class="bg-white overflow-hidden shadow-lg rounded-lg">
        <div class="p-6 sm:p-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('cars.store') }}"
                x-data='carCreateWizard({ startStep: {{ old('brand') || old('model') || old('price') || old('mileage') || old('seats') || old('doors') || old('production_year') || old('weight') || old('color') || old('image') ? 2 : 1 }}, initialLicensePlate: @json(old('license_plate')), checkUrl: @json(route('cars.check-license-plate')) })'
                x-cloak
                class="space-y-6"
            >
                @csrf

                <div class="flex items-center gap-3 text-sm font-medium text-gray-500">
                    <span class="inline-flex items-center rounded-full px-3 py-1" :class="step === 1 ? 'bg-cyan-700 text-white' : 'bg-cyan-100 text-cyan-800'">1. Kenteken</span>
                    <span class="inline-flex items-center rounded-full px-3 py-1" :class="step === 2 ? 'bg-cyan-700 text-white' : 'bg-gray-100 text-gray-500'">2. Gegevens</span>
                </div>

                <div x-show="step === 1" x-transition>
                    <label for="license_plate" class="block font-medium text-sm text-gray-700">
                        Kenteken <span class="text-red-500">*</span>
                    </label>
                    <input id="license_plate"
                           name="license_plate"
                           type="text"
                           x-model="licensePlate"
                           x-on:blur="licensePlate = formatLicensePlate(licensePlate)"
                           value="{{ old('license_plate') }}"
                           class="mt-1 block w-full border-gray-300 focus:border-cyan-500 focus:ring-cyan-500 rounded-md shadow-sm"
                           required>
                    @error('license_plate')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">Na controle verschijnt stap 2 automatisch.</p>

                    <div class="mt-6 flex items-center justify-end">
                        <button type="button"
                                x-on:click="checkLicensePlate()"
                                x-bind:disabled="isChecking"
                                class="inline-flex items-center px-6 py-3 bg-cyan-700 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-cyan-800 focus:bg-cyan-800 active:bg-cyan-900 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <span x-show="!isChecking">Kenteken Controleren</span>
                            <span x-show="isChecking">Controleren...</span>
                        </button>
                    </div>
                </div>

                <template x-if="validationMessage">
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                        <p x-text="validationMessage"></p>
                    </div>
                </template>

                <div x-show="step === 2" x-transition class="space-y-6">
                    <div class="bg-cyan-50 border border-cyan-100 rounded-lg px-4 py-3 text-sm text-cyan-900">
                        Kenteken gecontroleerd: <span class="font-semibold" x-text="licensePlate"></span>. Vul nu de resterende gegevens in.
                    </div>

                    <div>
                        <label for="brand" class="block font-medium text-sm text-gray-700">
                            Merk <span class="text-red-500">*</span>
                        </label>
                        <input id="brand"
                               name="brand"
                               type="text"
                               value="{{ old('brand') }}"
                               x-bind:disabled="step === 1"
                               x-bind:required="step === 2"
                               class="mt-1 block w-full border-gray-300 focus:border-cyan-500 focus:ring-cyan-500 rounded-md shadow-sm">
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
                               x-bind:disabled="step === 1"
                               x-bind:required="step === 2"
                               class="mt-1 block w-full border-gray-300 focus:border-cyan-500 focus:ring-cyan-500 rounded-md shadow-sm">
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
                               x-bind:disabled="step === 1"
                               x-bind:required="step === 2"
                               class="mt-1 block w-full border-gray-300 focus:border-cyan-500 focus:ring-cyan-500 rounded-md shadow-sm">
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
                               x-bind:disabled="step === 1"
                               x-bind:required="step === 2"
                               class="mt-1 block w-full border-gray-300 focus:border-cyan-500 focus:ring-cyan-500 rounded-md shadow-sm">
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

                    <div class="flex items-center justify-between gap-3">
                        <button type="button"
                                x-show="step === 2"
                                x-on:click="goBack()"
                                class="inline-flex items-center px-5 py-3 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:bg-gray-50 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Terug
                        </button>

                        <button type="submit"
                                x-show="step === 2"
                                class="inline-flex items-center px-6 py-3 bg-cyan-700 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-cyan-800 focus:bg-cyan-800 active:bg-cyan-900 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Auto Toevoegen
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
