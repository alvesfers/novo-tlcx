<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Dirigentes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        h1 {
            color: #1f2937;
            border-bottom: 3px solid #10b981;
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
            width: 30%;
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
            background-color: #10b981;
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
    <h1>👥 Relatório de Dirigentes</h1>

    <div class="header-info">
        <p><strong>Entidade:</strong> {{ $entidade?->nome ?? 'Sistema Completo' }}</p>
        <p><strong>Gerado em:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
        <p><strong>Usuário:</strong> {{ auth()->user()->name }}</p>
    </div>

    <h2>Resumo de Dirigentes</h2>
    <div class="resumo">
        <div class="resumo-item">
            <label>Total</label>
            <value>{{ $resumo['total'] }}</value>
        </div>
        <div class="resumo-item">
            <label>Ativos</label>
            <value>{{ $resumo['ativos'] }}</value>
        </div>
        <div class="resumo-item">
            <label>Inativos</label>
            <value>{{ $resumo['inativos'] }}</value>
        </div>
    </div>

    <h2>Detalhes dos Dirigentes</h2>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>UUID</th>
                <th>Entidade Principal</th>
                <th>Cargo</th>
                <th class="text-center">Data de Nascimento</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dirigentes as $dirigente)
            <tr>
                <td><strong>{{ $dirigente->nome }}</strong></td>
                <td><small>{{ substr($dirigente->uuid, 0, 8) }}...</small></td>
                <td>{{ $dirigente->vinculos->first()?->entidade->nome ?? '-' }}</td>
                <td>{{ $dirigente->vinculos->first()?->pivot->cargo ?? '-' }}</td>
                <td class="text-center">{{ $dirigente->data_nascimento?->format('d/m/Y') ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Nenhum dirigente encontrado</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Este relatório foi gerado automaticamente pelo sistema TLC Admin</p>
    </div>
</body>
</html>
