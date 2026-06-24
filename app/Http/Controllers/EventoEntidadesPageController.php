<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Entidade;

class EventoEntidadesPageController extends Controller
{
    public function index(Evento $evento)
    {
        $this->authorize('view', $evento);

        if (!$evento->isModuloHabilitado('entidades')) {
            return redirect()->route('eventos.show', $evento);
        }

        $evento->load('eventoEntidades.entidade');

        // Buscar dioceses, núcleos e secretarias já envolvidos
        $dioceses = Entidade::ativas()->whereNotNull('tipo_entidade')->where('tipo_entidade', 'diocese')->get();
        $nucleos = Entidade::ativas()->whereNotNull('tipo_entidade')->where('tipo_entidade', 'nucleo')->get();
        $secretarias = Entidade::ativas()->whereNotNull('tipo_entidade')->where('tipo_entidade', 'secretaria')->get();

        return view('eventos.modulos.entidades', compact('evento', 'dioceses', 'nucleos', 'secretarias'));
    }
}
