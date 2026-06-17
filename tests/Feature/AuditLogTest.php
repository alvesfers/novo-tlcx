<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\User;
use App\Models\Dirigente;
use App\Services\AuditLogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    private AuditLogService $auditService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->auditService = app(AuditLogService::class);
    }

    /** @test */
    public function can_log_create_action()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $dirigente = Dirigente::factory()->create();

        $this->auditService->logCreate($dirigente);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'action' => 'create',
            'model_type' => Dirigente::class,
            'model_id' => $dirigente->id,
        ]);
    }

    /** @test */
    public function can_log_update_action()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $dirigente = Dirigente::factory()->create(['nome' => 'Original']);
        $oldValues = ['nome' => 'Original'];
        $dirigente->update(['nome' => 'Updated']);

        $this->auditService->logUpdate($dirigente, $oldValues);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'action' => 'update',
            'model_type' => Dirigente::class,
        ]);
    }

    /** @test */
    public function can_log_delete_action()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $dirigente = Dirigente::factory()->create();
        $this->auditService->logDelete($dirigente);

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $user->id,
            'action' => 'delete',
            'model_type' => Dirigente::class,
        ]);
    }

    /** @test */
    public function audit_log_page_shows_logs()
    {
        $user = User::factory()->create();
        AuditLog::factory(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('auditoria.index'));

        $response->assertStatus(200);
        $response->assertViewIs('auditoria.index');
        $response->assertViewHas('logs');
    }

    /** @test */
    public function audit_logs_can_be_filtered()
    {
        $user = User::factory()->create();
        AuditLog::factory(3)->create(['user_id' => $user->id, 'action' => 'create']);
        AuditLog::factory(2)->create(['user_id' => $user->id, 'action' => 'update']);

        $response = $this->actingAs($user)->get(route('auditoria.index', ['action' => 'create']));

        $response->assertStatus(200);
    }
}
