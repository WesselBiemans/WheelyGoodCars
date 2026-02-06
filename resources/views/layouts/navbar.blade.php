<header>
    <nav class="bg-slate-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}">
                        <h1 class="text-2xl font-bold text-cyan-700">WheelyGoodCars</h1>
                    </a>
                </div>
                <div class="flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-cyan-700 {{ request()->routeIs('home') ? 'text-cyan-700 font-semibold' : '' }}">Home</a>
                    <a href="{{ route('cars.index') }}" class="text-gray-700 hover:text-cyan-700 {{ request()->routeIs('cars.index') ? 'text-cyan-700 font-semibold' : '' }}">Overzicht alle auto's</a>
                    <a href="{{ route('cars.create') }}" class="text-gray-700 hover:text-cyan-700 {{ request()->routeIs('cars.create') ? 'text-cyan-700 font-semibold' : '' }}">Auto verkopen</a>

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
