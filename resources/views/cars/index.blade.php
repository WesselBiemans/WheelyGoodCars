@extends('layouts.main')

@section('title', 'WheelyGoodCars - Auto\'s Overzicht')

@section('main-class', 'py-12')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Auto's Te Koop</h2>
        <p class="mt-2 text-gray-600">Bekijk ons volledige aanbod van auto's</p>
    </div>

    @if($cars->count() > 0)
        <div class="car-items grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($cars as $car)
                <div class="car-item bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    @if($car->image)
                        <img src="{{ $car->image }}" alt="{{ $car->brand }} {{ $car->model }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-cyan-600 to-slate-600 flex items-center justify-center">
                        </div>
                    @endif

                    <div class="description p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $car->brand }} {{ $car->model }}</h3>

                        <div class="values space-y-2 mb-4 min-h-[110px]">
                            <div class="text-sm text-gray-600">
                                <span class="font-mono">{{ $car->license_plate }}</span>
                            </div>

                            <div class="text-sm text-gray-600">
                                <span>{{ number_format($car->mileage, 0, ',', '.') }} km</span>
                            </div>

                            @if($car->production_year)
                                <div class="text-sm text-gray-600">
                                    <span>{{ $car->production_year }}</span>
                                </div>
                            @endif

                            @if($car->color)
                                <div class="text-sm text-gray-600">
                                    <span>{{ $car->color }}</span>
                                </div>
                            @endif
                        </div>

                        @if($car->tags->count() > 0)
                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach($car->tags as $tag)
                                    <span class="inline-block px-2 py-1 text-xs rounded text-white" style="background-color: {{ $tag->color ?? '#6B7280' }}">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                            <span class="text-2xl font-bold text-cyan-700">€ {{ number_format($car->price, 2, ',', '.') }}</span>
                            <a href="#" class="bg-cyan-700 text-white px-4 py-2 rounded-lg hover:bg-cyan-800 transition-colors">
                                Bekijk
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $cars->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-lg p-12 text-center">
            <h3 class="text-xl font-bold text-gray-900 mb-2">Geen auto's beschikbaar</h3>
            <p class="text-gray-600 mb-6">Er zijn momenteel geen auto's te koop.</p>
            @auth
                <a href="{{ route('cars.create') }}" class="inline-block bg-cyan-700 text-white px-6 py-3 rounded-lg hover:bg-cyan-800">
                    Voeg je auto toe
                </a>
            @endauth
        </div>
    @endif
</div>
@endsection
