<?php

namespace App\Console\Commands;

use App\Models\Evento;
use App\Models\EventoEntidade;
use App\Enums\TipoParticipacaoEvento;
use Illuminate\Console\Command;

class FixEventoEntidades extends Command
{
    protected $signature = 'fix:evento-entidades';
    protected $description = 'Adiciona registros faltantes em evento_entidades para eventos antigos';

    public function handle()
    {
        $this->info('🔍 Procurando eventos sem entidade registrada...');

        $eventosSemEntidade = Evento::whereDoesntHave('entidades')->get();

        if ($eventosSemEntidade->isEmpty()) {
            $this->info('✅ Todos os eventos já têm entidades registradas!');
            return 0;
        }

        $this->warn("⚠️ Encontrados {$eventosSemEntidade->count()} eventos sem entidades");

        foreach ($eventosSemEntidade as $evento) {
            EventoEntidade::create([
                'evento_id' => $evento->id,
                'entidade_id' => $evento->entidade_criadora_id,
                'tipo_participacao' => TipoParticipacaoEvento::Organizadora->value,
            ]);

            $this->line("✅ Evento ID {$evento->id} ({$evento->nome}) - Entidade adicionada");
        }

        $this->info("\n✅ Processo concluído! {$eventosSemEntidade->count()} eventos corrigidos");

        return 0;
    }
}
