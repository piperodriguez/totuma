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
        $csv->setDelimiter(',');

        $this->validateHeaders($csv->getHeader());

        // Añadimos 'points' al array de estadísticas
        $stats = [
            'processed' => 0,
            'skipped' => 0,
            'duplicates' => 0,
            'points' => 0 // <--- Nueva métrica para el total de puntos del cargue
        ];

        DB::transaction(function () use ($csv, &$stats) {
            foreach ($csv as $record) {
                $documento = trim($record['Documento'] ?? '');
                $facturaNo = trim($record['Factura No.'] ?? '');
                $cliente = trim($record['Cliente'] ?? 'Desconocido');
                $totalRaw = $record['Total'] ?? '0';

                if ($documento === '222222222222') {
                    $stats['skipped']++;
                    continue;
                }

                $existe = PuntosMovimiento::where('referencia_loggro', $facturaNo)->exists();
                if ($existe) {
                    $stats['duplicates']++;
                    continue;
                }

                $cleanTotal = preg_replace('/[^\d,]/', '', $totalRaw);
                $cleanTotal = str_replace(',', '.', $cleanTotal);
                $totalValue = (float) $cleanTotal;

                if ($totalValue <= 0) {
                    $stats['skipped']++;
                    continue;
                }

                $puntosCalculados = (int) floor($totalValue / 1000);

                if ($puntosCalculados > 0) {
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

                    // Actualizamos el acumulado total Y el valor del último cargue específico
                    $user->update([
                        'puntos' => $user->puntos + $puntosCalculados,
                        'ultimo_cargue_puntos' => $puntosCalculados // <--- Seteamos el valor actual
                    ]);

                    $user->movimientos()->create([
                        'cantidad' => $puntosCalculados,
                        'tipo' => 'acumulacion',
                        'descripcion' => "Puntos por compra Factura #{$facturaNo}",
                        'referencia_loggro' => $facturaNo,
                    ]);

                    // ACTUALIZACIÓN CLAVE: Acumulamos los puntos en las estadísticas
                    $stats['processed']++;
                    $stats['points'] += $puntosCalculados; // <--- Suma los puntos de esta factura al total



                    Log::info("Importación exitosa: {$puntosCalculados} puntos para {$cliente} (Factura #{$facturaNo})");
                } else {
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
