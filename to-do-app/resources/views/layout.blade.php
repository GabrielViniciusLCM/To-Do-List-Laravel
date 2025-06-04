<!DOCTYPE html>
<html>
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

    .titulo {
        font-weight: bold;
    }

</style>
<head>
    <title>To-Do List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous">


</head>
<body>
    @foreach (['success', 'error', 'warning', 'info'] as $msg)
        @if (session($msg))
            <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }} alert-dismissible fade show text-center" role="alert">
                {{ session($msg) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        @endif
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <div class="container mt-5">
        @yield('content')
    </div>
    @yield('scripts')
</body>
</html>
