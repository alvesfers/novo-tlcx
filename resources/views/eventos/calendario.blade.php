@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Calendário de Eventos" />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
        <!-- Filtros -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Filtros</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Meus Eventos -->
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="meusEventos" class="w-4 h-4 rounded border-gray-300 text-blue-600">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Mostrar apenas meus eventos</span>
                    </label>
                </div>

                <!-- Filtro por Entidades -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Filtrar por Entidades
                    </label>
                    <div class="relative">
                        <select id="filtroEntidades" multiple class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:border-gray-700 dark:text-white" size="5">
                            @forelse($entidades->groupBy('tipo_entidade') as $tipo => $grupo)
                                <optgroup label="@php
                                    $tipoLabels = ['diocese' => 'Dioceses', 'nucleo' => 'Núcleos', 'secretaria' => 'Secretarias'];
                                    echo $tipoLabels[$tipo] ?? $tipo;
                                @endphp">
                                    @foreach($grupo as $entidade)
                                        <option value="{{ $entidade->id }}">
                                            {{ $entidade->nome }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @empty
                                <option disabled>Nenhuma entidade disponível</option>
                            @endforelse
                        </select>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            Segure Ctrl/Cmd para selecionar múltiplas entidades
                        </p>
                    </div>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="flex gap-2 mt-4">
                <button type="button" id="btnLimpar" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700">
                    Limpar Filtros
                </button>
            </div>
        </div>

        <!-- Calendário -->
        <div class="mt-8">
            <div id="calendar" class="min-h-screen"></div>
        </div>
    </div>

    <!-- Modal de Detalhes do Evento -->
    <div class="fixed inset-0 items-center justify-center hidden p-5 overflow-y-auto modal z-99999" id="eventModal">
        <div class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"></div>
        <div class="modal-dialog relative flex w-full max-w-2xl flex-col overflow-y-auto rounded-3xl bg-white p-6 lg:p-11 dark:bg-gray-900">

            <!-- Close Button -->
            <button class="modal-close-btn transition-color absolute top-5 right-5 z-999 flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-gray-600 sm:h-11 sm:w-11 dark:bg-white/[0.05] dark:text-gray-400 dark:hover:bg-white/[0.07] dark:hover:text-gray-300">
                <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.04289 16.5418C5.65237 16.9323 5.65237 17.5655 6.04289 17.956C6.43342 18.3465 7.06658 18.3465 7.45711 17.956L11.9987 13.4144L16.5408 17.9565C16.9313 18.347 17.5645 18.347 17.955 17.9565C18.3455 17.566 18.3455 16.9328 17.955 16.5423L13.4129 12.0002L17.955 7.45808C18.3455 7.06756 18.3455 6.43439 17.955 6.04387C17.5645 5.65335 16.9313 5.65335 16.5408 6.04387L11.9987 10.586L7.45711 6.04439C7.06658 5.65386 6.43342 5.65386 6.04289 6.04439C5.65237 6.43491 5.65237 7.06808 6.04289 7.4586L10.5845 12.0002L6.04289 16.5418Z" fill="" />
                </svg>
            </button>

            <div class="flex flex-col px-2 overflow-y-auto modal-content custom-scrollbar">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="mb-2 font-semibold text-gray-800 modal-title text-xl lg:text-2xl dark:text-white/90" id="eventModalTitle">
                        Detalhes do Evento
                    </h5>
                </div>

                <!-- Modal Body -->
                <div class="mt-6 modal-body">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase">Tipo</label>
                            <p id="eventType" class="text-gray-900 dark:text-white/90 mt-1"></p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase">Entidade Criadora</label>
                            <p id="eventEntidade" class="text-gray-900 dark:text-white/90 mt-1"></p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase">Data e Hora</label>
                            <p id="eventDateTime" class="text-gray-900 dark:text-white/90 mt-1"></p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase">Local</label>
                            <p id="eventLocal" class="text-gray-900 dark:text-white/90 mt-1"></p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase">Status</label>
                            <div id="eventStatus" class="mt-1"></div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase">Descrição</label>
                            <p id="eventDescricao" class="text-gray-900 dark:text-white/90 mt-1 whitespace-pre-wrap"></p>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center gap-3 mt-6 modal-footer sm:justify-end">
                    <a href="" id="btnVerDetalhes" class="flex w-full justify-center rounded-lg bg-blue-600 hover:bg-blue-700 px-4 py-2.5 text-sm font-medium text-white sm:w-auto">
                        Ver Detalhes Completos
                    </a>
                    <button type="button" class="modal-close-btn flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 sm:w-auto dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const calendarEl = document.getElementById('calendar');
                const filtroEntidades = document.getElementById('filtroEntidades');
                const meusEventos = document.getElementById('meusEventos');
                const btnLimpar = document.getElementById('btnLimpar');
                const eventModal = document.getElementById('eventModal');
                const modalCloseButtons = document.querySelectorAll('.modal-close-btn');

                // Configurar modal
                modalCloseButtons.forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        eventModal.classList.add('hidden');
                    });
                });

                eventModal.addEventListener('click', function(e) {
                    if (e.target === eventModal) {
                        eventModal.classList.add('hidden');
                    }
                });

                // Inicializar FullCalendar
                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    locale: 'pt-br',
                    height: 'auto',
                    events: function(info, successCallback, failureCallback) {
                        loadEventos(successCallback);
                    },
                    eventClick: function(info) {
                        showEventDetails(info.event);
                    }
                });

                calendar.render();

                // Função para carregar eventos
                function loadEventos(callback) {
                    const params = new URLSearchParams();

                    // Adicionar filtros
                    if (meusEventos.checked) {
                        params.append('meus_eventos', '1');
                    }

                    const selectedEntidades = Array.from(filtroEntidades.selectedOptions).map(opt => opt.value);
                    if (selectedEntidades.length > 0) {
                        selectedEntidades.forEach(id => params.append('entidades[]', id));
                    }

                    fetch(`{{ route('eventos.calendario.get') }}?${params.toString()}`)
                        .then(response => response.json())
                        .then(data => callback(data))
                        .catch(error => {
                            console.error('Erro ao carregar eventos:', error);
                            failureCallback(error);
                        });
                }

                // Função para mostrar detalhes do evento
                function showEventDetails(event) {
                    const extendedProps = event.extendedProps;

                    document.getElementById('eventModalTitle').textContent = event.title;
                    document.getElementById('eventType').textContent = extendedProps.tipo || 'N/A';
                    document.getElementById('eventEntidade').textContent = extendedProps.criadora || 'N/A';

                    const startDate = new Date(event.start);
                    const endDate = new Date(event.end);
                    const dateTimeStr = `${startDate.toLocaleDateString('pt-BR', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    })} até ${endDate.toLocaleDateString('pt-BR', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    })}`;

                    document.getElementById('eventDateTime').textContent = dateTimeStr;
                    document.getElementById('eventLocal').textContent = extendedProps.local || 'Não informado';

                    const statusLabels = {
                        'rascunho': 'Rascunho',
                        'publicado': 'Publicado',
                        'encerrado': 'Encerrado',
                        'cancelado': 'Cancelado'
                    };
                    const statusColors = {
                        'rascunho': 'bg-gray-100 text-gray-800',
                        'publicado': 'bg-blue-100 text-blue-800',
                        'encerrado': 'bg-gray-100 text-gray-800',
                        'cancelado': 'bg-red-100 text-red-800'
                    };

                    const statusBadge = document.getElementById('eventStatus');
                    statusBadge.innerHTML = `<span class="inline-block px-3 py-1 rounded-full text-sm font-medium ${statusColors[extendedProps.status] || 'bg-gray-100 text-gray-800'}">
                        ${statusLabels[extendedProps.status] || 'N/A'}
                    </span>`;

                    document.getElementById('eventDescricao').textContent = extendedProps.description || 'Sem descrição';

                    const btnVerDetalhes = document.getElementById('btnVerDetalhes');
                    btnVerDetalhes.href = `/eventos/${event.id}`;

                    eventModal.classList.remove('hidden');
                }

                // Event listeners para filtros
                filtroEntidades.addEventListener('change', () => calendar.refetchEvents());
                meusEventos.addEventListener('change', () => calendar.refetchEvents());

                // Limpar filtros
                btnLimpar.addEventListener('click', () => {
                    filtroEntidades.selectedIndex = -1;
                    meusEventos.checked = false;
                    calendar.refetchEvents();
                });
            });
        </script>
    @endpush
@endsection
