<?php

namespace App\Http\Controllers;

use App\Enums\NivelHabilidade;
use App\Enums\TipoEntidade;
use App\Enums\TipoVinculo;
use App\Models\Dirigente;
use App\Models\DirigenteEntidade;
use App\Models\Entidade;
use App\Models\Evento;
use App\Models\Habilidade;
use App\Models\TipoEvento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InscricaoController extends Controller
{
    public function show()
    {
        $dioceses = Entidade::where('tipo_entidade', TipoEntidade::Diocese)
            ->where('ativo', true)->orderBy('nome')->get(['id', 'nome']);

        $nucleos = Entidade::where('tipo_entidade', TipoEntidade::Nucleo)
            ->where('ativo', true)->orderBy('nome')->get(['id', 'nome', 'entidade_pai_id']);

        $secretarias = Entidade::where('tipo_entidade', TipoEntidade::Secretaria)
            ->where('ativo', true)->orderBy('nome')->get(['id', 'nome', 'entidade_pai_id']);

        $habilidades = Habilidade::where('ativo', true)
            ->orderBy('nome')->get(['id', 'nome', 'entidade_id']);

        $tipoTlcId     = TipoEvento::where('nome', 'TLC')->value('id');
        $tipoMiniTlcId = TipoEvento::where('nome', 'Mini TLC')->value('id');

        // Apenas eventos que já aconteceram
        $eventosTlc = Evento::where('tipo_evento_id', $tipoTlcId)
            ->where('ativo', true)
            ->whereNotNull('data_inicio')
            ->where('data_inicio', '<=', now())
            ->orderBy('data_inicio', 'desc')
            ->get(['id', 'nome', 'tema', 'data_inicio', 'entidade_criadora_id'])
            ->map(fn($e) => [
                'id'                   => $e->id,
                'nome'                 => $e->nome,
                'tema'                 => $e->tema,
                'data_inicio'          => $e->data_inicio?->format('Y-m-d'),
                'entidade_criadora_id' => $e->entidade_criadora_id,
            ]);

        $eventosMiniTlc = Evento::where('tipo_evento_id', $tipoMiniTlcId)
            ->where('ativo', true)
            ->whereNotNull('data_inicio')
            ->where('data_inicio', '<=', now())
            ->orderBy('data_inicio', 'desc')
            ->get(['id', 'nome', 'tema', 'data_inicio', 'entidade_criadora_id'])
            ->map(fn($e) => [
                'id'                   => $e->id,
                'nome'                 => $e->nome,
                'tema'                 => $e->tema,
                'data_inicio'          => $e->data_inicio?->format('Y-m-d'),
                'entidade_criadora_id' => $e->entidade_criadora_id,
            ]);

        $niveis = collect(NivelHabilidade::cases())->map(fn($c) => [
            'value' => $c->value,
            'label' => $c->label(),
        ]);

        return view('inscricao.index', compact(
            'dioceses', 'nucleos', 'secretarias', 'habilidades',
            'eventosTlc', 'eventosMiniTlc', 'niveis'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'            => 'required|string|max:255',
            'apelido'         => 'nullable|string|max:255',
            'email'           => 'nullable|email|max:255',
            'telefone'        => 'nullable|string|max:20',
            'genero'          => 'nullable|in:M,F,O',
            'data_nascimento' => 'nullable|date',
            'id_mini_tlc'     => 'nullable|exists:eventos,id',
            'id_tlc'          => 'nullable|exists:eventos,id',
            'timeline'        => 'nullable|json',
            'secretarias'     => 'nullable|array',
            'secretarias.*'   => 'exists:entidades,id',
            'habilidades'     => 'nullable|array',
        ]);

        $timeline = json_decode($request->input('timeline', '[]'), true) ?? [];

        // Garante que haja ao menos um núcleo ativo na timeline
        $temAtivo = collect($timeline)->contains('ativo', true);
        if (!$temAtivo && count($timeline) > 0) {
            $timeline[count($timeline) - 1]['ativo']    = true;
            $timeline[count($timeline) - 1]['data_fim'] = null;
        }

        DB::transaction(function () use ($request, $timeline) {
            $dirigente = Dirigente::create([
                'nome'            => $request->nome,
                'apelido'         => $request->apelido,
                'email'           => $request->email,
                'telefone'        => $request->telefone,
                'genero'          => $request->genero,
                'data_nascimento' => $request->data_nascimento,
                'id_tlc'          => $request->id_tlc,
                'id_mini_tlc'     => $request->id_mini_tlc,
                'ativo'           => true,
            ]);

            // Vínculos de núcleo (principal) — histórico completo
            foreach ($timeline as $periodo) {
                if (empty($periodo['nucleo_id'])) continue;

                DirigenteEntidade::create([
                    'dirigente_id' => $dirigente->id,
                    'entidade_id'  => $periodo['nucleo_id'],
                    'tipo_vinculo' => TipoVinculo::Principal,
                    'cargo'        => 'dirigente',
                    'data_inicio'  => $periodo['data_inicio'] ?: null,
                    'data_fim'     => $periodo['data_fim'] ?: null,
                    'ativo'        => (bool) $periodo['ativo'],
                ]);
            }

            // Vínculos adicionais com secretarias
            foreach ($request->input('secretarias', []) as $secretariaId) {
                DirigenteEntidade::create([
                    'dirigente_id' => $dirigente->id,
                    'entidade_id'  => $secretariaId,
                    'tipo_vinculo' => TipoVinculo::Adicional,
                    'cargo'        => 'dirigente',
                    'data_inicio'  => now()->toDateString(),
                    'ativo'        => true,
                ]);
            }

            // Habilidades
            $habs = [];
            foreach ($request->input('habilidades', []) as $habId => $dados) {
                if (!empty($dados['nivel'])) {
                    $habs[$habId] = ['nivel' => $dados['nivel']];
                }
            }
            if ($habs) {
                $dirigente->habilidades()->attach($habs);
            }
        });

        return redirect()->route('inscricao.show')
            ->with('success', 'Inscrição realizada com sucesso! Em breve entraremos em contato.');
    }
}
