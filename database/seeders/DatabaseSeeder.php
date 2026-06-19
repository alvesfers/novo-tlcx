<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            InitialDataSeeder::class,
            FinanceiroSeeder::class,
            AlmoxarifadoSeeder::class,
            TarefaSeeder::class,
            DocumentoSeeder::class,
            CasasDeRetiroSeeder::class,
            HabilidadeSeeder::class,
            // Fase 7: Sistema de Eventos Expandido
            FuncaoDirigentSeeder::class,
            FornecedorCamisetaSeeder::class,
            FormaPagamentoSeeder::class,
            BarzinhoSeeder::class,
            EventoValorSeeder::class,
            EventosTLCSeeder::class,
            DirigenteEventoFuncaoSeeder::class,
        ]);
    }
}
