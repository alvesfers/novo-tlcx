<?php

namespace Database\Seeders;

use App\Enums\TipoUsuario;
use App\Enums\TipoEntidade;
use App\Enums\TipoSecretaria;
use App\Enums\CargoDirigente;
use App\Enums\TipoVinculo;
use App\Enums\EscopoEvento;
use App\Enums\StatusEvento;
use App\Models\User;
use App\Models\Entidade;
use App\Models\Dirigente;
use App\Models\TipoEvento;
use App\Models\Evento;
use App\Models\FinanceiroCategoria;
use App\Models\FinanceiroMovimento;
use Illuminate\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@tlc.local',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Admin,
            'ativo' => true,
        ]);

        // ===== DIOCESES =====
        $dioceses = [];

        $dioceses['santo_amaro'] = Entidade::create([
            'tipo_entidade' => TipoEntidade::Diocese,
            'nome' => 'Diocese de Santo Amaro',
            'email' => 'diocese@santoamaro.com',
            'ativo' => true,
        ]);

        // Usuário para Diocese Santo Amaro
        $userDioceseSantoAmaro = User::create([
            'name' => 'Liderança Diocese de Santo Amaro',
            'email' => 'diocese@santoamaro.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Diocese,
            'entidade_id' => $dioceses['santo_amaro']->id,
            'ativo' => true,
        ]);
        $dioceses['santo_amaro']->update(['user_id' => $userDioceseSantoAmaro->id]);

        $dioceses['campo_limpo'] = Entidade::create([
            'tipo_entidade' => TipoEntidade::Diocese,
            'nome' => 'Diocese de Campo Limpo',
            'email' => 'diocese@campolimpo.com',
            'ativo' => true,
        ]);

        // Usuário para Diocese Campo Limpo
        $userDioceseCampoLimpo = User::create([
            'name' => 'Liderança Diocese de Campo Limpo',
            'email' => 'diocese@campolimpo.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Diocese,
            'entidade_id' => $dioceses['campo_limpo']->id,
            'ativo' => true,
        ]);
        $dioceses['campo_limpo']->update(['user_id' => $userDioceseCampoLimpo->id]);

        $dioceses['santos'] = Entidade::create([
            'tipo_entidade' => TipoEntidade::Diocese,
            'nome' => 'Diocese de Santos',
            'email' => 'diocese@santos.com',
            'ativo' => true,
        ]);

        // Usuário para Diocese Santos
        $userDioceseSantos = User::create([
            'name' => 'Liderança Diocese de Santos',
            'email' => 'diocese@santos.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Diocese,
            'entidade_id' => $dioceses['santos']->id,
            'ativo' => true,
        ]);
        $dioceses['santos']->update(['user_id' => $userDioceseSantos->id]);

        // ===== NÚCLEOS =====
        $nucleos = [];

        $nucleos['santa_paulina'] = Entidade::create([
            'entidade_pai_id' => $dioceses['santo_amaro']->id,
            'tipo_entidade' => TipoEntidade::Nucleo,
            'nome' => 'Núcleo Santa Paulina',
            'email' => 'nucleo@santapaulina.com',
            'ativo' => true,
        ]);

        $userNucleoSantaPaulina = User::create([
            'name' => 'Liderança Núcleo Santa Paulina',
            'email' => 'nucleo@santapaulina.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Nucleo,
            'entidade_id' => $nucleos['santa_paulina']->id,
            'ativo' => true,
        ]);
        $nucleos['santa_paulina']->update(['user_id' => $userNucleoSantaPaulina->id]);

        $nucleos['igreja_verde'] = Entidade::create([
            'entidade_pai_id' => $dioceses['santo_amaro']->id,
            'tipo_entidade' => TipoEntidade::Nucleo,
            'nome' => 'Núcleo Igreja Verde',
            'email' => 'nucleo@igrejverde.com',
            'ativo' => true,
        ]);

        $userNucleoIgrejaVerde = User::create([
            'name' => 'Liderança Núcleo Igreja Verde',
            'email' => 'nucleo@igrejverde.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Nucleo,
            'entidade_id' => $nucleos['igreja_verde']->id,
            'ativo' => true,
        ]);
        $nucleos['igreja_verde']->update(['user_id' => $userNucleoIgrejaVerde->id]);

        $nucleos['ideal'] = Entidade::create([
            'entidade_pai_id' => $dioceses['santo_amaro']->id,
            'tipo_entidade' => TipoEntidade::Nucleo,
            'nome' => 'Núcleo Ideal',
            'email' => 'nucleo@ideal.com',
            'ativo' => true,
        ]);

        $userNucleoIdeal = User::create([
            'name' => 'Liderança Núcleo Ideal',
            'email' => 'nucleo@ideal.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Nucleo,
            'entidade_id' => $nucleos['ideal']->id,
            'ativo' => true,
        ]);
        $nucleos['ideal']->update(['user_id' => $userNucleoIdeal->id]);

        $nucleos['santuario_santa_terezinha'] = Entidade::create([
            'entidade_pai_id' => $dioceses['campo_limpo']->id,
            'tipo_entidade' => TipoEntidade::Nucleo,
            'nome' => 'Núcleo Santuário Santa Terezinha',
            'email' => 'nucleo@santuariosantaterezinha.com',
            'ativo' => true,
        ]);

        $userNucleoSantuario = User::create([
            'name' => 'Liderança Núcleo Santuário Santa Terezinha',
            'email' => 'nucleo@santuariosantaterezinha.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Nucleo,
            'entidade_id' => $nucleos['santuario_santa_terezinha']->id,
            'ativo' => true,
        ]);
        $nucleos['santuario_santa_terezinha']->update(['user_id' => $userNucleoSantuario->id]);

        // ===== SECRETARIAS =====
        $secretarias = [];

        $secretarias['musica'] = Entidade::create([
            'tipo_entidade' => TipoEntidade::Secretaria,
            'nome' => 'Secretaria de Música',
            'tipo_secretaria' => TipoSecretaria::Aberta,
            'ativo' => true,
        ]);

        $userSecretariaMusica = User::create([
            'name' => 'Liderança Secretaria de Música',
            'email' => 'secretaria.musica@tlc.local',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Secretaria,
            'entidade_id' => $secretarias['musica']->id,
            'ativo' => true,
        ]);
        $secretarias['musica']->update(['user_id' => $userSecretariaMusica->id]);

        $secretarias['apoio'] = Entidade::create([
            'tipo_entidade' => TipoEntidade::Secretaria,
            'nome' => 'Secretaria de Apoio',
            'tipo_secretaria' => TipoSecretaria::Aberta,
            'ativo' => true,
        ]);

        $userSecretariaApoio = User::create([
            'name' => 'Liderança Secretaria de Apoio',
            'email' => 'secretaria.apoio@tlc.local',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Secretaria,
            'entidade_id' => $secretarias['apoio']->id,
            'ativo' => true,
        ]);
        $secretarias['apoio']->update(['user_id' => $userSecretariaApoio->id]);

        $secretarias['espiritualidade'] = Entidade::create([
            'tipo_entidade' => TipoEntidade::Secretaria,
            'nome' => 'Secretaria de Espiritualidade e Formação',
            'tipo_secretaria' => TipoSecretaria::Fechada,
            'ativo' => true,
        ]);

        $userSecretariaEspiritualidade = User::create([
            'name' => 'Liderança Secretaria de Espiritualidade',
            'email' => 'secretaria.espiritualidade@tlc.local',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Secretaria,
            'entidade_id' => $secretarias['espiritualidade']->id,
            'ativo' => true,
        ]);
        $secretarias['espiritualidade']->update(['user_id' => $userSecretariaEspiritualidade->id]);

        $secretarias['eventos'] = Entidade::create([
            'tipo_entidade' => TipoEntidade::Secretaria,
            'nome' => 'Secretaria de Eventos',
            'tipo_secretaria' => TipoSecretaria::Aberta,
            'ativo' => true,
        ]);

        $userSecretariaEventos = User::create([
            'name' => 'Liderança Secretaria de Eventos',
            'email' => 'secretaria.eventos@tlc.local',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Secretaria,
            'entidade_id' => $secretarias['eventos']->id,
            'ativo' => true,
        ]);
        $secretarias['eventos']->update(['user_id' => $userSecretariaEventos->id]);

        // ===== DIRIGENTES =====
        $dirigentes = [];

        // Fernando: participa do núcleo santa Paulina, coordena a secretaria da música e participa da secretaria de Espiritualidade
        $dirigentes['fernando'] = Dirigente::create([
            'nome' => 'Fernando',
            'telefone' => '(11) 91234-5678',
            'genero' => 'm',
            'data_nascimento' => '1980-03-15',
            'ativo' => true,
        ]);

        $dirigentes['fernando']->vinculos()->create([
            'entidade_id' => $nucleos['santa_paulina']->id,
            'tipo_vinculo' => TipoVinculo::Principal,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['fernando']->vinculos()->create([
            'entidade_id' => $secretarias['musica']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenador',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['fernando']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Julianne: participa do núcleo igreja Verde, secretaria de Eventos e Espiritualidade
        $dirigentes['julianne'] = Dirigente::create([
            'nome' => 'Julianne',
            'telefone' => '(11) 98765-4321',
            'genero' => 'f',
            'data_nascimento' => '1985-07-22',
            'ativo' => true,
        ]);

        $dirigentes['julianne']->vinculos()->create([
            'entidade_id' => $nucleos['igreja_verde']->id,
            'tipo_vinculo' => TipoVinculo::Principal,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['julianne']->vinculos()->create([
            'entidade_id' => $secretarias['eventos']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['julianne']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Ygor: coordena o núcleo santa Paulina, e participa da secretaria do Apoio
        $dirigentes['ygor'] = Dirigente::create([
            'nome' => 'Ygor',
            'telefone' => '(11) 99876-5432',
            'genero' => 'm',
            'data_nascimento' => '1982-11-10',
            'ativo' => true,
        ]);

        $dirigentes['ygor']->vinculos()->create([
            'entidade_id' => $nucleos['santa_paulina']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenador',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['ygor']->vinculos()->create([
            'entidade_id' => $secretarias['apoio']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Bruno: participa do núcleo igreja Verde, coordena a diocese de santo Amaro, participa da secretaria do Eventos
        $dirigentes['bruno'] = Dirigente::create([
            'nome' => 'Bruno',
            'telefone' => '(11) 94567-8901',
            'genero' => 'm',
            'data_nascimento' => '1987-09-05',
            'ativo' => true,
        ]);

        $dirigentes['bruno']->vinculos()->create([
            'entidade_id' => $nucleos['igreja_verde']->id,
            'tipo_vinculo' => TipoVinculo::Principal,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['bruno']->vinculos()->create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenador Diocesano',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['bruno']->vinculos()->create([
            'entidade_id' => $secretarias['eventos']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // ===== CATEGORIAS FINANCEIRAS =====
        $categorias = [];

        $categorias['receita'] = FinanceiroCategoria::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'nome' => 'Dízimos e Ofertas',
            'tipo' => 'entrada',
        ]);

        $categorias['despesa'] = FinanceiroCategoria::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'nome' => 'Despesas Operacionais',
            'tipo' => 'saida',
        ]);

        $categorias['eventos'] = FinanceiroCategoria::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'nome' => 'Eventos',
            'tipo' => 'saida',
        ]);

        // ===== MOVIMENTAÇÕES FINANCEIRAS =====
        // Diocese Santo Amaro
        FinanceiroMovimento::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'financeiro_categoria_id' => $categorias['receita']->id,
            'tipo' => 'entrada',
            'descricao' => 'Dízimos coletados - Domingo',
            'valor' => 1500.00,
            'data_movimento' => now()->toDateString(),
            'forma_pagamento' => 'dinheiro',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'financeiro_categoria_id' => $categorias['despesa']->id,
            'tipo' => 'saida',
            'descricao' => 'Aluguel do espaço',
            'valor' => 800.00,
            'data_movimento' => now()->toDateString(),
            'forma_pagamento' => 'transferencia',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'financeiro_categoria_id' => $categorias['eventos']->id,
            'tipo' => 'saida',
            'descricao' => 'Material para retiro diocesano',
            'valor' => 2200.00,
            'data_movimento' => now()->subDays(5)->toDateString(),
            'forma_pagamento' => 'cartao',
        ]);

        // Núcleo Santa Paulina
        $catMusica = FinanceiroCategoria::create([
            'entidade_id' => $nucleos['santa_paulina']->id,
            'nome' => 'Música e Instrumentos',
            'tipo' => 'saida',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $nucleos['santa_paulina']->id,
            'financeiro_categoria_id' => $catMusica->id,
            'tipo' => 'saida',
            'descricao' => 'Compra de partituras',
            'valor' => 450.00,
            'data_movimento' => now()->subDays(3)->toDateString(),
            'forma_pagamento' => 'pix',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $nucleos['santa_paulina']->id,
            'financeiro_categoria_id' => $categorias['receita']->id,
            'tipo' => 'entrada',
            'descricao' => 'Contribuições de membros',
            'valor' => 600.00,
            'data_movimento' => now()->toDateString(),
            'forma_pagamento' => 'dinheiro',
        ]);

        // Núcleo Igreja Verde
        FinanceiroMovimento::create([
            'entidade_id' => $nucleos['igreja_verde']->id,
            'financeiro_categoria_id' => $categorias['eventos']->id,
            'tipo' => 'saida',
            'descricao' => 'Lanche para reunião mensal',
            'valor' => 320.00,
            'data_movimento' => now()->subDays(1)->toDateString(),
            'forma_pagamento' => 'dinheiro',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $nucleos['igreja_verde']->id,
            'financeiro_categoria_id' => $categorias['receita']->id,
            'tipo' => 'entrada',
            'descricao' => 'Dízimos coletados',
            'valor' => 1200.00,
            'data_movimento' => now()->toDateString(),
            'forma_pagamento' => 'dinheiro',
        ]);

        // Núcleo Ideal
        FinanceiroMovimento::create([
            'entidade_id' => $nucleos['ideal']->id,
            'financeiro_categoria_id' => $categorias['receita']->id,
            'tipo' => 'entrada',
            'descricao' => 'Ofertas dos fiéis',
            'valor' => 800.00,
            'data_movimento' => now()->toDateString(),
            'forma_pagamento' => 'dinheiro',
        ]);

        // Núcleo Santuário Santa Terezinha
        FinanceiroMovimento::create([
            'entidade_id' => $nucleos['santuario_santa_terezinha']->id,
            'financeiro_categoria_id' => $categorias['receita']->id,
            'tipo' => 'entrada',
            'descricao' => 'Doações para manutenção',
            'valor' => 950.00,
            'data_movimento' => now()->toDateString(),
            'forma_pagamento' => 'pix',
        ]);

        // ===== TIPOS DE EVENTO =====
        $tipos = [];
        foreach ([
            'Retiro' => 'Encontro de reflexão espiritual',
            'Reunião' => 'Encontro de alinhamento',
            'Missa' => 'Celebração eucarística',
            'Festa' => 'Celebração festiva',
            'Ação Social' => 'Atividade de caridade',
            'Formação' => 'Atividade de capacitação',
            'Luau' => 'Confraternização informal',
        ] as $nome => $descricao) {
            $tipos[$nome] = TipoEvento::create([
                'nome' => $nome,
                'descricao' => $descricao,
                'ativo' => true,
            ])->id;
        }

        // ===== EVENTOS =====

        // Retiro Diocesano
        $retiroDiocesano = Evento::create([
            'tipo_evento_id' => $tipos['Retiro'],
            'entidade_criadora_id' => $dioceses['santo_amaro']->id,
            'nome' => 'Retiro Diocesano Anual',
            'descricao' => 'Encontro de reflexão espiritual para toda a diocese',
            'data_inicio' => now()->addDays(15)->format('Y-m-d 08:00:00'),
            'data_fim' => now()->addDays(16)->format('Y-m-d 17:00:00'),
            'local' => 'Centro de Retiros São José',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        $retiroDiocesano->participantes()->create(['tipo_participante' => 'dirigente', 'dirigente_id' => $dirigentes['fernando']->id]);
        $retiroDiocesano->participantes()->create(['tipo_participante' => 'dirigente', 'dirigente_id' => $dirigentes['julianne']->id]);
        $retiroDiocesano->participantes()->create(['tipo_participante' => 'dirigente', 'dirigente_id' => $dirigentes['ygor']->id]);
        $retiroDiocesano->participantes()->create(['tipo_participante' => 'dirigente', 'dirigente_id' => $dirigentes['bruno']->id]);

        // Reunião Núcleo Santa Paulina
        $reuniaoSantaPaulina = Evento::create([
            'tipo_evento_id' => $tipos['Reunião'],
            'entidade_criadora_id' => $nucleos['santa_paulina']->id,
            'nome' => 'Reunião Mensal - Núcleo Santa Paulina',
            'descricao' => 'Encontro para alinhamento e planejamento',
            'data_inicio' => now()->addDays(7)->format('Y-m-d 19:30:00'),
            'local' => 'Salão da Igreja Santa Paulina',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        $reuniaoSantaPaulina->participantes()->create(['tipo_participante' => 'dirigente', 'dirigente_id' => $dirigentes['fernando']->id]);
        $reuniaoSantaPaulina->participantes()->create(['tipo_participante' => 'dirigente', 'dirigente_id' => $dirigentes['ygor']->id]);

        // Reunião Núcleo Igreja Verde
        $reuniaoIgrejaVerde = Evento::create([
            'tipo_evento_id' => $tipos['Reunião'],
            'entidade_criadora_id' => $nucleos['igreja_verde']->id,
            'nome' => 'Reunião Mensal - Núcleo Igreja Verde',
            'descricao' => 'Encontro para alinhamento e planejamento',
            'data_inicio' => now()->addDays(10)->format('Y-m-d 19:30:00'),
            'local' => 'Igreja Verde - Sala de Encontros',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        $reuniaoIgrejaVerde->participantes()->create(['tipo_participante' => 'dirigente', 'dirigente_id' => $dirigentes['julianne']->id]);
        $reuniaoIgrejaVerde->participantes()->create(['tipo_participante' => 'dirigente', 'dirigente_id' => $dirigentes['bruno']->id]);

        // Ensaio de Música
        $ensaioMusica = Evento::create([
            'tipo_evento_id' => $tipos['Formação'],
            'entidade_criadora_id' => $secretarias['musica']->id,
            'nome' => 'Ensaio de Música Litúrgica',
            'descricao' => 'Preparação de músicas para celebrações',
            'data_inicio' => now()->addDays(3)->format('Y-m-d 20:00:00'),
            'local' => 'Sala de Música',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        $ensaioMusica->participantes()->create(['tipo_participante' => 'dirigente', 'dirigente_id' => $dirigentes['fernando']->id]);

        // Ação Social
        $acaoSocial = Evento::create([
            'tipo_evento_id' => $tipos['Ação Social'],
            'entidade_criadora_id' => $secretarias['apoio']->id,
            'nome' => 'Distribuição de Alimentos à Comunidade',
            'descricao' => 'Atividade de caridade e solidariedade',
            'data_inicio' => now()->addDays(8)->format('Y-m-d 09:00:00'),
            'local' => 'Comunidade do Bairro Central',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        $acaoSocial->participantes()->create(['tipo_participante' => 'dirigente', 'dirigente_id' => $dirigentes['ygor']->id]);

        // Formação em Espiritualidade
        $formacao = Evento::create([
            'tipo_evento_id' => $tipos['Formação'],
            'entidade_criadora_id' => $secretarias['espiritualidade']->id,
            'nome' => 'Formação em Espiritualidade',
            'descricao' => 'Aprofundamento na vida espiritual',
            'data_inicio' => now()->addDays(20)->format('Y-m-d 19:00:00'),
            'data_fim' => now()->addDays(20)->format('Y-m-d 21:00:00'),
            'local' => 'Sala de Formação',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        $formacao->participantes()->create(['tipo_participante' => 'dirigente', 'dirigente_id' => $dirigentes['fernando']->id]);
        $formacao->participantes()->create(['tipo_participante' => 'dirigente', 'dirigente_id' => $dirigentes['julianne']->id]);

        // Festa de Confraternização
        $festa = Evento::create([
            'tipo_evento_id' => $tipos['Festa'],
            'entidade_criadora_id' => $secretarias['eventos']->id,
            'nome' => 'Festa de Confraternização',
            'descricao' => 'Celebração festiva com toda a comunidade',
            'data_inicio' => now()->addDays(30)->format('Y-m-d 18:00:00'),
            'local' => 'Salão Principal',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        $festa->participantes()->create(['tipo_participante' => 'dirigente', 'dirigente_id' => $dirigentes['julianne']->id]);
        $festa->participantes()->create(['tipo_participante' => 'dirigente', 'dirigente_id' => $dirigentes['bruno']->id]);

        // Log
        $this->command->info('✅ Dados iniciais criados com sucesso!');
        $this->command->info('');
        $this->command->info('👤 Usuários de Acesso (senha: password):');
        $this->command->info('  Admin: admin@tlc.local');
        $this->command->info('  Diocese Santo Amaro: diocese@santoamaro.com');
        $this->command->info('  Diocese Campo Limpo: diocese@campolimpo.com');
        $this->command->info('  Diocese Santos: diocese@santos.com');
        $this->command->info('  Núcleo Santa Paulina: nucleo@santapaulina.com');
        $this->command->info('  Núcleo Igreja Verde: nucleo@igrejverde.com');
        $this->command->info('  Núcleo Ideal: nucleo@ideal.com');
        $this->command->info('  Núcleo Santuário: nucleo@santuariosantaterezinha.com');
        $this->command->info('');
        $this->command->info('📍 Dioceses:');
        $this->command->info('  • Diocese de Santo Amaro');
        $this->command->info('  • Diocese de Campo Limpo');
        $this->command->info('  • Diocese de Santos');
        $this->command->info('');
        $this->command->info('🏛️ Núcleos:');
        $this->command->info('  • Santa Paulina (Santo Amaro)');
        $this->command->info('  • Igreja Verde (Santo Amaro)');
        $this->command->info('  • Ideal (Santo Amaro)');
        $this->command->info('  • Santuário Santa Terezinha (Campo Limpo)');
        $this->command->info('');
        $this->command->info('📚 Secretarias:');
        $this->command->info('  • Música, Apoio, Espiritualidade e Formação, Eventos');
        $this->command->info('');
        $this->command->info('👥 Dirigentes:');
        $this->command->info('  • Fernando (Coord. Música, part. Espiritualidade)');
        $this->command->info('  • Julianne (part. Igreja Verde, Eventos, Espiritualidade)');
        $this->command->info('  • Ygor (Coord. Santa Paulina, part. Apoio)');
        $this->command->info('  • Bruno (part. Igreja Verde, Coord. Diocese Santo Amaro, part. Eventos)');
        $this->command->info('');
        $this->command->info('📊 Dados Financeiros: Movimentações em todas as dioceses e núcleos');
        $this->command->info('📅 Eventos: 7 eventos distribuídos entre dioceses, núcleos e secretarias');
    }
}
