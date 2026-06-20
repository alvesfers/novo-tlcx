<?php

namespace App\Http\Controllers;

use App\Models\FormaPagamento;
use App\Http\Requests\StoreFormaPagamentoRequest;
use Illuminate\View\View;

class FormaPagamentoController extends Controller
{
    public function index(): View
    {
        $formas = FormaPagamento::with('entidade')->paginate(15);
        return view('formas-pagamento.index', compact('formas'));
    }

    public function create(): View
    {
        return view('formas-pagamento.create');
    }

    public function store(StoreFormaPagamentoRequest $request)
    {
        $forma = FormaPagamento::create($request->validated());

        if ($request->header('Accept') === 'application/json' || $request->isJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Forma de pagamento criada com sucesso!',
                'forma' => $forma,
            ]);
        }

        return redirect()->route('formas-pagamento.index')
            ->with('success', 'Forma de pagamento criada com sucesso!');
    }

    public function show($id): View
    {
        $formaPagamento = FormaPagamento::findOrFail($id);
        return view('formas-pagamento.show', compact('formaPagamento'));
    }

    public function edit($id): View
    {
        $formaPagamento = FormaPagamento::findOrFail($id);
        return view('formas-pagamento.edit', compact('formaPagamento'));
    }

    public function update(StoreFormaPagamentoRequest $request, $id)
    {
        $formaPagamento = FormaPagamento::findOrFail($id);
        $formaPagamento->update($request->validated());

        if ($request->header('Accept') === 'application/json' || $request->isJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Forma de pagamento atualizada com sucesso!',
                'forma' => $formaPagamento,
            ]);
        }

        return redirect()->route('formas-pagamento.show', $formaPagamento)
            ->with('success', 'Forma de pagamento atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $formaPagamento = FormaPagamento::findOrFail($id);
        $formaPagamento->delete();

        if (request()->header('Accept') === 'application/json' || request()->isJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Forma de pagamento deletada com sucesso!'
            ]);
        }

        return redirect()->route('formas-pagamento.index')
            ->with('success', 'Forma de pagamento deletada com sucesso!');
    }
}
