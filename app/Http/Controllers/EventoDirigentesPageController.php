<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Entidade;

class EventoDirigentesPageController extends Controller
{
    public function index(Evento $evento)
    {
        $this->authorize('view', $evento);

        if (!$evento->isModuloHabilitado('participantes_dirigentes')) {
            return redirect()->route('eventos.show', $evento);
        }

        $entidadesEvento = $evento->entidades()->select('entidades.id', 'entidades.nome', 'entidades.tipo_entidade')->get();

        $dioceses   = Entidade::where('tipo_entidade', 'diocese')->where('ativo', true)->orderBy('nome')->get(['id', 'nome']);
        $nucleos    = Entidade::where('tipo_entidade', 'nucleo')->where('ativo', true)->orderBy('nome')->get(['id', 'nome', 'entidade_pai_id']);
        $secretarias = Entidade::where('tipo_entidade', 'secretaria')->where('ativo', true)->orderBy('nome')->get(['id', 'nome']);

        $participantes = $evento->participantes()
            ->where('tipo_participante', 'dirigente')
            ->with(['dirigente', 'funcaoDirigente', 'camisetaMedidas.tamanho', 'inscricaoOpcao.tipo', 'inscricaoOpcao.camisetas'])
            ->orderBy('created_at')
            ->get();

        $camisetaHabilitada = $evento->isModuloHabilitado('tipos_camiseta');

        $tiposInscricao = $evento->tiposInscricaoAtivos()
            ->whereIn('publico', ['dirigente_interno', 'dirigente_externo'])
            ->with(['opcoesAtivas.camisetasAtivas'])
            ->get();

        return view('eventos.modulos.dirigentes', compact(
            'evento', 'participantes', 'camisetaHabilitada', 'tiposInscricao',
            'entidadesEvento', 'dioceses', 'nucleos', 'secretarias'
        ));
    }
}
