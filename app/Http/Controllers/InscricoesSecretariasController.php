<?php

namespace App\Http\Controllers;

use App\Models\Entidade;
use App\Models\Habilidade;
use App\Enums\NivelHabilidade;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InscricoesSecretariasController extends Controller
{
    public function index()
    {
        $dioceses = Entidade::where('tipo_entidade', 'diocese')
            ->where('ativo', true)
            ->orderBy('nome')
            ->get(['id', 'nome']);

        $secretarias = Entidade::where('tipo_entidade', 'secretaria')
            ->where('ativo', true)
            ->orderBy('nome')
            ->get(['id', 'nome', 'tipo_secretaria']);

        $habilidades = Habilidade::where('ativo', true)
            ->orderBy('nome')
            ->get(['id', 'nome', 'entidade_id']);

        $niveis = collect(NivelHabilidade::cases())->map(fn($c) => [
            'value' => $c->value,
            'label' => $c->label(),
        ]);

        return view('inscricoes.secretarias', compact(
            'dioceses', 'secretarias', 'habilidades', 'niveis'
        ));
    }

    public function storeSecretaria(Request $request): JsonResponse
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'tipo_secretaria' => 'nullable|string|in:aberta,fechada',
        ]);

        try {
            $secretaria = Entidade::create([
                'tipo_entidade' => 'secretaria',
                'nome' => $request->nome,
                'tipo_secretaria' => $request->tipo_secretaria ?? 'aberta',
                'ativo' => true,
            ]);

            return response()->json($secretaria, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao criar secretaria', 'error' => $e->getMessage()], 500);
        }
    }

    public function storeHabilidade(Request $request): JsonResponse
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'secretaria_id' => 'required|exists:entidades,id',
        ]);

        try {
            $habilidade = Habilidade::create([
                'nome' => $request->nome,
                'entidade_id' => $request->secretaria_id,
                'ativo' => true,
            ]);

            return response()->json($habilidade, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao criar habilidade', 'error' => $e->getMessage()], 500);
        }
    }

    public function deleteHabilidade(Request $request): JsonResponse
    {
        $request->validate([
            'habilidade_id' => 'required|exists:habilidades,id',
        ]);

        try {
            $habilidade = Habilidade::find($request->habilidade_id);
            $habilidade->delete();

            return response()->json(['message' => 'Habilidade removida com sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao remover habilidade', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateSecretaria(Request $request): JsonResponse
    {
        $request->validate([
            'secretaria_id' => 'required|exists:entidades,id',
            'tipo_secretaria' => 'required|string|in:aberta,fechada',
        ]);

        try {
            $secretaria = Entidade::find($request->secretaria_id);
            $secretaria->update(['tipo_secretaria' => $request->tipo_secretaria]);

            return response()->json($secretaria, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar secretaria', 'error' => $e->getMessage()], 500);
        }
    }
}
