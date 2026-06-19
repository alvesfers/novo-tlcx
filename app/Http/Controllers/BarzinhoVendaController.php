<?php

namespace App\Http\Controllers;

use App\Models\BarzinhoVenda;
use App\Models\Barzinho;
use App\Http\Requests\StoreBarzinhoVendaRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BarzinhoVendaController extends Controller
{
    public function index(Barzinho $barzinho): View
    {
        $vendas = $barzinho->vendas()->with('participante')->paginate(20);
        return view('barzinho-vendas.index', compact('barzinho', 'vendas'));
    }

    public function create(Barzinho $barzinho): View
    {
        $barzinho->load(['produtos', 'combos']);
        return view('barzinho-vendas.create', compact('barzinho'));
    }

    public function store(StoreBarzinhoVendaRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $subtotal = $data['subtotal'];
        $desconto = $data['desconto'] ?? 0;
        $taxa = 0;

        if (isset($data['forma_pagamento_id']) && isset($data['tipo_pagamento'])) {
            $formaPagamento = \App\Models\FormaPagamento::find($data['forma_pagamento_id']);
            if ($formaPagamento) {
                $fieldTaxa = 'taxa_' . $data['tipo_pagamento'];
                if (property_exists($formaPagamento, $fieldTaxa)) {
                    $taxa = (($subtotal - $desconto) * $formaPagamento->{$fieldTaxa}) / 100;
                }
            }
        }

        $data['taxa_pagamento'] = $taxa;
        $data['total'] = $subtotal - $desconto + $taxa;

        BarzinhoVenda::create($data);
        return redirect()->route('barzinhos.vendas.index', $data['barzinho_id'])
            ->with('success', 'Venda registrada com sucesso!');
    }

    public function show(Barzinho $barzinho, BarzinhoVenda $venda): View
    {
        $venda->load(['itens', 'participante', 'formaPagamento']);
        return view('barzinho-vendas.show', compact('barzinho', 'venda'));
    }

    public function destroy(BarzinhoVenda $venda): RedirectResponse
    {
        $barzinhoId = $venda->barzinho_id;
        $venda->delete();
        return redirect()->route('barzinhos.vendas.index', $barzinhoId)
            ->with('success', 'Venda deletada com sucesso!');
    }
}
