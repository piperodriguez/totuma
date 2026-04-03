<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Totuma Express') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-50">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen">
            @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-totuma-green focus:outline focus:outline-2 focus:rounded-sm focus:outline-totuma-green">Panel</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-totuma-green focus:outline focus:outline-2 focus:rounded-sm focus:outline-totuma-green">Iniciar Sesión</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-totuma-green focus:outline focus:outline-2 focus:rounded-sm focus:outline-totuma-green">Registrarse</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="max-w-7xl mx-auto p-6 lg:p-8 text-center">
                <div class="flex justify-center">
                    <img src="{{ asset('images/logo.png') }}" class="h-32 w-auto mb-8" alt="Logo Totuma Express">
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-4">Bienvenido a nuestro Programa de Puntos</h1>
                <p class="text-lg text-gray-600">Registra tus compras y redime premios deliciosos.</p>

                <div class="mt-8">
                    <a href="{{ route('register') }}" class="bg-totuma-green text-white px-8 py-3 rounded-full font-bold text-lg hover:bg-totuma-dark transition shadow-lg">
                        ¡Quiero empezar a acumular!
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
