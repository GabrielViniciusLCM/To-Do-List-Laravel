@extends('layout')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <h1>Editar Tarefa</h1>
    <form action="{{ route('tarefas.update', $tarefa) }}" method="POST">
        @method('PUT')
        @include('tarefas._form')
    </form>
@endsection
