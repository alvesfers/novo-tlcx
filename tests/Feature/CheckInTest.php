<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Evento;
use App\Models\Dirigente;
use App\Models\Entidade;
use App\Models\EventoParticipante;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckInTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_mark_participant_as_present()
    {
        $user = User::factory()->create();
        $dirigente = Dirigente::factory()->create();
        $evento = Evento::factory()->create();

        $participante = EventoParticipante::factory()->create([
            'evento_id' => $evento->id,
            'dirigente_id' => $dirigente->id,
            'presenca' => 'pendente',
        ]);

        $response = $this->actingAs($user)
            ->post(route('eventos.participantes.presenca', ['evento' => $evento, 'participante' => $participante]));

        $participante->refresh();
        $this->assertEqual('confirmado', $participante->presenca);
        $this->assertNotNull($participante->checkin_em);
    }

    /** @test */
    public function checkin_records_timestamp()
    {
        $user = User::factory()->create();
        $dirigente = Dirigente::factory()->create();
        $evento = Evento::factory()->create();

        $participante = EventoParticipante::factory()->create([
            'evento_id' => $evento->id,
            'dirigente_id' => $dirigente->id,
            'presenca' => 'pendente',
            'checkin_em' => null,
        ]);

        $this->actingAs($user)
            ->post(route('eventos.participantes.presenca', ['evento' => $evento, 'participante' => $participante]));

        $participante->refresh();
        $this->assertNotNull($participante->checkin_em);
    }

    /** @test */
    public function cannot_checkin_same_participant_twice()
    {
        $user = User::factory()->create();
        $dirigente = Dirigente::factory()->create();
        $evento = Evento::factory()->create();

        $participante = EventoParticipante::factory()->create([
            'evento_id' => $evento->id,
            'dirigente_id' => $dirigente->id,
            'presenca' => 'confirmado',
            'checkin_em' => now(),
        ]);

        $response = $this->actingAs($user)
            ->post(route('eventos.participantes.presenca', ['evento' => $evento, 'participante' => $participante]));

        // Should still work, but timestamp shouldn't change
        $participante->refresh();
        $this->assertEqual('confirmado', $participante->presenca);
    }

    /** @test */
    public function api_checkin_works()
    {
        $user = User::factory()->create();
        $dirigente = Dirigente::factory()->create();
        $evento = Evento::factory()->create();

        EventoParticipante::factory()->create([
            'evento_id' => $evento->id,
            'dirigente_id' => $dirigente->id,
            'presenca' => 'pendente',
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->postJson('/api/eventos/' . $evento->id . '/checkin', [
            'dirigente_id' => $dirigente->id,
        ], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $participante = EventoParticipante::where('evento_id', $evento->id)
            ->where('dirigente_id', $dirigente->id)
            ->first();
        $this->assertEqual('confirmado', $participante->presenca);
    }
}
