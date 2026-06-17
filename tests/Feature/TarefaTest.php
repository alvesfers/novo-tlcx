<?php

namespace Tests\Feature;

use App\Models\Entidade;
use App\Models\Tarefa;
use App\Models\TarefaCategoria;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TarefaTest extends TestCase
{
    use RefreshDatabase;

    private User $diocese;
    private Entidade $dioceseEntidade;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dioceseEntidade = Entidade::create([
            'nome' => 'Diocese Teste',
            'tipo_entidade' => 'diocese',
            'ativo' => true
        ]);
        $this->diocese = User::factory()->create([
            'tipo_usuario' => 'diocese',
            'entidade_id' => $this->dioceseEntidade->id
        ]);
    }

    public function test_criar_tarefa()
    {
        $categoria = TarefaCategoria::create([
            'entidade_id' => $this->dioceseEntidade->id,
            'nome' => 'Geral',
            'ativo' => true
        ]);

        $this->actingAs($this->diocese);

        $response = $this->post(route('tarefas.store'), [
            'entidade_id' => $this->dioceseEntidade->id,
            'tarefa_categoria_id' => $categoria->id,
            'titulo' => 'Comprar materiais',
            'status' => 'pendente',
            'prioridade' => 'alta',
            'data_limite' => now()->addDays(7)->format('Y-m-d'),
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('tarefas', [
            'titulo' => 'Comprar materiais',
            'entidade_id' => $this->dioceseEntidade->id,
        ]);
    }

    public function test_concluir_tarefa()
    {
        $tarefa = Tarefa::create([
            'entidade_id' => $this->dioceseEntidade->id,
            'titulo' => 'Tarefa teste',
            'status' => 'pendente',
            'prioridade' => 'media',
        ]);

        $this->actingAs($this->diocese);

        $response = $this->post(route('tarefas.concluir', $tarefa));

        $response->assertStatus(200);
        $tarefa->refresh();
        $this->assertEquals('concluida', $tarefa->status->value);
        $this->assertNotNull($tarefa->concluida_em);
    }

    public function test_tarefa_vencida()
    {
        $tarefa = Tarefa::create([
            'entidade_id' => $this->dioceseEntidade->id,
            'titulo' => 'Tarefa teste',
            'status' => 'pendente',
            'prioridade' => 'media',
            'data_limite' => now()->subDays(1)->format('Y-m-d'),
        ]);

        $this->assertTrue($tarefa->isVencida);
    }
}
