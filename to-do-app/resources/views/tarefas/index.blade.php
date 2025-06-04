@extends('layout')

@section('content')
    <h1 class="text-center mb-4">Lista de Tarefas</h1>

    <div class="note-block mx-auto p-4">
        <div class="text-end mb-3">
            <a href="{{ route('tarefas.create') }}" class="btn btn-success">Nova Tarefa</a>
        </div>

        <table class="table table-borderless table-striped note-table">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Criada em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($tarefas as $tarefa)
                    <tr data-id="{{ $tarefa->id }}">
                        <td class="titulo" style="{{ $tarefa->status === 'concluida' ? 'text-decoration: line-through;' : '' }}">
                            {{ $tarefa->titulo }}
                        </td>
                        <td class="criada-em" style="{{ $tarefa->status === 'concluida' ? 'text-decoration: line-through;' : '' }}">
                            {{ $tarefa->created_at->format('d/m/Y') }}
                        </td>
                        <td>
                            <button class="status-toggle btn btn-sm {{ $tarefa->status === 'concluida' ? 'btn-success' : 'btn-secondary' }}"
                                    data-status="{{ $tarefa->status }}">
                                {{ $tarefa->status === 'concluida' ? 'Concluída' : 'Pendente' }}
                            </button>
                            <a href="{{ route('tarefas.show', $tarefa) }}" class="btn btn-info btn-sm">Ver</a>
                            <a href="{{ route('tarefas.edit', $tarefa) }}" class="btn btn-primary btn-sm">Editar</a>
                            <form action="{{ route('tarefas.destroy', $tarefa) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Deseja excluir?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
@section('scripts')
<script>
   document.querySelectorAll('.status-toggle').forEach(function(button) {
    button.addEventListener('click', function() {
        const row = this.closest('tr');
        const id = row.dataset.id;
        const currentStatus = this.dataset.status;
        const newStatus = currentStatus === 'concluida' ? 'pendente' : 'concluida';

        fetch(`/tarefas/${id}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => {
            if (response.ok) {
                // Atualiza atributo data-status e texto
                this.dataset.status = newStatus;
                this.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                this.classList.toggle('btn-success', newStatus === 'concluida');
                this.classList.toggle('btn-secondary', newStatus !== 'concluida');

                // Atualiza risco das colunas título e criada em
                row.querySelector('.titulo').style.textDecoration = newStatus === 'concluida' ? 'line-through' : 'none';
                row.querySelector('.criada-em').style.textDecoration = newStatus === 'concluida' ? 'line-through' : 'none';

            } else {
                alert('Erro ao atualizar o status.');
            }
        });
    });
});


</script>
@endsection