@extends('layouts.app')

@section('title', 'Importar Excel')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5>📥 Importación de Archivos Excel</h5>
    </div>
    <div class="card-body">
        @if(session('resultados'))
            <div class="alert alert-info">
                <h6>Resultados de la importación:</h6>
                @foreach(session('resultados') as $res)
                    <p>
                        <strong>{{ $res['archivo'] }}</strong>:
                        @if(isset($res['error']))
                            ❌ Error: {{ $res['error'] }}
                        @else
                            ✅ {{ $res['importados'] }} registros nuevos
                            @if(($res['duplicados'] ?? 0) > 0)
                                , 🔄 {{ $res['duplicados'] }} duplicados omitidos
                            @endif
                            @if(($res['errores'] ?? 0) > 0)
                                , ⚠️ {{ $res['errores'] }} errores
                            @endif
                        @endif
                    </p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('importaciones.procesar') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Selecciona uno o más archivos Excel</label>
                <input type="file" name="archivos[]" class="form-control" multiple accept=".xlsx,.xls,.csv" required>
                <small class="text-muted">
                    Puedes seleccionar múltiples archivos a la vez. Los duplicados se omitirán automáticamente.
                </small>
            </div>
            <button type="submit" class="btn btn-primary">
                🚀 Importar Archivos
            </button>
        </form>

        <hr>

        <h6>📊 Historial de Importaciones</h6>
        <div class="table-responsive">
            <table class="table table-sm" id="tablaImportaciones">
                <thead>
                    <tr>
                        <th>Archivo</th>
                        <th>Fecha</th>
                        <th>Registros</th>
                        <th>Duplicados</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($importaciones as $imp)
                    <tr>
                        <td>{{ $imp->nombre_archivo }}</td>
                        <td>{{ $imp->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>{{ $imp->registros_importados }}</td>
                        <td>{{ $imp->registros_duplicados }}</td>
                        <td>
                            @if($imp->estado == 'completada')
                                <span class="badge bg-success">Completada</span>
                            @elseif($imp->estado == 'parcial')
                                <span class="badge bg-warning">Parcial</span>
                            @else
                                <span class="badge bg-danger">Fallida</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#tablaImportaciones').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' },
            order: [[1, 'desc']]
        });
    });
</script>
@endpush
