<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Entidade;
use Illuminate\Http\Request;

class EventoEntidadesPageController extends Controller
{
    public function index(Evento $evento)
    {
        $this->authorize('view', $evento);

        if (!$evento->isModuloHabilitado('entidades')) {
            return redirect()->route('eventos.show', $evento);
        }

        $evento->load('eventoEntidades.entidade');
        $entidades = Entidade::ativas()->get();

        return view('eventos.modulos.entidades', compact('evento', 'entidades'));
    }
}
