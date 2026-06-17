<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Entidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventoCalendarController extends Controller
{
    public function index()
    {
        $entidades = Entidade::where('ativo', true)
            ->orderBy('tipo_entidade')
            ->orderBy('nome')
            ->get();

        $data = [
            'title' => 'Calendário de Eventos',
            'entidades' => $entidades,
        ];

        return view('eventos.calendario', $data);
    }

    public function getEventos(Request $request)
    {
        $query = Evento::query()
            ->where('ativo', true)
            ->with(['entidadeCriadora', 'tipoEvento', 'entidades']);

        // Filtro por entidades
        if ($request->has('entidades') && is_array($request->entidades)) {
            $entidadesIds = array_filter($request->entidades);
            if (!empty($entidadesIds)) {
                $query->whereHas('entidades', function ($q) use ($entidadesIds) {
                    $q->whereIn('entidade_id', $entidadesIds);
                }, '>', 0)
                ->orWhereIn('entidade_criadora_id', $entidadesIds);
            }
        }

        // Filtro por meus eventos (entidades onde o usuário é membro)
        if ($request->has('meus_eventos') && $request->meus_eventos) {
            $user = Auth::user();
            if ($user && $user->entidade_id) {
                $query->where(function ($q) use ($user) {
                    $q->where('entidade_criadora_id', $user->entidade_id)
                        ->orWhereHas('entidades', function ($subQ) use ($user) {
                            $subQ->where('entidade_id', $user->entidade_id);
                        });
                });
            }
        }

        $eventos = $query->get();

        $result = $eventos->map(function ($evento) {
            return [
                'id' => $evento->id,
                'title' => $evento->nome,
                'start' => $evento->data_inicio->toIso8601String(),
                'end' => $evento->data_fim ? $evento->data_fim->toIso8601String() : $evento->data_inicio->toIso8601String(),
                'description' => $evento->descricao,
                'local' => $evento->local,
                'tipo' => $evento->tipoEvento?->nome,
                'criadora' => $evento->entidadeCriadora?->nome,
                'status' => $evento->status->value,
                'color' => $this->getCorPorStatus($evento->status->value),
            ];
        });

        return response()->json($result);
    }

    private function getCorPorStatus($status)
    {
        $cores = [
            'rascunho' => '#9CA3AF',
            'publicado' => '#3B82F6',
            'encerrado' => '#6B7280',
            'cancelado' => '#EF4444',
        ];

        return $cores[$status] ?? '#3B82F6';
    }
}
