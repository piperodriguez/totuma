<?php

namespace App\Http\Controllers;

use App\Services\LoggroImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Jobs\ProcessLoggroImport;

class LoggroController extends Controller
{
    public function __construct(
        protected LoggroImportService $importService
    ) {}

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
