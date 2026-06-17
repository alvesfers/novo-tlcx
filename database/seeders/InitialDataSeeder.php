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
            'name' => 'Diocese de Santo Amaro',
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
            'name' => 'Diocese de Campo Limpo',
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
            'name' => 'Diocese de Santos',
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
            'name' => 'Núcleo Santa Paulina',
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
            'name' => 'Núcleo Igreja Verde',
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
            'name' => 'Núcleo Ideal',
            'email' => 'nucleo@ideal.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Nucleo,
            'entidade_id' => $nucleos['ideal']->id,
            'ativo' => true,
        ]);
        $nucleos['ideal']->update(['user_id' => $userNucleoIdeal->id]);

        $nucleos['sao_jose'] = Entidade::create([
            'entidade_pai_id' => $dioceses['santo_amaro']->id,
            'tipo_entidade' => TipoEntidade::Nucleo,
            'nome' => 'Núcleo São José',
            'email' => 'nucleo@saojose.com',
            'ativo' => true,
        ]);

        $userNucleoSaoJose = User::create([
            'name' => 'Núcleo São José',
            'email' => 'nucleo@saojose.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Nucleo,
            'entidade_id' => $nucleos['sao_jose']->id,
            'ativo' => true,
        ]);
        $nucleos['sao_jose']->update(['user_id' => $userNucleoSaoJose->id]);

        $nucleos['ap_miriam'] = Entidade::create([
            'entidade_pai_id' => $dioceses['santo_amaro']->id,
            'tipo_entidade' => TipoEntidade::Nucleo,
            'nome' => 'Núcleo Ap Miriam',
            'email' => 'nucleo@apmiriam.com',
            'ativo' => true,
        ]);

        $userNucleoApMiriam = User::create([
            'name' => 'Núcleo Ap Miriam',
            'email' => 'nucleo@apmiriam.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Nucleo,
            'entidade_id' => $nucleos['ap_miriam']->id,
            'ativo' => true,
        ]);
        $nucleos['ap_miriam']->update(['user_id' => $userNucleoApMiriam->id]);

        $nucleos['cidinha'] = Entidade::create([
            'entidade_pai_id' => $dioceses['santo_amaro']->id,
            'tipo_entidade' => TipoEntidade::Nucleo,
            'nome' => 'Núcleo Cidinha',
            'email' => 'nucleo@cidinha.com',
            'ativo' => true,
        ]);

        $userNucleoCidinha = User::create([
            'name' => 'Núcleo Cidinha',
            'email' => 'nucleo@cidinha.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Nucleo,
            'entidade_id' => $nucleos['cidinha']->id,
            'ativo' => true,
        ]);
        $nucleos['cidinha']->update(['user_id' => $userNucleoCidinha->id]);

        $nucleos['sao_bernardo'] = Entidade::create([
            'entidade_pai_id' => $dioceses['santo_amaro']->id,
            'tipo_entidade' => TipoEntidade::Nucleo,
            'nome' => 'Núcleo São Bernardo',
            'email' => 'nucleo@saobernardo.com',
            'ativo' => true,
        ]);

        $userNucleoSaoBernardo = User::create([
            'name' => 'Núcleo São Bernardo',
            'email' => 'nucleo@saobernardo.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Nucleo,
            'entidade_id' => $nucleos['sao_bernardo']->id,
            'ativo' => true,
        ]);
        $nucleos['sao_bernardo']->update(['user_id' => $userNucleoSaoBernardo->id]);

        $nucleos['borromeu'] = Entidade::create([
            'entidade_pai_id' => $dioceses['santo_amaro']->id,
            'tipo_entidade' => TipoEntidade::Nucleo,
            'nome' => 'Núcleo Borromeu',
            'email' => 'nucleo@borromeu.com',
            'ativo' => true,
        ]);

        $userNucleoBorromeu = User::create([
            'name' => 'Núcleo Borromeu',
            'email' => 'nucleo@borromeu.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Nucleo,
            'entidade_id' => $nucleos['borromeu']->id,
            'ativo' => true,
        ]);
        $nucleos['borromeu']->update(['user_id' => $userNucleoBorromeu->id]);

        $nucleos['rosario'] = Entidade::create([
            'entidade_pai_id' => $dioceses['santo_amaro']->id,
            'tipo_entidade' => TipoEntidade::Nucleo,
            'nome' => 'Núcleo Rosário',
            'email' => 'nucleo@rosario.com',
            'ativo' => true,
        ]);

        $userNucleoRosario = User::create([
            'name' => 'Núcleo Rosário',
            'email' => 'nucleo@rosario.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Nucleo,
            'entidade_id' => $nucleos['rosario']->id,
            'ativo' => true,
        ]);
        $nucleos['rosario']->update(['user_id' => $userNucleoRosario->id]);

        $nucleos['santa_clara'] = Entidade::create([
            'entidade_pai_id' => $dioceses['santo_amaro']->id,
            'tipo_entidade' => TipoEntidade::Nucleo,
            'nome' => 'Núcleo Santa Clara',
            'email' => 'nucleo@santaclara.com',
            'ativo' => true,
        ]);

        $userNucleoSantaClara = User::create([
            'name' => 'Núcleo Santa Clara',
            'email' => 'nucleo@santaclara.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Nucleo,
            'entidade_id' => $nucleos['santa_clara']->id,
            'ativo' => true,
        ]);
        $nucleos['santa_clara']->update(['user_id' => $userNucleoSantaClara->id]);

        $nucleos['rainha'] = Entidade::create([
            'entidade_pai_id' => $dioceses['santo_amaro']->id,
            'tipo_entidade' => TipoEntidade::Nucleo,
            'nome' => 'Núcleo Rainha',
            'email' => 'nucleo@rainha.com',
            'ativo' => true,
        ]);

        $userNucleoRainha = User::create([
            'name' => 'Núcleo Rainha',
            'email' => 'nucleo@rainha.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Nucleo,
            'entidade_id' => $nucleos['rainha']->id,
            'ativo' => true,
        ]);
        $nucleos['rainha']->update(['user_id' => $userNucleoRainha->id]);

        $nucleos['sao_bento'] = Entidade::create([
            'entidade_pai_id' => $dioceses['santo_amaro']->id,
            'tipo_entidade' => TipoEntidade::Nucleo,
            'nome' => 'Núcleo São Bento',
            'email' => 'nucleo@saobento.com',
            'ativo' => true,
        ]);

        $userNucleoSaoBento = User::create([
            'name' => 'Núcleo São Bento',
            'email' => 'nucleo@saobento.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Nucleo,
            'entidade_id' => $nucleos['sao_bento']->id,
            'ativo' => true,
        ]);
        $nucleos['sao_bento']->update(['user_id' => $userNucleoSaoBento->id]);

        $nucleos['santa_terezinha'] = Entidade::create([
            'entidade_pai_id' => $dioceses['santo_amaro']->id,
            'tipo_entidade' => TipoEntidade::Nucleo,
            'nome' => 'Núcleo Santa Terezinha',
            'email' => 'nucleo@santaterezinha.com',
            'ativo' => true,
        ]);

        $userNucleoSantaTerezinha = User::create([
            'name' => 'Núcleo Santa Terezinha',
            'email' => 'nucleo@santaterezinha.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Nucleo,
            'entidade_id' => $nucleos['santa_terezinha']->id,
            'ativo' => true,
        ]);
        $nucleos['santa_terezinha']->update(['user_id' => $userNucleoSantaTerezinha->id]);

        $nucleos['ap_grajau'] = Entidade::create([
            'entidade_pai_id' => $dioceses['santo_amaro']->id,
            'tipo_entidade' => TipoEntidade::Nucleo,
            'nome' => 'Núcleo Ap Grajau',
            'email' => 'nucleo@apgrajau.com',
            'ativo' => true,
        ]);

        $userNucleoApGrajau = User::create([
            'name' => 'Núcleo Ap Grajau',
            'email' => 'nucleo@apgrajau.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Nucleo,
            'entidade_id' => $nucleos['ap_grajau']->id,
            'ativo' => true,
        ]);
        $nucleos['ap_grajau']->update(['user_id' => $userNucleoApGrajau->id]);

        $nucleos['consolacao'] = Entidade::create([
            'entidade_pai_id' => $dioceses['santo_amaro']->id,
            'tipo_entidade' => TipoEntidade::Nucleo,
            'nome' => 'Núcleo Consolação',
            'email' => 'nucleo@consolacao.com',
            'ativo' => true,
        ]);

        $userNucleoConsolacao = User::create([
            'name' => 'Núcleo Consolação',
            'email' => 'nucleo@consolacao.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Nucleo,
            'entidade_id' => $nucleos['consolacao']->id,
            'ativo' => true,
        ]);
        $nucleos['consolacao']->update(['user_id' => $userNucleoConsolacao->id]);

        $nucleos['santa_rita'] = Entidade::create([
            'entidade_pai_id' => $dioceses['santo_amaro']->id,
            'tipo_entidade' => TipoEntidade::Nucleo,
            'nome' => 'Núcleo Santa Rita',
            'email' => 'nucleo@santarita.com',
            'ativo' => true,
        ]);

        $userNucleoSantaRita = User::create([
            'name' => 'Núcleo Santa Rita',
            'email' => 'nucleo@santarita.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Nucleo,
            'entidade_id' => $nucleos['santa_rita']->id,
            'ativo' => true,
        ]);
        $nucleos['santa_rita']->update(['user_id' => $userNucleoSantaRita->id]);

        $nucleos['tlc_pais'] = Entidade::create([
            'entidade_pai_id' => $dioceses['santo_amaro']->id,
            'tipo_entidade' => TipoEntidade::Nucleo,
            'nome' => 'Núcleo TLC de Pais',
            'email' => 'nucleo@tlcdepais.com',
            'ativo' => true,
        ]);

        $userNucleoTlcPais = User::create([
            'name' => 'Núcleo TLC de Pais',
            'email' => 'nucleo@tlcdepais.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Nucleo,
            'entidade_id' => $nucleos['tlc_pais']->id,
            'ativo' => true,
        ]);
        $nucleos['tlc_pais']->update(['user_id' => $userNucleoTlcPais->id]);

        $nucleos['rocio'] = Entidade::create([
            'entidade_pai_id' => $dioceses['santo_amaro']->id,
            'tipo_entidade' => TipoEntidade::Nucleo,
            'nome' => 'Núcleo Rocio',
            'email' => 'nucleo@rocio.com',
            'ativo' => true,
        ]);

        $userNucleoRocio = User::create([
            'name' => 'Núcleo Rocio',
            'email' => 'nucleo@rocio.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Nucleo,
            'entidade_id' => $nucleos['rocio']->id,
            'ativo' => true,
        ]);
        $nucleos['rocio']->update(['user_id' => $userNucleoRocio->id]);

        // Núcleos inativos
        $nucleos['missionaria'] = Entidade::create([
            'entidade_pai_id' => $dioceses['santo_amaro']->id,
            'tipo_entidade' => TipoEntidade::Nucleo,
            'nome' => 'Núcleo Missionaria',
            'email' => 'nucleo@missionaria.com',
            'ativo' => false,
        ]);

        $userNucleoMissionaria = User::create([
            'name' => 'Núcleo Missionaria',
            'email' => 'nucleo@missionaria.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Nucleo,
            'entidade_id' => $nucleos['missionaria']->id,
            'ativo' => false,
        ]);
        $nucleos['missionaria']->update(['user_id' => $userNucleoMissionaria->id]);

        $nucleos['saojoaopauloii'] = Entidade::create([
            'entidade_pai_id' => $dioceses['santo_amaro']->id,
            'tipo_entidade' => TipoEntidade::Nucleo,
            'nome' => 'Núcleo São João Paulo II',
            'email' => 'nucleo@saojoaopauloii.com',
            'ativo' => false,
        ]);

        $userNucleoSaoJoaoPauloII = User::create([
            'name' => 'Núcleo São João Paulo II',
            'email' => 'nucleo@saojoaopauloii.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Nucleo,
            'entidade_id' => $nucleos['saojoaopauloii']->id,
            'ativo' => false,
        ]);
        $nucleos['saojoaopauloii']->update(['user_id' => $userNucleoSaoJoaoPauloII->id]);

        $nucleos['salette'] = Entidade::create([
            'entidade_pai_id' => $dioceses['santo_amaro']->id,
            'tipo_entidade' => TipoEntidade::Nucleo,
            'nome' => 'Núcleo Salette',
            'email' => 'nucleo@salette.com',
            'ativo' => false,
        ]);

        $userNucleoSalette = User::create([
            'name' => 'Núcleo Salette',
            'email' => 'nucleo@salette.com',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Nucleo,
            'entidade_id' => $nucleos['salette']->id,
            'ativo' => false,
        ]);
        $nucleos['salette']->update(['user_id' => $userNucleoSalette->id]);

        $nucleos['santuario_santa_terezinha'] = Entidade::create([
            'entidade_pai_id' => $dioceses['campo_limpo']->id,
            'tipo_entidade' => TipoEntidade::Nucleo,
            'nome' => 'Núcleo Santuário Santa Terezinha',
            'email' => 'nucleo@santuariosantaterezinha.com',
            'ativo' => true,
        ]);

        $userNucleoSantuario = User::create([
            'name' => 'Núcleo Santuário Santa Terezinha',
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
            'name' => 'Secretaria de Música',
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
            'name' => 'Secretaria de Apoio',
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
            'name' => 'Secretaria de Espiritualidade',
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
            'name' => 'Secretaria de Eventos',
            'email' => 'secretaria.eventos@tlc.local',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Secretaria,
            'entidade_id' => $secretarias['eventos']->id,
            'ativo' => true,
        ]);
        $secretarias['eventos']->update(['user_id' => $userSecretariaEventos->id]);

        $secretarias['intercessao'] = Entidade::create([
            'tipo_entidade' => TipoEntidade::Secretaria,
            'nome' => 'Secretaria de Intercessão',
            'tipo_secretaria' => TipoSecretaria::Aberta,
            'ativo' => true,
        ]);

        $userSecretariaIntercessao = User::create([
            'name' => 'Secretaria de Intercessão',
            'email' => 'secretaria.intercessao@tlc.local',
            'password' => bcrypt('password'),
            'tipo_usuario' => TipoUsuario::Secretaria,
            'entidade_id' => $secretarias['intercessao']->id,
            'ativo' => true,
        ]);
        $secretarias['intercessao']->update(['user_id' => $userSecretariaIntercessao->id]);

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

        // Cleide: Rocio, Coordenadora Diocesana
        $dirigentes['cleide'] = Dirigente::create([
            'nome' => 'Cleide',
            'genero' => 'f',
            'ativo' => true,
        ]);

        $dirigentes['cleide']->vinculos()->create([
            'entidade_id' => $nucleos['rocio']->id,
            'tipo_vinculo' => TipoVinculo::Principal,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['cleide']->vinculos()->create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenadora Diocesana',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['cleide']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Fernanda: Igreja Verde, Coordenadora Diocesana
        $dirigentes['fernanda'] = Dirigente::create([
            'nome' => 'Fernanda',
            'genero' => 'f',
            'ativo' => true,
        ]);

        $dirigentes['fernanda']->vinculos()->create([
            'entidade_id' => $nucleos['igreja_verde']->id,
            'tipo_vinculo' => TipoVinculo::Principal,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['fernanda']->vinculos()->create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenadora Diocesana',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['fernanda']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Ricardinho: Ideal
        $dirigentes['ricardinho'] = Dirigente::create([
            'nome' => 'Ricardinho',
            'genero' => 'm',
            'ativo' => true,
        ]);

        $dirigentes['ricardinho']->vinculos()->create([
            'entidade_id' => $nucleos['ideal']->id,
            'tipo_vinculo' => TipoVinculo::Principal,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['ricardinho']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Alan Lira: Rainha
        $dirigentes['alan_lira'] = Dirigente::create([
            'nome' => 'Alan Lira',
            'genero' => 'm',
            'ativo' => true,
        ]);

        $dirigentes['alan_lira']->vinculos()->create([
            'entidade_id' => $nucleos['rainha']->id,
            'tipo_vinculo' => TipoVinculo::Principal,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['alan_lira']->vinculos()->create([
            'entidade_id' => $secretarias['musica']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['alan_lira']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Giovana: Igreja Verde, Coordenadora de Eventos
        $dirigentes['giovana'] = Dirigente::create([
            'nome' => 'Giovana',
            'genero' => 'f',
            'ativo' => true,
        ]);

        $dirigentes['giovana']->vinculos()->create([
            'entidade_id' => $nucleos['igreja_verde']->id,
            'tipo_vinculo' => TipoVinculo::Principal,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['giovana']->vinculos()->create([
            'entidade_id' => $secretarias['eventos']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenadora',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['giovana']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Karina: Rainha
        $dirigentes['karina'] = Dirigente::create([
            'nome' => 'Karina',
            'genero' => 'f',
            'ativo' => true,
        ]);

        $dirigentes['karina']->vinculos()->create([
            'entidade_id' => $nucleos['rainha']->id,
            'tipo_vinculo' => TipoVinculo::Principal,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['karina']->vinculos()->create([
            'entidade_id' => $secretarias['musica']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['karina']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Marquinhos: Santa Terezinha, Coordenador de Intercessão
        $dirigentes['marquinhos'] = Dirigente::create([
            'nome' => 'Marquinhos',
            'genero' => 'm',
            'ativo' => true,
        ]);

        $dirigentes['marquinhos']->vinculos()->create([
            'entidade_id' => $nucleos['santa_terezinha']->id,
            'tipo_vinculo' => TipoVinculo::Principal,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['marquinhos']->vinculos()->create([
            'entidade_id' => $secretarias['intercessao']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenador',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['marquinhos']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // William: Santa Paulina
        $dirigentes['william'] = Dirigente::create([
            'nome' => 'William',
            'genero' => 'm',
            'ativo' => true,
        ]);

        $dirigentes['william']->vinculos()->create([
            'entidade_id' => $nucleos['santa_paulina']->id,
            'tipo_vinculo' => TipoVinculo::Principal,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['william']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Sabrina: Santa Terezinha, Coordenadora de Intercessão
        $dirigentes['sabrina'] = Dirigente::create([
            'nome' => 'Sabrina',
            'genero' => 'f',
            'ativo' => true,
        ]);

        $dirigentes['sabrina']->vinculos()->create([
            'entidade_id' => $nucleos['santa_terezinha']->id,
            'tipo_vinculo' => TipoVinculo::Principal,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['sabrina']->vinculos()->create([
            'entidade_id' => $secretarias['intercessao']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenadora',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['sabrina']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Washington: Ap Grajau, Coordenador de Apoio
        $dirigentes['washington'] = Dirigente::create([
            'nome' => 'Washington',
            'genero' => 'm',
            'ativo' => true,
        ]);

        $dirigentes['washington']->vinculos()->create([
            'entidade_id' => $nucleos['ap_grajau']->id,
            'tipo_vinculo' => TipoVinculo::Principal,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['washington']->vinculos()->create([
            'entidade_id' => $secretarias['apoio']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenador',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['washington']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Aline: Santa Rita
        $dirigentes['aline'] = Dirigente::create([
            'nome' => 'Aline',
            'genero' => 'f',
            'ativo' => true,
        ]);

        $dirigentes['aline']->vinculos()->create([
            'entidade_id' => $nucleos['santa_rita']->id,
            'tipo_vinculo' => TipoVinculo::Principal,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['aline']->vinculos()->create([
            'entidade_id' => $secretarias['intercessao']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['aline']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Erica: Cidinha
        $dirigentes['erica'] = Dirigente::create([
            'nome' => 'Erica',
            'genero' => 'f',
            'ativo' => true,
        ]);

        $dirigentes['erica']->vinculos()->create([
            'entidade_id' => $nucleos['cidinha']->id,
            'tipo_vinculo' => TipoVinculo::Principal,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['erica']->vinculos()->create([
            'entidade_id' => $secretarias['intercessao']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['erica']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Jessica: Consolação
        $dirigentes['jessica'] = Dirigente::create([
            'nome' => 'Jessica',
            'genero' => 'f',
            'ativo' => true,
        ]);

        $dirigentes['jessica']->vinculos()->create([
            'entidade_id' => $nucleos['consolacao']->id,
            'tipo_vinculo' => TipoVinculo::Principal,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['jessica']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Jefferson: Rocio
        $dirigentes['jefferson'] = Dirigente::create([
            'nome' => 'Jefferson',
            'genero' => 'm',
            'ativo' => true,
        ]);

        $dirigentes['jefferson']->vinculos()->create([
            'entidade_id' => $nucleos['rocio']->id,
            'tipo_vinculo' => TipoVinculo::Principal,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['jefferson']->vinculos()->create([
            'entidade_id' => $secretarias['eventos']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['jefferson']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Karine: Santa Rita
        $dirigentes['karine'] = Dirigente::create([
            'nome' => 'Karine',
            'genero' => 'f',
            'ativo' => true,
        ]);

        $dirigentes['karine']->vinculos()->create([
            'entidade_id' => $nucleos['santa_rita']->id,
            'tipo_vinculo' => TipoVinculo::Principal,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['karine']->vinculos()->create([
            'entidade_id' => $secretarias['musica']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['karine']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Núbia: Consolação
        $dirigentes['nubia'] = Dirigente::create([
            'nome' => 'Núbia',
            'genero' => 'f',
            'ativo' => true,
        ]);

        $dirigentes['nubia']->vinculos()->create([
            'entidade_id' => $nucleos['consolacao']->id,
            'tipo_vinculo' => TipoVinculo::Principal,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['nubia']->vinculos()->create([
            'entidade_id' => $secretarias['musica']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['nubia']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Ana Clara: Rainha, Coordenadora
        $dirigentes['ana_clara'] = Dirigente::create([
            'nome' => 'Ana Clara',
            'genero' => 'f',
            'ativo' => true,
        ]);

        $dirigentes['ana_clara']->vinculos()->create([
            'entidade_id' => $nucleos['rainha']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenadora',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['ana_clara']->vinculos()->create([
            'entidade_id' => $secretarias['musica']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['ana_clara']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Ana Lucia: Borromeu, Coordenadora
        $dirigentes['ana_lucia'] = Dirigente::create([
            'nome' => 'Ana Lucia',
            'genero' => 'f',
            'ativo' => true,
        ]);

        $dirigentes['ana_lucia']->vinculos()->create([
            'entidade_id' => $nucleos['borromeu']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenadora',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['ana_lucia']->vinculos()->create([
            'entidade_id' => $secretarias['musica']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['ana_lucia']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Ana Carvalho: Cidinha, Coordenadora
        $dirigentes['ana_carvalho'] = Dirigente::create([
            'nome' => 'Ana Carvalho',
            'genero' => 'f',
            'ativo' => true,
        ]);

        $dirigentes['ana_carvalho']->vinculos()->create([
            'entidade_id' => $nucleos['cidinha']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenadora',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['ana_carvalho']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Daniel: Santa Paulina, Coordenador
        $dirigentes['daniel'] = Dirigente::create([
            'nome' => 'Daniel',
            'genero' => 'm',
            'ativo' => true,
        ]);

        $dirigentes['daniel']->vinculos()->create([
            'entidade_id' => $nucleos['santa_paulina']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenador',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['daniel']->vinculos()->create([
            'entidade_id' => $secretarias['musica']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['daniel']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Dhafny: Ap Miriam, Coordenadora
        $dirigentes['dhafny'] = Dirigente::create([
            'nome' => 'Dhafny',
            'genero' => 'f',
            'ativo' => true,
        ]);

        $dirigentes['dhafny']->vinculos()->create([
            'entidade_id' => $nucleos['ap_miriam']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenadora',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['dhafny']->vinculos()->create([
            'entidade_id' => $secretarias['musica']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['dhafny']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Dyeimes: Rocio, Coordenador
        $dirigentes['dyeimes'] = Dirigente::create([
            'nome' => 'Dyeimes',
            'genero' => 'm',
            'ativo' => true,
        ]);

        $dirigentes['dyeimes']->vinculos()->create([
            'entidade_id' => $nucleos['rocio']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenador',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['dyeimes']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Gabriel Martins: Santa Terezinha, Coordenador
        $dirigentes['gabriel_martins'] = Dirigente::create([
            'nome' => 'Gabriel Martins',
            'genero' => 'm',
            'ativo' => true,
        ]);

        $dirigentes['gabriel_martins']->vinculos()->create([
            'entidade_id' => $nucleos['santa_terezinha']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenador',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['gabriel_martins']->vinculos()->create([
            'entidade_id' => $secretarias['musica']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['gabriel_martins']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Aline: Santa Clara, Coordenadora
        $dirigentes['aline_santa_clara'] = Dirigente::create([
            'nome' => 'Aline',
            'genero' => 'f',
            'ativo' => true,
        ]);

        $dirigentes['aline_santa_clara']->vinculos()->create([
            'entidade_id' => $nucleos['santa_clara']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenadora',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['aline_santa_clara']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Gabriel Schoene: Igreja Verde, Coordenador
        $dirigentes['gabriel_schoene'] = Dirigente::create([
            'nome' => 'Gabriel Schoene',
            'genero' => 'm',
            'ativo' => true,
        ]);

        $dirigentes['gabriel_schoene']->vinculos()->create([
            'entidade_id' => $nucleos['igreja_verde']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenador',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['gabriel_schoene']->vinculos()->create([
            'entidade_id' => $secretarias['eventos']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['gabriel_schoene']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Leticia Rocha: Igreja Verde, Coordenadora
        $dirigentes['leticia_rocha'] = Dirigente::create([
            'nome' => 'Leticia Rocha',
            'genero' => 'f',
            'ativo' => true,
        ]);

        $dirigentes['leticia_rocha']->vinculos()->create([
            'entidade_id' => $nucleos['igreja_verde']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenadora',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['leticia_rocha']->vinculos()->create([
            'entidade_id' => $secretarias['eventos']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['leticia_rocha']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Gabriel Mariano: Santa Paulina, Coordenador
        $dirigentes['gabriel_mariano'] = Dirigente::create([
            'nome' => 'Gabriel Mariano',
            'genero' => 'm',
            'ativo' => true,
        ]);

        $dirigentes['gabriel_mariano']->vinculos()->create([
            'entidade_id' => $nucleos['santa_paulina']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenador',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['gabriel_mariano']->vinculos()->create([
            'entidade_id' => $secretarias['musica']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['gabriel_mariano']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Isabelle: Santa Rita, Coordenadora
        $dirigentes['isabelle'] = Dirigente::create([
            'nome' => 'Isabelle',
            'genero' => 'f',
            'ativo' => true,
        ]);

        $dirigentes['isabelle']->vinculos()->create([
            'entidade_id' => $nucleos['santa_rita']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenadora',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['isabelle']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // James: Santa Terezinha, Coordenador
        $dirigentes['james'] = Dirigente::create([
            'nome' => 'James',
            'genero' => 'm',
            'ativo' => true,
        ]);

        $dirigentes['james']->vinculos()->create([
            'entidade_id' => $nucleos['santa_terezinha']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenador',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['james']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Jefferson S: Rocio, Coordenador
        $dirigentes['jefferson_s'] = Dirigente::create([
            'nome' => 'Jefferson S',
            'genero' => 'm',
            'ativo' => true,
        ]);

        $dirigentes['jefferson_s']->vinculos()->create([
            'entidade_id' => $nucleos['rocio']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenador',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['jefferson_s']->vinculos()->create([
            'entidade_id' => $secretarias['musica']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['jefferson_s']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // João Pedro: Consolação, Coordenador
        $dirigentes['joao_pedro'] = Dirigente::create([
            'nome' => 'João Pedro',
            'genero' => 'm',
            'ativo' => true,
        ]);

        $dirigentes['joao_pedro']->vinculos()->create([
            'entidade_id' => $nucleos['consolacao']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenador',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['joao_pedro']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Junior: Ap Miriam, Coordenador
        $dirigentes['junior'] = Dirigente::create([
            'nome' => 'Junior',
            'genero' => 'm',
            'ativo' => true,
        ]);

        $dirigentes['junior']->vinculos()->create([
            'entidade_id' => $nucleos['ap_miriam']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenador',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['junior']->vinculos()->create([
            'entidade_id' => $secretarias['musica']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['junior']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Michele: Cidinha, Coordenadora
        $dirigentes['michele'] = Dirigente::create([
            'nome' => 'Michele',
            'genero' => 'f',
            'ativo' => true,
        ]);

        $dirigentes['michele']->vinculos()->create([
            'entidade_id' => $nucleos['cidinha']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenadora',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['michele']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Raquel: Ap Grajau, Coordenadora
        $dirigentes['raquel'] = Dirigente::create([
            'nome' => 'Raquel',
            'genero' => 'f',
            'ativo' => true,
        ]);

        $dirigentes['raquel']->vinculos()->create([
            'entidade_id' => $nucleos['ap_grajau']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenadora',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['raquel']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Thalita: São Bento, Coordenadora
        $dirigentes['thalita'] = Dirigente::create([
            'nome' => 'Thalita',
            'genero' => 'f',
            'ativo' => true,
        ]);

        $dirigentes['thalita']->vinculos()->create([
            'entidade_id' => $nucleos['sao_bento']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenadora',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['thalita']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Tio Ricardo: TLC de Pais, Coordenador
        $dirigentes['tio_ricardo'] = Dirigente::create([
            'nome' => 'Tio Ricardo',
            'genero' => 'm',
            'ativo' => true,
        ]);

        $dirigentes['tio_ricardo']->vinculos()->create([
            'entidade_id' => $nucleos['tlc_pais']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenador',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['tio_ricardo']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Vithoria Ramalho: Cidinha, Coordenadora
        $dirigentes['vithoria_ramalho'] = Dirigente::create([
            'nome' => 'Vithoria Ramalho',
            'genero' => 'f',
            'ativo' => true,
        ]);

        $dirigentes['vithoria_ramalho']->vinculos()->create([
            'entidade_id' => $nucleos['cidinha']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenadora',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['vithoria_ramalho']->vinculos()->create([
            'entidade_id' => $secretarias['musica']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['vithoria_ramalho']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Viviane: São Bento, Coordenadora
        $dirigentes['viviane'] = Dirigente::create([
            'nome' => 'Viviane',
            'genero' => 'f',
            'ativo' => true,
        ]);

        $dirigentes['viviane']->vinculos()->create([
            'entidade_id' => $nucleos['sao_bento']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenadora',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['viviane']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Adailton: Rocio, Coordenador
        $dirigentes['adailton'] = Dirigente::create([
            'nome' => 'Adailton',
            'genero' => 'm',
            'ativo' => true,
        ]);

        $dirigentes['adailton']->vinculos()->create([
            'entidade_id' => $nucleos['rocio']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenador',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['adailton']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Gil: São José, Coordenador
        $dirigentes['gil'] = Dirigente::create([
            'nome' => 'Gil',
            'genero' => 'm',
            'ativo' => true,
        ]);

        $dirigentes['gil']->vinculos()->create([
            'entidade_id' => $nucleos['sao_jose']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenador',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['gil']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Nathan: São José, Coordenador
        $dirigentes['nathan'] = Dirigente::create([
            'nome' => 'Nathan',
            'genero' => 'm',
            'ativo' => true,
        ]);

        $dirigentes['nathan']->vinculos()->create([
            'entidade_id' => $nucleos['sao_jose']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenador',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['nathan']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Ricardo: Rosario, Coordenador
        $dirigentes['ricardo'] = Dirigente::create([
            'nome' => 'Ricardo',
            'genero' => 'm',
            'ativo' => true,
        ]);

        $dirigentes['ricardo']->vinculos()->create([
            'entidade_id' => $nucleos['rosario']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenador',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['ricardo']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Geovanna: Rocio (principal), Coordenadora de Música
        $dirigentes['geovanna'] = Dirigente::create([
            'nome' => 'Geovanna',
            'genero' => 'f',
            'ativo' => true,
        ]);

        $dirigentes['geovanna']->vinculos()->create([
            'entidade_id' => $nucleos['rocio']->id,
            'tipo_vinculo' => TipoVinculo::Principal,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['geovanna']->vinculos()->create([
            'entidade_id' => $secretarias['musica']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenadora',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['geovanna']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
            'tipo_vinculo' => TipoVinculo::Adicional,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        // Messias: Ap Grajau (principal), Coordenador de Apoio
        $dirigentes['messias'] = Dirigente::create([
            'nome' => 'Messias',
            'genero' => 'm',
            'ativo' => true,
        ]);

        $dirigentes['messias']->vinculos()->create([
            'entidade_id' => $nucleos['ap_grajau']->id,
            'tipo_vinculo' => TipoVinculo::Principal,
            'cargo' => CargoDirigente::Dirigente,
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['messias']->vinculos()->create([
            'entidade_id' => $secretarias['apoio']->id,
            'tipo_vinculo' => TipoVinculo::Coordenacao,
            'cargo' => CargoDirigente::Coordenador,
            'papel' => 'Coordenador',
            'data_inicio' => now()->toDateString(),
            'ativo' => true,
        ]);

        $dirigentes['messias']->vinculos()->create([
            'entidade_id' => $secretarias['espiritualidade']->id,
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
            'Congresso' => 'Congresso diocesano',
            'Eleição' => 'Eleição diocesana',
            'TLC' => 'Encontro TLC',
        ] as $nome => $descricao) {
            $tipos[$nome] = TipoEvento::create([
                'nome' => $nome,
                'descricao' => $descricao,
                'ativo' => true,
            ])->id;
        }

        // ===== EVENTOS 2026 =====

        // JANEIRO - Formações Secretarias
        Evento::create([
            'tipo_evento_id' => $tipos['Formação'],
            'entidade_criadora_id' => $secretarias['musica']->id,
            'nome' => 'Formação Secretaria da Música',
            'descricao' => 'Formação dos dirigentes da secretaria de música',
            'data_inicio' => '2026-01-18 09:00:00',
            'local' => 'Sede Diocese Santo Amaro',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['Formação'],
            'entidade_criadora_id' => $secretarias['apoio']->id,
            'nome' => 'Formação Secretaria do Apoio',
            'descricao' => 'Formação dos dirigentes da secretaria de apoio',
            'data_inicio' => '2026-01-18 09:00:00',
            'local' => 'Sede Diocese Santo Amaro',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['Formação'],
            'entidade_criadora_id' => $secretarias['eventos']->id,
            'nome' => 'Formação Secretaria de Eventos',
            'descricao' => 'Formação dos dirigentes da secretaria de eventos',
            'data_inicio' => '2026-01-18 09:00:00',
            'local' => 'Sede Diocese Santo Amaro',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['Formação'],
            'entidade_criadora_id' => $secretarias['intercessao']->id,
            'nome' => 'Formação Secretaria de Intercessão',
            'descricao' => 'Formação dos dirigentes da secretaria de intercessão',
            'data_inicio' => '2026-01-18 09:00:00',
            'local' => 'Sede Diocese Santo Amaro',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        // FEVEREIRO - Congresso Diocesano
        Evento::create([
            'tipo_evento_id' => $tipos['Congresso'],
            'entidade_criadora_id' => $dioceses['santo_amaro']->id,
            'nome' => 'Congresso Diocesano de Espiritualidade',
            'descricao' => 'Congresso para coordenadores e membros da secretaria de espiritualidade',
            'data_inicio' => '2026-02-07 08:00:00',
            'data_fim' => '2026-02-08 18:00:00',
            'local' => 'Centro de Retiros',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        // MARÇO - Formações e Reuniões
        Evento::create([
            'tipo_evento_id' => $tipos['Retiro'],
            'entidade_criadora_id' => $secretarias['intercessao']->id,
            'nome' => 'Formação Retiro TLC Carismático',
            'descricao' => 'Retiro carismático para a secretaria de intercessão',
            'data_inicio' => '2026-03-14 08:00:00',
            'local' => 'Centro de Retiros',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['Formação'],
            'entidade_criadora_id' => $secretarias['musica']->id,
            'nome' => 'Formação Secretaria da Música',
            'descricao' => 'Formação dos dirigentes da secretaria de música',
            'data_inicio' => '2026-03-21 09:00:00',
            'local' => 'Sede Diocese Santo Amaro',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['Formação'],
            'entidade_criadora_id' => $secretarias['apoio']->id,
            'nome' => 'Formação Secretaria do Apoio',
            'descricao' => 'Formação dos dirigentes da secretaria de apoio',
            'data_inicio' => '2026-03-21 09:00:00',
            'local' => 'Sede Diocese Santo Amaro',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['Formação'],
            'entidade_criadora_id' => $secretarias['eventos']->id,
            'nome' => 'Formação Secretaria de Eventos',
            'descricao' => 'Formação dos dirigentes da secretaria de eventos',
            'data_inicio' => '2026-03-21 09:00:00',
            'local' => 'Sede Diocese Santo Amaro',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['Formação'],
            'entidade_criadora_id' => $secretarias['intercessao']->id,
            'nome' => 'Formação Secretaria de Intercessão',
            'descricao' => 'Formação dos dirigentes da secretaria de intercessão',
            'data_inicio' => '2026-03-21 09:00:00',
            'local' => 'Sede Diocese Santo Amaro',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['Reunião'],
            'entidade_criadora_id' => $dioceses['santo_amaro']->id,
            'nome' => 'Reunião Diocesana Online',
            'descricao' => 'Reunião para coordenadores diocesanos',
            'data_inicio' => '2026-03-27 20:00:00',
            'local' => 'Online',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        // ABRIL - TLC e Mini TLC
        Evento::create([
            'tipo_evento_id' => $tipos['TLC'],
            'entidade_criadora_id' => $nucleos['sao_jose']->id,
            'nome' => 'Implantação Mini TLC São José (Cajula)',
            'descricao' => 'Mini TLC para o núcleo São José',
            'data_inicio' => '2026-04-10 08:00:00',
            'data_fim' => '2026-04-12 18:00:00',
            'local' => 'Comunidade São José',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['TLC'],
            'entidade_criadora_id' => $nucleos['igreja_verde']->id,
            'nome' => 'TLC Igreja Verde (Tabor)',
            'descricao' => 'TLC para o núcleo Igreja Verde',
            'data_inicio' => '2026-04-17 08:00:00',
            'data_fim' => '2026-04-19 18:00:00',
            'local' => 'Igreja Verde',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['TLC'],
            'entidade_criadora_id' => $nucleos['ap_miriam']->id,
            'nome' => 'Implantação Mini TLC Ap Miriam (Juventude)',
            'descricao' => 'Mini TLC para o núcleo Ap Miriam',
            'data_inicio' => '2026-04-24 08:00:00',
            'data_fim' => '2026-04-26 18:00:00',
            'local' => 'Ap Miriam',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        // MAIO - Eleição e Retiro
        Evento::create([
            'tipo_evento_id' => $tipos['Eleição'],
            'entidade_criadora_id' => $dioceses['santo_amaro']->id,
            'nome' => 'Eleição Diocesana',
            'descricao' => 'Eleição para coordenadores diocesanos',
            'data_inicio' => '2026-05-09 08:00:00',
            'data_fim' => '2026-05-19 18:00:00',
            'local' => 'Sede Diocese Santo Amaro',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['Retiro'],
            'entidade_criadora_id' => $secretarias['intercessao']->id,
            'nome' => 'Retiro TLC Carismático (Juventude)',
            'descricao' => 'Retiro carismático para jovens',
            'data_inicio' => '2026-05-22 08:00:00',
            'data_fim' => '2026-05-24 18:00:00',
            'local' => 'Centro de Retiros',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        // JUNHO - TLC
        Evento::create([
            'tipo_evento_id' => $tipos['TLC'],
            'entidade_criadora_id' => $nucleos['rosario']->id,
            'nome' => 'TLC Cap Pedreria (Tabor)',
            'descricao' => 'TLC para a região',
            'data_inicio' => '2026-06-03 08:00:00',
            'data_fim' => '2026-06-07 18:00:00',
            'local' => 'Cap Pedreria',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['TLC'],
            'entidade_criadora_id' => $nucleos['ideal']->id,
            'nome' => 'Mini TLC Ideal (Tabor)',
            'descricao' => 'Mini TLC para o núcleo Ideal',
            'data_inicio' => '2026-06-19 08:00:00',
            'data_fim' => '2026-06-21 18:00:00',
            'local' => 'Ideal',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        // JULHO - TLC e Missa
        Evento::create([
            'tipo_evento_id' => $tipos['TLC'],
            'entidade_criadora_id' => $nucleos['borromeu']->id,
            'nome' => 'Implantação TLC Borromeu (Juventude)',
            'descricao' => 'TLC para o núcleo Borromeu',
            'data_inicio' => '2026-07-10 08:00:00',
            'data_fim' => '2026-07-12 18:00:00',
            'local' => 'Borromeu',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['TLC'],
            'entidade_criadora_id' => $nucleos['rosario']->id,
            'nome' => 'Mini TLC Rosário (Juventude)',
            'descricao' => 'Mini TLC para o núcleo Rosário',
            'data_inicio' => '2026-07-17 08:00:00',
            'data_fim' => '2026-07-19 18:00:00',
            'local' => 'Rosário',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['TLC'],
            'entidade_criadora_id' => $nucleos['ap_miriam']->id,
            'nome' => 'Implantação Mini TLC Ap Miriam (Juventude)',
            'descricao' => 'Mini TLC para o núcleo Ap Miriam',
            'data_inicio' => '2026-07-24 08:00:00',
            'data_fim' => '2026-07-26 18:00:00',
            'local' => 'Ap Miriam',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['Missa'],
            'entidade_criadora_id' => $dioceses['santo_amaro']->id,
            'nome' => 'Missa em Honra Santo Inácio',
            'descricao' => 'Missa em honra a Santo Inácio de Loyola',
            'data_inicio' => '2026-07-31 19:00:00',
            'local' => 'Catedral Diocese Santo Amaro',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        // AGOSTO - Reunião, Formações e TLC
        Evento::create([
            'tipo_evento_id' => $tipos['Reunião'],
            'entidade_criadora_id' => $dioceses['santo_amaro']->id,
            'nome' => 'Reunião Diocesana Presencial',
            'descricao' => 'Reunião para coordenadores diocesanos',
            'data_inicio' => '2026-08-03 09:00:00',
            'local' => 'Sede Diocese Santo Amaro',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['Formação'],
            'entidade_criadora_id' => $secretarias['musica']->id,
            'nome' => 'Formação Secretaria da Música',
            'descricao' => 'Formação dos dirigentes da secretaria de música',
            'data_inicio' => '2026-08-12 09:00:00',
            'local' => 'Sede Diocese Santo Amaro',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['Formação'],
            'entidade_criadora_id' => $secretarias['apoio']->id,
            'nome' => 'Formação Secretaria do Apoio',
            'descricao' => 'Formação dos dirigentes da secretaria de apoio',
            'data_inicio' => '2026-08-12 09:00:00',
            'local' => 'Sede Diocese Santo Amaro',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['Formação'],
            'entidade_criadora_id' => $secretarias['eventos']->id,
            'nome' => 'Formação Secretaria de Eventos',
            'descricao' => 'Formação dos dirigentes da secretaria de eventos',
            'data_inicio' => '2026-08-12 09:00:00',
            'local' => 'Sede Diocese Santo Amaro',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['Formação'],
            'entidade_criadora_id' => $secretarias['intercessao']->id,
            'nome' => 'Formação Secretaria de Intercessão',
            'descricao' => 'Formação dos dirigentes da secretaria de intercessão',
            'data_inicio' => '2026-08-12 09:00:00',
            'local' => 'Sede Diocese Santo Amaro',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['TLC'],
            'entidade_criadora_id' => $nucleos['santa_clara']->id,
            'nome' => 'TLC Santa Clara (Cursilho)',
            'descricao' => 'TLC para o núcleo Santa Clara',
            'data_inicio' => '2026-08-15 08:00:00',
            'data_fim' => '2026-08-16 18:00:00',
            'local' => 'Santa Clara',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['TLC'],
            'entidade_criadora_id' => $nucleos['rainha']->id,
            'nome' => 'TLC Rainha (Cursilho)',
            'descricao' => 'TLC para o núcleo Rainha',
            'data_inicio' => '2026-08-21 08:00:00',
            'data_fim' => '2026-08-23 18:00:00',
            'local' => 'Rainha',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['TLC'],
            'entidade_criadora_id' => $nucleos['sao_bento']->id,
            'nome' => 'TLC São Bento',
            'descricao' => 'TLC para o núcleo São Bento',
            'data_inicio' => '2026-08-28 08:00:00',
            'data_fim' => '2026-08-30 18:00:00',
            'local' => 'São Bento',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        // SETEMBRO - TLC e Romaria
        Evento::create([
            'tipo_evento_id' => $tipos['TLC'],
            'entidade_criadora_id' => $nucleos['santa_terezinha']->id,
            'nome' => 'TLC Santa Terezinha (Tabor)',
            'descricao' => 'TLC para o núcleo Santa Terezinha',
            'data_inicio' => '2026-09-04 08:00:00',
            'data_fim' => '2026-09-06 18:00:00',
            'local' => 'Santa Terezinha',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['Festa'],
            'entidade_criadora_id' => $dioceses['santo_amaro']->id,
            'nome' => 'Romaria Diocesana',
            'descricao' => 'Romaria diocesana',
            'data_inicio' => '2026-09-12 09:00:00',
            'local' => 'Santuário Diocesano',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['TLC'],
            'entidade_criadora_id' => $nucleos['consolacao']->id,
            'nome' => 'TLC Consolação (Cursilho)',
            'descricao' => 'TLC para o núcleo Consolação',
            'data_inicio' => '2026-09-18 08:00:00',
            'data_fim' => '2026-09-20 18:00:00',
            'local' => 'Consolação',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['TLC'],
            'entidade_criadora_id' => $nucleos['santa_rita']->id,
            'nome' => 'TLC Santa Rita (Tabor)',
            'descricao' => 'TLC para o núcleo Santa Rita',
            'data_inicio' => '2026-09-25 08:00:00',
            'data_fim' => '2026-09-27 18:00:00',
            'local' => 'Santa Rita',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        // OUTUBRO - Reunião e TLC
        Evento::create([
            'tipo_evento_id' => $tipos['Reunião'],
            'entidade_criadora_id' => $dioceses['santo_amaro']->id,
            'nome' => 'Reunião Diocesana Presencial',
            'descricao' => 'Reunião para coordenadores diocesanos',
            'data_inicio' => '2026-10-03 09:00:00',
            'local' => 'Sede Diocese Santo Amaro',
            'escopo' => EscopoEvento::Dirigentes->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['TLC'],
            'entidade_criadora_id' => $nucleos['igreja_verde']->id,
            'nome' => 'Mini TLC Igreja Verde (Cajula)',
            'descricao' => 'Mini TLC para o núcleo Igreja Verde',
            'data_inicio' => '2026-10-09 08:00:00',
            'data_fim' => '2026-10-11 18:00:00',
            'local' => 'Igreja Verde',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        // NOVEMBRO - Jornada Nacional e TLC
        Evento::create([
            'tipo_evento_id' => $tipos['Retiro'],
            'entidade_criadora_id' => $dioceses['santo_amaro']->id,
            'nome' => 'Jornada Nacional do TLC em Aparecida',
            'descricao' => 'Jornada nacional do TLC',
            'data_inicio' => '2026-11-09 08:00:00',
            'data_fim' => '2026-11-11 18:00:00',
            'local' => 'Santuário Nacional de Aparecida',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['TLC'],
            'entidade_criadora_id' => $nucleos['tlc_pais']->id,
            'nome' => 'TLC de Pais (Tabor)',
            'descricao' => 'TLC para pais',
            'data_inicio' => '2026-11-13 08:00:00',
            'data_fim' => '2026-11-15 18:00:00',
            'local' => 'TLC de Pais',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['TLC'],
            'entidade_criadora_id' => $nucleos['ap_miriam']->id,
            'nome' => 'TLC Ap Miriam (Tabor)',
            'descricao' => 'TLC para o núcleo Ap Miriam',
            'data_inicio' => '2026-11-20 08:00:00',
            'data_fim' => '2026-11-22 18:00:00',
            'local' => 'Ap Miriam',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['TLC'],
            'entidade_criadora_id' => $nucleos['ap_grajau']->id,
            'nome' => 'TLC Ap Grajau',
            'descricao' => 'TLC para o núcleo Ap Grajau',
            'data_inicio' => '2026-11-27 08:00:00',
            'data_fim' => '2026-11-29 18:00:00',
            'local' => 'Ap Grajau',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        // DEZEMBRO - TLC e Missa
        Evento::create([
            'tipo_evento_id' => $tipos['TLC'],
            'entidade_criadora_id' => $nucleos['rocio']->id,
            'nome' => 'TLC Rocio (Tabor)',
            'descricao' => 'TLC para o núcleo Rocio',
            'data_inicio' => '2026-12-04 08:00:00',
            'data_fim' => '2026-12-06 18:00:00',
            'local' => 'Rocio',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        Evento::create([
            'tipo_evento_id' => $tipos['Missa'],
            'entidade_criadora_id' => $dioceses['santo_amaro']->id,
            'nome' => 'Missa em Honra a N.Sra de Guadalupe',
            'descricao' => 'Missa em honra a Nossa Senhora de Guadalupe',
            'data_inicio' => '2026-12-12 19:00:00',
            'local' => 'Catedral Diocese Santo Amaro',
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);

        // Retiro Diocesano Original
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

        // ===== DADOS FINANCEIROS DETALHADOS =====

        // Diocese Santo Amaro - Categorias adicionais
        $cat_diezmos = FinanceiroCategoria::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'nome' => 'Dízimos',
            'tipo' => 'entrada',
        ]);

        $cat_ofertas = FinanceiroCategoria::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'nome' => 'Ofertas',
            'tipo' => 'entrada',
        ]);

        $cat_aluguel = FinanceiroCategoria::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'nome' => 'Aluguel e Espaço',
            'tipo' => 'saida',
        ]);

        $cat_servicos = FinanceiroCategoria::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'nome' => 'Serviços',
            'tipo' => 'saida',
        ]);

        $cat_manutencao = FinanceiroCategoria::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'nome' => 'Manutenção',
            'tipo' => 'saida',
        ]);

        $cat_viagem = FinanceiroCategoria::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'nome' => 'Viagem e Deslocamento',
            'tipo' => 'saida',
        ]);

        // Diocese Santo Amaro - Movimentos financeiros
        FinanceiroMovimento::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'financeiro_categoria_id' => $cat_diezmos->id,
            'tipo' => 'entrada',
            'descricao' => 'Dízimos coletados - Domingo 15 de março',
            'valor' => 2500.00,
            'data_movimento' => '2026-03-15',
            'forma_pagamento' => 'dinheiro',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'financeiro_categoria_id' => $cat_ofertas->id,
            'tipo' => 'entrada',
            'descricao' => 'Ofertas especiais - Missa 22 de março',
            'valor' => 1800.00,
            'data_movimento' => '2026-03-22',
            'forma_pagamento' => 'dinheiro',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'financeiro_categoria_id' => $cat_aluguel->id,
            'tipo' => 'saida',
            'descricao' => 'Aluguel sala de reuniões - Março',
            'valor' => 1200.00,
            'data_movimento' => '2026-03-01',
            'forma_pagamento' => 'transferencia',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'financeiro_categoria_id' => $cat_servicos->id,
            'tipo' => 'saida',
            'descricao' => 'Limpeza e conservação',
            'valor' => 450.00,
            'data_movimento' => '2026-03-10',
            'forma_pagamento' => 'cartao',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'financeiro_categoria_id' => $cat_manutencao->id,
            'tipo' => 'saida',
            'descricao' => 'Reparo de equipamentos',
            'valor' => 680.00,
            'data_movimento' => '2026-03-18',
            'forma_pagamento' => 'pix',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'financeiro_categoria_id' => $cat_viagem->id,
            'tipo' => 'saida',
            'descricao' => 'Combustível para visitas pastorais',
            'valor' => 320.00,
            'data_movimento' => '2026-03-20',
            'forma_pagamento' => 'cartao',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'financeiro_categoria_id' => $cat_diezmos->id,
            'tipo' => 'entrada',
            'descricao' => 'Dízimos coletados - Domingo 29 de março',
            'valor' => 2800.00,
            'data_movimento' => '2026-03-29',
            'forma_pagamento' => 'dinheiro',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'financeiro_categoria_id' => $categorias['eventos']->id,
            'tipo' => 'saida',
            'descricao' => 'Material para congresso diocesano',
            'valor' => 1500.00,
            'data_movimento' => '2026-02-01',
            'forma_pagamento' => 'transferencia',
        ]);

        // Núcleo Igreja Verde - Categorias e movimentos
        $catIV_receita = FinanceiroCategoria::create([
            'entidade_id' => $nucleos['igreja_verde']->id,
            'nome' => 'Dízimos e Ofertas',
            'tipo' => 'entrada',
        ]);

        $catIV_aluguel = FinanceiroCategoria::create([
            'entidade_id' => $nucleos['igreja_verde']->id,
            'nome' => 'Aluguel Espaço',
            'tipo' => 'saida',
        ]);

        $catIV_material = FinanceiroCategoria::create([
            'entidade_id' => $nucleos['igreja_verde']->id,
            'nome' => 'Material e Suprimentos',
            'tipo' => 'saida',
        ]);

        $catIV_evento = FinanceiroCategoria::create([
            'entidade_id' => $nucleos['igreja_verde']->id,
            'nome' => 'Eventos e Encontros',
            'tipo' => 'saida',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $nucleos['igreja_verde']->id,
            'financeiro_categoria_id' => $catIV_receita->id,
            'tipo' => 'entrada',
            'descricao' => 'Dízimos coletados - Reunião núcleo',
            'valor' => 1200.00,
            'data_movimento' => '2026-03-14',
            'forma_pagamento' => 'dinheiro',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $nucleos['igreja_verde']->id,
            'financeiro_categoria_id' => $catIV_receita->id,
            'tipo' => 'entrada',
            'descricao' => 'Ofertas especiais',
            'valor' => 680.00,
            'data_movimento' => '2026-03-21',
            'forma_pagamento' => 'dinheiro',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $nucleos['igreja_verde']->id,
            'financeiro_categoria_id' => $catIV_aluguel->id,
            'tipo' => 'saida',
            'descricao' => 'Aluguel sala - Março',
            'valor' => 600.00,
            'data_movimento' => '2026-03-01',
            'forma_pagamento' => 'transferencia',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $nucleos['igreja_verde']->id,
            'financeiro_categoria_id' => $catIV_material->id,
            'tipo' => 'saida',
            'descricao' => 'Cópias e material de papelaria',
            'valor' => 240.00,
            'data_movimento' => '2026-03-10',
            'forma_pagamento' => 'dinheiro',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $nucleos['igreja_verde']->id,
            'financeiro_categoria_id' => $catIV_evento->id,
            'tipo' => 'saida',
            'descricao' => 'Lanche para reunião',
            'valor' => 380.00,
            'data_movimento' => '2026-03-14',
            'forma_pagamento' => 'dinheiro',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $nucleos['igreja_verde']->id,
            'financeiro_categoria_id' => $catIV_receita->id,
            'tipo' => 'entrada',
            'descricao' => 'Contribuição para TLC',
            'valor' => 950.00,
            'data_movimento' => '2026-03-25',
            'forma_pagamento' => 'pix',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $nucleos['igreja_verde']->id,
            'financeiro_categoria_id' => $catIV_evento->id,
            'tipo' => 'saida',
            'descricao' => 'Material para TLC Igreja Verde',
            'valor' => 1200.00,
            'data_movimento' => '2026-04-01',
            'forma_pagamento' => 'transferencia',
        ]);

        // Núcleo Santa Paulina - Categorias e movimentos
        $catSP_receita = FinanceiroCategoria::create([
            'entidade_id' => $nucleos['santa_paulina']->id,
            'nome' => 'Dízimos e Ofertas',
            'tipo' => 'entrada',
        ]);

        $catSP_musica = FinanceiroCategoria::create([
            'entidade_id' => $nucleos['santa_paulina']->id,
            'nome' => 'Música e Louvor',
            'tipo' => 'saida',
        ]);

        $catSP_evento = FinanceiroCategoria::create([
            'entidade_id' => $nucleos['santa_paulina']->id,
            'nome' => 'Eventos e Reuniões',
            'tipo' => 'saida',
        ]);

        $catSP_manutencao = FinanceiroCategoria::create([
            'entidade_id' => $nucleos['santa_paulina']->id,
            'nome' => 'Manutenção e Limpeza',
            'tipo' => 'saida',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $nucleos['santa_paulina']->id,
            'financeiro_categoria_id' => $catSP_receita->id,
            'tipo' => 'entrada',
            'descricao' => 'Dízimos - Domingo',
            'valor' => 1500.00,
            'data_movimento' => '2026-03-08',
            'forma_pagamento' => 'dinheiro',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $nucleos['santa_paulina']->id,
            'financeiro_categoria_id' => $catSP_receita->id,
            'tipo' => 'entrada',
            'descricao' => 'Ofertas',
            'valor' => 820.00,
            'data_movimento' => '2026-03-15',
            'forma_pagamento' => 'dinheiro',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $nucleos['santa_paulina']->id,
            'financeiro_categoria_id' => $catSP_musica->id,
            'tipo' => 'saida',
            'descricao' => 'Compra de partituras e hinos',
            'valor' => 580.00,
            'data_movimento' => '2026-03-05',
            'forma_pagamento' => 'cartao',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $nucleos['santa_paulina']->id,
            'financeiro_categoria_id' => $catSP_evento->id,
            'tipo' => 'saida',
            'descricao' => 'Lanche para reunião mensal',
            'valor' => 420.00,
            'data_movimento' => '2026-03-12',
            'forma_pagamento' => 'dinheiro',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $nucleos['santa_paulina']->id,
            'financeiro_categoria_id' => $catSP_manutencao->id,
            'tipo' => 'saida',
            'descricao' => 'Limpeza e higiene',
            'valor' => 300.00,
            'data_movimento' => '2026-03-20',
            'forma_pagamento' => 'dinheiro',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $nucleos['santa_paulina']->id,
            'financeiro_categoria_id' => $catSP_receita->id,
            'tipo' => 'entrada',
            'descricao' => 'Dízimos - Domingo',
            'valor' => 1650.00,
            'data_movimento' => '2026-03-22',
            'forma_pagamento' => 'dinheiro',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $nucleos['santa_paulina']->id,
            'financeiro_categoria_id' => $catSP_evento->id,
            'tipo' => 'saida',
            'descricao' => 'Material para TLC Santa Paulina',
            'valor' => 1400.00,
            'data_movimento' => '2026-04-05',
            'forma_pagamento' => 'transferencia',
        ]);

        FinanceiroMovimento::create([
            'entidade_id' => $nucleos['santa_paulina']->id,
            'financeiro_categoria_id' => $catSP_musica->id,
            'tipo' => 'saida',
            'descricao' => 'Manutenção de instrumentos',
            'valor' => 250.00,
            'data_movimento' => '2026-03-25',
            'forma_pagamento' => 'pix',
        ]);

        // ===== ALMOXARIFADO =====

        // Categorias Almoxarifado
        $almCat_consumiveis = \App\Models\AlmoxarifadoCategoria::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'nome' => 'Consumíveis',
            'descricao' => 'Itens consumíveis gerais',
            'ativo' => true,
        ]);

        $almCat_escritorio = \App\Models\AlmoxarifadoCategoria::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'nome' => 'Escritório',
            'descricao' => 'Materiais de escritório',
            'ativo' => true,
        ]);

        $almCat_limpeza = \App\Models\AlmoxarifadoCategoria::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'nome' => 'Limpeza',
            'descricao' => 'Produtos de limpeza',
            'ativo' => true,
        ]);

        $almCat_liturgia = \App\Models\AlmoxarifadoCategoria::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'nome' => 'Litúrgico',
            'descricao' => 'Materiais litúrgicos',
            'ativo' => true,
        ]);

        // Itens Almoxarifado
        $item_papel = \App\Models\AlmoxarifadoItem::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'almoxarifado_categoria_id' => $almCat_escritorio->id,
            'nome' => 'Papel A4',
            'descricao' => 'Papel branco A4 75g',
            'unidade_medida' => 'resma',
            'quantidade_minima' => 5,
            'quantidade_atual' => 12,
            'status' => 'ativo',
        ]);

        $item_toner = \App\Models\AlmoxarifadoItem::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'almoxarifado_categoria_id' => $almCat_escritorio->id,
            'nome' => 'Toner Impressora',
            'descricao' => 'Toner compatível para HP',
            'unidade_medida' => 'unidade',
            'quantidade_minima' => 2,
            'quantidade_atual' => 4,
            'status' => 'ativo',
        ]);

        $item_limpador = \App\Models\AlmoxarifadoItem::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'almoxarifado_categoria_id' => $almCat_limpeza->id,
            'nome' => 'Detergente Neutro',
            'descricao' => 'Detergente neutro 2L',
            'unidade_medida' => 'litro',
            'quantidade_minima' => 3,
            'quantidade_atual' => 8,
            'status' => 'ativo',
        ]);

        $item_velas = \App\Models\AlmoxarifadoItem::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'almoxarifado_categoria_id' => $almCat_liturgia->id,
            'nome' => 'Velas Brancas',
            'descricao' => 'Velas brancas litúrgicas',
            'unidade_medida' => 'unidade',
            'quantidade_minima' => 10,
            'quantidade_atual' => 25,
            'status' => 'ativo',
        ]);

        $item_incenso = \App\Models\AlmoxarifadoItem::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'almoxarifado_categoria_id' => $almCat_liturgia->id,
            'nome' => 'Incenso',
            'descricao' => 'Incenso litúrgico',
            'unidade_medida' => 'caixa',
            'quantidade_minima' => 2,
            'quantidade_atual' => 5,
            'status' => 'ativo',
        ]);

        $item_cafezinho = \App\Models\AlmoxarifadoItem::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'almoxarifado_categoria_id' => $almCat_consumiveis->id,
            'nome' => 'Café',
            'descricao' => 'Café 500g',
            'unidade_medida' => 'quilograma',
            'quantidade_minima' => 1,
            'quantidade_atual' => 3,
            'status' => 'ativo',
        ]);

        // Movimentos Almoxarifado
        \App\Models\AlmoxarifadoMovimento::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'almoxarifado_item_id' => $item_papel->id,
            'tipo_movimento' => 'entrada',
            'quantidade' => 10,
            'quantidade_anterior' => 2,
            'quantidade_posterior' => 12,
            'descricao' => 'Compra fornecedor',
            'data_movimento' => '2026-03-01 10:00:00',
            'responsavel_user_id' => $userDioceseSantoAmaro->id,
        ]);

        \App\Models\AlmoxarifadoMovimento::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'almoxarifado_item_id' => $item_papel->id,
            'tipo_movimento' => 'saida',
            'quantidade' => 5,
            'quantidade_anterior' => 12,
            'quantidade_posterior' => 7,
            'descricao' => 'Uso em reunião',
            'data_movimento' => '2026-03-15 14:30:00',
            'responsavel_user_id' => $userDioceseSantoAmaro->id,
        ]);

        \App\Models\AlmoxarifadoMovimento::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'almoxarifado_item_id' => $item_velas->id,
            'tipo_movimento' => 'entrada',
            'quantidade' => 30,
            'quantidade_anterior' => 0,
            'quantidade_posterior' => 30,
            'descricao' => 'Compra para liturgia',
            'data_movimento' => '2026-02-28 09:00:00',
            'responsavel_user_id' => $userDioceseSantoAmaro->id,
        ]);

        \App\Models\AlmoxarifadoMovimento::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'almoxarifado_item_id' => $item_velas->id,
            'tipo_movimento' => 'saida',
            'quantidade' => 5,
            'quantidade_anterior' => 30,
            'quantidade_posterior' => 25,
            'descricao' => 'Consumo missa semanal',
            'data_movimento' => '2026-03-20 18:00:00',
            'responsavel_user_id' => $userDioceseSantoAmaro->id,
        ]);

        \App\Models\AlmoxarifadoMovimento::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'almoxarifado_item_id' => $item_limpador->id,
            'tipo_movimento' => 'entrada',
            'quantidade' => 5,
            'quantidade_anterior' => 3,
            'quantidade_posterior' => 8,
            'descricao' => 'Compra para limpeza',
            'data_movimento' => '2026-03-05 11:00:00',
            'responsavel_user_id' => $userDioceseSantoAmaro->id,
        ]);

        \App\Models\AlmoxarifadoMovimento::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'almoxarifado_item_id' => $item_cafezinho->id,
            'tipo_movimento' => 'saida',
            'quantidade' => 1.5,
            'quantidade_anterior' => 3,
            'quantidade_posterior' => 1.5,
            'descricao' => 'Consumo reuniões',
            'data_movimento' => '2026-03-18 16:00:00',
            'responsavel_user_id' => $userDioceseSantoAmaro->id,
        ]);

        \App\Models\AlmoxarifadoMovimento::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'almoxarifado_item_id' => $item_toner->id,
            'tipo_movimento' => 'entrada',
            'quantidade' => 3,
            'quantidade_anterior' => 1,
            'quantidade_posterior' => 4,
            'descricao' => 'Compra para impressoras',
            'data_movimento' => '2026-02-15 08:30:00',
            'responsavel_user_id' => $userDioceseSantoAmaro->id,
        ]);

        \App\Models\AlmoxarifadoMovimento::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'almoxarifado_item_id' => $item_toner->id,
            'tipo_movimento' => 'saida',
            'quantidade' => 1,
            'quantidade_anterior' => 4,
            'quantidade_posterior' => 3,
            'descricao' => 'Troca de toner impressora',
            'data_movimento' => '2026-03-10 13:00:00',
            'responsavel_user_id' => $userDioceseSantoAmaro->id,
        ]);

        \App\Models\AlmoxarifadoMovimento::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'almoxarifado_item_id' => $item_incenso->id,
            'tipo_movimento' => 'entrada',
            'quantidade' => 5,
            'quantidade_anterior' => 0,
            'quantidade_posterior' => 5,
            'descricao' => 'Reposição de incenso',
            'data_movimento' => '2026-02-20 10:00:00',
            'responsavel_user_id' => $userDioceseSantoAmaro->id,
        ]);

        \App\Models\AlmoxarifadoMovimento::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'almoxarifado_item_id' => $item_incenso->id,
            'tipo_movimento' => 'saida',
            'quantidade' => 2,
            'quantidade_anterior' => 5,
            'quantidade_posterior' => 3,
            'descricao' => 'Consumo em celebrações',
            'data_movimento' => '2026-03-22 19:30:00',
            'responsavel_user_id' => $userDioceseSantoAmaro->id,
        ]);

        // ===== TAREFAS =====

        // Tarefas Diocese Santo Amaro
        \App\Models\Tarefa::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'titulo' => 'Preparar material para congresso',
            'descricao' => 'Preparar todos os materiais para o congresso diocesano de fevereiro',
            'prioridade' => 'alta',
            'status' => 'em_andamento',
            'data_limite' => '2026-02-01',
            'responsavel_user_id' => $userDioceseSantoAmaro->id,
        ]);

        \App\Models\Tarefa::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'titulo' => 'Confirmar presença no TLC',
            'descricao' => 'Confirmar presença dos dirigentes nos TLCs agendados',
            'prioridade' => 'media',
            'status' => 'pendente',
            'data_limite' => '2026-03-31',
            'responsavel_user_id' => $userDioceseSantoAmaro->id,
        ]);

        \App\Models\Tarefa::create([
            'entidade_id' => $dioceses['santo_amaro']->id,
            'titulo' => 'Organizar eleição diocesana',
            'descricao' => 'Preparar e organizar toda a eleição diocesana de maio',
            'prioridade' => 'alta',
            'status' => 'pendente',
            'data_limite' => '2026-05-01',
            'responsavel_user_id' => $userDioceseSantoAmaro->id,
        ]);

        // Tarefas Núcleo Igreja Verde
        \App\Models\Tarefa::create([
            'entidade_id' => $nucleos['igreja_verde']->id,
            'titulo' => 'Preparar TLC Igreja Verde',
            'descricao' => 'Organizar todos os detalhes do TLC Igreja Verde de abril',
            'prioridade' => 'alta',
            'status' => 'em_andamento',
            'data_limite' => '2026-04-01',
            'responsavel_user_id' => $userNucleoIgrejaVerde->id,
        ]);

        \App\Models\Tarefa::create([
            'entidade_id' => $nucleos['igreja_verde']->id,
            'titulo' => 'Confirmar local para reunião',
            'descricao' => 'Confirmar reserva da sala para reunião de março',
            'prioridade' => 'media',
            'status' => 'concluida',
            'data_limite' => '2026-03-01',
            'responsavel_user_id' => $userNucleoIgrejaVerde->id,
        ]);

        // Tarefas Núcleo Santa Paulina
        \App\Models\Tarefa::create([
            'entidade_id' => $nucleos['santa_paulina']->id,
            'titulo' => 'Revisar repertório musical',
            'descricao' => 'Revisar e preparar repertório para o TLC',
            'prioridade' => 'alta',
            'status' => 'em_andamento',
            'data_limite' => '2026-03-25',
            'responsavel_user_id' => $userNucleoSantaPaulina->id,
        ]);

        \App\Models\Tarefa::create([
            'entidade_id' => $nucleos['santa_paulina']->id,
            'titulo' => 'Comprar material de limpeza',
            'descricao' => 'Repor material de limpeza do espaço',
            'prioridade' => 'baixa',
            'status' => 'pendente',
            'data_limite' => '2026-03-20',
            'responsavel_user_id' => $userNucleoSantaPaulina->id,
        ]);

        \App\Models\Tarefa::create([
            'entidade_id' => $nucleos['santa_paulina']->id,
            'titulo' => 'Enviar lista de participantes',
            'descricao' => 'Enviar lista de participantes para o TLC',
            'prioridade' => 'alta',
            'status' => 'pendente',
            'data_limite' => '2026-04-10',
            'responsavel_user_id' => $userNucleoSantaPaulina->id,
        ]);

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
        $this->command->info('  Núcleo São José: nucleo@saojose.com');
        $this->command->info('  Núcleo Ap Miriam: nucleo@apmiriam.com');
        $this->command->info('  Núcleo Cidinha: nucleo@cidinha.com');
        $this->command->info('  Núcleo São Bernardo: nucleo@saobernardo.com');
        $this->command->info('  Núcleo Borromeu: nucleo@borromeu.com');
        $this->command->info('  Núcleo Rosário: nucleo@rosario.com');
        $this->command->info('  Núcleo Santa Clara: nucleo@santaclara.com');
        $this->command->info('  Núcleo Rainha: nucleo@rainha.com');
        $this->command->info('  Núcleo São Bento: nucleo@saobento.com');
        $this->command->info('  Núcleo Santa Terezinha: nucleo@santaterezinha.com');
        $this->command->info('  Núcleo Ap Grajau: nucleo@apgrajau.com');
        $this->command->info('  Núcleo Consolação: nucleo@consolacao.com');
        $this->command->info('  Núcleo Santa Rita: nucleo@santarita.com');
        $this->command->info('  Núcleo TLC de Pais: nucleo@tlcdepais.com');
        $this->command->info('  Núcleo Rocio: nucleo@rocio.com');
        $this->command->info('  Núcleo Missionaria (Inativo): nucleo@missionaria.com');
        $this->command->info('  Núcleo São João Paulo II (Inativo): nucleo@saojoaopauloii.com');
        $this->command->info('  Núcleo Salette (Inativo): nucleo@salette.com');
        $this->command->info('  Núcleo Santuário (Campo Limpo): nucleo@santuariosantaterezinha.com');
        $this->command->info('');
        $this->command->info('📍 Dioceses:');
        $this->command->info('  • Diocese de Santo Amaro');
        $this->command->info('  • Diocese de Campo Limpo');
        $this->command->info('  • Diocese de Santos');
        $this->command->info('');
        $this->command->info('🏛️ Núcleos:');
        $this->command->info('  Santo Amaro (Ativos):');
        $this->command->info('    • Santa Paulina');
        $this->command->info('    • Igreja Verde');
        $this->command->info('    • Ideal');
        $this->command->info('    • São José');
        $this->command->info('    • Ap Miriam');
        $this->command->info('    • Cidinha');
        $this->command->info('    • São Bernardo');
        $this->command->info('    • Borromeu');
        $this->command->info('    • Rosário');
        $this->command->info('    • Santa Clara');
        $this->command->info('    • Rainha');
        $this->command->info('    • São Bento');
        $this->command->info('    • Santa Terezinha');
        $this->command->info('    • Ap Grajau');
        $this->command->info('    • Consolação');
        $this->command->info('    • Santa Rita');
        $this->command->info('    • TLC de Pais');
        $this->command->info('    • Rocio');
        $this->command->info('  Santo Amaro (Inativos):');
        $this->command->info('    • Missionaria');
        $this->command->info('    • São João Paulo II');
        $this->command->info('    • Salette');
        $this->command->info('  Campo Limpo:');
        $this->command->info('    • Santuário Santa Terezinha');
        $this->command->info('');
        $this->command->info('📚 Secretarias:');
        $this->command->info('  • Música, Apoio, Espiritualidade e Formação, Eventos, Intercessão');
        $this->command->info('');
        $this->command->info('👥 Dirigentes (Total: 45):');
        $this->command->info('  Originais:');
        $this->command->info('    • Fernando (Coord. Música, part. Espiritualidade)');
        $this->command->info('    • Julianne (part. Igreja Verde, Eventos, Espiritualidade)');
        $this->command->info('    • Ygor (Coord. Santa Paulina, part. Apoio)');
        $this->command->info('    • Bruno (part. Igreja Verde, Coord. Diocese Santo Amaro, part. Eventos)');
        $this->command->info('  Secretarias (Apoio/Eventos/Intercessão):');
        $this->command->info('    • Cleide (Coord. Rocio, Coord. Diocesana, part. Espiritualidade)');
        $this->command->info('    • Fernanda (Coord. Igreja Verde, Coord. Diocesana, part. Espiritualidade)');
        $this->command->info('    • Ricardinho (Ideal, part. Espiritualidade)');
        $this->command->info('    • Alan Lira (Rainha, Coord. Música, part. Espiritualidade)');
        $this->command->info('    • Giovana (Coord. Igreja Verde, Coord. Eventos, part. Espiritualidade)');
        $this->command->info('    • Karina (Rainha, part. Música, Espiritualidade)');
        $this->command->info('    • Marquinhos (Coord. Santa Terezinha, Coord. Intercessão, part. Espiritualidade)');
        $this->command->info('    • William (Santa Paulina, part. Espiritualidade)');
        $this->command->info('    • Sabrina (Coord. Santa Terezinha, Coord. Intercessão, part. Espiritualidade)');
        $this->command->info('    • Washington (Coord. Ap Grajau, Coord. Apoio, part. Espiritualidade)');
        $this->command->info('    • Aline (Santa Rita, part. Intercessão, Espiritualidade)');
        $this->command->info('    • Erica (Cidinha, part. Intercessão, Espiritualidade)');
        $this->command->info('    • Jessica (Consolação, part. Espiritualidade)');
        $this->command->info('    • Jefferson (Rocio, part. Eventos, Espiritualidade)');
        $this->command->info('    • Karine (Santa Rita, part. Música, Espiritualidade)');
        $this->command->info('    • Núbia (Consolação, part. Música, Espiritualidade)');
        $this->command->info('  Coordenadores de Núcleos:');
        $this->command->info('    • Ana Clara (Coord. Rainha, part. Música, Espiritualidade)');
        $this->command->info('    • Ana Lucia (Coord. Borromeu, part. Música, Espiritualidade)');
        $this->command->info('    • Ana Carvalho (Coord. Cidinha, part. Espiritualidade)');
        $this->command->info('    • Daniel (Coord. Santa Paulina, part. Música, Espiritualidade)');
        $this->command->info('    • Dhafny (Coord. Ap Miriam, part. Música, Espiritualidade)');
        $this->command->info('    • Dyeimes (Coord. Rocio, part. Espiritualidade)');
        $this->command->info('    • Gabriel Martins (Coord. Santa Terezinha, part. Música, Espiritualidade)');
        $this->command->info('    • Aline (Coord. Santa Clara, part. Espiritualidade)');
        $this->command->info('    • Gabriel Schoene (Coord. Igreja Verde, part. Eventos, Espiritualidade)');
        $this->command->info('    • Leticia Rocha (Coord. Igreja Verde, part. Eventos, Espiritualidade)');
        $this->command->info('    • Gabriel Mariano (Coord. Santa Paulina, part. Música, Espiritualidade)');
        $this->command->info('    • Isabelle (Coord. Santa Rita, part. Espiritualidade)');
        $this->command->info('    • James (Coord. Santa Terezinha, part. Espiritualidade)');
        $this->command->info('    • Jefferson S (Coord. Rocio, part. Música, Espiritualidade)');
        $this->command->info('    • João Pedro (Coord. Consolação, part. Espiritualidade)');
        $this->command->info('    • Junior (Coord. Ap Miriam, part. Música, Espiritualidade)');
        $this->command->info('    • Michele (Coord. Cidinha, part. Espiritualidade)');
        $this->command->info('    • Raquel (Coord. Ap Grajau, part. Espiritualidade)');
        $this->command->info('    • Thalita (Coord. São Bento, part. Espiritualidade)');
        $this->command->info('    • Tio Ricardo (Coord. TLC de Pais, part. Espiritualidade)');
        $this->command->info('    • Vithoria Ramalho (Coord. Cidinha, part. Música, Espiritualidade)');
        $this->command->info('    • Viviane (Coord. São Bento, part. Espiritualidade)');
        $this->command->info('    • Adailton (Coord. Rocio, part. Espiritualidade)');
        $this->command->info('    • Gil (Coord. São José, part. Espiritualidade)');
        $this->command->info('    • Nathan (Coord. São José, part. Espiritualidade)');
        $this->command->info('    • Ricardo (Coord. Rosario, part. Espiritualidade)');
        $this->command->info('  Coordenadores de Secretarias (não coord. núcleo):');
        $this->command->info('    • Geovanna (part. Rocio, Coord. Música, part. Espiritualidade)');
        $this->command->info('    • Messias (part. Ap Grajau, Coord. Secretaria Apoio (Diocese), part. Espiritualidade)');
        $this->command->info('');
        $this->command->info('📊 Dados Financeiros: Movimentações em todas as dioceses e núcleos');
        $this->command->info('📅 Eventos: 7 eventos distribuídos entre dioceses, núcleos e secretarias');
    }
}
