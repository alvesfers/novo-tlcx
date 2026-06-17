<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FinanceiroMovimento;
use Illuminate\Http\Request;

class FinanceiroController extends Controller
{
    public function movimentos(Request $request)
    {
        $user = auth()->user();
        $query = FinanceiroMovimento::with(['categoria', 'entidade']);

        if (!$user->isAdmin()) {
            $query->where('entidade_id', $user->entidade_id);
        }

        $movimentos = $query->paginate(25);

        return response()->json([
            'success' => true,
            'data' => $movimentos->items(),
            'meta' => [
                'current_page' => $movimentos->currentPage(),
                'total' => $movimentos->total(),
                'per_page' => $movimentos->perPage(),
            ],
        ]);
    }

    public function storeMovimento(Request $request)
    {
        $request->validate([
            'categoria_id' => 'required|exists:financeiro_categorias,id',
            'tipo' => 'required|in:entrada,saida',
            'valor' => 'required|numeric|min:0.01',
            'descricao' => 'required|string',
            'data_movimento' => 'required|date',
            'forma_pagamento' => 'required|string',
        ]);

        $movimento = FinanceiroMovimento::create(array_merge(
            $request->validated(),
            ['entidade_id' => auth()->user()->entidade_id]
        ));

        return response()->json([
            'success' => true,
            'data' => $movimento,
            'message' => 'Movimento registrado com sucesso',
        ], 201);
    }

    public function extrato(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->input('start_date', now()->subMonths(1)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $query = FinanceiroMovimento::with(['categoria', 'entidade'])
            ->whereBetween('data_movimento', [$startDate, $endDate]);

        if (!$user->isAdmin()) {
            $query->where('entidade_id', $user->entidade_id);
        }

        $movimentos = $query->orderByDesc('data_movimento')->get();

        $entradas = $movimentos->where('tipo', 'entrada')->sum('valor');
        $saidas = $movimentos->where('tipo', 'saida')->sum('valor');

        return response()->json([
            'success' => true,
            'data' => $movimentos,
            'meta' => [
                'entradas' => $entradas,
                'saidas' => $saidas,
                'saldo' => $entradas - $saidas,
            ],
        ]);
    }

    public function saldo(Request $request)
    {
        $user = auth()->user();
        $query = FinanceiroMovimento::query();

        if (!$user->isAdmin()) {
            $query->where('entidade_id', $user->entidade_id);
        }

        $entradas = $query->clone()->where('tipo', 'entrada')->sum('valor');
        $saidas = $query->clone()->where('tipo', 'saida')->sum('valor');

        return response()->json([
            'success' => true,
            'data' => [
                'entradas' => $entradas,
                'saidas' => $saidas,
                'saldo' => $entradas - $saidas,
            ],
        ]);
    }
}
