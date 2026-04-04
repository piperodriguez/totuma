<?php

namespace App\Jobs;

use App\Services\LoggroImportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessLoggroImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * El número de veces que se puede reintentar el Job si falla.
     */
    public $tries = 3;

    /**
     * Crea una nueva instancia del Job.
     */
    public function __construct(
        protected string $filePath,
        protected int $importLogId
    ) {
        // Forzamos la conexión de base de datos si es necesario
        $this->onConnection(config('queue.default') === 'sync' ? 'database' : null);
    }

    /**
     * Ejecuta la lógica del Job.
     */
    public function handle(LoggroImportService $service): void
    {
        $importLog = \App\Models\ImportLog::find($this->importLogId);
        try {
            Log::info("Job: Iniciando procesamiento de Loggro para: {$this->filePath}");

            $stats = $service->import($this->filePath);

            $importLog->update([
                'status' => 'completed',
                'processed_count' => $stats['processed'],
                'skipped_count' => $stats['skipped'],
                'duplicates_count' => $stats['duplicates'],
            ]);

            Log::info("Job: Importación finalizada con éxito.", $stats);

            // Limpiar el archivo temporal de storage/app/imports
            if (Storage::exists($this->filePath)) {
                Storage::delete($this->filePath);
            }
        } catch (\Exception $e) {
            $importLog->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);
            Log::error("Job: Error crítico procesando archivo de Loggro: " . $e->getMessage());
            throw $e; // Re-lanzamos para que Laravel gestione el reintento
        }
    }
}
