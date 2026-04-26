@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>Sistema en modo solo lectura:</strong> Los datos provienen directamente de los archivos Excel importados.
                No se pueden modificar ni eliminar desde esta interfaz.
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">📄 Documentos</h5>
                    <h2 class="mb-0">{{ $totalDocumentos ?? 0 }}</h2>
                    <small>Registros activos</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">💰 Depósitos</h5>
                    <h2 class="mb-0">Bs {{ number_format($totalDepositos ?? 0, 2) }}</h2>
                    <small>Suma total</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">📑 Contratos</h5>
                    <h2 class="mb-0">Bs {{ number_format($totalContratos ?? 0, 2) }}</h2>
                    <small>Suma total</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">📂 Importaciones</h5>
                    <h2 class="mb-0">{{ $totalImportaciones ?? 0 }}</h2>
                    <small>Archivos procesados</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Últimos Documentos Importados
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr><th>Carpeta</th><th>Nombre</th><th>Depósito</th><th>Fecha</th></tr>
                            </thead>
                            <tbody>
                                @forelse($ultimosDocumentos ?? [] as $doc)
                                <tr>
                                    <td>{{ $doc->nro_carpeta }}</td>
                                    <td>{{ $doc->persona?->nombre_completo ?? 'N/D' }}</td>
                                    <td>Bs {{ number_format($doc->importe_deposito, 2) }}</td>
                                    <td>{{ $doc->created_at->format('d/m/Y') }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center">No hay documentos aún</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Últimas Importaciones
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr><th>Archivo</th><th>Registros</th><th>Fecha</th></tr>
                            </thead>
                            <tbody>
                                @forelse($ultimasImportaciones ?? [] as $imp)
                                <tr>
                                    <td>{{ $imp->nombre_archivo }}</td>
                                    <td>{{ $imp->registros_importados }}</td>
                                    <td>{{ $imp->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center">No hay importaciones aún</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
