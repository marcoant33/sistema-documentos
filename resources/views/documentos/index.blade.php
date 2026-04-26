@extends('layouts.app')

@section('title', 'Documentos')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5>📋 Inventario de Documentos</h5>
        <small>Total: {{ $documentos->total() }} registros activos</small>
    </div>
    <div class="card-body">
        <!-- Formulario de búsqueda -->
        <form method="GET" action="{{ route('documentos.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre" value="{{ request('nombre') }}">
                </div>
                <div class="col-md-2">
                    <input type="text" name="ci" class="form-control" placeholder="Cédula" value="{{ request('ci') }}">
                </div>
                <div class="col-md-2">
                    <input type="text" name="nro_boleta" class="form-control" placeholder="N° Boleta" value="{{ request('nro_boleta') }}">
                </div>
                <div class="col-md-2">
                    <input type="text" name="carpeta" class="form-control" placeholder="N° Carpeta" value="{{ request('carpeta') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="manzano_lote" class="form-control" placeholder="Manzano/Lote (ej: 578/14)" value="{{ request('manzano_lote') }}">
                </div>
                <div class="col-md-2">
                    <input type="number" name="importe_min" class="form-control" placeholder="Depósito mínimo" value="{{ request('importe_min') }}">
                </div>
                <div class="col-md-2">
                    <input type="number" name="importe_max" class="form-control" placeholder="Depósito máximo" value="{{ request('importe_max') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">🔍 Buscar</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('documentos.index') }}" class="btn btn-secondary w-100">🗑️ Limpiar</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>N° Carpeta</th>
                        <th>Nombre</th>
                        <th>CI</th>
                        <th>N° Boleta</th>
                        <th>Depósito (Bs)</th>
                        <th>Contrato (Bs)</th>
                        <th>Diferencia</th>
                        <th>Manzano/Lote</th>
                        <th>Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documentos as $doc)
                    <tr>
                        <td>{{ $doc->nro_carpeta }}</td>
                        <td>{{ $doc->persona?->nombre_completo ?? 'N/D' }}</td>
                        <td>{{ $doc->persona?->ci ?? 'N/D' }}</td>
                        <td>{{ $doc->nro_boleta ?? 'N/D' }}</td>
                        <td class="text-end">Bs {{ number_format($doc->importe_deposito, 2) }}</td>
                        <td class="text-end">Bs {{ number_format($doc->importe_contrato ?? 0, 2) }}</td>
                        <td class="text-end {{ $doc->diferencia > 0 ? 'text-danger' : 'text-success' }}">
                            Bs {{ number_format($doc->diferencia, 2) }}
                        </td>
                        <td>{{ $doc->cod_manzano }}/{{ $doc->cod_lote }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modal{{ $doc->id }}">
                                Ver
                            </button>
                        </td>
                    </tr>

                    <!-- Modal de Detalle -->
                    <div class="modal fade" id="modal{{ $doc->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-info text-white">
                                    <h5 class="modal-title">Detalle del Documento</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>📁 Carpeta:</strong> {{ $doc->nro_carpeta }}<br>
                                            <strong>👤 Nombre:</strong> {{ $doc->persona?->nombre_completo ?? 'N/D' }}<br>
                                            <strong>🆔 CI:</strong> {{ $doc->persona?->ci ?? 'N/D' }}<br>
                                            <strong>🎫 N° Boleta:</strong> {{ $doc->nro_boleta ?? 'N/D' }}<br>
                                            <strong>💰 Importe Depósito:</strong> Bs {{ number_format($doc->importe_deposito, 2) }}<br>
                                            <strong>📅 Fecha Depósito:</strong> {{ $doc->fecha_deposito?->format('d/m/Y') ?? 'N/D' }}<br>
                                            <strong>🕒 Hora:</strong> {{ $doc->hora_deposito ?? 'N/D' }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>📍 Manzano/Lote:</strong> {{ $doc->cod_manzano }}/{{ $doc->cod_lote }}<br>
                                            <strong>📄 Contrato:</strong> {{ $doc->tipo_contrato ?? 'N/D' }}<br>
                                            <strong>📐 Superficie:</strong> {{ number_format($doc->superficie_m2 ?? 0, 2) }} m²<br>
                                            <strong>💰 Valor m²:</strong> Bs {{ number_format($doc->valor_por_m2, 2) }}<br>
                                            <strong>💵 Importe Contrato:</strong> Bs {{ number_format($doc->importe_contrato ?? 0, 2) }}<br>
                                            <strong>📅 Fecha Contrato:</strong> {{ $doc->fecha_contrato?->format('d/m/Y') ?? 'N/D' }}<br>
                                            <strong>📝 Folio:</strong> {{ $doc->nro_folio ?? 'N/D' }}
                                        </div>
                                        <div class="col-12 mt-3">
                                            <strong>📌 Observaciones:</strong> {{ $doc->observaciones ?? 'Ninguna' }}<br>
                                            <strong>📂 Archivo Origen:</strong> {{ $doc->archivo_origen ?? 'N/D' }}<br>
                                            <small class="text-muted">🕒 Importado: {{ $doc->created_at->format('d/m/Y H:i:s') }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No se encontraron documentos</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $documentos->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
