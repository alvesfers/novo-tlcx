<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sucesso - {{ $evento->nome }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="w-full max-w-2xl">
            <!-- Card de Sucesso -->
            <div class="bg-white rounded-lg shadow-lg p-8 md:p-12 text-center">
                <!-- Ícone de Sucesso -->
                <div class="flex justify-center mb-6">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>

                <!-- Mensagem -->
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Obrigado!
                </h1>

                <p class="text-lg text-gray-700 mb-2">
                    Seu cadastro foi recebido com sucesso.
                </p>

                <p class="text-gray-600 mb-8">
                    Você está registrado como dirigente de <strong>{{ $evento->nome }}</strong>. Em breve você receberá mais informações sobre o evento.
                </p>

                <!-- Informações do Evento -->
                <div class="bg-gray-50 rounded-lg p-6 mb-8">
                    <h2 class="font-bold text-gray-900 mb-3">Informações do Evento</h2>
                    <div class="space-y-2 text-sm text-gray-700">
                        <p><strong>Evento:</strong> {{ $evento->nome }}</p>
                        <p><strong>Data:</strong> {{ $evento->data_inicio->format('d/m/Y') }}{{ $evento->data_fim ? ' até ' . $evento->data_fim->format('d/m/Y') : '' }}</p>
                        @if ($evento->local)
                            <p><strong>Local:</strong> {{ $evento->local }}</p>
                        @endif
                        @if ($evento->entidadeCriadora)
                            <p><strong>Organizado por:</strong> {{ $evento->entidadeCriadora->nome }}</p>
                        @endif
                    </div>
                </div>

                <!-- Botão Voltar -->
                <a href="{{ url('/') }}" class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-blue-700 transition">
                    ← Voltar para o Site
                </a>
            </div>

            <!-- Rodapé -->
            <div class="mt-8 text-center text-gray-600 text-sm">
                <p>Se tiver dúvidas, entre em contato com os organizadores do evento.</p>
            </div>
        </div>
    </div>
</body>
</html>
