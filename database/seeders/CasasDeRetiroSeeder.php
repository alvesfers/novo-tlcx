<?php

namespace Database\Seeders;

use App\Models\AlasRetiro;
use App\Models\CasasDeRetiro;
use App\Models\QuartosCasasDeRetiro;
use Illuminate\Database\Seeder;

class CasasDeRetiroSeeder extends Seeder
{
    public function run(): void
    {
        // 1. MONTE TABOR
        $tabor = CasasDeRetiro::create([
            'nome_casa' => 'Monte Tabor',
            'endereco' => 'Local de Retiro Monte Tabor',
            'valor_estimado' => 5000.00,
            'acessibilidade' => true,
            'ativa' => true,
            'capacidade' => 150,
        ]);

        $quartos_tabor = [
            ['numero' => '1A', 'vagas' => 8],
            ['numero' => '1B', 'vagas' => 8],
            ['numero' => '2A', 'vagas' => 8],
            ['numero' => '2B', 'vagas' => 8],
            ['numero' => '3A', 'vagas' => 8],
            ['numero' => '3B', 'vagas' => 8],
            ['numero' => '4', 'vagas' => 16],
            ['numero' => '5', 'vagas' => 16],
            ['numero' => '6', 'vagas' => 16],
            ['numero' => '7A', 'vagas' => 8],
            ['numero' => '7B', 'vagas' => 8],
            ['numero' => '8', 'vagas' => 8],
            ['numero' => '8 Interno', 'vagas' => 2],
            ['numero' => '9 (Pago à parte)', 'vagas' => 16],
        ];

        foreach ($quartos_tabor as $quarto) {
            QuartosCasasDeRetiro::create([
                'id_casa' => $tabor->id_casa,
                'id_ala' => null,
                'numero_quarto' => $quarto['numero'],
                'vagas' => $quarto['vagas'],
                'banheiros' => null,
                'acessibilidade' => false,
            ]);
        }

        // 2. SÃO PAULO APÓSTOLO
        $sao_paulo = CasasDeRetiro::create([
            'nome_casa' => 'São Paulo Apóstolo',
            'endereco' => 'Local de Retiro São Paulo Apóstolo',
            'valor_estimado' => 8000.00,
            'acessibilidade' => true,
            'ativa' => true,
            'capacidade' => 300,
        ]);

        // ALA 6 (Frente da Casa)
        $ala6 = AlasRetiro::create([
            'id_casa' => $sao_paulo->id_casa,
            'nome_ala' => 'ALA 6',
            'descricao' => 'Frente da Casa',
        ]);

        for ($i = 1; $i <= 10; $i++) {
            QuartosCasasDeRetiro::create([
                'id_casa' => $sao_paulo->id_casa,
                'id_ala' => $ala6->id_ala,
                'numero_quarto' => $i,
                'vagas' => 2,
                'banheiros' => null,
                'acessibilidade' => false,
            ]);
        }

        // ALA 5
        $ala5 = AlasRetiro::create([
            'id_casa' => $sao_paulo->id_casa,
            'nome_ala' => 'ALA 5',
            'descricao' => null,
        ]);

        for ($i = 11; $i <= 20; $i++) {
            QuartosCasasDeRetiro::create([
                'id_casa' => $sao_paulo->id_casa,
                'id_ala' => $ala5->id_ala,
                'numero_quarto' => $i,
                'vagas' => 3,
                'banheiros' => null,
                'acessibilidade' => false,
            ]);
        }

        // ALA 4
        $ala4 = AlasRetiro::create([
            'id_casa' => $sao_paulo->id_casa,
            'nome_ala' => 'ALA 4',
            'descricao' => null,
        ]);

        for ($i = 21; $i <= 30; $i++) {
            QuartosCasasDeRetiro::create([
                'id_casa' => $sao_paulo->id_casa,
                'id_ala' => $ala4->id_ala,
                'numero_quarto' => $i,
                'vagas' => 3,
                'banheiros' => null,
                'acessibilidade' => false,
            ]);
        }

        // ALA 3 F (Feminino)
        $ala3f = AlasRetiro::create([
            'id_casa' => $sao_paulo->id_casa,
            'nome_ala' => 'ALA 3 F',
            'descricao' => 'Feminino',
        ]);

        for ($i = 31; $i <= 40; $i++) {
            QuartosCasasDeRetiro::create([
                'id_casa' => $sao_paulo->id_casa,
                'id_ala' => $ala3f->id_ala,
                'numero_quarto' => $i,
                'vagas' => 3,
                'banheiros' => null,
                'acessibilidade' => false,
            ]);
        }

        // ALA 2 F (Feminino)
        $ala2f = AlasRetiro::create([
            'id_casa' => $sao_paulo->id_casa,
            'nome_ala' => 'ALA 2 F',
            'descricao' => 'Feminino',
        ]);

        $quartos_ala2f = [41, 42, 43, 44, 46, 47];
        foreach ($quartos_ala2f as $numero) {
            QuartosCasasDeRetiro::create([
                'id_casa' => $sao_paulo->id_casa,
                'id_ala' => $ala2f->id_ala,
                'numero_quarto' => $numero,
                'vagas' => 5,
                'banheiros' => null,
                'acessibilidade' => false,
            ]);
        }

        // ALA 1 (Fundo da Casa)
        $ala1 = AlasRetiro::create([
            'id_casa' => $sao_paulo->id_casa,
            'nome_ala' => 'ALA 1',
            'descricao' => 'Fundo da Casa',
        ]);

        for ($i = 48; $i <= 52; $i++) {
            QuartosCasasDeRetiro::create([
                'id_casa' => $sao_paulo->id_casa,
                'id_ala' => $ala1->id_ala,
                'numero_quarto' => $i,
                'vagas' => 4,
                'banheiros' => null,
                'acessibilidade' => false,
            ]);
        }

        // 3. CAJULÁ
        $cajula = CasasDeRetiro::create([
            'nome_casa' => 'Cajulá',
            'endereco' => 'Local de Retiro Cajulá',
            'valor_estimado' => 6500.00,
            'acessibilidade' => true,
            'ativa' => true,
            'capacidade' => 200,
        ]);

        // ALA - CASA 1
        $ala_casa1 = AlasRetiro::create([
            'id_casa' => $cajula->id_casa,
            'nome_ala' => 'CASA 1',
            'descricao' => null,
        ]);

        $quartos_casa1 = [
            ['numero' => '1A', 'vagas' => 16],
            ['numero' => '1B', 'vagas' => 18],
            ['numero' => '2A', 'vagas' => 6],
            ['numero' => '2B', 'vagas' => 10],
            ['numero' => '14A', 'vagas' => 16],
            ['numero' => '14B', 'vagas' => 18],
            ['numero' => '13A', 'vagas' => 6],
            ['numero' => '13B', 'vagas' => 10],
        ];

        foreach ($quartos_casa1 as $quarto) {
            QuartosCasasDeRetiro::create([
                'id_casa' => $cajula->id_casa,
                'id_ala' => $ala_casa1->id_ala,
                'numero_quarto' => $quarto['numero'],
                'vagas' => $quarto['vagas'],
                'banheiros' => null,
                'acessibilidade' => false,
            ]);
        }

        // ALA - CASA 2 (Paga a parte)
        $ala_casa2 = AlasRetiro::create([
            'id_casa' => $cajula->id_casa,
            'nome_ala' => 'CASA 2',
            'descricao' => 'Paga a parte',
        ]);

        QuartosCasasDeRetiro::create([
            'id_casa' => $cajula->id_casa,
            'id_ala' => $ala_casa2->id_ala,
            'numero_quarto' => '23',
            'vagas' => 20,
            'banheiros' => null,
            'acessibilidade' => false,
        ]);
    }
}
