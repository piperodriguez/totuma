<?php

namespace App\Services;

use App\Models\User;
use App\Models\PuntosMovimiento;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class LoggroImportService
{
    public function import(string $filePath): array
    {
        $csv = Reader::createFromPath(Storage::path($filePath), 'r');
        $csv->setHeaderOffset(0);

        // Ajustamos el delimitador a coma según tu archivo real
        $csv->setDelimiter(',');

        // Validamos la integridad de los encabezados antes de procesar
        $this->validateHeaders($csv->getHeader());

        $stats = ['processed' => 0, 'skipped' => 0, 'duplicates' => 0];

        DB::transaction(function () use ($csv, &$stats) {
            foreach ($csv as $record) {
                $documento = trim($record['Documento'] ?? '');
                $facturaNo = trim($record['Factura No.'] ?? '');
                $cliente = trim($record['Cliente'] ?? 'Desconocido');
                $totalRaw = $record['Total'] ?? '0';

                // 1. Log de Salto: Consumidor Final
                if ($documento === '222222222222') {
                    Log::info("Fila omitida: Consumidor Final. Cliente: {$cliente} (Factura #{$facturaNo})");
                    $stats['skipped']++;
                    continue;
                }

                // 2. Log de Duplicado: Factura ya procesada
                $existe = PuntosMovimiento::where('referencia_loggro', $facturaNo)->exists();
                if ($existe) {
                    Log::notice("Factura duplicada omitida: #{$facturaNo}. Cliente: {$cliente}");
                    $stats['duplicates']++;
                    continue;
                }

                // 3. Limpieza y Cálculo de Puntos con Log de Error de Formato
                $cleanTotal = preg_replace('/[^\d,]/', '', $totalRaw);
                $cleanTotal = str_replace(',', '.', $cleanTotal);
                $totalValue = (float) $cleanTotal;

                if ($totalValue <= 0) {
                    Log::error("Error de formato o total en cero. Cliente: {$cliente}, Documento: {$documento}, Valor Recibido: {$totalRaw}");
                    $stats['skipped']++;
                    continue;
                }

                $puntosCalculados = (int) floor($totalValue / 1000);

                if ($puntosCalculados > 0) {
                    // 4. Upsert de Usuario (Crear o Encontrar)
                    $user = User::firstOrCreate(
                        ['identificacion' => $documento],
                        [
                            'name' => $cliente,
                            'telefono' => $record['Teléfono'] ?? null,
                            'email' => "{$documento}@totuma.com",
                            'password' => Hash::make(Str::random(16)),
                            'role' => 'customer',
                        ]
                    );

                    $user->increment('puntos', $puntosCalculados);

                    $user->movimientos()->create([
                        'cantidad' => $puntosCalculados,
                        'tipo' => 'acumulacion',
                        'descripcion' => "Puntos por compra Factura #{$facturaNo}",
                        'referencia_loggro' => $facturaNo,
                    ]);

                    Log::info("Importación exitosa: {$puntosCalculados} puntos para {$cliente} (Factura #{$facturaNo})");
                    $stats['processed']++;
                } else {
                    Log::warning("Puntos insuficientes para procesar. Cliente: {$cliente}, Total: {$totalRaw}");
                    $stats['skipped']++;
                }
            }
        });

        return $stats;
    }

    /**
     * Valida que el archivo CSV contenga las columnas obligatorias para el negocio.
     */
    private function validateHeaders(array $headers): void
    {
        $required = ['Documento', 'Factura No.', 'Cliente', 'Total', 'Teléfono'];
        $trimmedHeaders = array_map('trim', $headers);
        $missing = array_diff($required, $trimmedHeaders);

        if (!empty($missing)) {
            throw new \Exception("El archivo no contiene las columnas requeridas: " . implode(', ', $missing));
        }
    }
}
