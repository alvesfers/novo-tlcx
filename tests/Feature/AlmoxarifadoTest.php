<?php

namespace Tests\Feature;

use App\Models\AlmoxarifadoCategoria;
use App\Models\AlmoxarifadoItem;
use App\Models\Entidade;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlmoxarifadoTest extends TestCase
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

    public function test_criar_categoria()
    {
        $this->actingAs($this->diocese);

        $response = $this->post(route('almoxarifado-categorias.store'), [
            'entidade_id' => $this->dioceseEntidade->id,
            'nome' => 'Cozinha',
            'descricao' => 'Categoria de cozinha',
            'ativo' => true,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('almoxarifado_categorias', [
            'nome' => 'Cozinha',
            'entidade_id' => $this->dioceseEntidade->id,
        ]);
    }

    public function test_criar_item_e_registrar_entrada()
    {
        $categoria = AlmoxarifadoCategoria::create([
            'entidade_id' => $this->dioceseEntidade->id,
            'nome' => 'Cozinha',
            'ativo' => true
        ]);

        $this->actingAs($this->diocese);

        $response = $this->post(route('almoxarifado-itens.store'), [
            'entidade_id' => $this->dioceseEntidade->id,
            'almoxarifado_categoria_id' => $categoria->id,
            'nome' => 'Copos',
            'unidade_medida' => 'pacote',
            'quantidade_minima' => 20,
            'status' => 'ativo',
        ]);

        $response->assertStatus(201);

        $item = AlmoxarifadoItem::where('nome', 'Copos')->first();
        $this->assertNotNull($item);
        $this->assertEquals(0, $item->quantidade_atual);
    }

    public function test_registrar_entrada_aumenta_quantidade()
    {
        $categoria = AlmoxarifadoCategoria::create([
            'entidade_id' => $this->dioceseEntidade->id,
            'nome' => 'Cozinha',
            'ativo' => true
        ]);

        $item = AlmoxarifadoItem::create([
            'entidade_id' => $this->dioceseEntidade->id,
            'almoxarifado_categoria_id' => $categoria->id,
            'nome' => 'Copos',
            'unidade_medida' => 'pacote',
            'quantidade_atual' => 0,
            'status' => 'ativo'
        ]);

        $this->actingAs($this->diocese);

        $response = $this->post(route('almoxarifado-movimentos.store'), [
            'entidade_id' => $this->dioceseEntidade->id,
            'almoxarifado_item_id' => $item->id,
            'tipo_movimento' => 'entrada',
            'quantidade' => 50,
            'data_movimento' => now()->format('Y-m-d'),
        ]);

        $response->assertStatus(201);
        $item->refresh();
        $this->assertEquals(50, $item->quantidade_atual);
    }
}
