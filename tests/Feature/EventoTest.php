<?php

use App\Models\Evento;
use App\Models\TipoEvento;
use App\Models\Entidade;
use App\Models\Dirigente;
use App\Models\EventoEntidade;
use App\Models\EventoParticipante;
use App\Models\ParticipanteExterno;
use App\Models\User;
use App\Services\EventoService;
use App\Enums\TipoEntidade as TipoEntidadeEnum;
use App\Enums\TipoUsuario;
use App\Enums\StatusEvento;
use App\Enums\EscopoEvento;
use App\Enums\TipoParticipacaoEvento;
use App\Enums\TipoParticipanteEvento;
use App\Enums\TipoVinculo;
use App\Enums\CargoDirigente;

describe('TipoEvento', function () {
    it('pode criar tipo de evento', function () {
        $tipo = TipoEvento::create([
            'nome' => 'Retiro Novo',
            'descricao' => 'Encontro de reflexão',
            'ativo' => true,
        ]);

        expect($tipo->id)->not->toBeNull();
        expect($tipo->nome)->toBe('Retiro Novo');
    });

    it('tipo de evento tem scope ativos', function () {
        $ativo = TipoEvento::create(['nome' => 'Tipo Ativo ' . uniqid(), 'ativo' => true]);
        $inativo = TipoEvento::create(['nome' => 'Tipo Inativo ' . uniqid(), 'ativo' => false]);

        $ativos = TipoEvento::ativos()->get();
        expect($ativos->pluck('id'))->toContain($ativo->id);
        expect($ativos->pluck('id'))->not->toContain($inativo->id);
    });
});

