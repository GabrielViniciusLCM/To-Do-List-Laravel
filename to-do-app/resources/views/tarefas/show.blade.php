@extends('layout')

@section('content')
    <h1>{{ $tarefa->titulo }}</h1>

    <p><strong>Status:</strong> {{ ucfirst($tarefa->status) }}</p>
    <p><strong>Descrição:</strong> {{ $tarefa->descricao ?? '—' }}</p>
    <p><strong>Criada em:</strong> {{ $tarefa->created_at->format('d/m/Y H:i') }}</p>

    <a href="{{ route('tarefas.index') }}" class="btn btn-secondary">Voltar</a>
@endsection
