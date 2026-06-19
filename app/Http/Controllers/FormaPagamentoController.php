<?php

namespace App\Http\Controllers;

use App\Models\FormaPagamento;
use App\Http\Requests\StoreFormaPagamentoRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

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

    public function store(StoreFormaPagamentoRequest $request): RedirectResponse
    {
        FormaPagamento::create($request->validated());
        return redirect()->route('formas-pagamento.index')
            ->with('success', 'Forma de pagamento criada com sucesso!');
    }

    public function show(FormaPagamento $formaPagamento): View
    {
        return view('formas-pagamento.show', compact('formaPagamento'));
    }

    public function edit(FormaPagamento $formaPagamento): View
    {
        return view('formas-pagamento.edit', compact('formaPagamento'));
    }

    public function update(StoreFormaPagamentoRequest $request, FormaPagamento $formaPagamento): RedirectResponse
    {
        $formaPagamento->update($request->validated());
        return redirect()->route('formas-pagamento.show', $formaPagamento)
            ->with('success', 'Forma de pagamento atualizada com sucesso!');
    }

    public function destroy(FormaPagamento $formaPagamento): RedirectResponse
    {
        $formaPagamento->delete();
        return redirect()->route('formas-pagamento.index')
            ->with('success', 'Forma de pagamento deletada com sucesso!');
    }
}
