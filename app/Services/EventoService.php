<?php

namespace App\Services;

use App\Enums\TipoParticipacaoEvento;
use App\Enums\TipoParticipanteEvento;
use App\Models\Dirigente;
use App\Models\Entidade;
use App\Models\Evento;
use App\Models\EventoEntidade;
use App\Models\EventoParticipante;
use App\Models\ParticipanteExterno;
use Illuminate\Support\Facades\DB;

class EventoService
{
    public function criar(array $data): Evento
    {
        return DB::transaction(function () use ($data) {
            $evento = Evento::create($data);

            EventoEntidade::create([
                'evento_id' => $evento->id,
                'entidade_id' => $evento->entidade_criadora_id,
                'tipo_participacao' => TipoParticipacaoEvento::Organizadora->value,
            ]);

            return $evento;
        });
    }

    public function atualizar(Evento $evento, array $data): Evento
    {
        $evento->update($data);
        return $evento;
    }

    public function adicionarEntidade(Evento $evento, Entidade $entidade, string $tipo): EventoEntidade
    {
        if ($evento->eventoEntidades()->where('entidade_id', $entidade->id)->exists()) {
            throw new \InvalidArgumentException('Entidade já participa deste evento');
        }

        return EventoEntidade::create([
            'evento_id' => $evento->id,
            'entidade_id' => $entidade->id,
            'tipo_participacao' => $tipo,
        ]);
    }

    public function removerEntidade(EventoEntidade $eventoEntidade): bool
    {
        if ($eventoEntidade->isOrganizadora()) {
            throw new \InvalidArgumentException('Não é possível remover a entidade organizadora');
        }

        return $eventoEntidade->delete();
    }

    public function adicionarDirigente(Evento $evento, Dirigente $dirigente, ?string $observacao = null): EventoParticipante
    {
        if ($this->dirigenteJaParticipaDoEvento($evento, $dirigente)) {
            throw new \InvalidArgumentException('Dirigente já participa deste evento');
        }

        if (!$this->validarDirigentePodeParticipar($evento, $dirigente)) {
            throw new \InvalidArgumentException('Dirigente não atende os critérios de escopo do evento');
        }

        return EventoParticipante::create([
            'evento_id' => $evento->id,
            'tipo_participante' => TipoParticipanteEvento::Dirigente->value,
            'dirigente_id' => $dirigente->id,
            'observacao' => $observacao,
        ]);
    }

    public function adicionarExterno(Evento $evento, ParticipanteExterno $externo, ?string $observacao = null): EventoParticipante
    {
        if ($this->externoJaParticipaDoEvento($evento, $externo)) {
            throw new \InvalidArgumentException('Participante externo já participa deste evento');
        }

        return EventoParticipante::create([
            'evento_id' => $evento->id,
            'tipo_participante' => TipoParticipanteEvento::Externo->value,
            'participante_externo_id' => $externo->id,
            'observacao' => $observacao,
        ]);
    }

    public function removerParticipante(EventoParticipante $participante): bool
    {
        return $participante->delete();
    }

    public function marcarPresenca(EventoParticipante $participante): EventoParticipante
    {
        return $participante->marcarPresenca();
    }

    public function validarDirigentePodeParticipar(Evento $evento, Dirigente $dirigente): bool
    {
        $entidadesEvento = $evento->entidades()->pluck('id');

        foreach ($entidadesEvento as $entidadeId) {
            if (!$dirigente->pertenceAEntidade($entidadeId)) {
                continue;
            }

            // Dirigente pertence à entidade, agora valida escopo
            $entidade = Entidade::find($entidadeId);
            $temVinculoNecesario = match($evento->escopo->value) {
                'coordenadores' => $dirigente->vinculos()
                    ->where('entidade_id', $entidadeId)
                    ->where('cargo', 'coordenador')
                    ->where('ativo', true)
                    ->exists(),
                'dirigentes' => true,
                'ambos' => true,
                'externos' => false,
                'publico' => false,
                default => false,
            };

            if ($temVinculoNecesario) {
                return true;
            }
        }

        return false;
    }

    private function dirigenteJaParticipaDoEvento(Evento $evento, Dirigente $dirigente): bool
    {
        return $evento->participantes()
            ->where('tipo_participante', TipoParticipanteEvento::Dirigente->value)
            ->where('dirigente_id', $dirigente->id)
            ->exists();
    }

    private function externoJaParticipaDoEvento(Evento $evento, ParticipanteExterno $externo): bool
    {
        return $evento->participantes()
            ->where('tipo_participante', TipoParticipanteEvento::Externo->value)
            ->where('participante_externo_id', $externo->id)
            ->exists();
    }
}
