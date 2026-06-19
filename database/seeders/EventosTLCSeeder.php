<?php

namespace Database\Seeders;

use App\Enums\EscopoEvento;
use App\Enums\StatusEvento;
use App\Models\Evento;
use App\Models\Entidade;
use App\Models\TipoEvento;
use Illuminate\Database\Seeder;

class EventosTLCSeeder extends Seeder
{
    public function run(): void
    {
        $nucleos = Entidade::where('tipo_entidade', 'nucleo')->get()->keyBy('nome');
        $dioceses = Entidade::where('tipo_entidade', 'diocese')->get()->keyBy('nome');

        $tlcType = TipoEvento::where('nome', 'TLC')->first();
        $miniTlcType = TipoEvento::where('nome', 'Mini TLC')->first();

        if (!$tlcType || !$miniTlcType) {
            return;
        }

        $tlcTypeId = $tlcType->id;
        $miniTlcTypeId = $miniTlcType->id;

        // ===== SANTA PAULINA =====
        $this->createEventoIfNotExists(
            $nucleos['Núcleo Santa Paulina'] ?? null,
            $tlcTypeId,
            '19º TLC Santa Paulina',
            'Tema: Silencioso em meu esconderijo com Meu Pai Deus',
            '2026-05-22',
            '2026-05-24'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo Santa Paulina'] ?? null,
            $tlcTypeId,
            '18 TLC Santa Paulina',
            'Tema: Não vou mais tirar os meus olhos de Ti',
            '2025-10-31',
            '2025-11-02'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo Santa Paulina'] ?? null,
            $tlcTypeId,
            '17 TLC Santa Paulina',
            'Tema: Todas as coisas cooperam para o bem daqueles que amam a Deus',
            '2024-11-06',
            '2024-11-08'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo Santa Paulina'] ?? null,
            $tlcTypeId,
            '16 TLC Santa Paulina',
            'Tema: Novas Vestes',
            '2023-11-17',
            '2023-11-19'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo Santa Paulina'] ?? null,
            $tlcTypeId,
            '15 TLC Santa Paulina',
            'Tema: Dai-me um coração semelhante ao Teu',
            '2022-11-25',
            '2022-11-27'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo Santa Paulina'] ?? null,
            $tlcTypeId,
            '14 TLC Santa Paulina',
            'Tema: Livre para amar',
            '2019-11-22',
            '2019-11-24'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo Santa Paulina'] ?? null,
            $tlcTypeId,
            '13 TLC Santa Paulina',
            'Tema: Eu prefiro o paraiso',
            '2018-11-23',
            '2018-11-08'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo Santa Paulina'] ?? null,
            $tlcTypeId,
            '12 TLC Santa Paulina',
            'Tema: O poderoso fez em mim Maravilhas',
            '2017-11-03',
            '2017-11-05'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo Santa Paulina'] ?? null,
            $tlcTypeId,
            '11 TLC Santa Paulina',
            'Tema: Eu não Vim chamar os justos, mas sim os pecadores',
            '2016-11-04',
            '2016-11-06'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo Santa Paulina'] ?? null,
            $tlcTypeId,
            '10 TLC Santa Paulina',
            '',
            '2015-11-06',
            '2015-11-08'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo Santa Paulina'] ?? null,
            $tlcTypeId,
            '09 TLC Santa Paulina',
            '',
            '2014-11-07',
            '2014-11-09'
        );

        // ===== CIDINHA =====
        $this->createEventoIfNotExists(
            $nucleos['Núcleo Cidinha'] ?? null,
            $tlcTypeId,
            '9.6 TLC Cidinha',
            'Tema: Curados para Amar',
            '2023-06-02',
            '2023-06-04'
        );

        // ===== AP GRAJAU =====
        $this->createEventoIfNotExists(
            $nucleos['Núcleo Ap Grajau'] ?? null,
            $tlcTypeId,
            '18° TLC Ap Grajau',
            'Tema: Luto contra mim pois desejo a Ti',
            '2022-04-22',
            '2022-04-24'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo Ap Grajau'] ?? null,
            $tlcTypeId,
            '19° TLC Ap Grajau',
            'Tema: O meu coração deseja te encontrar como a terra seca anseia pela chuva',
            '2023-04-21',
            '2023-04-23'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo Ap Grajau'] ?? null,
            $tlcTypeId,
            '20° TLC Ap Grajau',
            'Tema: Pode morar, serei tua casa',
            '2024-04-24',
            '2024-04-26'
        );

        // ===== IGREJA VERDE =====
        $this->createEventoIfNotExists(
            $nucleos['Núcleo Igreja Verde'] ?? null,
            $tlcTypeId,
            '4° TLC Igreja Verde',
            'Tema: O teu olhar jamais se afastou de mim',
            '2022-07-15',
            '2022-07-17'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo Igreja Verde'] ?? null,
            $miniTlcTypeId,
            '4° Mini TLC Igreja Verde',
            'Tema: O mundo não é o meu lugar',
            '2022-10-14',
            '2022-10-16'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo Igreja Verde'] ?? null,
            $miniTlcTypeId,
            '5° Mini TLC Igreja Verde',
            'Tema: Eu seguirei, eu irei aonde fores Senhor',
            '2023-10-13',
            '2023-10-15'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo Igreja Verde'] ?? null,
            $tlcTypeId,
            '5° TLC Igreja Verde',
            'Tema: É ele quem te saciará',
            '2023-07-07',
            '2023-07-09'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo Igreja Verde'] ?? null,
            $tlcTypeId,
            '6° TLC Igreja Verde',
            'Tema: Jesus, se eu te olhar eu vou',
            '2024-05-03',
            '2024-05-05'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo Igreja Verde'] ?? null,
            $miniTlcTypeId,
            '6° Mini TLC Igreja Verde',
            'Tema: Jesus, eu só sei pensar em você',
            '2024-11-01',
            '2024-11-03'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo Igreja Verde'] ?? null,
            $miniTlcTypeId,
            '7° Mini TLC Igreja Verde',
            'Tema: Eu deixo tudo pela tua presença',
            '2025-11-07',
            '2025-11-09'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo Igreja Verde'] ?? null,
            $tlcTypeId,
            '7° TLC Igreja Verde',
            'Tema: Somos só eu e o Senhor',
            '2025-05-16',
            '2025-05-18'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo Igreja Verde'] ?? null,
            $tlcTypeId,
            '8° TLC Igreja Verde',
            'Tema: Me deixa passar a vida com você',
            '2026-04-17',
            '2026-04-19'
        );

        // ===== CONSOLAÇÃO =====
        $this->createEventoIfNotExists(
            $nucleos['Núcleo Consolação'] ?? null,
            $tlcTypeId,
            '2° TLC Consolação',
            'Tema: Deixa as noventa e nove só para me buscar'
        );

        // ===== SANTA CLARA =====
        $this->createEventoIfNotExists(
            $nucleos['Núcleo Santa Clara'] ?? null,
            $tlcTypeId,
            '3° TLC Santa Clara',
            'Tema: Seduziste-me senhor e eu me deixei seduzir'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo Santa Clara'] ?? null,
            $tlcTypeId,
            '3° TLC Santa Clara - EQR',
            'Tema: É tudo o que tenho, recebe o meu nada'
        );

        // ===== SANTA TEREZINHA =====
        $this->createEventoIfNotExists(
            $nucleos['Núcleo Santa Terezinha'] ?? null,
            $tlcTypeId,
            '7° TLC Santa Terezinha',
            'Tema: É tempo de voltar'
        );

        // ===== RAINHA =====
        $this->createEventoIfNotExists(
            $nucleos['Núcleo Rainha'] ?? null,
            $tlcTypeId,
            '7° TLC Rainha',
            'Tema: Que o teu plano em mim possa realizar sem limitações'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo Rainha'] ?? null,
            $tlcTypeId,
            '10° TLC Rainha',
            'Tema: Desperta e se achegue a quem quer te dar Amor',
            '2022-08-05',
            '2022-08-07'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo Rainha'] ?? null,
            $tlcTypeId,
            '11° TLC Rainha',
            'Tema: Senhor te encontrei, como te deixarei?',
            '2023-06-16',
            '2023-06-18'
        );

        // ===== SÃO JOSÉ =====
        $this->createEventoIfNotExists(
            $nucleos['Núcleo São José'] ?? null,
            $tlcTypeId,
            '21° TLC São Jose',
            'Tema: Vou recomeçar em ti um tempo novo'
        );

        // ===== TLC DE PAIS =====
        $this->createEventoIfNotExists(
            $nucleos['Núcleo TLC de Pais'] ?? null,
            $tlcTypeId,
            '15° TLC de Pais',
            'Tema: ?'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo TLC de Pais'] ?? null,
            $tlcTypeId,
            '17° TLC de Pais',
            'Tema: Eu e minha casa, serviremos ao Senhor'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo TLC de Pais'] ?? null,
            $tlcTypeId,
            '21° TLC de Pais',
            'Tema: Eu te levantarei'
        );

        // ===== SÃO BENTO =====
        $this->createEventoIfNotExists(
            $nucleos['Núcleo São Bento'] ?? null,
            $tlcTypeId,
            '5° TLC São Bento',
            'Tema: Não tenho nada a oferecer meu Senhor mas te doa a minha vida'
        );

        // ===== SANTA RITA =====
        $this->createEventoIfNotExists(
            $nucleos['Núcleo Santa Rita'] ?? null,
            $tlcTypeId,
            '5° TLC Santa Rita',
            'Tema: Nada temas, pois eu te resgato e te chamo pelo nome, és meu',
            '2025-05-02',
            '2025-05-04'
        );

        // ===== SÃO BERNARDO =====
        $this->createEventoIfNotExists(
            $nucleos['Núcleo São Bernardo'] ?? null,
            $miniTlcTypeId,
            '1° Mini TLC São Bernardo',
            'Tema: Aqui eu sou criança, embalado em teus braços papai'
        );

        $this->createEventoIfNotExists(
            $nucleos['Núcleo São Bernardo'] ?? null,
            $miniTlcTypeId,
            '2° Mini TLC São Bernardo',
            'Tema: Aqui eu sou criança, embalado em teus braços papai',
            '2026-06-05',
            '2026-06-07'
        );

        // ===== AP MIRIAM =====
        $this->createEventoIfNotExists(
            $nucleos['Núcleo Ap Miriam'] ?? null,
            $tlcTypeId,
            '10° TLC Ap Miriam',
            'Tema: Dá-me tuas vestes'
        );

        // ===== SÃO SEBASTIÃO (Inativo) =====
        $this->createEventoIfNotExists(
            $nucleos['Núcleo São Sebastião'] ?? null,
            $tlcTypeId,
            '1° TLC São Sebastião',
            'Tema: Se ninguém te adorar eu vou'
        );

        // ===== MEDALHA MILAGROSA (Inativo) =====
        $this->createEventoIfNotExists(
            $nucleos['Núcleo Medalha Milagrosa'] ?? null,
            $tlcTypeId,
            '1° TLC Medalha Milagrosa',
            'Tema: Maria, mãe do puro amor'
        );

        // ===== AREADO - TLC DE PAIS =====
        $this->createEventoIfNotExists(
            $dioceses['Diocese de Areado'] ?? null,
            $tlcTypeId,
            '1° TLC de Pais de Areado',
            'Tema: Tudo posso naquele que me fortalece'
        );

        // ===== SANTO ANDRÉ - SANTO ARNALDO =====
        $this->createEventoIfNotExists(
            $nucleos['Núcleo Santo Arnaldo'] ?? null,
            $tlcTypeId,
            '2° TLC Santo Arnaldo',
            'Tema: Eu estou aqui'
        );
    }

    private function createEventoIfNotExists(?Entidade $nucleo, int $tipoEventoId, string $nome, string $descricao = '', ?string $dataInicio = null, ?string $dataFim = null): void
    {
        if (!$nucleo) {
            return;
        }

        $exists = Evento::where('entidade_criadora_id', $nucleo->id)
            ->where('nome', $nome)
            ->exists();

        if ($exists) {
            return;
        }

        $startTime = $dataInicio ? $dataInicio . ' 08:00:00' : null;
        $endTime = $dataFim ? $dataFim . ' 18:00:00' : null;

        Evento::create([
            'tipo_evento_id' => $tipoEventoId,
            'entidade_criadora_id' => $nucleo->id,
            'nome' => $nome,
            'descricao' => $descricao,
            'tema' => $descricao,
            'data_inicio' => $startTime,
            'data_fim' => $endTime,
            'escopo' => EscopoEvento::Externos->value,
            'status' => StatusEvento::Publicado->value,
            'ativo' => true,
        ]);
    }
}
