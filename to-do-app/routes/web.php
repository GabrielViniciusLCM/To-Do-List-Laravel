<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TarefaController;

// Página inicial redireciona para lista de tarefas
Route::get('/', function () {
    return redirect()->route('tarefas.index');
});



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rotas do CRUD de tarefas (RESTful)
    Route::resource('tarefas', TarefaController::class);

    //Rota AJAX para atualizar status Tarefa
    Route::patch('/tarefas/{tarefa}/status', [TarefaController::class, 'atualizarStatus']);

    //Rota para restaurar tarefa excluida
    Route::patch('/tarefas/{id}/restore', [TarefaController::class, 'restore'])->name('tarefas.restore');
});

require __DIR__.'/auth.php';
