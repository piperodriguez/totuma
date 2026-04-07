<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Administración - Totuma Express') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Contenedor de la Gráfica -->
            <div class="p-6 bg-white border-b border-gray-200 shadow-sm sm:rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        Rendimiento de Importaciones (Facturas con Puntos)
                    </h3>
                </div>
                @include('admin.upload-form') {{-- Asumiendo que moviste el form a un partial --}}
                <br><br>
                <div class="relative w-full" style="height: 300px;">
                    <canvas id="importPerformanceChart"></canvas>

                </div>
            </div>

            <!-- Formulario de Carga y Tabla de Historial -->
            <div class="p-6 bg-white shadow-sm sm:rounded-lg">

                <div class="mt-8">
                    <h3 class="text-md font-semibold mb-4 text-gray-700 uppercase tracking-wider">Historial Reciente</h3>
                    @include('admin.history-table', ['items' => $history])
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Usamos window.onload para asegurar que el CDN de Chart.js cargó al 100%
        window.onload = function() {
            console.log("Iniciando carga de gráfica...");

            const labels = {!! json_encode($chartLabels) !!};
            const values = {!! json_encode($chartValues) !!};

            // Debug en consola
            console.log("Datos recibidos:", { labels, values });

            const canvas = document.getElementById('importPerformanceChart');
            if (!canvas) {
                alert("Error: No se encontró el canvas con ID importPerformanceChart");
                return;
            }

            const ctx = canvas.getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Facturas Procesadas',
                        data: values,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.2)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        };
    </script>
    @endpush
</x-app-layout>
