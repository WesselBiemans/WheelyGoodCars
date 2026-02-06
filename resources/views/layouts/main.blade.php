<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'WheelyGoodCars')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col @yield('body-class', 'bg-slate-100')">
    @include('layouts.navbar')

    <main class="content flex-1 @yield('main-class')">
        @yield('content')
    </main>

    @include('layouts.footer')
</body>

</html>
