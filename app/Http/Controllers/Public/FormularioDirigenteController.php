<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Dirigente;
use App\Models\EventoParticipante;
use Illuminate\Http\Request;

class FormularioDirigenteController extends Controller
{
    // ── Formulário geral (legado) ────────────────────────────────────────────

    public function show(string $eventoUuid)
    {
        $evento    = Evento::where('uuid', $eventoUuid)->firstOrFail();
        $formulario = $evento->formulario_dirigentes ?? [];
        return view('public.formulario-dirigente', compact('evento', 'formulario'));
    }

    public function enviar(Request $request, string $eventoUuid)
    {
        $evento    = Evento::where('uuid', $eventoUuid)->firstOrFail();
        $formulario = $evento->formulario_dirigentes ?? [];
        return $this->processarEnvio($request, $evento, $formulario, 'geral');
    }

    public function sucesso(string $eventoUuid)
    {
        $evento = Evento::where('uuid', $eventoUuid)->firstOrFail();
        return view('public.formulario-sucesso-dirigente', compact('evento'));
    }

    // ── Formulário interno ───────────────────────────────────────────────────

    public function showInterno(string $eventoUuid)
    {
        $evento    = Evento::where('uuid', $eventoUuid)->firstOrFail();
        $formulario = $evento->formulario_dirigentes_interno ?? [];

        if (empty($formulario)) {
            abort(404, 'Formulário para dirigentes internos não configurado.');
        }

        return view('public.formulario-dirigente', [
            'evento'     => $evento,
            'formulario' => $formulario,
            'tipo'       => 'interno',
            'titulo'     => 'Formulário — Dirigente Interno',
            'rotaEnvio'  => route('evento.formulario.enviar.dirigente.interno', $eventoUuid),
        ]);
    }

    public function enviarInterno(Request $request, string $eventoUuid)
    {
        $evento    = Evento::where('uuid', $eventoUuid)->firstOrFail();
        $formulario = $evento->formulario_dirigentes_interno ?? [];
        return $this->processarEnvio($request, $evento, $formulario, 'interno');
    }

    // ── Formulário externo ───────────────────────────────────────────────────

    public function showExterno(string $eventoUuid)
    {
        $evento    = Evento::where('uuid', $eventoUuid)->firstOrFail();
        $formulario = $evento->formulario_dirigentes_externo ?? [];

        if (empty($formulario)) {
            abort(404, 'Formulário para dirigentes externos não configurado.');
        }

        return view('public.formulario-dirigente', [
            'evento'     => $evento,
            'formulario' => $formulario,
            'tipo'       => 'externo',
            'titulo'     => 'Formulário — Dirigente Externo',
            'rotaEnvio'  => route('evento.formulario.enviar.dirigente.externo', $eventoUuid),
        ]);
    }

    public function enviarExterno(Request $request, string $eventoUuid)
    {
        $evento    = Evento::where('uuid', $eventoUuid)->firstOrFail();
        $formulario = $evento->formulario_dirigentes_externo ?? [];
        return $this->processarEnvio($request, $evento, $formulario, 'externo');
    }

    // ── Processamento compartilhado ──────────────────────────────────────────

    private function processarEnvio(Request $request, Evento $evento, array $formulario, string $tipo): \Illuminate\Http\JsonResponse
    {
        if (empty($formulario)) {
            return response()->json(['success' => false, 'message' => 'Formulário não configurado.'], 422);
        }

        // Monta regras de validação dinâmicas
        $regras = [];
        foreach ($formulario as $campo) {
            $chave = $this->slugCampo($campo['nome']);
            $regra = $campo['obrigatorio'] ? 'required' : 'nullable';
            if ($campo['tipo'] === 'email')  $regra .= '|email';
            if ($campo['tipo'] === 'number') $regra .= '|numeric';
            if ($campo['tipo'] === 'date')   $regra .= '|date';
            $regras[$chave] = array_filter(explode('|', $regra));
        }

        $validated = $request->validate($regras);

        // Busca campo nome e email
        $nomeCampo  = $this->slugCampo($formulario[0]['nome'] ?? 'nome');
        $nome       = $validated[$nomeCampo] ?? 'Dirigente';
        $emailCampo = collect($formulario)->first(fn($c) => $c['tipo'] === 'email');
        $email      = $emailCampo ? ($validated[$this->slugCampo($emailCampo['nome'])] ?? null) : null;

        // Busca ou cria o dirigente
        $dirigente = Dirigente::when($email, fn($q) => $q->where('email', $email))
            ->where('nome', $nome)
            ->first()
            ?? Dirigente::create(['nome' => $nome, 'email' => $email, 'ativo' => true]);

        // Cria ou atualiza participação
        EventoParticipante::updateOrCreate(
            [
                'evento_id'        => $evento->id,
                'dirigente_id'     => $dirigente->id,
                'tipo_participante' => 'dirigente',
            ],
            ['respostas_formulario' => $validated, 'presenca' => false]
        );

        return response()->json([
            'success'  => true,
            'message'  => 'Formulário enviado com sucesso! Obrigado por participar.',
            'redirect' => route('evento.formulario.sucesso.dirigente', $evento->uuid),
        ]);
    }

    private function slugCampo(string $nome): string
    {
        return preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($nome));
    }
}
