<?php

namespace Database\Seeders;

use App\Models\FuncaoDirigente;
use Illuminate\Database\Seeder;

class FuncaoDirigentSeeder extends Seeder
{
    /**
     * Seed the application's database with dirigent functions.
     */
    public function run(): void
    {
        $funcoes = [
            [
                'nome' => 'Coordenador',
                'descricao' => 'Responsável pela coordenação do evento ou atividade',
                'tipo' => 'interna',
                'ativo' => true,
            ],
            [
                'nome' => 'Formador',
                'descricao' => 'Responsável pela formação e treinamento',
                'tipo' => 'interna',
                'ativo' => true,
            ],
            [
                'nome' => 'Palestrante',
                'descricao' => 'Faz palestra ou apresentação no evento',
                'tipo' => 'externa',
                'ativo' => true,
            ],
            [
                'nome' => 'Monitor',
                'descricao' => 'Auxilia na coordenação e acompanhamento',
                'tipo' => 'interna',
                'ativo' => true,
            ],
            [
                'nome' => 'Facilitador',
                'descricao' => 'Facilita discussões e dinâmicas de grupo',
                'tipo' => 'interna',
                'ativo' => true,
            ],
            [
                'nome' => 'Instrutor',
                'descricao' => 'Instrui em práticas específicas',
                'tipo' => 'externa',
                'ativo' => true,
            ],
            [
                'nome' => 'Gestor Administrativo',
                'descricao' => 'Responsável pela gestão administrativa do evento',
                'tipo' => 'interna',
                'ativo' => true,
            ],
            [
                'nome' => 'Voluntário',
                'descricao' => 'Voluntário geral para apoio em atividades',
                'tipo' => 'interna',
                'ativo' => true,
            ],
            [
                'nome' => 'Música',
                'descricao' => 'Responsável pela música e adoração no evento',
                'tipo' => 'interna',
                'ativo' => true,
            ],
            [
                'nome' => 'Chefe',
                'descricao' => 'Chefe do evento ou equipe',
                'tipo' => 'interna',
                'ativo' => true,
            ],
            [
                'nome' => 'Subchefe Sala',
                'descricao' => 'Subchefe responsável pela sala',
                'tipo' => 'interna',
                'ativo' => true,
            ],
            [
                'nome' => 'Subchefe Escondido',
                'descricao' => 'Subchefe responsável pelo escondido',
                'tipo' => 'interna',
                'ativo' => true,
            ],
            [
                'nome' => 'Intercessão',
                'descricao' => 'Responsável pela intercessão e oração',
                'tipo' => 'interna',
                'ativo' => true,
            ],
            [
                'nome' => 'Ordem',
                'descricao' => 'Responsável pela ordem e segurança do evento',
                'tipo' => 'interna',
                'ativo' => true,
            ],
            [
                'nome' => 'EQR',
                'descricao' => 'Equipe de Qualidade e Responsabilidade (função externa)',
                'tipo' => 'externa',
                'ativo' => true,
            ],
            [
                'nome' => 'Cozinha',
                'descricao' => 'Responsável pela cozinha e alimentação',
                'tipo' => 'interna',
                'ativo' => true,
            ],
        ];

        foreach ($funcoes as $funcao) {
            FuncaoDirigente::updateOrCreate(
                ['nome' => $funcao['nome']],
                $funcao
            );
        }
    }
}
