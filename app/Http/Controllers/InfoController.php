<?php

namespace App\Http\Controllers;

use App\Models\Entidade;
use App\Models\Evento;
use App\Models\Dirigente;

class InfoController extends Controller
{
    /**
     * Get diocese info (coordenadores + próximos 3 eventos)
     */
    public function diocese(Entidade $diocese)
    {
        $this->authorize('view', $diocese);

        $coordenadores = $diocese->dirigentes()
            ->wherePivot('tipo_vinculo', 'coordenacao')
            ->limit(5)
            ->get(['dirigentes.id', 'dirigentes.nome'])
            ->toArray();

        $eventos = Evento::whereHas('entidades', function ($q) use ($diocese) {
            $q->where('entidade_id', $diocese->id);
        })
            ->where('data_inicio', '>=', now())
            ->orderBy('data_inicio', 'asc')
            ->limit(3)
            ->get(['id', 'nome', 'data_inicio'])
            ->map(function ($evento) {
                return [
                    'nome' => $evento->nome,
                    'data' => $evento->data_inicio->format('d/m/Y'),
                ];
            })
            ->toArray();

        return response()->json([
            'coordenadores' => $coordenadores,
            'eventos' => $eventos,
        ]);
    }

    /**
     * Get nucleo info (coordenadores + próximos 3 eventos)
     */
    public function nucleo(Entidade $nucleo)
    {
        $this->authorize('view', $nucleo);

        $coordenadores = $nucleo->dirigentes()
            ->wherePivot('tipo_vinculo', 'coordenacao')
            ->limit(5)
            ->get(['dirigentes.id', 'dirigentes.nome'])
            ->toArray();

        $eventos = Evento::whereHas('entidades', function ($q) use ($nucleo) {
            $q->where('entidade_id', $nucleo->id);
        })
            ->where('data_inicio', '>=', now())
            ->orderBy('data_inicio', 'asc')
            ->limit(3)
            ->get(['id', 'nome', 'data_inicio'])
            ->map(function ($evento) {
                return [
                    'nome' => $evento->nome,
                    'data' => $evento->data_inicio->format('d/m/Y'),
                ];
            })
            ->toArray();

        return response()->json([
            'coordenadores' => $coordenadores,
            'eventos' => $eventos,
        ]);
    }

    /**
     * Get secretaria info (coordenadores + próximos 3 eventos)
     */
    public function secretaria(Entidade $secretaria)
    {
        $this->authorize('view', $secretaria);

        $coordenadores = $secretaria->dirigentes()
            ->wherePivot('tipo_vinculo', 'coordenacao')
            ->limit(5)
            ->get(['dirigentes.id', 'dirigentes.nome'])
            ->toArray();

        $eventos = Evento::whereHas('entidades', function ($q) use ($secretaria) {
            $q->where('entidade_id', $secretaria->id);
        })
            ->where('data_inicio', '>=', now())
            ->orderBy('data_inicio', 'asc')
            ->limit(3)
            ->get(['id', 'nome', 'data_inicio'])
            ->map(function ($evento) {
                return [
                    'nome' => $evento->nome,
                    'data' => $evento->data_inicio->format('d/m/Y'),
                ];
            })
            ->toArray();

        return response()->json([
            'coordenadores' => $coordenadores,
            'eventos' => $eventos,
        ]);
    }

    /**
     * Get dirigente info (nome + nucleos + secretarias + habilidades)
     */
    public function dirigente(Dirigente $dirigente)
    {
        $this->authorize('view', $dirigente);

        $nucleos = $dirigente->entidades()
            ->where('tipo_entidade', 'nucleo')
            ->get(['entidades.id', 'entidades.nome'])
            ->toArray();

        $secretarias = $dirigente->entidades()
            ->where('tipo_entidade', 'secretaria')
            ->get(['entidades.id', 'entidades.nome'])
            ->toArray();

        $habilidades = $dirigente->habilidades()
            ->get(['habilidades.id', 'habilidades.nome'])
            ->toArray();

        return response()->json([
            'nome' => $dirigente->nome,
            'nucleos' => $nucleos,
            'secretarias' => $secretarias,
            'habilidades' => $habilidades,
        ]);
    }
}
