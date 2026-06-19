<?php

namespace Database\Seeders;

use App\Models\EventoValor;
use App\Models\Evento;
use Illuminate\Database\Seeder;

class EventoValorSeeder extends Seeder
{
    /**
     * Seed the application's database with event values/prices.
     */
    public function run(): void
    {
        // Busca o evento 19° TLC Santa Paulina especificamente
        $evento19TLC = Evento::where('nome', '19º TLC Santa Paulina')
            ->orWhere('nome', '19 TLC Santa Paulina')
            ->first();

        if ($evento19TLC) {
            $this->criarValoresEvento($evento19TLC);
        }

        // Também processa os primeiros 3 eventos como antes (para compatibilidade)
        $eventos = Evento::limit(3)->get();

        foreach ($eventos as $evento) {
            if ($evento19TLC && $evento->id === $evento19TLC->id) {
                continue; // Já processamos
            }
            $this->criarValoresEvento($evento);
        }
    }

    private function criarValoresEvento($evento): void
    {
        // Preços de inscrição
        EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'inscricao_dirigente_interna',
                ],
                [
                    'valor' => 80.00,
                    'descricao' => 'Inscrição de dirigente com função interna',
                    'ativo' => true,
                ]
            );

            EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'inscricao_dirigente_externa',
                ],
                [
                    'valor' => 120.00,
                    'descricao' => 'Inscrição de dirigente com função externa',
                    'ativo' => true,
                ]
            );

            EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'inscricao_cursista',
                ],
                [
                    'valor' => 150.00,
                    'descricao' => 'Inscrição de cursista/participante externo',
                    'ativo' => true,
                ]
            );

            // Camisetas Infantil
            EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'camiseta_infantil_p',
                ],
                [
                    'valor' => 25.00,
                    'descricao' => 'Camiseta Infantil - Tamanho P',
                    'ativo' => true,
                ]
            );

            EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'camiseta_infantil_m',
                ],
                [
                    'valor' => 25.00,
                    'descricao' => 'Camiseta Infantil - Tamanho M',
                    'ativo' => true,
                ]
            );

            EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'camiseta_infantil_g',
                ],
                [
                    'valor' => 25.00,
                    'descricao' => 'Camiseta Infantil - Tamanho G',
                    'ativo' => true,
                ]
            );

            // Camisetas Normal
            EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'camiseta_normal_p',
                ],
                [
                    'valor' => 30.00,
                    'descricao' => 'Camiseta Normal - Tamanho P',
                    'ativo' => true,
                ]
            );

            EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'camiseta_normal_m',
                ],
                [
                    'valor' => 30.00,
                    'descricao' => 'Camiseta Normal - Tamanho M',
                    'ativo' => true,
                ]
            );

            EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'camiseta_normal_g',
                ],
                [
                    'valor' => 30.00,
                    'descricao' => 'Camiseta Normal - Tamanho G',
                    'ativo' => true,
                ]
            );

            EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'camiseta_normal_gg',
                ],
                [
                    'valor' => 30.00,
                    'descricao' => 'Camiseta Normal - Tamanho GG',
                    'ativo' => true,
                ]
            );

            EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'camiseta_normal_ggg',
                ],
                [
                    'valor' => 35.00,
                    'descricao' => 'Camiseta Normal - Tamanho GGG',
                    'ativo' => true,
                ]
            );

            // Camisetas Plus
            EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'camiseta_plus_p',
                ],
                [
                    'valor' => 45.00,
                    'descricao' => 'Camiseta Plus - Tamanho P',
                    'ativo' => true,
                ]
            );

            EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'camiseta_plus_m',
                ],
                [
                    'valor' => 45.00,
                    'descricao' => 'Camiseta Plus - Tamanho M',
                    'ativo' => true,
                ]
            );

            EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'camiseta_plus_g',
                ],
                [
                    'valor' => 50.00,
                    'descricao' => 'Camiseta Plus - Tamanho G',
                    'ativo' => true,
                ]
            );

            EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'camiseta_plus_gg',
                ],
                [
                    'valor' => 50.00,
                    'descricao' => 'Camiseta Plus - Tamanho GG',
                    'ativo' => true,
                ]
            );

            EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'camiseta_plus_ggg',
                ],
                [
                    'valor' => 55.00,
                    'descricao' => 'Camiseta Plus - Tamanho GGG',
                    'ativo' => true,
                ]
            );

            // Camisetas Babylook
            EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'camiseta_babylook_p',
                ],
                [
                    'valor' => 28.00,
                    'descricao' => 'Camiseta Babylook - Tamanho P',
                    'ativo' => true,
                ]
            );

            EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'camiseta_babylook_m',
                ],
                [
                    'valor' => 28.00,
                    'descricao' => 'Camiseta Babylook - Tamanho M',
                    'ativo' => true,
                ]
            );

            EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'camiseta_babylook_g',
                ],
                [
                    'valor' => 28.00,
                    'descricao' => 'Camiseta Babylook - Tamanho G',
                    'ativo' => true,
                ]
            );

            // Camisetas Oversized
            EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'camiseta_oversized_gg',
                ],
                [
                    'valor' => 40.00,
                    'descricao' => 'Camiseta Oversized - Tamanho GG',
                    'ativo' => true,
                ]
            );

            EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'camiseta_oversized_ggg',
                ],
                [
                    'valor' => 40.00,
                    'descricao' => 'Camiseta Oversized - Tamanho GGG',
                    'ativo' => true,
                ]
            );

            // Combos de Inscrição + Camiseta
            EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'combo_inscricao_camiseta_infantil',
                ],
                [
                    'valor' => 100.00,
                    'descricao' => 'Combo: Inscrição + Camiseta Infantil (economia de R$ 10,00)',
                    'ativo' => true,
                ]
            );

            EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'combo_inscricao_camiseta_normal',
                ],
                [
                    'valor' => 105.00,
                    'descricao' => 'Combo: Inscrição + Camiseta Normal (economia de R$ 5,00)',
                    'ativo' => true,
                ]
            );

            EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'combo_inscricao_camiseta_plus',
                ],
                [
                    'valor' => 125.00,
                    'descricao' => 'Combo: Inscrição + Camiseta Plus (economia de R$ 5,00)',
                    'ativo' => true,
                ]
            );

            EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'combo_inscricao_camiseta_babylook',
                ],
                [
                    'valor' => 105.00,
                    'descricao' => 'Combo: Inscrição + Camiseta Babylook (economia de R$ 3,00)',
                    'ativo' => true,
                ]
            );

            EventoValor::updateOrCreate(
                [
                    'evento_id' => $evento->id,
                    'tipo_valor' => 'combo_inscricao_camiseta_oversized',
                ],
                [
                    'valor' => 115.00,
                    'descricao' => 'Combo: Inscrição + Camiseta Oversized (economia de R$ 5,00)',
                    'ativo' => true,
                ]
            );
    }
}
