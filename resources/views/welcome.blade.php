<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Totuma Express') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            /* Fondo con patrón de hojas sutil (opcional si tienes la imagen) */
            .bg-pattern {
                background-color: #ffffff;
                background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 5c13.8 0 25 11.2 25 25S43.8 55 30 55 5 43.8 5 30 16.2 5 30 5z' fill='%2010b981' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
            }
        </style>
    </head>
    <body class="antialiased bg-pattern text-gray-900 font-sans">

        <nav class="flex justify-between items-center px-8 py-6 max-w-7xl mx-auto">
            <div class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" class="h-12 w-auto" alt="Logo">
            </div>

            <div class="hidden md:flex items-center space-x-8 text-sm font-bold text-gray-700">
                <a href="#" class="hover:text-emerald-600 transition">Inicio</a>
                <a href="#" class="hover:text-emerald-600 transition">Cómo Funciona</a>
                <a href="#" class="hover:text-emerald-600 transition">Premios</a>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-emerald-50 text-emerald-700 px-6 py-2 rounded-full border border-emerald-200 hover:bg-emerald-100 transition">Panel</a>
                    @else
                        <a href="{{ route('login') }}" class="bg-emerald-50 text-emerald-700 px-6 py-2 rounded-full border border-emerald-200 hover:bg-emerald-100 transition">Registro / Login</a>
                    @endauth
                @endif
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-6 pt-10 pb-20 text-center">
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo.png') }}" class="h-28 md:h-36 w-auto" alt="Logo Central">
            </div>

            <h1 class="text-4xl md:text-6xl font-black text-gray-900 mb-4 tracking-tight">
                Bienvenido a Tu Programa de Puntos
            </h1>
            <p class="text-lg md:text-xl text-gray-500 mb-12">
                Registra tus compras y redime premios deliciosos.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto mb-16">
                <div class="bg-white p-4 rounded-3xl shadow-lg border border-gray-50 flex items-center gap-4 hover:scale-105 transition-transform cursor-pointer">
                    <div class="flex-1 text-left">
                        <h3 class="font-bold text-gray-800 leading-tight">Hamburguesa Totuma Gratis</h3>
                        <p class="text-emerald-600 font-black mt-1">400 PTS</p>
                    </div>
                    <div class="w-20 h-20 bg-gray-100 rounded-2xl overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?q=80&w=150&h=150&auto=format&fit=crop" alt="Hamburguesa" class="object-cover w-full h-full">
                    </div>
                </div>

                <div class="bg-white p-4 rounded-3xl shadow-lg border border-gray-50 flex items-center gap-4 hover:scale-105 transition-transform cursor-pointer">
                    <div class="flex-1 text-left">
                        <h3 class="font-bold text-gray-800 leading-tight">Postre Especial</h3>
                        <p class="text-emerald-600 font-black mt-1">250 PTS</p>
                    </div>
                    <div class="w-20 h-20 bg-gray-100 rounded-2xl overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1551024601-bec78aea704b?q=80&w=150&h=150&auto=format&fit=crop" alt="Postre" class="object-cover w-full h-full">
                    </div>
                </div>

                <div class="bg-white p-4 rounded-3xl shadow-lg border border-gray-50 flex items-center gap-4 hover:scale-105 transition-transform cursor-pointer">
                    <div class="flex-1 text-left">
                        <h3 class="font-bold text-gray-800 leading-tight">Descuento del 15%</h3>
                        <p class="text-emerald-600 font-black mt-1">600 PTS</p>
                    </div>
                    <div class="w-20 h-20 bg-gray-800 rounded-2xl flex items-center justify-center">
                        <span class="text-white font-black text-2xl">%</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-center gap-4">
                <a href="{{ route('register') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-12 py-4 rounded-full font-black text-xl transition-all shadow-xl shadow-emerald-200 transform hover:-translate-y-1">
                    Quiero mis Puntos 👉
                </a>

                @if (Route::has('login'))
                    <p class="text-sm text-gray-400">
                        O <a href="{{ route('login') }}" class="text-gray-600 font-bold underline">inicia sesión aquí</a>
                    </p>
                @endif
            </div>
        </main>

        <footer class="pb-10 flex justify-center space-x-6 text-gray-400">
            <a href="#" class="hover:text-emerald-500 transition"><i class="fab fa-facebook text-xl"></i></a>
            <a href="#" class="hover:text-emerald-500 transition"><i class="fab fa-twitter text-xl"></i></a>
            <a href="#" class="hover:text-emerald-500 transition"><i class="fab fa-instagram text-xl"></i></a>
        </footer>
    </body>
</html>
