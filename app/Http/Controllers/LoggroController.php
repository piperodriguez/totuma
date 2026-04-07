<?php

namespace App\Http\Controllers;

use App\Services\LoggroImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Jobs\ProcessLoggroImport;
use App\Models\ImportLog;
use Illuminate\View\View;

class LoggroController extends Controller
{
    public function __construct(
        protected LoggroImportService $importService
    ) {}

    /**
     * Muestra el dashboard administrativo con estadísticas.
     */
    public function index()
    {
        // 1. Obtenemos los últimos 10 logs completados
        $chartData = \App\Models\ImportLog::where('status', 'completed')
            ->latest()
            ->take(10)
            ->get()
            ->reverse()
            ->values();

        // 2. Preparamos labels y valores como ARRAYS PLANOS
        $chartLabels = $chartData->map(function ($log) {
            return $log->created_at->format('d/m H:i');
        })->values()->toArray(); // <--- Forzar array plano

        $chartValues = $chartData->pluck('processed_count')->values()->toArray(); // <--- Forzar array plano

        // 3. Historial para la tabla
        $history = \App\Models\ImportLog::latest()->paginate(15);

        return view('admin.index', compact('chartLabels', 'chartValues', 'history'));
    }

    /**
     * Procesa el archivo subido por el administrador.
     */
    public function upload(Request $request): RedirectResponse
    {
        $request->validate([
            'loggro_file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        // Guardamos el archivo en el disco local (storage/app/private/imports)
        $path = $request->file('loggro_file')->store('imports');

        // Creamos el registro del log
        $importLog = \App\Models\ImportLog::create([
            'filename' => $request->file('loggro_file')->getClientOriginalName(),
            'status' => 'pending'
        ]);

        // Despachamos el Job pasándole la ruta del archivo
        ProcessLoggroImport::dispatch($path, $importLog->id);

        return back()->with('status', 'El archivo se está procesando en segundo plano. Te notificaremos al terminar.');
    }
}
