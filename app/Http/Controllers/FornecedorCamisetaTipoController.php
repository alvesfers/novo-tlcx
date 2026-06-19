<?php

namespace App\Http\Controllers;

use App\Models\FornecedorCamisetaTipo;
use App\Models\FornecedorCamiseta;
use App\Http\Requests\StoreFornecedorCamisetaTipoRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FornecedorCamisetaTipoController extends Controller
{
    public function index(FornecedorCamiseta $fornecedorsCamisetum): View
    {
        $tipos = $fornecedorsCamisetum->tipos()->paginate(20);
        return view('fornecedor-camiseta-tipos.index', compact('fornecedorsCamisetum', 'tipos'));
    }

    public function create(FornecedorCamiseta $fornecedorsCamisetum): View
    {
        return view('fornecedor-camiseta-tipos.create', compact('fornecedorsCamisetum'));
    }

    public function store(StoreFornecedorCamisetaTipoRequest $request): RedirectResponse
    {
        FornecedorCamisetaTipo::create($request->validated());
        return redirect()->route('fornecedores-camisetas.tipos.index', $request->fornecedor_id)
            ->with('success', 'Tipo de camiseta criado com sucesso!');
    }

    public function destroy(FornecedorCamisetaTipo $tipo): RedirectResponse
    {
        $fornecedorId = $tipo->fornecedor_id;
        $tipo->delete();
        return redirect()->route('fornecedores-camisetas.tipos.index', $fornecedorId)
            ->with('success', 'Tipo de camiseta deletado com sucesso!');
    }
}
