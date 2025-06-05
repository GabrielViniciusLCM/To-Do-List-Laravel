<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TarefaController;

// Página inicial redireciona para lista de tarefas
Route::get('/', function () {
    return redirect()->route('tarefas.index');
});

// Rotas do CRUD de tarefas (RESTful)
Route::resource('tarefas', TarefaController::class);

//Rota AJAX para atualizar status Tarefa
Route::patch('/tarefas/{tarefa}/status', [TarefaController::class, 'atualizarStatus']);

//Rota para restaurar tarefa excluida
Route::patch('/tarefas/{id}/restore', [TarefaController::class, 'restore'])->name('tarefas.restore');



