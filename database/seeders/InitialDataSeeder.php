<?php

namespace Database\Seeders;

use App\Enums\TipoUsuario;
use App\Enums\TipoEntidade;
use App\Enums\TipoSecretaria;
use App\Enums\CargoDirigente;
use App\Enums\TipoVinculo;
use App\Enums\EscopoEvento;
use App\Enums\StatusEvento;
use App\Enums\TipoParticipacaoEvento;
use App\Models\User;
use App\Models\Entidade;
use App\Models\Dirigente;
use App\Models\TipoEvento;
use App\Models\Evento;
use App\Models\ParticipanteExterno;
use Illuminate\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // Criar Admin Principal
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@tlc.local',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Admin,
            'ativo' => true,
        ]);

        // Criar Diocese de Santo Amaro
        $dioceseSantoAmaro = Entidade::create([
            'user_id' => null,
            'entidade_pai_id' => null,
            'tipo_entidade' => TipoEntidade::Diocese,
            'nome' => 'Diocese de Santo Amaro',
            'email' => 'diocesantoamaro@tlc.local',
            'tipo_secretaria' => null,
            'ativo' => true,
        ]);

        // Criar usuário para Diocese
        $userDiocese = User::create([
            'name' => 'Liderança Diocese de Santo Amaro',
            'email' => 'diocese@tlc.local',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Diocese,
            'ativo' => true,
        ]);

        // Vincular usuário à diocese
        $dioceseSantoAmaro->update(['user_id' => $userDiocese->id]);

        // Criar Núcleo Igreja Verde (filho da Diocese)
        $nucleoIgrejaVerde = Entidade::create([
            'user_id' => null,
            'entidade_pai_id' => $dioceseSantoAmaro->id,
            'tipo_entidade' => TipoEntidade::Nucleo,
            'nome' => 'Núcleo Igreja Verde',
            'email' => 'igrejaverde@tlc.local',
            'tipo_secretaria' => null,
            'ativo' => true,
        ]);

        // Criar usuário para Núcleo
        $userNucleo = User::create([
            'name' => 'Liderança Núcleo Igreja Verde',
            'email' => 'nucleo@tlc.local',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Nucleo,
            'ativo' => true,
        ]);

        // Vincular usuário ao núcleo
        $nucleoIgrejaVerde->update(['user_id' => $userNucleo->id]);

        // Criar Secretaria de Jovens (filha da Diocese)
        $secretariaJovens = Entidade::create([
            'user_id' => null,
            'entidade_pai_id' => $dioceseSantoAmaro->id,
            'tipo_entidade' => TipoEntidade::Secretaria,
            'nome' => 'Secretaria de Jovens',
            'email' => 'jovens@tlc.local',
            'tipo_secretaria' => TipoSecretaria::Aberta,
            'ativo' => true,
        ]);

        // Criar usuário para Secretaria
        $userSecretaria = User::create([
            'name' => 'Liderança Secretaria de Jovens',
            'email' => 'secretaria@tlc.local',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Secretaria,
            'ativo' => true,
        ]);

        // Vincular usuário à secretaria
        $secretariaJovens->update(['user_id' => $userSecretaria->id]);

        // Criar Dirigentes
        $dirigente1 = Dirigente::create([
            'nome' => 'João Silva',
            'telefone' => '(11) 91234-5678',
            'genero' => 'm',
            'data_nascimento' => '1985-05-15',
            'foto_url' => null,
            'ativo' => true,
        ]);

        // Vínculo principal de João com Núcleo Igreja Verde
        $dirigente1->vinculos()->create([
            'entidade_id' => $nucleoIgrejaVerde->id,
            'tipo_vinculo' => TipoVinculo::Principal,
            'cargo' => CargoDirigente::Dirigente,
            'papel' => 'Líder',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigente2 = Dirigente::create([
            'nome' => 'Maria Santos',
            'telefone' => '(11) 98765-4321',
            'genero' => 'f',
            'data_nascimento' => '1990-08-22',
            'foto_url' => null,
            'ativo' => true,
        ]);

        // Vínculo principal de Maria com Núcleo Igreja Verde
        $dirigente2->vinculos()->create([
            'entidade_id' => $nucleoIgrejaVerde->id,
            'tipo_vinculo' => TipoVinculo::Principal,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenadora',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Vínculo adicional de Maria com Secretaria de Jovens
        $dirigente2->vinculos()->create([
            'entidade_id' => $secretariaJovens->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'papel' => 'Coordenadora de Eventos',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigente3 = Dirigente::create([
            'nome' => 'Pedro Oliveira',
            'telefone' => '(11) 99876-5432',
            'genero' => 'm',
            'data_nascimento' => '1988-03-10',
            'foto_url' => null,
            'ativo' => true,
        ]);

        // Vínculo principal de Pedro com Núcleo Igreja Verde
        $dirigente3->vinculos()->create([
            'entidade_id' => $nucleoIgrejaVerde->id,
            'tipo_vinculo' => TipoVinculo::Principal,
            'cargo' => CargoDirigente::Dirigente,
            'papel' => 'Tesoureiro',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Vínculo de coordenação de Pedro com Diocese
        $dirigente3->vinculos()->create([
            'entidade_id' => $dioceseSantoAmaro->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenador Diocesano',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Criar Tipos de Evento
        $tipos = [];
        foreach ([
            'Retiro' => 'Encontro de reflexão espiritual',
            'Luau' => 'Confraternização informal',
            'Formação' => 'Atividade de capacitação',
            'Missa' => 'Celebração eucarística',
            'Reunião' => 'Encontro de alinhamento',
            'Festa' => 'Celebração festiva',
            'Ação Social' => 'Atividade de caridade e solidariedade',
        ] as $nome => $descricao) {
            $tipos[$nome] = TipoEvento::create([
                'nome' => $nome,
                'descricao' => $descricao,
                'ativo' => true,
            ])->id;
        }

        // Criar Participante Externo
        $participanteExterno = ParticipanteExterno::create([
            'nome' => 'João da Silva (Visitante)',
            'telefone' => '(11) 94567-8901',
            'email' => 'joao.visitante@example.com',
            'documento' => '12345678900',
            'genero' => 'm',
            'data_nascimento' => '1995-06-20',
        ]);

        // Criar Evento da Diocese (multi-entidade: Diocese como organizadora, Núcleo como participante)
        $eventoDidocese = Evento::create([
            'tipo_evento_id' => $tipos['Retiro'],
            'entidade_criadora_id' => $dioceseSantoAmaro->id,
            'nome' => 'Retiro Diocesano Anual',
            'descricao' => 'Encontro de reflexão espiritual para toda a diocese',
            'data_inicio' => now()->addDays(15)->format('Y-m-d H:i:s'),
            'data_fim' => now()->addDays(16)->format('Y-m-d H:i:s'),
            'local' => 'Centro de Retiros São José',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        // Adicionar Núcleo como participante ao evento da Diocese
        $eventoDidocese->eventoEntidades()->create([
            'entidade_id' => $nucleoIgrejaVerde->id,
            'tipo_participacao' => TipoParticipacaoEvento::Participante->value,
        ]);

        // Adicionar Secretaria de Jovens como participante
        $eventoDidocese->eventoEntidades()->create([
            'entidade_id' => $secretariaJovens->id,
            'tipo_participacao' => TipoParticipacaoEvento::Participante->value,
        ]);

        // Inscrever dirigentes no evento da Diocese
        $eventoDidocese->participantes()->create([
            'tipo_participante' => 'dirigente',
            'dirigente_id' => $dirigente1->id,
        ]);

        $eventoDidocese->participantes()->create([
            'tipo_participante' => 'dirigente',
            'dirigente_id' => $dirigente2->id,
        ]);

        // Criar Evento do Núcleo Igreja Verde (local)
        $eventoNucleo = Evento::create([
            'tipo_evento_id' => $tipos['Reunião'],
            'entidade_criadora_id' => $nucleoIgrejaVerde->id,
            'nome' => 'Reunião do Núcleo Igreja Verde',
            'descricao' => 'Encontro mensal do núcleo',
            'data_inicio' => now()->addDays(7)->format('Y-m-d H:i:s'),
            'data_fim' => null,
            'local' => 'Igreja Verde - Sala de Encontros',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        // Inscrever dirigentes no evento do núcleo
        $eventoNucleo->participantes()->create([
            'tipo_participante' => 'dirigente',
            'dirigente_id' => $dirigente1->id,
        ]);

        $eventoNucleo->participantes()->create([
            'tipo_participante' => 'dirigente',
            'dirigente_id' => $dirigente3->id,
        ]);

        // Criar Evento da Secretaria (com participante externo)
        $eventoSecretaria = Evento::create([
            'tipo_evento_id' => $tipos['Ação Social'],
            'entidade_criadora_id' => $secretariaJovens->id,
            'nome' => 'Ação Social - Distribuição de Alimentos',
            'descricao' => 'Atividade de caridade junto à comunidade',
            'data_inicio' => now()->addDays(10)->format('Y-m-d H:i:s'),
            'data_fim' => null,
            'local' => 'Comunidade do Bairro Central',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        // Inscrever dirigente e participante externo
        $eventoSecretaria->participantes()->create([
            'tipo_participante' => 'dirigente',
            'dirigente_id' => $dirigente2->id,
        ]);

        $eventoSecretaria->participantes()->create([
            'tipo_participante' => 'externo',
            'participante_externo_id' => $participanteExterno->id,
        ]);

        // Marcar presença de um participante
        $eventoNucleo->participantes()->first()->marcarPresenca();

        $this->command->info('Dados iniciais criados com sucesso!');
        $this->command->info('');
        $this->command->info('Usuários criados:');
        $this->command->info('  Admin: admin@tlc.local / password');
        $this->command->info('  Diocese: diocese@tlc.local / password');
        $this->command->info('  Núcleo: nucleo@tlc.local / password');
        $this->command->info('  Secretaria: secretaria@tlc.local / password');
        $this->command->info('');
        $this->command->info('Dirigentes criados:');
        $this->command->info('  João Silva - Vínculo principal em Núcleo Igreja Verde');
        $this->command->info('  Maria Santos - Vínculo principal em Núcleo Igreja Verde + Vínculo adicional em Secretaria de Jovens');
        $this->command->info('  Pedro Oliveira - Vínculo principal em Núcleo Igreja Verde + Vínculo de coordenação na Diocese');
        $this->command->info('');
        $this->command->info('Eventos criados:');
        $this->command->info('  Retiro Diocesano Anual - Diocese como organizadora');
        $this->command->info('  Reunião do Núcleo Igreja Verde - Evento local');
        $this->command->info('  Ação Social - Secretaria de Jovens com participante externo');
    }
}
