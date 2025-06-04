@csrf

<div class="mb-3">
    <label for="titulo" class="form-label">Título</label>
    <input type="text" name="titulo" class="form-control" value="{{ old('titulo', $tarefa->titulo ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="descricao" class="form-label">Descrição</label>
    <textarea name="descricao" class="form-control">{{ old('descricao', $tarefa->descricao ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label for="status" class="form-label">Status</label>
    <select name="status" class="form-select">
        <option value="pendente" {{ (old('status', $tarefa->status ?? '') == 'pendente') ? 'selected' : '' }}>Pendente</option>
        <option value="concluida" {{ (old('status', $tarefa->status ?? '') == 'concluida') ? 'selected' : '' }}>Concluída</option>
    </select>
</div>

<button type="submit" class="btn btn-primary">Salvar</button>
<a href="{{ route('tarefas.index') }}" class="btn btn-secondary">Cancelar</a>
