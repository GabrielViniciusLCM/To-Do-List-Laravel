@extends('layout')

@section('content')
    <h1>Editar Tarefa</h1>
    <form action="{{ route('tarefas.update', $tarefa) }}" method="POST">
        @method('PUT')
        @include('tarefas._form')
    </form>
@endsection
