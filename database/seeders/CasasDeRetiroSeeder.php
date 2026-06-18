<?php

namespace Database\Seeders;

use App\Models\CasasDeRetiro;
use App\Models\QuartosCasasDeRetiro;
use Illuminate\Database\Seeder;

class CasasDeRetiroSeeder extends Seeder
{
    public function run(): void
    {
        // 1. TABOR
        $tabor = CasasDeRetiro::create([
            'nome_casa' => 'Tabor',
            'endereco' => 'Local de Retiro Tabor',
            'valor_estimado' => 5000.00,
            'acessibilidade' => true,
            'ativa' => true,
            'capacidade' => 120,
        ]);

        $quartos_tabor = ['1A', '1B', '2A', '2B', '3A', '3B', '4A', '4B', '5', '6', '7', '8', '9'];
        $vagas_tabor = [4, 4, 4, 4, 4, 4, 4, 4, 8, 8, 8, 8, 6];

        foreach ($quartos_tabor as $idx => $numero) {
            QuartosCasasDeRetiro::create([
                'id_casa' => $tabor->id_casa,
                'numero_quarto' => $numero,
                'vagas' => $vagas_tabor[$idx],
                'cama' => 2,
                'beliche' => 1,
                'banheiros' => 1,
                'chuveiros' => 1,
                'acessibilidade' => false,
            ]);
        }

        // 2. CAJULÁ
        $cajula = CasasDeRetiro::create([
            'nome_casa' => 'Cajulá',
            'endereco' => 'Local de Retiro Cajulá',
            'valor_estimado' => 6500.00,
            'acessibilidade' => true,
            'ativa' => true,
            'capacidade' => 150,
        ]);

        // Marcador: 1A, 1B, 2A, 2B
        $quartos_marcador = ['M-1A', 'M-1B', 'M-2A', 'M-2B'];
        foreach ($quartos_marcador as $numero) {
            QuartosCasasDeRetiro::create([
                'id_casa' => $cajula->id_casa,
                'numero_quarto' => $numero,
                'vagas' => 4,
                'cama' => 2,
                'beliche' => 0,
                'banheiros' => 1,
                'chuveiros' => 1,
                'acessibilidade' => false,
            ]);
        }

        // Feminino: 14A, 14B, 13A, 13B
        $quartos_feminino = ['F-13A', 'F-13B', 'F-14A', 'F-14B'];
        foreach ($quartos_feminino as $numero) {
            QuartosCasasDeRetiro::create([
                'id_casa' => $cajula->id_casa,
                'numero_quarto' => $numero,
                'vagas' => 4,
                'cama' => 2,
                'beliche' => 0,
                'banheiros' => 1,
                'chuveiros' => 1,
                'acessibilidade' => false,
            ]);
        }

        // Casa 2: 1-23
        for ($i = 1; $i <= 23; $i++) {
            QuartosCasasDeRetiro::create([
                'id_casa' => $cajula->id_casa,
                'numero_quarto' => "C2-{$i}",
                'vagas' => 3,
                'cama' => 1,
                'beliche' => 1,
                'banheiros' => 1,
                'chuveiros' => 1,
                'acessibilidade' => $i === 1,
            ]);
        }

        // 3. CASA DA JUVENTUDE
        $casa_juventude = CasasDeRetiro::create([
            'nome_casa' => 'Casa da Juventude',
            'endereco' => 'Local de Retiro Casa da Juventude',
            'valor_estimado' => 7200.00,
            'acessibilidade' => true,
            'ativa' => true,
            'capacidade' => 180,
        ]);

        // Ala C - Piso 1
        for ($i = 1; $i <= 3; $i++) {
            QuartosCasasDeRetiro::create([
                'id_casa' => $casa_juventude->id_casa,
                'numero_quarto' => "C1-P1-{$i}",
                'vagas' => 6,
                'cama' => 3,
                'beliche' => 0,
                'banheiros' => 1,
                'chuveiros' => 1,
                'acessibilidade' => false,
            ]);
        }

        // Ala C - Piso 2
        for ($i = 1; $i <= 3; $i++) {
            QuartosCasasDeRetiro::create([
                'id_casa' => $casa_juventude->id_casa,
                'numero_quarto' => "C1-P2-{$i}",
                'vagas' => 6,
                'cama' => 3,
                'beliche' => 0,
                'banheiros' => 1,
                'chuveiros' => 1,
                'acessibilidade' => false,
            ]);
        }

        // Ala C - Piso 3
        for ($i = 1; $i <= 4; $i++) {
            QuartosCasasDeRetiro::create([
                'id_casa' => $casa_juventude->id_casa,
                'numero_quarto' => "C1-P3-{$i}",
                'vagas' => 6,
                'cama' => 3,
                'beliche' => 0,
                'banheiros' => 1,
                'chuveiros' => 1,
                'acessibilidade' => false,
            ]);
        }

        // Ala C - Piso 4
        for ($i = 1; $i <= 4; $i++) {
            QuartosCasasDeRetiro::create([
                'id_casa' => $casa_juventude->id_casa,
                'numero_quarto' => "C1-P4-{$i}",
                'vagas' => 6,
                'cama' => 3,
                'beliche' => 0,
                'banheiros' => 1,
                'chuveiros' => 1,
                'acessibilidade' => false,
            ]);
        }

        // Quarto Especial
        QuartosCasasDeRetiro::create([
            'id_casa' => $casa_juventude->id_casa,
            'numero_quarto' => 'C1-Especial',
            'vagas' => 3,
            'cama' => 1,
            'beliche' => 1,
            'banheiros' => 2,
            'chuveiros' => 2,
            'acessibilidade' => true,
        ]);

        // 4. RECANTO TAGASTI
        $recanto = CasasDeRetiro::create([
            'nome_casa' => 'Recanto Tagasti',
            'endereco' => 'Local de Retiro Recanto Tagasti',
            'valor_estimado' => 4500.00,
            'acessibilidade' => true,
            'ativa' => true,
            'capacidade' => 80,
        ]);

        // Quartos com banheiros
        $quartos_recanto = [1, 2, 3, 4];
        foreach ($quartos_recanto as $numero) {
            QuartosCasasDeRetiro::create([
                'id_casa' => $recanto->id_casa,
                'numero_quarto' => "R-{$numero}",
                'vagas' => 5,
                'cama' => 2,
                'beliche' => 1,
                'banheiros' => 1,
                'chuveiros' => 1,
                'acessibilidade' => $numero === 1,
            ]);
        }

        // Quartos adicionais
        for ($i = 5; $i <= 8; $i++) {
            QuartosCasasDeRetiro::create([
                'id_casa' => $recanto->id_casa,
                'numero_quarto' => "R-{$i}",
                'vagas' => 4,
                'cama' => 2,
                'beliche' => 0,
                'banheiros' => 1,
                'chuveiros' => 1,
                'acessibilidade' => false,
            ]);
        }
    }
}
