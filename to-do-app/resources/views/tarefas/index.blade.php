@extends('layout')

@section('content')
@auth
    <div class="text-center mb-4">
        <h1>Lista de Tarefas de {{ Auth::user()->name }}</h1>
    </div>

    <div class="note-block mx-auto p-4">

        {{-- Mensagem se não houver tarefas --}}
        @if ($tarefas->isEmpty() && (request('titulo') || request('status')))
            <div class="alert alert-warning text-center">
                <i class="fas fa-search fa-lg"></i> Nenhuma tarefa encontrada com os filtros aplicados.
            </div>
        @elseif ($tarefas->isEmpty())
            <div class="alert alert-info text-center">
                <i class="fas fa-check-circle fa-lg"></i> Nenhuma tarefa encontrada. Crie uma nova!
            </div>
        @endif

        <form method="GET" action="{{ route('tarefas.index') }}" class="row g-2 mb-4">
            <div class="col-md-4">
                <input type="text" name="titulo" value="{{ request('titulo') }}" class="form-control" placeholder="Filtrar por título">
            </div>

            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">Pendentes e Concluidas</option>
                    <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                    <option value="concluida" {{ request('status') == 'concluida' ? 'selected' : '' }}>Concluída</option>
                    <option value="deletada" {{ request('status') == 'deletada' ? 'selected' : '' }}>Deletadas</option>
                </select>
            </div>

            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
                <a href="{{ route('tarefas.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Limpar
                </a>
            </div>
        </form>

        <table class="table table-borderless table-striped note-table align-middle">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Criada em</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tarefas as $tarefa)
                    <tr data-id="{{ $tarefa->id }}" class="{{ ($tarefa->status === 'concluida' && !$tarefa->trashed()) ? 'concluida' : '' }}">
                        <td class="titulo" style="{{ ($tarefa->status === 'concluida' && !$tarefa->trashed()) ? 'text-decoration: line-through;' : '' }}">
                            {{ $tarefa->titulo }}
                        </td>
                        <td class="criada-em" style="{{ ($tarefa->status === 'concluida' && !$tarefa->trashed()) ? 'text-decoration: line-through;' : '' }}">
                            {{ $tarefa->created_at->format('d/m/Y') }}
                        </td>
                        <td>
                            @if (!$tarefa->trashed())
                                <button class="status-toggle btn btn-sm {{ $tarefa->status === 'concluida' ? 'btn-success' : 'btn-secondary' }}"
                                    data-status="{{ $tarefa->status }}" title="Alterar Status">
                                    <i class="fas fa-check"></i>
                                </button>
                                <span class="badge {{ $tarefa->status === 'concluida' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($tarefa->status) }}
                                </span>
                            @else
                                <span class="badge bg-danger">Deletada</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                @if (!$tarefa->trashed())
                                    <a href="{{ route('tarefas.show', $tarefa) }}" class="btn btn-info btn-sm" title="Ver Detalhes">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('tarefas.edit', $tarefa) }}" class="btn btn-primary btn-sm" title="Editar Tarefa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('tarefas.destroy', $tarefa) }}" method="POST" onsubmit="return confirm('Deseja excluir?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" title="Excluir Tarefa">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('tarefas.restore', $tarefa->id) }}" method="POST" onsubmit="return confirm('Deseja restaurar esta tarefa?')">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-warning btn-sm" title="Restaurar Tarefa">
                                            <i class="fas fa-undo"></i> Restaurar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Paginação --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $tarefas->links('pagination::bootstrap-5') }}
        </div>
    </div>

    {{-- Botão flutuante para nova tarefa --}}
    <a href="{{ route('tarefas.create') }}"
        class="btn btn-success rounded-circle position-fixed shadow"
        style="bottom: 20px; right: 20px; width: 60px; height: 60px; font-size: 30px; text-align: center; line-height: 42px;">
        +
    </a>
@endauth

@guest
    <div class="alert alert-warning text-center">
        Você precisa <a href="{{ route('login') }}">entrar</a> para ver suas tarefas.
    </div>
@endguest
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.status-toggle').forEach(button => {
        button.addEventListener('click', function () {
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
                if (!response.ok) throw new Error('Erro ao atualizar o status.');

                // Atualizações visuais
                this.dataset.status = newStatus;
                this.classList.toggle('btn-success', newStatus === 'concluida');
                this.classList.toggle('btn-secondary', newStatus !== 'concluida');

                const badge = row.querySelector('.badge');
                badge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                badge.className = 'badge ' + (newStatus === 'concluida' ? 'bg-success' : 'bg-secondary');

                row.querySelector('.titulo').style.textDecoration = newStatus === 'concluida' ? 'line-through' : 'none';
                row.querySelector('.criada-em').style.textDecoration = newStatus === 'concluida' ? 'line-through' : 'none';
                row.classList.toggle('concluida', newStatus === 'concluida');

                row.classList.add('pulse');
                setTimeout(() => row.classList.remove('pulse'), 300);
            })
            .catch(() => alert('Erro ao atualizar o status.'));
        });
    });
</script>
@endsection
