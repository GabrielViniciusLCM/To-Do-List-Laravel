@extends('layout')

@section('content')
    <h1 class="text-center mb-4">Lista de Tarefas</h1>

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
                    <option value="">Todos os status</option>
                    <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                    <option value="concluida" {{ request('status') == 'concluida' ? 'selected' : '' }}>Concluída</option>
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
        <br/>


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
                    <tr data-id="{{ $tarefa->id }}" class="{{ $tarefa->status === 'concluida' ? 'concluida' : '' }}">
                        <td class="titulo" style="{{ $tarefa->status === 'concluida' ? 'text-decoration: line-through;' : '' }}">
                            {{ $tarefa->titulo }}
                        </td>
                        <td class="criada-em" style="{{ $tarefa->status === 'concluida' ? 'text-decoration: line-through;' : '' }}">
                            {{ $tarefa->created_at->format('d/m/Y') }}
                        </td>
                        <td>
                           
                                <button class="status-toggle btn btn-sm {{ $tarefa->status === 'concluida' ? 'btn-success' : 'btn-secondary' }}"
                                    data-status="{{ $tarefa->status }}"  title = "Alterar Status">
                                    <i class="fas fa-check"></i>
                                </button>
                           
                           
                            <span class="badge {{ $tarefa->status === 'concluida' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($tarefa->status) }}
                            </span>
                            
                            
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('tarefas.show', $tarefa) }}" class="btn btn-info btn-sm" title = "Ver Detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('tarefas.edit', $tarefa) }}" class="btn btn-primary btn-sm"  title = "Editar Tarefa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('tarefas.destroy', $tarefa) }}" method="POST" onsubmit="return confirm('Deseja excluir?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"  title = "Excluir Tarefa">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
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
@endsection

@section('scripts')

    {{-- JS para status toggle --}}
    <script>
        document.querySelectorAll('.status-toggle').forEach(function(button) {
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
                    body: JSON.stringify({status: newStatus})
                })
                    .then(response => {
                        if (response.ok) {
                            this.dataset.status = newStatus;
                            this.classList.toggle('btn-success', newStatus === 'concluida');
                            this.classList.toggle('btn-secondary', newStatus !== 'concluida');

                            // Badge
                            const badge = row.querySelector('.badge');
                            badge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                            badge.className = 'badge ' + (newStatus === 'concluida' ? 'bg-success' : 'bg-secondary');

                            // Riscado
                            row.querySelector('.titulo').style.textDecoration = newStatus === 'concluida' ? 'line-through' : 'none';
                            row.querySelector('.criada-em').style.textDecoration = newStatus === 'concluida' ? 'line-through' : 'none';

                            // Cor da linha
                            row.classList.toggle('concluida', newStatus === 'concluida');

                            // Efeito pulse
                            row.classList.add('pulse');
                            setTimeout(() => row.classList.remove('pulse'), 300);
                        } else {
                            alert('Erro ao atualizar o status.');
                        }
                    });
            });
        });
    </script>

    {{-- Estilos extras --}}
    <style>
        .note-table tbody tr:hover {
            background-color: #f0f8ff;
            transition: background-color 0.3s ease;
        }

        .concluida {
            background-color: #e6ffe6 !important;
        }

        .pulse {
            animation: pulse 0.3s ease-in-out;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.03); }
            100% { transform: scale(1); }
        }

        .rounded-circle {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pagination .page-link {
            color: #8b4513;
            background-color: #fff8dc;
            border: 1px solid #deb887;
        }

        .pagination .page-item.active .page-link {
            background-color: #f4a460;
            border-color: #cd853f;
            color: white;
        }

        .pagination .page-link:hover {
            background-color: #ffe4b5;
            border-color: #d2b48c;
        }

        
    </style>
@endsection
