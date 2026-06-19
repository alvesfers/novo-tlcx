<?php

namespace App\Http\Controllers;

use App\Models\BarzinhoProdutoConsignado;
use App\Models\Barzinho;
use App\Models\BarzinhoProduto;
use App\Models\AlmoxarifadoItem;
use App\Models\Entidade;
use App\Http\Requests\StoreBarzinhoProdutoConsignadoRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BarzinhoProdutoConsignadoController extends Controller
{
    public function index(Barzinho $barzinho): View
    {
        $consignados = $barzinho->produtos()
            ->whereHas('consignados')
            ->with('consignados.almoxarifadoItem', 'consignados.entidadeComissao')
            ->paginate(20);
        return view('barzinho-consignados.index', compact('barzinho', 'consignados'));
    }

    public function create(Barzinho $barzinho): View
    {
        $produtos = $barzinho->produtos()->get();
        $itens = AlmoxarifadoItem::where('ativo', true)->get();
        $entidades = Entidade::where('ativo', true)->get();
        return view('barzinho-consignados.create', compact('barzinho', 'produtos', 'itens', 'entidades'));
    }

    public function store(StoreBarzinhoProdutoConsignadoRequest $request): RedirectResponse
    {
        BarzinhoProdutoConsignado::create($request->validated());

        $produto = BarzinhoProduto::find($request->barzinho_produto_id);
        return redirect()->route('barzinhos.consignados.index', $produto->barzinho_id)
            ->with('success', 'Consignação criada com sucesso!');
    }

    public function destroy(BarzinhoProdutoConsignado $consignado): RedirectResponse
    {
        $barzinhoId = $consignado->produto->barzinho_id;
        $consignado->delete();
        return redirect()->route('barzinhos.consignados.index', $barzinhoId)
            ->with('success', 'Consignação deletada com sucesso!');
    }
}
