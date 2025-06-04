@extends('layout')

@section('content')
    <h1>Nova Tarefa</h1>
    <form action="{{ route('tarefas.store') }}" method="POST">
        @include('tarefas._form')
    </form>
@endsection
