<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WheelyGoodCars - Home</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col">
    <header>
        <nav class="bg-slate-50 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-cyan-700">WheelyGoodCars</h1>
                    </div>
                    <div class="flex items-center space-x-8">
                        <a href="#" class="text-gray-700 hover:text-cyan-700">Home</a>
                        <a href="#" class="text-gray-700 hover:text-cyan-700">Overzicht alle auto's</a>
                        <a href="#" class="text-gray-700 hover:text-cyan-700">Auto verkopen</a>

                        @guest
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-cyan-700">Login</a>
                        <a href="{{ route('register') }}" class="bg-cyan-700 text-white px-4 py-2 rounded-lg hover:bg-cyan-800">Registreer</a>
                        @endguest

                        @auth
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-cyan-700">Uitloggen</button>
                        </form>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="content flex-1 flex flex-col">
        <div class="hero bg-gradient-to-b from-cyan-700 to-slate-700 text-slate-50 h-80 flex items-center justify-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-4xl font-extrabold sm:text-5xl">Welcome bij WheelyGoodCars</h2>
                <p class="mt-4 text-xl text-center">(Ver)koop je auto's hier!</p>
                <a href="#" class="inline-block mt-8 bg-slate-50 text-cyan-700 px-8 py-3 rounded-lg font-semibold hover:bg-zinc-300">Bekijk onze auto's</a>
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
    </main>

    <footer>

    </footer>
</body>

</html>
