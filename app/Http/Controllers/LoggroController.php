<?php

namespace App\Http\Controllers;

use App\Services\LoggroImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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

        try {
            $res = $this->importService->import($request->file('loggro_file'));

            return back()->with('status', "Importación exitosa: {$res['processed']} facturas procesadas. ({$res['skipped']} ignoradas, {$res['duplicates']} duplicadas)");
        } catch (\Exception $e) {
            return back()->withErrors(['loggro_file' => 'Error al procesar el archivo: ' . $e->getMessage()]);
        }
    }
}
