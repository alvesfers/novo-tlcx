<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Eventos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        h1 {
            color: #1f2937;
            border-bottom: 3px solid #8b5cf6;
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
            background-color: #f5f3ff;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #8b5cf6;
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
            color: #8b5cf6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        thead {
            background-color: #8b5cf6;
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
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 10px;
            color: #6b7280;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>📅 Relatório de Eventos</h1>

    <div class="header-info">
        <p><strong>Entidade:</strong> {{ $entidade?->nome ?? 'Sistema Completo' }}</p>
        <p><strong>Período:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
        <p><strong>Gerado em:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
        <p><strong>Usuário:</strong> {{ auth()->user()->name }}</p>
    </div>

    <h2>Resumo de Eventos</h2>
    <div class="resumo">
        <div class="resumo-item">
            <label>Total de Eventos</label>
            <value>{{ $resumo['total'] }}</value>
        </div>
        <div class="resumo-item">
            <label>Tipos de Eventos</label>
            <value>{{ $resumo['por_tipo'] }}</value>
        </div>
        <div class="resumo-item">
            <label>Total Participantes</label>
            <value>{{ $resumo['total_participantes'] }}</value>
        </div>
        <div class="resumo-item">
            <label>Presentes</label>
            <value>{{ $resumo['presentes'] }}</value>
        </div>
    </div>

    <h2>Detalhes dos Eventos</h2>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Data Início</th>
                <th>Data Fim</th>
                <th>Status</th>
                <th class="text-center">Participantes</th>
                <th class="text-center">Local</th>
            </tr>
        </thead>
        <tbody>
            @forelse($eventos as $evento)
            <tr>
                <td><strong>{{ $evento->nome }}</strong></td>
                <td>{{ $evento->data_inicio->format('d/m/Y') }}</td>
                <td>{{ $evento->data_fim->format('d/m/Y') }}</td>
                <td>
                    @if($evento->status === 'publicado')
                        <span style="background-color: #bfdbfe; color: #1e40af; padding: 2px 6px; border-radius: 3px;">Publicado</span>
                    @elseif($evento->status === 'rascunho')
                        <span style="background-color: #fef08a; color: #78350f; padding: 2px 6px; border-radius: 3px;">Rascunho</span>
                    @elseif($evento->status === 'encerrado')
                        <span style="background-color: #d1d5db; color: #374151; padding: 2px 6px; border-radius: 3px;">Encerrado</span>
                    @else
                        <span style="background-color: #fecaca; color: #7f1d1d; padding: 2px 6px; border-radius: 3px;">Cancelado</span>
                    @endif
                </td>
                <td class="text-center">{{ $evento->participantes->count() }}</td>
                <td>{{ $evento->local ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Nenhum evento encontrado</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Este relatório foi gerado automaticamente pelo sistema TLC Admin</p>
    </div>
</body>
</html>
