<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">{{ __("¡Bienvenido, :name!", ['name' => Auth::user()->name]) }}</h3>

                    <div class="bg-totuma-green border-l-4 border-totuma-dark p-4 rounded shadow-md text-white">
                        <p class="text-sm uppercase tracking-wide font-semibold">Tus puntos acumulados</p>
                        <p class="text-4xl font-black">
                            {{ Auth::user()->puntos }}
                            <span class="text-lg font-normal">puntos</span>
                        </p>
                    </div>

                    <p class="mt-6 text-sm text-gray-600 italic">
                        {{ __("¡Usted está conectado!") }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
