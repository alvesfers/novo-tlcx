<?php

namespace App\Http\Controllers;

use App\Models\EventoTipoCamiseta;
use App\Models\Evento;
use App\Models\FornecedorCamiseta;
use App\Http\Requests\StoreEventoTipoCamisetaRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EventoTipoCamisetaController extends Controller
{
    public function index(Evento $evento): View
    {
        $tipos = $evento->tiposCamiseta()->with('fornecedor')->paginate(20);
        return view('evento-tipos-camiseta.index', compact('evento', 'tipos'));
    }

    public function create(Evento $evento): View
    {
        $fornecedores = FornecedorCamiseta::where('ativo', true)->get();
        return view('evento-tipos-camiseta.create', compact('evento', 'fornecedores'));
    }

    public function store(StoreEventoTipoCamisetaRequest $request): RedirectResponse
    {
        EventoTipoCamiseta::create($request->validated());
        return redirect()->route('eventos.tipos-camiseta.index', $request->evento_id)
            ->with('success', 'Tipo de camiseta adicionado com sucesso!');
    }

    public function destroy(EventoTipoCamiseta $tiposCamisetum): RedirectResponse
    {
        $eventoId = $tiposCamisetum->evento_id;
        $tiposCamisetum->delete();
        return redirect()->route('eventos.tipos-camiseta.index', $eventoId)
            ->with('success', 'Tipo de camiseta removido com sucesso!');
    }
}
