<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Importacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function dashboard()
    {
        $totalDocumentos = Documento::activo()->count();
        $totalDepositos = Documento::activo()->sum('importe_deposito');
        $totalContratos = Documento::activo()->sum('importe_contrato');
        $totalImportaciones = Importacion::count();

        $ultimosDocumentos = Documento::activo()->with('persona')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $ultimasImportaciones = Importacion::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('welcome', compact(
            'totalDocumentos', 'totalDepositos', 'totalContratos',
            'totalImportaciones', 'ultimosDocumentos', 'ultimasImportaciones'
        ));
    }

    public function reportesIndex()
    {
        // Estadísticas por carpeta
        $estadisticasCarpetas = Documento::activo()
            ->select('nro_carpeta',
                DB::raw('COUNT(*) as total_documentos'),
                DB::raw('SUM(importe_deposito) as total_depositos'),
                DB::raw('SUM(importe_contrato) as total_contratos'),
                DB::raw('AVG(superficie_m2) as promedio_superficie'))
            ->groupBy('nro_carpeta')
            ->orderBy('total_documentos', 'desc')
            ->get();

        // Resumen general
        $resumen = [
            'total_documentos' => Documento::activo()->count(),
            'total_depositos' => Documento::activo()->sum('importe_deposito'),
            'total_contratos' => Documento::activo()->sum('importe_contrato'),
            'promedio_superficie' => Documento::activo()->avg('superficie_m2'),
            'total_personas' => Documento::activo()->distinct('persona_id')->count('persona_id')
        ];

        // Documentos con saldo pendiente
        $saldosPendientes = Documento::activo()
            ->with('persona')
            ->whereRaw('(importe_contrato - importe_deposito) > 0')
            ->orderByRaw('(importe_contrato - importe_deposito) desc')
            ->limit(20)
            ->get();

        return view('reportes.index', compact('estadisticasCarpetas', 'resumen', 'saldosPendientes'));
    }
}
