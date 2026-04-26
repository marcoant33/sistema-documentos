<?php

namespace App\Imports;

use App\Models\Persona;
use App\Models\Documento;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DocumentosImport implements ToCollection, WithStartRow
{
    private $archivoOrigen;
    private $importacionId;
    private $registrosProcesados = 0;
    private $registrosDuplicados = 0;
    private $registrosConError = 0;
    private $errores = [];

    public function __construct($archivoOrigen, $importacionId)
    {
        $this->archivoOrigen = $archivoOrigen;
        $this->importacionId = $importacionId;
    }

    /**
     * Los datos empiezan en la fila 3 (índice 0 = fila 1)
     */
    public function startRow(): int
    {
        return 3;
    }

    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        try {
            foreach ($rows as $index => $fila) {
                // $fila es un array indexado numéricamente (0,1,2...)
                // Saltar filas vacías (si la columna 2, NOMBRE, está vacía)
                if (empty($fila[2]) && empty($fila[3])) {
                    continue;
                }

                // 1. LIMPIAR Y NORMALIZAR DATOS
                $nroBoleta = $this->limpiarTexto($fila[3] ?? '');
                $nroCarpeta = $this->extraerCarpeta($fila);
                $manzano = $this->limpiarTexto($fila[7] ?? '');
                $lote = $this->limpiarTexto($fila[8] ?? '');

                // 2. VERIFICAR DUPLICADOS
                $existente = Documento::where('nro_boleta', $nroBoleta)
                    ->where('nro_carpeta', $nroCarpeta)
                    ->where('cod_manzano', $manzano)
                    ->where('cod_lote', $lote)
                    ->first();

                if ($existente) {
                    $this->registrosDuplicados++;
                    continue;
                }

                // 3. PROCESAR PERSONA
                $nombre = $this->limpiarTexto($fila[2] ?? '');
                $ci = $this->limpiarCI($fila[13] ?? '');

                $persona = null;
                if (!empty($nombre) || !empty($ci)) {
                    $persona = Persona::firstOrCreate(
                        ['ci' => $ci],
                        [
                            'nombre_completo' => $nombre,
                            'ci_observacion' => in_array($ci, ['S/D', 'ILEGIBLE']) ? $ci : null
                        ]
                    );
                    if (empty($persona->nombre_completo) && !empty($nombre)) {
                        $persona->nombre_completo = $nombre;
                        $persona->save();
                    }
                }

                // 4. HASH DE INTEGRIDAD
                $hash = md5(json_encode([
                    $nroBoleta, $nroCarpeta, $manzano, $lote,
                    $this->limpiarMonto($fila[4] ?? 0),
                    $this->limpiarFecha($fila[5] ?? null)
                ]));

                // 5. CREAR DOCUMENTO
                Documento::create([
                    'persona_id' => $persona?->id,
                    'nro_carpeta' => $nroCarpeta,
                    'nro_boleta' => $nroBoleta,
                    'importe_deposito' => $this->limpiarMonto($fila[4] ?? 0),
                    'fecha_deposito' => $this->limpiarFecha($fila[5] ?? null),
                    'hora_deposito' => $this->limpiarHora($fila[6] ?? ''),
                    'cod_manzano' => $manzano,
                    'cod_lote' => $lote,
                    'tipo_contrato' => $this->limpiarTexto($fila[9] ?? ''),
                    'superficie_m2' => $this->limpiarMonto($fila[10] ?? 0),
                    'importe_contrato' => $this->limpiarMonto($fila[11] ?? 0),
                    'fecha_contrato' => $this->limpiarFecha($fila[12] ?? null),
                    'nro_folio' => $this->limpiarTexto($fila[14] ?? ''),
                    'observaciones' => $this->limpiarTexto($fila[15] ?? ''),
                    'archivo_origen' => $this->archivoOrigen,
                    'hash_identidad' => $hash,
                    'activo' => true,
                    'importacion_id' => $this->importacionId
                ]);

                $this->registrosProcesados++;
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->registrosConError++;
            $this->errores[] = "Error en fila: " . $e->getMessage();
            Log::error("Error en importación: " . $e->getMessage());
        }
    }

    // ============ MÉTODOS DE LIMPIEZA (iguales a los que ya tenías) ============
    private function limpiarTexto($texto)
    {
        if (empty($texto)) return null;
        $texto = trim($texto);
        $texto = preg_replace('/\s+/', ' ', $texto);
        return $texto === '' ? null : $texto;
    }

    private function limpiarCI($ci)
    {
        $ci = trim($ci);
        if (empty($ci) || strtoupper($ci) === 'S/D' || strtoupper($ci) === 'S/N') {
            return 'S/D';
        }
        if (strtoupper($ci) === 'ILEGIBLE') return 'ILEGIBLE';
        return $ci;
    }

    private function limpiarMonto($monto)
    {
        if (empty($monto)) return 0;
        $monto = (string) $monto;
        $monto = str_replace(',', '.', $monto);
        $monto = preg_replace('/[^0-9.]/', '', $monto);
        if (substr_count($monto, '.') > 1) {
            $partes = explode('.', $monto);
            $decimal = array_pop($partes);
            $entero = implode('', $partes);
            $monto = $entero . '.' . $decimal;
        }
        return floatval($monto);
    }

    private function limpiarFecha($fecha)
    {
        if (empty($fecha)) return null;
        if ($fecha instanceof \DateTime) {
            return $fecha->format('Y-m-d');
        }
        $fechaStr = trim($fecha);
        if (preg_match('/(\d{1,3})\/(\d{1,3})\/(\d{1,2})/', $fechaStr, $matches)) {
            $dia = $matches[1];
            $mes = $matches[2];
            $anio = $matches[3];
            if (strlen($anio) == 2) $anio = '20' . $anio;
            if (checkdate($mes, $dia, $anio)) {
                return "$anio-$mes-$dia";
            }
        }
        try {
            return Carbon::parse($fechaStr)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function limpiarHora($hora)
    {
        if (empty($hora)) return null;
        $hora = trim($hora);
        $hora = str_replace(['AM.', 'PM.', 'AM', 'PM'], '', $hora);
        $hora = trim($hora);
        try {
            return Carbon::parse($hora)->format('H:i:s');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function extraerCarpeta($fila)
    {
        // La columna 1 es NUMERO DE CARPETA
        $carpeta = $this->limpiarTexto($fila[1] ?? '');
        return $carpeta ?? 'SIN CARPETA';
    }

    // ============ GETTERS ============
    public function getRegistrosProcesados() { return $this->registrosProcesados; }
    public function getRegistrosDuplicados() { return $this->registrosDuplicados; }
    public function getRegistrosConError() { return $this->registrosConError; }
    public function getErrores() { return $this->errores; }
}
