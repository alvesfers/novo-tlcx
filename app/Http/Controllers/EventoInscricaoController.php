<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\EventoTipoInscricao;
use App\Models\EventoInscricaoOpcao;
use App\Models\EventoInscricaoOpcaoCamiseta;
use Illuminate\Http\Request;

class EventoInscricaoController extends Controller
{
    public function index(Evento $evento)
    {
        $this->authorize('view', $evento);

        $tipos = $evento->tiposInscricao()->with(['opcoes.camisetas'])->get();

        return view('eventos.modulos.inscricoes.index', compact('evento', 'tipos'));
    }

    // ── Tipos ────────────────────────────────────────────

    public function storeTipo(Request $request, Evento $evento)
    {
        $this->authorize('update', $evento);

        $data = $request->validate([
            'nome'      => 'required|string|max:100',
            'publico'   => 'required|in:dirigente_interno,dirigente_externo,externo',
            'descricao' => 'nullable|string|max:500',
            'ordem'     => 'nullable|integer',
        ]);

        $evento->tiposInscricao()->create($data + ['ativo' => true, 'ordem' => $data['ordem'] ?? 0]);

        return back()->with('success', 'Tipo de inscrição criado!');
    }

    public function updateTipo(Request $request, Evento $evento, EventoTipoInscricao $tipo)
    {
        $this->authorize('update', $evento);

        $data = $request->validate([
            'nome'      => 'required|string|max:100',
            'publico'   => 'required|in:dirigente_interno,dirigente_externo,externo',
            'descricao' => 'nullable|string|max:500',
            'ativo'     => 'boolean',
            'ordem'     => 'nullable|integer',
        ]);

        $tipo->update($data);

        return back()->with('success', 'Tipo atualizado!');
    }

    public function destroyTipo(Evento $evento, EventoTipoInscricao $tipo)
    {
        $this->authorize('update', $evento);
        $tipo->delete();
        return back()->with('success', 'Tipo removido!');
    }

    // ── Opções ───────────────────────────────────────────

    public function storeOpcao(Request $request, Evento $evento, EventoTipoInscricao $tipo)
    {
        $this->authorize('update', $evento);

        $data = $request->validate([
            'nome'            => 'required|string|max:150',
            'valor_base'      => 'required|numeric|min:0',
            'descricao'       => 'nullable|string|max:500',
            'inclui_camiseta' => 'boolean',
            'ordem'           => 'nullable|integer',
        ]);

        $opcao = $tipo->opcoes()->create($data + [
            'ativo'           => true,
            'inclui_camiseta' => $request->boolean('inclui_camiseta'),
            'ordem'           => $data['ordem'] ?? 0,
        ]);

        // Salva camisetas disponíveis se inclui camiseta
        if ($request->boolean('inclui_camiseta') && $request->has('camisetas')) {
            foreach ($request->input('camisetas', []) as $tipo_cam => $cam_data) {
                if (!empty($cam_data['selecionado'])) {
                    $opcao->camisetas()->create([
                        'tipo_camiseta'   => $tipo_cam,
                        'valor_adicional' => $cam_data['valor_adicional'] ?? 0,
                        'ativo'           => true,
                    ]);
                }
            }
        }

        return back()->with('success', 'Opção criada!');
    }

    public function updateOpcao(Request $request, Evento $evento, EventoTipoInscricao $tipo, EventoInscricaoOpcao $opcao)
    {
        $this->authorize('update', $evento);

        $data = $request->validate([
            'nome'            => 'required|string|max:150',
            'valor_base'      => 'required|numeric|min:0',
            'descricao'       => 'nullable|string|max:500',
            'inclui_camiseta' => 'boolean',
            'ativo'           => 'boolean',
            'ordem'           => 'nullable|integer',
        ]);

        $opcao->update($data + ['inclui_camiseta' => $request->boolean('inclui_camiseta')]);

        // Atualiza camisetas
        if ($request->has('camisetas')) {
            $opcao->camisetas()->delete();
            foreach ($request->input('camisetas', []) as $tipo_cam => $cam_data) {
                if (!empty($cam_data['selecionado'])) {
                    $opcao->camisetas()->create([
                        'tipo_camiseta'   => $tipo_cam,
                        'valor_adicional' => $cam_data['valor_adicional'] ?? 0,
                        'ativo'           => true,
                    ]);
                }
            }
        }

        return back()->with('success', 'Opção atualizada!');
    }

    public function destroyOpcao(Evento $evento, EventoTipoInscricao $tipo, EventoInscricaoOpcao $opcao)
    {
        $this->authorize('update', $evento);
        $opcao->delete();
        return back()->with('success', 'Opção removida!');
    }

    // ── API ──────────────────────────────────────────────

    public function apiOpcoesPorPublico(Evento $evento, Request $request)
    {
        $publico = $request->query('publico');

        $tipos = $evento->tiposInscricaoAtivos()
            ->when($publico, fn($q) => $q->where('publico', $publico))
            ->with(['opcoesAtivas.camisetasAtivas'])
            ->get();

        return response()->json($tipos);
    }
}
