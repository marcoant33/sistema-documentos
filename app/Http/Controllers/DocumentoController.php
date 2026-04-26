<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;

class DocumentoController extends Controller
{
    public function index(Request $request)
    {
        $query = Documento::with('persona')->activo();

        // Aplicar filtros
        if ($request->filled('nombre')) {
            $query->whereHas('persona', function($q) use ($request) {
                $q->where('nombre_completo', 'LIKE', "%{$request->nombre}%");
            });
        }

        if ($request->filled('ci')) {
            $query->whereHas('persona', function($q) use ($request) {
                $q->where('ci', 'LIKE', "%{$request->ci}%");
            });
        }

        if ($request->filled('nro_boleta')) {
            $query->where('nro_boleta', 'LIKE', "%{$request->nro_boleta}%");
        }

        if ($request->filled('carpeta')) {
            $query->where('nro_carpeta', 'LIKE', "%{$request->carpeta}%");
        }

        if ($request->filled('manzano_lote')) {
            $parts = explode('/', $request->manzano_lote);
            if (count($parts) == 2) {
                $query->where('cod_manzano', $parts[0])
                      ->where('cod_lote', $parts[1]);
            }
        }

        if ($request->filled('importe_min')) {
            $query->where('importe_deposito', '>=', $request->importe_min);
        }

        if ($request->filled('importe_max')) {
            $query->where('importe_deposito', '<=', $request->importe_max);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_deposito', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_deposito', '<=', $request->fecha_hasta);
        }

        $documentos = $query->orderBy('created_at', 'desc')->paginate(50);

        return view('documentos.index', compact('documentos'));
    }
}
