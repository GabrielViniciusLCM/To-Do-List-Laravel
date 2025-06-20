<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Exception;

class TarefaController extends Controller
{
    public function index(Request $request)
    {
        $query = Tarefa::query();
        $query = Tarefa::where('user_id', auth()->id());

        // Verifica se quer filtrar só deletadas

        if ($request->status == 'deletada') {
            // Mostrar só tarefas deletadas (soft deleted)
            $query->onlyTrashed();
        } else {
            // Mostrar só tarefas não deletadas
            $query->whereNull('deleted_at');
        }

        // Filtros opcionais
        if ($request->filled('titulo')) {
            $query->where('titulo', 'like', '%' . $request->titulo . '%');
        }

        if ($request->filled('status') && in_array($request->status, ['pendente', 'concluida'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('prioridade') && in_array($request->prioridade, ['alta', 'media', 'baixa'])) {
            $query->where('prioridade', $request->prioridade);
        }


        $query->orderByRaw("CASE WHEN status = 'pendente' THEN 0 ELSE 1 END")
        ->orderByRaw("CASE prioridade
                        WHEN 'alta' THEN 0
                        WHEN 'media' THEN 1
                        WHEN 'baixa' THEN 2
                        ELSE 3
                    END")
        ->orderBy('created_at', 'asc');

        // Paginação (5 por página)
        $tarefas = $query->paginate(5)->withQueryString();
        $allTarefas = (clone $query)->get();
        $todasConcluidas = $allTarefas->isNotEmpty() && $allTarefas->every(function ($tarefa) {
            return $tarefa->status === 'concluida';
        });

        return view('tarefas.index', compact('tarefas', 'todasConcluidas'));
    }




    public function create()
    {
        return view('tarefas.create');
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'titulo' => 'required|max:255',
                'descricao' => 'nullable',
                'status' => 'in:pendente,concluida',
                'prioridade' => 'required|in:alta,media,baixa',
            ], [
                'titulo.required' => 'O campo título é obrigatório.',
                'titulo.max' => 'O título não pode ter mais de 255 caracteres.',
                'status.in' => 'O status deve ser "pendente" ou "concluida".',
                'prioridade.required' => 'A prioridade é obrigatória.',
                'prioridade.in' => 'A prioridade deve ser alta, média ou baixa.',
            ]);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Erro na validação. Verifique os campos e tente novamente.');
            }
            $request->merge(['user_id' => auth()->id()]);
            Tarefa::create($request->all());

            return redirect()->route('tarefas.index')->with('success', 'Tarefa criada com sucesso!');
        } catch (Exception $e) {
            Log::error('Erro ao criar tarefa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao criar a tarefa.');
        }
    }

    public function show(Tarefa $tarefa)
    {
        if ($tarefa->user_id !== auth()->id()) {
            return redirect()->route('tarefas.index')
                ->with('error', 'Você não tem permissão para fazer isso.');
        }
        return view('tarefas.show', compact('tarefa'));
    }

    public function edit(Tarefa $tarefa)
    {
        if ($tarefa->user_id !== auth()->id()) {
            return redirect()->route('tarefas.index')
                ->with('error', 'Você não tem permissão para fazer isso.');
        }
        return view('tarefas.edit', compact('tarefa'));
    }

    public function update(Request $request, Tarefa $tarefa)
    {
        try {
            if ($tarefa->user_id !== auth()->id()) {
                return redirect()->route('tarefas.index')
                    ->with('error', 'Você não tem permissão para fazer isso.');
            }
            $validator = Validator::make($request->all(), [
                'titulo' => 'required|max:255',
                'descricao' => 'nullable',
                'status' => 'in:pendente,concluida',
                'prioridade' => 'required|in:alta,media,baixa',
            ], [
                'titulo.required' => 'O campo título é obrigatório.',
                'titulo.max' => 'O título não pode ter mais de 255 caracteres.',
                'status.in' => 'O status deve ser "pendente" ou "concluida".',
                'prioridade.required' => 'A prioridade é obrigatória.',
                'prioridade.in' => 'A prioridade deve ser alta, média ou baixa.',

            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Erro na validação. Verifique os campos e tente novamente.');
            }

            $tarefa->update($request->all());

            return redirect()->route('tarefas.index')->with('success', 'Tarefa atualizada com sucesso!');
        } catch (Exception $e) {
            Log::error('Erro ao atualizar tarefa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao atualizar a tarefa.');
        }
    }

    public function destroy(Tarefa $tarefa)
    {
        try {
            if ($tarefa->user_id !== auth()->id()) {
                return redirect()->route('tarefas.index')
                    ->with('error', 'Você não tem permissão para fazer isso.');
            }
            $tarefa->delete();
            return redirect()->route('tarefas.index')->with('success', 'Tarefa deletada com sucesso!');
        } catch (Exception $e) {
            Log::error('Erro ao deletar tarefa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao deletar a tarefa.');
        }
    }

    public function atualizarStatus(Request $request, Tarefa $tarefa)
    {
        try {
            if ($tarefa->user_id !== auth()->id()) {
                return redirect()->route('tarefas.index')
                    ->with('error', 'Você não tem permissão para fazer isso.');
            }
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pendente,concluida',
            ], [
                'status.required' => 'O campo status é obrigatório.',
                'status.in' => 'O status deve ser "pendente" ou "concluida".',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $tarefa->update(['status' => $request->status]);

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            Log::error('Erro ao atualizar status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro interno ao atualizar status.'
            ], 500);
        }
    }

    public function restore($id)
    {
        $tarefa = Tarefa::onlyTrashed()->findOrFail($id);
        $tarefa->restore();

        // Atualiza o status para 'pendente' ao restaurar
        $tarefa->status = 'pendente';
        $tarefa->save();

        return redirect()->route('tarefas.index')->with('success', 'Tarefa restaurada com sucesso!');
    }


}
