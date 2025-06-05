<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>To-Do List</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous">

    <style>
        .note-block {
            background: #fff8dc;
            border: 2px solid #deb887;
            border-radius: 15px;
            box-shadow: 3px 3px 10px rgba(0,0,0,0.1);
            max-width: 800px;
            font-family: 'Comic Sans MS', cursive, sans-serif;
        }

        .note-table thead {
            background-color: #ffe4b5;
            border-bottom: 2px dashed #d2b48c;
        }

        .note-table tbody tr {
            border-bottom: 1px dashed #d2b48c;
        }

        .note-table td, .note-table th {
            vertical-align: middle;
        }

        .note-table tbody tr:hover {
            background-color: #f0f8ff;
            transition: background-color 0.3s ease;
        }

        .titulo {
            font-weight: bold;
        }

        .concluida {
            background-color: #e6ffe6 !important;
        }

        .pulse {
            animation: pulse 0.3s ease-in-out;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }
    </style>
</head>

<body>
    @yield('css')
    <div class="container mt-3">
        @auth
            <div class="text-end mb-2">
                <span class="text-muted">Bem-vindo(a), <strong>{{ Auth::user()->name }}</strong>!</span>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        @endauth

        @guest
            <div class="text-end mb-2">
                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Entrar</a>
                <a href="{{ route('register') }}" class="btn btn-outline-success btn-sm">Registrar</a>
            </div>
        @endguest
    </div>

    @foreach (['success', 'error', 'warning', 'info'] as $msg)
        @if (session($msg))
            <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} alert-dismissible fade show text-center" role="alert">
                {{ session($msg) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        @endif
    @endforeach

    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    @yield('scripts')
</body>
</html>
