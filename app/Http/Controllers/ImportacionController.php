<?php

namespace App\Http\Controllers;

use App\Imports\DocumentosImport;
use App\Models\Importacion;
use App\Models\Documento;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class ImportacionController extends Controller
{
    public function index()
    {
        $importaciones = Importacion::orderBy('created_at', 'desc')->paginate(20);
        $totalDocumentos = Documento::activo()->count();
        $totalImportaciones = Importacion::count();

        return view('importaciones.index', compact('importaciones', 'totalDocumentos', 'totalImportaciones'));
    }

    public function importar(Request $request)
    {
        $request->validate([
            'archivos.*' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        $resultados = [];

        foreach ($request->file('archivos') as $archivo) {
            $nombreOriginal = $archivo->getClientOriginalName();

            DB::beginTransaction();
            try {
                // Crear registro de importación
                $importacion = Importacion::create([
                    'nombre_archivo' => $nombreOriginal,
                    'fecha_importacion' => now(),
                    'usuario_importador' => 'web',
                    'estado' => 'procesando'
                ]);

                // Ejecutar importación
                $importador = new DocumentosImport($nombreOriginal, $importacion->id);
                Excel::import($importador, $archivo);

                // Actualizar registro
                $importacion->update([
                    'registros_importados' => $importador->getRegistrosProcesados(),
                    'registros_duplicados' => $importador->getRegistrosDuplicados(),
                    'registros_con_error' => $importador->getRegistrosConError(),
                    'errores' => json_encode($importador->getErrores()),
                    'estado' => $importador->getRegistrosConError() > 0 ? 'parcial' : 'completada'
                ]);

                DB::commit();

                $resultados[] = [
                    'archivo' => $nombreOriginal,
                    'importados' => $importador->getRegistrosProcesados(),
                    'duplicados' => $importador->getRegistrosDuplicados(),
                    'errores' => $importador->getRegistrosConError()
                ];

            } catch (\Exception $e) {
                DB::rollBack();
                $resultados[] = [
                    'archivo' => $nombreOriginal,
                    'error' => $e->getMessage()
                ];
            }
        }

        return redirect()->route('importaciones.index')->with('resultados', $resultados);
    }
}
