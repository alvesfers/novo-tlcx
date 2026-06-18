<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFinanceiroMovimentoRequest;
use App\Http\Requests\UpdateFinanceiroMovimentoRequest;
use App\Models\FinanceiroCategoria;
use App\Models\FinanceiroMovimento;
use App\Services\FinanceiroService;
use App\Traits\BulkDeleteable;
use Illuminate\Http\Request;

class FinanceiroMovimentoController extends Controller
{
    use BulkDeleteable;
    public function __construct(
        private FinanceiroService $service
    ) {
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = FinanceiroMovimento::where('entidade_id', $user->entidade_id);

        // Filtros
        if ($request->filled('data_inicio')) {
            $query->where('data_movimento', '>=', $request->data_inicio);
        }
        if ($request->filled('data_fim')) {
            $query->where('data_movimento', '<=', $request->data_fim);
        }
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('categoria_id')) {
            $query->where('financeiro_categoria_id', $request->categoria_id);
        }

        $movimentos = $query->orderBy('data_movimento', 'desc')->paginate(20);

        $categorias = FinanceiroCategoria::where('entidade_id', $user->entidade_id)
            ->where('ativo', true)
            ->orderBy('nome')
            ->get();

        return view('financeiro.movimentos.index', compact('movimentos', 'categorias'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        $categorias = FinanceiroCategoria::where('entidade_id', $user->entidade_id)
            ->where('ativo', true)
            ->orderBy('tipo')
            ->orderBy('nome')
            ->get();

        if ($request->expectsJson()) {
            return response()->json([
                'categorias' => $categorias,
            ]);
        }

        return view('financeiro.movimentos.create', compact('categorias'));
    }

    public function store(StoreFinanceiroMovimentoRequest $request)
    {
        $user = auth()->user();

        $data = $request->validated();
        $data['entidade_id'] = $user->entidade_id;

        try {
            $movimento = $this->service->criarMovimento($data);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Movimento registrado com sucesso',
                    'movimento' => $movimento,
                ]);
            }
        } catch (\InvalidArgumentException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['error' => $e->getMessage()],
                    'message' => $e->getMessage(),
                ], 422);
            }
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }

        return redirect()->route('financeiro-movimentos.index')
            ->with('success', 'Movimento registrado com sucesso');
    }

    public function edit(Request $request, FinanceiroMovimento $financeiro_movimento)
    {
        $this->authorize('update', $financeiro_movimento);
        $user = auth()->user();
        $categorias = FinanceiroCategoria::where('entidade_id', $user->entidade_id)
            ->where('ativo', true)
            ->orderBy('tipo')
            ->orderBy('nome')
            ->get();

        if ($request->expectsJson()) {
            return response()->json([
                'movimento' => $financeiro_movimento,
                'categorias' => $categorias,
            ]);
        }

        return view('financeiro.movimentos.edit', [
            'movimento' => $financeiro_movimento,
            'categorias' => $categorias,
        ]);
    }

    public function update(UpdateFinanceiroMovimentoRequest $request, FinanceiroMovimento $financeiro_movimento)
    {
        $this->authorize('update', $financeiro_movimento);
        $data = $request->validated();

        try {
            $this->service->atualizarMovimento($financeiro_movimento, $data);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Movimento atualizado com sucesso',
                    'movimento' => $financeiro_movimento,
                ]);
            }
        } catch (\InvalidArgumentException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['error' => $e->getMessage()],
                    'message' => $e->getMessage(),
                ], 422);
            }
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }

        return redirect()->route('financeiro-movimentos.index')
            ->with('success', 'Movimento atualizado com sucesso');
    }

    public function destroy(FinanceiroMovimento $financeiro_movimento)
    {
        $this->authorize('delete', $financeiro_movimento);
        $this->service->deletarMovimento($financeiro_movimento);

        return redirect()->route('financeiro-movimentos.index')
            ->with('success', 'Movimento deletado com sucesso');
    }

    public function extrato(Request $request)
    {
        $user = auth()->user();

        $dataInicio = $request->data_inicio ? \Carbon\Carbon::parse($request->data_inicio) : now()->startOfMonth();
        $dataFim = $request->data_fim ? \Carbon\Carbon::parse($request->data_fim) : now();

        $movimentos = FinanceiroMovimento::where('entidade_id', $user->entidade_id)
            ->porPeriodo($dataInicio, $dataFim)
            ->with('categoria', 'entidade', 'evento')
            ->orderBy('data_movimento', 'desc')
            ->get();

        $entradas = $movimentos->where('tipo', 'entrada')->sum('valor');
        $saidas = $movimentos->where('tipo', 'saida')->sum('valor');
        $saldo = $entradas - $saidas;

        return view('financeiro.relatorios.extrato', compact(
            'movimentos',
            'dataInicio',
            'dataFim',
            'entradas',
            'saidas',
            'saldo'
        ));
    }

    public function resumo()
    {
        $user = auth()->user();

        $saldoAtual = $this->service->calcularSaldo($user->entidade_id);

        $dataInicio = now()->startOfMonth();
        $dataFim = now();

        $periodoAtual = $this->service->calcularSaldoPeriodo($user->entidade_id, $dataInicio, $dataFim);

        $categorias = FinanceiroCategoria::where('entidade_id', $user->entidade_id)
            ->with('movimentos')
            ->get()
            ->map(function ($cat) use ($dataInicio, $dataFim) {
                return [
                    'nome' => $cat->nome,
                    'tipo' => $cat->tipo->value,
                    'total' => $cat->movimentos()
                        ->whereBetween('data_movimento', [$dataInicio, $dataFim])
                        ->sum('valor'),
                ];
            });

        return view('financeiro.relatorios.resumo', compact(
            'saldoAtual',
            'periodoAtual',
            'categorias'
        ));
    }

    protected function getModel()
    {
        return FinanceiroMovimento::class;
    }
}
