<?php

namespace App\Http\Controllers;

use App\Models\Evento;

class EventoQuartosPageController extends Controller
{
    public function index(Evento $evento)
    {
        $this->authorize('view', $evento);

        $alas = $evento->casaDeRetiro?->alas ?? [];
        $quartos = [];

        foreach ($alas as $ala) {
            $quartos[$ala->id_ala] = $ala->quartos ?? [];
        }

        $participantes = $evento->participantes;
        $alocacoes = $evento->quartos ?? [];

        return view('eventos.modulos.quartos', compact('evento', 'alas', 'quartos', 'participantes', 'alocacoes'));
    }
}
