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
</head>
<body>
    <div class="container mt-5">
        @yield('content')
    </div>
    @yield('scripts')
</body>
</html>