describe('Evento', function () {
    it('pode criar evento com entidade criadora', function () {
        $diocese = Entidade::create([
            'tipo_entidade' => TipoEntidadeEnum::Diocese,
            'nome' => 'Diocese ' . uniqid(),
            'email' => 'diocese' . uniqid() . '@test.local',
        ]);

        $tipo = TipoEvento::create([
            'nome' => 'Reunião ' . uniqid(),
            'ativo' => true,
        ]);

        $evento = Evento::create([
            'tipo_evento_id' => $tipo->id,
            'entidade_criadora_id' => $diocese->id,
            'nome' => 'Evento Teste',
            'data_inicio' => now()->addDays(7),
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Rascunho->value,
        ]);

        expect($evento->id)->not->toBeNull();
        expect($evento->nome)->toBe('Evento Teste');
    });

    it('entidade criadora entra automaticamente como organizadora', function () {
        $diocese = Entidade::create([
            'tipo_entidade' => TipoEntidadeEnum::Diocese,
            'nome' => 'Diocese ' . uniqid(),
            'email' => 'diocese' . uniqid() . '@test.local',
        ]);

        $tipo = TipoEvento::create([
            'nome' => 'Tipo ' . uniqid(),
            'ativo' => true,
        ]);

        $service = new EventoService();
        $evento = $service->criar([
            'tipo_evento_id' => $tipo->id,
            'entidade_criadora_id' => $diocese->id,
            'nome' => 'Evento Multi',
            'data_inicio' => now()->addDays(7),
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Rascunho->value,
        ]);

        $eventoEntidades = EventoEntidade::where('evento_id', $evento->id)->get();

        expect($eventoEntidades->count())->toBe(1);
        expect($eventoEntidades->first()->tipo_participacao->value)->toBe(TipoParticipacaoEvento::Organizadora->value);
    });

    it('pode criar evento multi-entidade', function () {
        $diocese = Entidade::create([
            'tipo_entidade' => TipoEntidadeEnum::Diocese,
            'nome' => 'Diocese ' . uniqid(),
            'email' => 'diocese' . uniqid() . '@test.local',
        ]);

        $nucleo = Entidade::create([
            'tipo_entidade' => TipoEntidadeEnum::Nucleo,
            'nome' => 'Núcleo ' . uniqid(),
            'email' => 'nucleo' . uniqid() . '@test.local',
            'entidade_pai_id' => $diocese->id,
        ]);

        $tipo = TipoEvento::create([
            'nome' => 'Tipo ' . uniqid(),
            'ativo' => true,
        ]);

        $service = new EventoService();
        $evento = $service->criar([
            'tipo_evento_id' => $tipo->id,
            'entidade_criadora_id' => $diocese->id,
            'nome' => 'Evento Multi',
            'data_inicio' => now()->addDays(7),
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Rascunho->value,
        ]);

        $service->adicionarEntidade($evento, $nucleo, TipoParticipacaoEvento::Participante->value);

        expect($evento->entidades()->count())->toBe(2);
    });

    it('pode adicionar dirigente como participante', function () {
        $diocese = Entidade::create([
            'tipo_entidade' => TipoEntidadeEnum::Diocese,
            'nome' => 'Diocese ' . uniqid(),
            'email' => 'diocese' . uniqid() . '@test.local',
        ]);

        $tipo = TipoEvento::create([
            'nome' => 'Tipo ' . uniqid(),
            'ativo' => true,
        ]);

        $dirigente = Dirigente::create([
            'nome' => 'João ' . uniqid(),
            'telefone' => '1199999999',
            'genero' => 'm',
            'ativo' => true,
        ]);

        $evento = Evento::create([
            'tipo_evento_id' => $tipo->id,
            'entidade_criadora_id' => $diocese->id,
            'nome' => 'Evento',
            'data_inicio' => now()->addDays(7),
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
        ]);

        $participante = EventoParticipante::create([
            'evento_id' => $evento->id,
            'tipo_participante' => TipoParticipanteEvento::Dirigente->value,
            'dirigente_id' => $dirigente->id,
        ]);

        expect($participante->id)->not->toBeNull();
        expect($participante->isDirigente())->toBeTrue();
    });

    it('pode adicionar participante externo', function () {
        $diocese = Entidade::create([
            'tipo_entidade' => TipoEntidadeEnum::Diocese,
            'nome' => 'Diocese ' . uniqid(),
            'email' => 'diocese' . uniqid() . '@test.local',
        ]);

        $tipo = TipoEvento::create([
            'nome' => 'Tipo ' . uniqid(),
            'ativo' => true,
        ]);

        $externo = ParticipanteExterno::create([
            'nome' => 'João Externo ' . uniqid(),
            'email' => 'joao' . uniqid() . '@example.com',
        ]);

        $evento = Evento::create([
            'tipo_evento_id' => $tipo->id,
            'entidade_criadora_id' => $diocese->id,
            'nome' => 'Evento',
            'data_inicio' => now()->addDays(7),
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
        ]);

        $participante = EventoParticipante::create([
            'evento_id' => $evento->id,
            'tipo_participante' => TipoParticipanteEvento::Externo->value,
            'participante_externo_id' => $externo->id,
        ]);

        expect($participante->id)->not->toBeNull();
        expect($participante->isExterno())->toBeTrue();
    });

    it('pode marcar presença de participante', function () {
        $diocese = Entidade::create([
            'tipo_entidade' => TipoEntidadeEnum::Diocese,
            'nome' => 'Diocese ' . uniqid(),
            'email' => 'diocese' . uniqid() . '@test.local',
        ]);

        $tipo = TipoEvento::create([
            'nome' => 'Tipo ' . uniqid(),
            'ativo' => true,
        ]);

        $dirigente = Dirigente::create([
            'nome' => 'João ' . uniqid(),
            'telefone' => '1199999999',
            'genero' => 'm',
            'ativo' => true,
        ]);

        $evento = Evento::create([
            'tipo_evento_id' => $tipo->id,
            'entidade_criadora_id' => $diocese->id,
            'nome' => 'Evento',
            'data_inicio' => now()->addDays(7),
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
        ]);

        $participante = EventoParticipante::create([
            'evento_id' => $evento->id,
            'tipo_participante' => TipoParticipanteEvento::Dirigente->value,
            'dirigente_id' => $dirigente->id,
            'presenca' => false,
        ]);

        $participante->marcarPresenca();

        expect($participante->presenca)->toBeTrue();
        expect($participante->checkin_em)->not->toBeNull();
    });

    it('evento tem helpers de status', function () {
        $diocese = Entidade::create([
            'tipo_entidade' => TipoEntidadeEnum::Diocese,
            'nome' => 'Diocese ' . uniqid(),
            'email' => 'diocese' . uniqid() . '@test.local',
        ]);

        $tipo = TipoEvento::create([
            'nome' => 'Tipo ' . uniqid(),
            'ativo' => true,
        ]);

        $evento = Evento::create([
            'tipo_evento_id' => $tipo->id,
            'entidade_criadora_id' => $diocese->id,
            'nome' => 'Evento',
            'data_inicio' => now()->addDays(7),
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Rascunho->value,
        ]);

        expect($evento->isRascunho())->toBeTrue();
        expect($evento->isPublicado())->toBeFalse();

        $evento->update(['status' => StatusEvento::Publicado->value]);
        expect($evento->fresh()->isPublicado())->toBeTrue();
    });
});
