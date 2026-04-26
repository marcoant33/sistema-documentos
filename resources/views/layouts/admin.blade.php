<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sistema')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #2c3e50, #1a252f);
        }

        .sidebar .nav-link {
            color: white;
            padding: 10px;
            display: block;
        }

        .sidebar .nav-link:hover {
            background: #34495e;
        }

        .content {
            padding: 20px;
            background: #f8f9fa;
            min-height: 100vh;
        }
    </style>
</head>

<body>
<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-md-2 sidebar p-0">
            <h5 class="text-white text-center mt-3">SIGEDOC</h5>

            <a href="{{ route('documentos.index') }}" class="nav-link">📄 Documentos</a>
            <a href="{{ route('importaciones.index') }}" class="nav-link">📥 Importar</a>
            <a href="{{ route('reportes.index') }}" class="nav-link">📊 Reportes</a>

            <!-- LOGOUT -->
            <form method="POST" action="{{ route('logout') }}" class="mt-3 px-2">
                @csrf
                <button class="btn btn-danger w-100">Cerrar sesión</button>
            </form>
        </div>

        <!-- CONTENIDO -->
        <div class="col-md-10 content">
            @yield('content')
        </div>

    </div>
</div>
</body>
</html>
