@extends('layouts.main')

@section('title', 'WheelyGoodCars - Home')

@section('body-class', '')

@section('main-class', 'flex flex-col')

@section('content')
<div class="hero bg-gradient-to-b from-cyan-700 to-slate-700 text-slate-50 h-80 flex items-center justify-center">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-extrabold sm:text-5xl">Welcome bij WheelyGoodCars</h2>
        <p class="mt-4 text-xl text-center">(Ver)koop je auto's hier!</p>
        <a href="{{ route('cars.index') }}" class="inline-block mt-8 bg-slate-50 text-cyan-700 px-8 py-3 rounded-lg font-semibold hover:bg-zinc-300">Bekijk onze auto's</a>
    </div>
</div>

<div class="bg-slate-700 flex-1 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="bg-slate-50 rounded-lg p-16">
                <h3 class="text-2xl font-bold text-cyan-700 mb-4">Betrouwbaar</h3>
                <p class="text-gray-700">Al onze auto's worden grondig gecontroleerd voordat ze op de website komen. Kwaliteit en veiligheid staan bij ons voorop.</p>
            </div>

            <div class="bg-slate-50 rounded-lg p-16">
                <h3 class="text-2xl font-bold text-cyan-700 mb-4">Gemakkelijk Verkopen</h3>
                <p class="text-gray-700">Verkoop je auto snel en eenvoudig via ons platform. Wij brengen kopers en verkopers samen voor de beste deals.</p>
            </div>

            <div class="bg-slate-50 rounded-lg p-16">
                <h3 class="text-2xl font-bold text-cyan-700 mb-4">Groot Aanbod</h3>
                <p class="text-gray-700">Duizenden auto's beschikbaar in alle prijsklassen. Van compact tot luxe, bij ons vind je altijd wat je zoekt.</p>
            </div>
        </div>
    </div>
</div>
@endsection
