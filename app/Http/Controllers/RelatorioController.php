<?php

namespace App\Http\Controllers;

use App\Models\FinanceiroMovimento;
use App\Models\Evento;
use App\Models\Dirigente;
use App\Models\Entidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class RelatorioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function financeiro(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->input('start_date', now()->subMonths(3)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $entidades = $this->getAccessibleEntidades($user);

        $movimentos = FinanceiroMovimento::with(['entidade', 'categoria'])
            ->whereIn('entidade_id', $entidades->pluck('id'))
            ->whereBetween('data_movimento', [$startDate, $endDate])
            ->orderByDesc('data_movimento')
            ->get();

        $resumo = $this->calcularResumoFinanceiro($movimentos);
        $porCategoria = $this->agruparPorCategoria($movimentos);
        $porFormaPagamento = $this->agruparPorFormaPagamento($movimentos);

        return view('relatorios.financeiro', compact(
            'movimentos',
            'resumo',
            'porCategoria',
            'porFormaPagamento',
            'startDate',
            'endDate'
        ));
    }

    public function eventos(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->input('start_date', now()->subMonths(3)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $entidades = $this->getAccessibleEntidades($user);

        $eventos = Evento::with(['entidades', 'participantes'])
            ->whereIn('entidade_criadora_id', $entidades->pluck('id'))
            ->whereBetween('data_inicio', [$startDate, $endDate])
            ->get();

        $resumo = $this->calcularResumoEventos($eventos);
        $porTipo = $this->agruparEventosPorTipo($eventos);
        $taxaPresenca = $this->calcularTaxaPresenca($eventos);

        return view('relatorios.eventos', compact(
            'eventos',
            'resumo',
            'porTipo',
            'taxaPresenca',
            'startDate',
            'endDate'
        ));
    }

    public function dirigentes(Request $request)
    {
        $user = auth()->user();
        $entidades = $this->getAccessibleEntidades($user);

        $dirigentes = Dirigente::with('vinculos.entidade')
            ->whereHas('vinculos', function ($q) use ($entidades) {
                $q->whereIn('entidade_id', $entidades->pluck('id'));
            })
            ->get();

        $porEntidade = $this->agruparDiigentesPorEntidade($dirigentes);
        $porCargo = $this->agruparDirigentesPorCargo($dirigentes);
        $resumo = $this->calcularResumoDirigentes($dirigentes);

        return view('relatorios.dirigentes', compact(
            'dirigentes',
            'porEntidade',
            'porCargo',
            'resumo'
        ));
    }

    public function export(Request $request)
    {
        $tipo = $request->input('tipo', 'financeiro');
        $formato = $request->input('formato', 'csv');

        if ($tipo === 'financeiro') {
            return $this->exportarFinanceiro($formato);
        } elseif ($tipo === 'eventos') {
            return $this->exportarEventos($formato);
        } elseif ($tipo === 'dirigentes') {
            return $this->exportarDirigentes($formato);
        }

        return back()->with('error', 'Tipo de relatório inválido');
    }

    public function financeiroPdf(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->input('start_date', now()->subMonths(3)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $entidades = $this->getAccessibleEntidades($user);

        $movimentos = FinanceiroMovimento::with(['entidade', 'categoria'])
            ->whereIn('entidade_id', $entidades->pluck('id'))
            ->whereBetween('data_movimento', [$startDate, $endDate])
            ->orderByDesc('data_movimento')
            ->get();

        $resumo = $this->calcularResumoFinanceiro($movimentos);

        $pdf = Pdf::loadView('relatorios.pdf.financeiro', [
            'movimentos' => $movimentos,
            'resumo' => $resumo,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'entidade' => $user->entidade,
        ]);

        return $pdf->download('relatorio-financeiro-' . now()->format('Y-m-d') . '.pdf');
    }

    public function eventosPdf(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->input('start_date', now()->subMonths(3)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $entidades = $this->getAccessibleEntidades($user);

        $eventos = Evento::with(['entidades', 'participantes'])
            ->whereIn('entidade_criadora_id', $entidades->pluck('id'))
            ->whereBetween('data_inicio', [$startDate, $endDate])
            ->get();

        $resumo = $this->calcularResumoEventos($eventos);

        $pdf = Pdf::loadView('relatorios.pdf.eventos', [
            'eventos' => $eventos,
            'resumo' => $resumo,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'entidade' => $user->entidade,
        ]);

        return $pdf->download('relatorio-eventos-' . now()->format('Y-m-d') . '.pdf');
    }

    public function dirigentesPdf(Request $request)
    {
        $user = auth()->user();
        $entidades = $this->getAccessibleEntidades($user);

        $dirigentes = Dirigente::with('vinculos.entidade')
            ->whereHas('vinculos', function ($q) use ($entidades) {
                $q->whereIn('entidade_id', $entidades->pluck('id'));
            })
            ->get();

        $resumo = $this->calcularResumoDirigentes($dirigentes);

        $pdf = Pdf::loadView('relatorios.pdf.dirigentes', [
            'dirigentes' => $dirigentes,
            'resumo' => $resumo,
            'entidade' => $user->entidade,
        ]);

        return $pdf->download('relatorio-dirigentes-' . now()->format('Y-m-d') . '.pdf');
    }

    public function financeiroExcel(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->input('start_date', now()->subMonths(3)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $entidades = $this->getAccessibleEntidades($user);

        $movimentos = FinanceiroMovimento::with(['entidade', 'categoria'])
            ->whereIn('entidade_id', $entidades->pluck('id'))
            ->whereBetween('data_movimento', [$startDate, $endDate])
            ->orderByDesc('data_movimento')
            ->get();

        return Excel::download(new \App\Exports\FinanceiroExport($movimentos), 'relatorio-financeiro-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function eventosExcel(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->input('start_date', now()->subMonths(3)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $entidades = $this->getAccessibleEntidades($user);

        $eventos = Evento::with(['entidades', 'participantes'])
            ->whereIn('entidade_criadora_id', $entidades->pluck('id'))
            ->whereBetween('data_inicio', [$startDate, $endDate])
            ->get();

        return Excel::download(new \App\Exports\EventosExport($eventos), 'relatorio-eventos-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function dirigenteExcel(Request $request)
    {
        $user = auth()->user();
        $entidades = $this->getAccessibleEntidades($user);

        $dirigentes = Dirigente::with('vinculos.entidade')
            ->whereHas('vinculos', function ($q) use ($entidades) {
                $q->whereIn('entidade_id', $entidades->pluck('id'));
            })
            ->get();

        return Excel::download(new \App\Exports\DirigentesExport($dirigentes), 'relatorio-dirigentes-' . now()->format('Y-m-d') . '.xlsx');
    }

    private function exportarFinanceiro($formato)
    {
        $user = auth()->user();
        $entidades = $this->getAccessibleEntidades($user);
        $movimentos = FinanceiroMovimento::with(['entidade', 'categoria'])
            ->whereIn('entidade_id', $entidades->pluck('id'))
            ->get();

        if ($formato === 'csv') {
            return $this->exportarCSV($movimentos, 'financeiro');
        }

        return back()->with('error', 'Formato não suportado');
    }

    private function exportarEventos($formato)
    {
        $user = auth()->user();
        $entidades = $this->getAccessibleEntidades($user);
        $eventos = Evento::with(['entidades', 'participantes'])
            ->whereIn('entidade_criadora_id', $entidades->pluck('id'))
            ->get();

        if ($formato === 'csv') {
            return $this->exportarCSV($eventos, 'eventos');
        }

        return back()->with('error', 'Formato não suportado');
    }

    private function exportarDirigentes($formato)
    {
        $user = auth()->user();
        $entidades = $this->getAccessibleEntidades($user);
        $dirigentes = Dirigente::with('vinculos.entidade')
            ->whereHas('vinculos', function ($q) use ($entidades) {
                $q->whereIn('entidade_id', $entidades->pluck('id'));
            })
            ->get();

        if ($formato === 'csv') {
            return $this->exportarCSV($dirigentes, 'dirigentes');
        }

        return back()->with('error', 'Formato não suportado');
    }

    private function exportarCSV($items, $tipo)
    {
        $filename = "{$tipo}_" . now()->format('Y-m-d') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($items, $tipo) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $this->getCSVHeaders($tipo));

            foreach ($items as $item) {
                fputcsv($file, $this->getCSVRow($item, $tipo));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getCSVHeaders($tipo)
    {
        return match($tipo) {
            'financeiro' => ['Data', 'Entidade', 'Categoria', 'Tipo', 'Valor', 'Descrição'],
            'eventos' => ['Nome', 'Data Início', 'Data Fim', 'Entidade Criadora', 'Status', 'Participantes'],
            'dirigentes' => ['Nome', 'UUID', 'Entidade Principal', 'Cargo', 'Data Nascimento'],
            default => [],
        };
    }

    private function getCSVRow($item, $tipo)
    {
        return match($tipo) {
            'financeiro' => [
                $item->data_movimento->format('d/m/Y'),
                $item->entidade->nome,
                $item->categoria->nome,
                $item->tipo,
                number_format($item->valor, 2, ',', '.'),
                $item->descricao,
            ],
            'eventos' => [
                $item->nome,
                $item->data_inicio->format('d/m/Y'),
                $item->data_fim->format('d/m/Y'),
                $item->criadora->nome,
                $item->status,
                $item->participantes->count(),
            ],
            'dirigentes' => [
                $item->nome,
                $item->uuid,
                $item->vinculos->first()?->entidade->nome ?? 'N/A',
                $item->vinculos->first()?->pivot->cargo ?? 'N/A',
                $item->data_nascimento?->format('d/m/Y') ?? 'N/A',
            ],
            default => [],
        };
    }

    private function getAccessibleEntidades($user)
    {
        if ($user->isAdmin()) {
            return Entidade::all();
        }

        if ($user->isDiocese()) {
            return Entidade::where('id', $user->entidade_id)
                ->orWhere('entidade_pai_id', $user->entidade_id)
                ->get();
        }

        return Entidade::where('id', $user->entidade_id)->get();
    }

    private function calcularResumoFinanceiro($movimentos)
    {
        return [
            'entradas' => $movimentos->where('tipo', 'entrada')->sum('valor'),
            'saidas' => $movimentos->where('tipo', 'saida')->sum('valor'),
            'saldo' => $movimentos->where('tipo', 'entrada')->sum('valor') -
                      $movimentos->where('tipo', 'saida')->sum('valor'),
            'total_movimentos' => $movimentos->count(),
        ];
    }

    private function agruparPorCategoria($movimentos)
    {
        return $movimentos->groupBy('categoria.nome')
            ->map(fn($items) => [
                'total' => $items->sum('valor'),
                'count' => $items->count(),
            ]);
    }

    private function agruparPorFormaPagamento($movimentos)
    {
        return $movimentos->groupBy('forma_pagamento')
            ->map(fn($items) => [
                'total' => $items->sum('valor'),
                'count' => $items->count(),
            ]);
    }

    private function calcularResumoEventos($eventos)
    {
        return [
            'total' => $eventos->count(),
            'por_tipo' => $eventos->groupBy('tipo_evento_id')->count(),
            'total_participantes' => $eventos->sum(fn($e) => $e->participantes->count()),
            'presentes' => $eventos->sum(fn($e) => $e->participantes->where('presenca', 'confirmado')->count()),
        ];
    }

    private function agruparEventosPorTipo($eventos)
    {
        return $eventos->groupBy('tipo_evento_id')
            ->map(fn($items) => [
                'count' => $items->count(),
                'participantes' => $items->sum(fn($e) => $e->participantes->count()),
            ]);
    }

    private function calcularTaxaPresenca($eventos)
    {
        $total = 0;
        $presentes = 0;

        foreach ($eventos as $evento) {
            $total += $evento->participantes->count();
            $presentes += $evento->participantes->where('presenca', 'confirmado')->count();
        }

        return $total > 0 ? ($presentes / $total) * 100 : 0;
    }

    private function agruparDiigentesPorEntidade($dirigentes)
    {
        $agrupado = [];
        foreach ($dirigentes as $dirigente) {
            foreach ($dirigente->vinculos as $vinculo) {
                $entidadeName = $vinculo->entidade->nome;
                $agrupado[$entidadeName] = ($agrupado[$entidadeName] ?? 0) + 1;
            }
        }
        return $agrupado;
    }

    private function agruparDirigentesPorCargo($dirigentes)
    {
        $agrupado = [];
        foreach ($dirigentes as $dirigente) {
            foreach ($dirigente->vinculos as $vinculo) {
                $cargo = $vinculo->cargo;
                $agrupado[$cargo] = ($agrupado[$cargo] ?? 0) + 1;
            }
        }
        return $agrupado;
    }

    private function calcularResumoDirigentes($dirigentes)
    {
        return [
            'total' => $dirigentes->count(),
            'ativos' => $dirigentes->where('ativo', true)->count(),
            'inativos' => $dirigentes->where('ativo', false)->count(),
        ];
    }
}
