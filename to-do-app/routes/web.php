<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TarefaController;

// Página inicial redireciona para lista de tarefas (opcional)
Route::get('/', function () {
    return redirect()->route('tarefas.index');
});

// Rotas do CRUD de tarefas
Route::resource('tarefas', TarefaController::class);
Route::patch('/tarefas/{tarefa}/status', [TarefaController::class, 'atualizarStatus']);

