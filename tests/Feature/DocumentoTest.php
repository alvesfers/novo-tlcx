<?php

namespace Tests\Feature;

use App\Models\Documento;
use App\Models\DocumentoCategoria;
use App\Models\Entidade;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentoTest extends TestCase
{
    use RefreshDatabase;

    private User $diocese;
    private Entidade $dioceseEntidade;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        Storage::fake('public');

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

    public function test_criar_categoria_documento()
    {
        $this->actingAs($this->diocese);

        $response = $this->post(route('documento-categorias.store'), [
            'entidade_id' => $this->dioceseEntidade->id,
            'nome' => 'Atas',
            'descricao' => 'Atas de reuniões',
            'ativo' => true,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('documento_categorias', [
            'nome' => 'Atas',
            'entidade_id' => $this->dioceseEntidade->id,
        ]);
    }

    public function test_documento_publico_visivel_para_usuario_autenticado()
    {
        $categoria = DocumentoCategoria::create([
            'entidade_id' => $this->dioceseEntidade->id,
            'nome' => 'Geral',
            'ativo' => true
        ]);

        $documento = Documento::create([
            'entidade_id' => $this->dioceseEntidade->id,
            'documento_categoria_id' => $categoria->id,
            'titulo' => 'Documento público',
            'arquivo_nome_original' => 'teste.pdf',
            'arquivo_nome_armazenado' => 'teste.pdf',
            'arquivo_path' => 'documentos/teste.pdf',
            'arquivo_mime' => 'application/pdf',
            'arquivo_tamanho' => 1024,
            'tipo_documento' => 'geral',
            'visibilidade' => 'publico',
            'ativo' => true
        ]);

        $outroUsuario = User::factory()->create(['tipo_usuario' => 'admin']);
        $this->actingAs($outroUsuario);

        $response = $this->get(route('documentos.show', $documento));
        $response->assertStatus(200);
    }

    public function test_documento_privado_nao_visivel_para_outra_entidade()
    {
        $outraEntidade = Entidade::create([
            'nome' => 'Outra Diocese',
            'tipo_entidade' => 'diocese',
            'ativo' => true
        ]);

        $categoria = DocumentoCategoria::create([
            'entidade_id' => $this->dioceseEntidade->id,
            'nome' => 'Geral',
            'ativo' => true
        ]);

        $documento = Documento::create([
            'entidade_id' => $this->dioceseEntidade->id,
            'documento_categoria_id' => $categoria->id,
            'titulo' => 'Documento privado',
            'arquivo_nome_original' => 'privado.pdf',
            'arquivo_nome_armazenado' => 'privado.pdf',
            'arquivo_path' => 'documentos/privado.pdf',
            'arquivo_mime' => 'application/pdf',
            'arquivo_tamanho' => 1024,
            'tipo_documento' => 'geral',
            'visibilidade' => 'privado',
            'ativo' => true
        ]);

        $outroUsuario = User::factory()->create([
            'tipo_usuario' => 'diocese',
            'entidade_id' => $outraEntidade->id
        ]);

        $this->actingAs($outroUsuario);
        $response = $this->get(route('documentos.show', $documento));

        $response->assertStatus(403);
    }
}
