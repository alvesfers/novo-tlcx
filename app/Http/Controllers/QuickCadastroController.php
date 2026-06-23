<?php

namespace App\Http\Controllers;

use App\Enums\EscopoEvento;
use App\Enums\StatusEvento;
use App\Enums\TipoEntidade;
use App\Models\Entidade;
use App\Models\Evento;
use App\Models\TipoEvento;
use Illuminate\Http\Request;

class QuickCadastroController extends Controller
{
    public function diocese(Request $request)
    {
        $request->validate(['nome' => 'required|string|max:255']);

        $diocese = Entidade::create([
            'tipo_entidade' => TipoEntidade::Diocese,
            'nome'          => $request->nome,
            'ativo'         => true,
        ]);

        return response()->json(['id' => $diocese->id, 'nome' => $diocese->nome]);
    }

    public function nucleo(Request $request)
    {
        $request->validate([
            'nome'             => 'required|string|max:255',
            'entidade_pai_id'  => 'required|exists:entidades,id',
        ]);

        $nucleo = Entidade::create([
            'tipo_entidade'   => TipoEntidade::Nucleo,
            'nome'            => $request->nome,
            'entidade_pai_id' => $request->entidade_pai_id,
            'ativo'           => true,
        ]);

        return response()->json(['id' => $nucleo->id, 'nome' => $nucleo->nome, 'entidade_pai_id' => $nucleo->entidade_pai_id]);
    }

    public function secretaria(Request $request)
    {
        $request->validate([
            'nome'             => 'required|string|max:255',
            'entidade_pai_id'  => 'required|exists:entidades,id',
        ]);

        $secretaria = Entidade::create([
            'tipo_entidade'   => TipoEntidade::Secretaria,
            'nome'            => $request->nome,
            'entidade_pai_id' => $request->entidade_pai_id,
            'ativo'           => true,
        ]);

        return response()->json(['id' => $secretaria->id, 'nome' => $secretaria->nome, 'entidade_pai_id' => $secretaria->entidade_pai_id]);
    }

    public function eventoTlc(Request $request)
    {
        $request->validate([
            'nome'                 => 'required|string|max:255',
            'data_inicio'          => 'required|date',
            'entidade_criadora_id' => 'required|exists:entidades,id',
        ]);

        $tipoId = TipoEvento::where('nome', 'TLC')->value('id');

        $evento = Evento::create([
            'tipo_evento_id'       => $tipoId,
            'entidade_criadora_id' => $request->entidade_criadora_id,
            'nome'                 => $request->nome,
            'tema'                 => $request->tema,
            'data_inicio'          => $request->data_inicio,
            'escopo'               => EscopoEvento::Dirigentes->value,
            'status'               => StatusEvento::Publicado->value,
            'ativo'                => true,
        ]);

        return response()->json(['id' => $evento->id, 'nome' => $evento->nome, 'tema' => $evento->tema, 'data_inicio' => $evento->data_inicio, 'entidade_criadora_id' => $evento->entidade_criadora_id]);
    }

    public function eventoMiniTlc(Request $request)
    {
        $request->validate([
            'nome'                 => 'required|string|max:255',
            'data_inicio'          => 'required|date',
            'entidade_criadora_id' => 'required|exists:entidades,id',
        ]);

        $tipoId = TipoEvento::where('nome', 'Mini TLC')->value('id');

        $evento = Evento::create([
            'tipo_evento_id'       => $tipoId,
            'entidade_criadora_id' => $request->entidade_criadora_id,
            'nome'                 => $request->nome,
            'tema'                 => $request->tema,
            'data_inicio'          => $request->data_inicio,
            'escopo'               => EscopoEvento::Dirigentes->value,
            'status'               => StatusEvento::Publicado->value,
            'ativo'                => true,
        ]);

        return response()->json(['id' => $evento->id, 'nome' => $evento->nome, 'tema' => $evento->tema, 'entidade_criadora_id' => $evento->entidade_criadora_id]);
    }
}
