<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório Financeiro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        h1 {
            color: #1f2937;
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 10px;
        }
        h2 {
            color: #374151;
            margin-top: 30px;
            font-size: 18px;
        }
        .header-info {
            background-color: #f3f4f6;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .header-info p {
            margin: 5px 0;
            font-size: 12px;
        }
        .resumo {
            background-color: #f0fdf4;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #10b981;
            border-radius: 3px;
        }
        .resumo-item {
            display: inline-block;
            width: 23%;
            margin-right: 2%;
            text-align: center;
            padding: 10px;
            background: white;
            margin-bottom: 10px;
            border-radius: 3px;
        }
        .resumo-item label {
            display: block;
            font-weight: bold;
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 5px;
        }
        .resumo-item value {
            display: block;
            font-size: 16px;
            font-weight: bold;
            color: #10b981;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        thead {
            background-color: #3b82f6;
            color: white;
        }
        th {
            padding: 10px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .text-green {
            color: #10b981;
        }
        .text-red {
            color: #ef4444;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 10px;
            color: #6b7280;
            text-align: center;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <h1>📊 Relatório Financeiro</h1>

    <div class="header-info">
        <p><strong>Entidade:</strong> {{ $entidade?->nome ?? 'Sistema Completo' }}</p>
        <p><strong>Período:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
        <p><strong>Gerado em:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
        <p><strong>Usuário:</strong> {{ auth()->user()->name }}</p>
    </div>

    <h2>Resumo Financeiro</h2>
    <div class="resumo">
        <div class="resumo-item">
            <label>Entradas</label>
            <value class="text-green">R$ {{ number_format($resumo['entradas'], 2, ',', '.') }}</value>
        </div>
        <div class="resumo-item">
            <label>Saídas</label>
            <value class="text-red">R$ {{ number_format($resumo['saidas'], 2, ',', '.') }}</value>
        </div>
        <div class="resumo-item">
            <label>Saldo</label>
            <value class="@if($resumo['saldo'] >= 0) text-green @else text-red @endif">R$ {{ number_format($resumo['saldo'], 2, ',', '.') }}</value>
        </div>
        <div class="resumo-item">
            <label>Movimentações</label>
            <value>{{ $resumo['total_movimentos'] }}</value>
        </div>
    </div>

    <h2>Detalhes das Movimentações</h2>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Entidade</th>
                <th>Categoria</th>
                <th>Tipo</th>
                <th>Descrição</th>
                <th class="text-right">Valor</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movimentos as $mov)
            <tr>
                <td>{{ $mov->data_movimento->format('d/m/Y') }}</td>
                <td>{{ $mov->entidade->nome }}</td>
                <td>{{ $mov->categoria->nome }}</td>
                <td class="text-center">
                    @if($mov->tipo === 'entrada')
                        <span style="background-color: #d1fae5; color: #065f46; padding: 2px 6px; border-radius: 3px;">Entrada</span>
                    @else
                        <span style="background-color: #fee2e2; color: #7f1d1d; padding: 2px 6px; border-radius: 3px;">Saída</span>
                    @endif
                </td>
                <td>{{ $mov->descricao }}</td>
                <td class="text-right @if($mov->tipo === 'entrada') text-green @else text-red @endif">
                    {{ $mov->tipo === 'entrada' ? '+' : '-' }} R$ {{ number_format($mov->valor, 2, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Nenhuma movimentação encontrada</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Este relatório foi gerado automaticamente pelo sistema TLC Admin</p>
    </div>
</body>
</html>
