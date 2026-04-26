<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema de Inventario Documental')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Ajustes de espaciado y logos */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar .logo-main {
            padding: 20px 15px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        .sidebar .logo-main img {
            max-width: 80%;
            height: auto;
            margin-bottom: 10px;
        }
        .sidebar .logo-main h5 {
            color: white;
            margin: 0;
            font-size: 1.1rem;
        }
        .sidebar .logo-main p {
            color: #a0aec0;
            font-size: 0.7rem;
            margin: 5px 0 0;
        }
        .sidebar .nav-link {
            padding: 12px 20px;
            transition: all 0.3s;
            border-radius: 8px;
            margin: 2px 10px;
        }
        .sidebar .nav-link:hover {
            background-color: #34495e;
            transform: translateX(5px);
        }
        .sidebar .footer-logo {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 15px;
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 0.75rem;
            color: #a0aec0;
        }
        .sidebar .footer-logo img {
            max-height: 30px;
            margin-bottom: 5px;
        }
        .content {
            padding: 20px;
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: none;
        }
        .card-header {
            border-radius: 12px 12px 0 0 !important;
            font-weight: 600;
        }
        .table th, .table td {
            vertical-align: middle;
            padding: 12px 8px;
        }
        .table th {
            background-color: #f1f3f5;
            font-weight: 600;
        }
        /* Eliminar espacios innecesarios en tablas */
        .table-responsive {
            overflow-x: auto;
        }
        .badge-solo-lectura {
            background-color: #6c757d;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: normal;
        }
        /* Ajustes de formularios de búsqueda */
        .search-form .row {
            margin-bottom: 15px;
        }
        .search-form .form-control,
        .search-form .btn {
            border-radius: 20px;
        }
        /* Paginación */
        .pagination {
            margin-top: 20px;
            justify-content: center;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0 position-relative">
                <!-- Logo principal del sistema -->
                <div class="logo-main">
                    <img src="{{ asset('images/logo-sistema.png') }}" alt="SIGEDOC-INM" onerror="this.src='https://via.placeholder.com/150x60?text=SIGEDOC-INM'">
                    <h5>SIGEDOC-INM</h5>
                    <p>Inventario Documental</p>
                </div>

                <nav class="nav flex-column">
                    <a href="{{ route('dashboard') }}" class="nav-link text-white py-2">
                        🏠 Dashboard
                    </a>
                    <a href="{{ route('documentos.index') }}" class="nav-link text-white py-2">
                        📄 Documentos
                    </a>
                    <a href="{{ route('importaciones.index') }}" class="nav-link text-white py-2">
                        📥 Importar Excel
                    </a>
                    <a href="{{ route('reportes.index') }}" class="nav-link text-white py-2">
                        📊 Reportes
                    </a>
                </nav>

                <!-- Logo personal de la empresa (abajo) -->
                <div class="footer-logo">
                    <img src="{{ asset('images/logo-empresa.png') }}" alt="Mi Empresa" onerror="this.style.display='none'">
                    <div>© {{ date('Y') }} Mi Empresa</div>
                    <div>Creado por: Tu Nombre</div>
                </div>

                <div class="position-absolute bottom-0 w-100 text-center p-2 mb-2">
                    <div class="badge-solo-lectura">Modo Solo Lectura</div>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="col-md-10 content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>
</html>
