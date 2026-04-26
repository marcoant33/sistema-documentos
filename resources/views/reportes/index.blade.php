@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5>📊 Resumen General</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="alert alert-primary">
                            <strong>📄 Total Documentos:</strong><br>
                            <h3>{{ number_format($resumen['total_documentos']) }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-success">
                            <strong>💰 Total Depósitos:</strong><br>
                            <h3>Bs {{ number_format($resumen['total_depositos'], 2) }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-warning">
                            <strong>📑 Total Contratos:</strong><br>
                            <h3>Bs {{ number_format($resumen['total_contratos'], 2) }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-info">
                            <strong>👥 Total Personas:</strong><br>
                            <h3>{{ number_format($resumen['total_personas']) }}</h3>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="alert alert-secondary">
                            <strong>📐 Promedio Superficie:</strong>
                            {{ number_format($resumen['promedio_superficie'] ?? 0, 2) }} m²
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-secondary">
                            <strong>💵 Saldo Total por Cobrar:</strong>
                            Bs {{ number_format($resumen['total_contratos'] - $resumen['total_depositos'], 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5>📁 Estadísticas por Carpeta</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Carpeta</th>
                                <th>Documentos</th>
                                <th>Depósitos</th>
                                <th>Contratos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($estadisticasCarpetas as $est)
                            <tr>
                                <td>{{ $est->nro_carpeta }}</td>
                                <td>{{ $est->total_documentos }}</td>
                                <td>Bs {{ number_format($est->total_depositos, 2) }}</td>
                                <td>Bs {{ number_format($est->total_contratos, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5>⚠️ Saldos Pendientes (Top 20)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Boleta</th>
                                <th>Depósito</th>
                                <th>Contrato</th>
                                <th>Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($saldosPendientes as $doc)
                            <tr>
                                <td>{{ $doc->persona?->nombre_completo ?? 'N/D' }}</td>
                                <td>{{ $doc->nro_boleta }}</td>
                                <td>Bs {{ number_format($doc->importe_deposito, 2) }}</td>
                                <td>Bs {{ number_format($doc->importe_contrato ?? 0, 2) }}</td>
                                <td class="text-danger">Bs {{ number_format($doc->diferencia, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
