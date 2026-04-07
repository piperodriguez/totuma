<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mi Panel de Puntos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-white overflow-hidden shadow-xl rounded-2xl p-6 border border-gray-100 flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-1">¡Hola, {{ Auth::user()->name }}! 👋</h3>
                        <p class="text-sm text-gray-500 mb-6">Tu actividad en Totuma Express</p>

                        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-8 rounded-2xl text-white shadow-lg shadow-emerald-200/50">
                            <span class="text-xs font-bold uppercase tracking-widest opacity-80">Saldo Acumulado</span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-6xl font-black">{{ number_format($puntosTotales) }}</span>
                                <span class="text-2xl font-medium opacity-90">pts</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center gap-3 text-emerald-700 bg-emerald-50 p-4 rounded-xl border border-emerald-100">
                        <span class="text-2xl">✨</span>
                        <div>
                            <p class="text-xs uppercase font-bold opacity-70">Último cargue</p>
                            <p class="text-sm font-bold">+{{ $ultimoCargue }} puntos ganados</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl rounded-2xl p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Tu Próximo Premio 🎁</h3>
                    <p class="text-sm text-gray-500 mb-6">Estás muy cerca de la meta</p>

                    <div class="flex flex-col items-center justify-center space-y-6">
                        <div class="relative" style="width: 220px; height: 220px;">
                            <canvas id="userPointsChart"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-4xl font-black text-emerald-600">{{ $porcentaje }}%</span>
                                <span class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Completado</span>
                            </div>
                        </div>

                        <div class="w-full text-center space-y-2">
                            <div class="flex justify-between text-xs font-bold text-gray-400 uppercase tracking-tighter px-4">
                                <span>0 pts</span>
                                <span>Meta: {{ number_format($metaPuntos) }} pts</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-emerald-500 h-full transition-all duration-1000" style="width: {{ $porcentaje }}%"></div>
                            </div>
                            <p class="text-sm font-medium text-gray-600">
                                @if($puntosTotales >= $metaPuntos)
                                    <span class="text-emerald-600 font-bold">¡Felicidades! Ya puedes reclamar tu premio.</span>
                                @else
                                    Te faltan <span class="text-emerald-600 font-bold">{{ number_format(max(0, $metaPuntos - $puntosTotales)) }}</span> puntos.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

            </div> </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('userPointsChart').getContext('2d');

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Progreso', 'Restante'],
                    datasets: [{
                        data: [
                            {{ min($puntosTotales, $metaPuntos) }},
                            {{ max(0, $metaPuntos - $puntosTotales) }}
                        ],
                        backgroundColor: ['#10b981', '#f3f4f6'],
                        borderWidth: 0,
                        borderRadius: 15,
                        hoverOffset: 0
                    }]
                },
                options: {
                    cutout: '85%',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
