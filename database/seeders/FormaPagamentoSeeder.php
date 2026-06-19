<?php

namespace Database\Seeders;

use App\Models\FormaPagamento;
use App\Models\Entidade;
use Illuminate\Database\Seeder;

class FormaPagamentoSeeder extends Seeder
{
    /**
     * Seed the application's database with payment methods.
     */
    public function run(): void
    {
        // Máquinas específicas para Diocese de Santo Amaro
        $dioceseSantoAmaro = Entidade::where('tipo_entidade', 'diocese')
            ->where('nome', 'Diocese de Santo Amaro')
            ->first();

        if ($dioceseSantoAmaro) {
            $this->criarMaquinaForDiocese($dioceseSantoAmaro);
        }

        // Máquina para um núcleo (Santa Paulina)
        $nucleoSantaPaulina = Entidade::where('tipo_entidade', 'nucleo')
            ->where('nome', 'Núcleo Santa Paulina')
            ->first();

        if ($nucleoSantaPaulina) {
            $this->criarMaquinaForNucleo($nucleoSantaPaulina);
        }

        // Obtém as dioceses e núcleos existentes para formas de pagamento básicas
        $entidades = Entidade::whereIn('tipo_entidade', ['diocese', 'nucleo', 'secretaria'])
            ->limit(5)
            ->get();

        foreach ($entidades as $entidade) {
            // Pagamento em dinheiro (sem taxa)
            FormaPagamento::updateOrCreate(
                [
                    'entidade_id' => $entidade->id,
                    'nome' => 'Dinheiro',
                ],
                [
                    'tipo' => 'dinheiro',
                    'taxa_credito' => 0,
                    'taxa_debito' => 0,
                    'taxa_pix' => 0,
                    'ativa' => true,
                    'observacao' => 'Pagamento em dinheiro - sem taxa de máquina',
                ]
            );

            // Máquina de crédito genérica
            FormaPagamento::updateOrCreate(
                [
                    'entidade_id' => $entidade->id,
                    'nome' => 'Maquininha Crédito',
                ],
                [
                    'tipo' => 'cartao_credito',
                    'taxa_credito' => 1.10,
                    'taxa_debito' => 0,
                    'taxa_pix' => 0,
                    'ativa' => true,
                    'observacao' => 'Máquina de cartão crédito - taxa de 1.10%',
                ]
            );

            // Máquina de débito genérica
            FormaPagamento::updateOrCreate(
                [
                    'entidade_id' => $entidade->id,
                    'nome' => 'Maquininha Débito',
                ],
                [
                    'tipo' => 'cartao_debito',
                    'taxa_credito' => 0,
                    'taxa_debito' => 0.50,
                    'taxa_pix' => 0,
                    'ativa' => true,
                    'observacao' => 'Máquina de cartão débito - taxa de 0.50%',
                ]
            );

            // PIX (sem taxa)
            FormaPagamento::updateOrCreate(
                [
                    'entidade_id' => $entidade->id,
                    'nome' => 'PIX',
                ],
                [
                    'tipo' => 'pix',
                    'taxa_credito' => 0,
                    'taxa_debito' => 0,
                    'taxa_pix' => 0,
                    'ativa' => true,
                    'observacao' => 'Transferência via PIX - sem taxa',
                ]
            );

            // Máquina combo (crédito + débito + PIX)
            if ($entidade->tipo === 'diocese') {
                FormaPagamento::updateOrCreate(
                    [
                        'entidade_id' => $entidade->id,
                        'nome' => 'Maquininha Ton',
                    ],
                    [
                        'tipo' => 'cartao_credito',
                        'taxa_credito' => 1.49,
                        'taxa_debito' => 0.50,
                        'taxa_pix' => 0,
                        'ativa' => true,
                        'observacao' => 'Máquina de pagamento 3 em 1 - crédito 1.49%, débito 0.50%, PIX sem taxa',
                    ]
                );

                // Vale prepago (pré-autorizado)
                FormaPagamento::updateOrCreate(
                    [
                        'entidade_id' => $entidade->id,
                        'nome' => 'Vale / Antecipação',
                    ],
                    [
                        'tipo' => 'outra',
                        'taxa_credito' => 0,
                        'taxa_debito' => 0,
                        'taxa_pix' => 0,
                        'ativa' => true,
                        'observacao' => 'Pagamento com vale ou antecipação - sem taxa',
                    ]
                );
            }
        }
    }

    private function criarMaquinaForDiocese($entidade): void
    {
        // Máquina Ton para Diocese
        FormaPagamento::updateOrCreate(
            [
                'entidade_id' => $entidade->id,
                'nome' => 'Maquininha Ton - Diocese Santo Amaro',
            ],
            [
                'tipo' => 'cartao_credito',
                'taxa_credito' => 1.49,
                'taxa_debito' => 0.50,
                'taxa_pix' => 0,
                'ativa' => true,
                'observacao' => 'Máquina de pagamento 3 em 1 para Diocese - crédito 1.49%, débito 0.50%, PIX sem taxa',
            ]
        );
    }

    private function criarMaquinaForNucleo($entidade): void
    {
        // Máquina para Núcleo
        FormaPagamento::updateOrCreate(
            [
                'entidade_id' => $entidade->id,
                'nome' => 'Maquininha Crédito - Núcleo',
            ],
            [
                'tipo' => 'cartao_credito',
                'taxa_credito' => 1.10,
                'taxa_debito' => 0,
                'taxa_pix' => 0,
                'ativa' => true,
                'observacao' => 'Máquina de cartão crédito para Núcleo Santa Paulina - taxa de 1.10%',
            ]
        );
    }
}
