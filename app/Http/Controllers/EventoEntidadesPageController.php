<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;

class EventoEntidadesPageController extends Controller
{
    public function index(Evento $evento)
    {
        $this->authorize('view', $evento);

        if (!$evento->isModuloHabilitado('entidades')) {
            return redirect()->route('eventos.show', $evento);
        }

        return view('eventos.modulos.entidades', compact('evento'));
    }
}
