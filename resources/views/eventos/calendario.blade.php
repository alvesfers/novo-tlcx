@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Calendário de Eventos" />

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
        <!-- Filtros -->
        <div class="mb-8 pb-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-400">Filtros</h3>
                <button type="button" id="btnLimpar" class="text-xs font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 underline">
                    Limpar todos
                </button>
            </div>

            <div class="space-y-3">
                <!-- Meus Eventos -->
                <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                    <input type="checkbox" id="meusEventos" class="w-4 h-4 rounded border-gray-300 text-blue-600 cursor-pointer">
                    <label for="meusEventos" class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                        Apenas meus eventos
                    </label>
                </div>

                <!-- Dioceses -->
                @php
                    $dioceses = $entidades->where('tipo_entidade', 'diocese');
                @endphp
                @if($dioceses->isNotEmpty())
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-3">
                        <button type="button" class="filtro-toggle flex w-full items-center justify-between" data-target="dioceses">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Dioceses</span>
                            <svg class="filtro-icon w-4 h-4 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        </button>
                        <div id="dioceses" class="filtro-content hidden mt-3 space-y-2">
                            @foreach($dioceses as $entidade)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" value="{{ $entidade->id }}" class="filtro-checkbox w-4 h-4 rounded border-gray-300 text-blue-600">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $entidade->nome }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Núcleos -->
                @php
                    $nucleos = $entidades->where('tipo_entidade', 'nucleo');
                @endphp
                @if($nucleos->isNotEmpty())
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-3">
                        <button type="button" class="filtro-toggle flex w-full items-center justify-between" data-target="nucleos">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Núcleos</span>
                            <svg class="filtro-icon w-4 h-4 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        </button>
                        <div id="nucleos" class="filtro-content hidden mt-3 space-y-2">
                            @foreach($nucleos as $entidade)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" value="{{ $entidade->id }}" class="filtro-checkbox w-4 h-4 rounded border-gray-300 text-blue-600">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $entidade->nome }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Secretarias -->
                @php
                    $secretarias = $entidades->where('tipo_entidade', 'secretaria');
                @endphp
                @if($secretarias->isNotEmpty())
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-3">
                        <button type="button" class="filtro-toggle flex w-full items-center justify-between" data-target="secretarias">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Secretarias</span>
                            <svg class="filtro-icon w-4 h-4 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        </button>
                        <div id="secretarias" class="filtro-content hidden mt-3 space-y-2">
                            @foreach($secretarias as $entidade)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" value="{{ $entidade->id }}" class="filtro-checkbox w-4 h-4 rounded border-gray-300 text-blue-600">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $entidade->nome }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Calendário -->
        <div class="mt-8">
            <!-- Header do Calendário -->
            <div class="flex items-center justify-between mb-6">
                <button id="prevMonth" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <h2 id="monthYear" class="text-2xl font-bold text-gray-900 dark:text-white min-w-[250px] text-center"></h2>
                <button id="nextMonth" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <!-- Grid de Dias -->
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <!-- Cabeçalho da Semana -->
                <div class="grid grid-cols-7 bg-gray-50 dark:bg-gray-800">
                    <div class="p-4 text-center font-semibold text-sm text-gray-700 dark:text-gray-300">Dom</div>
                    <div class="p-4 text-center font-semibold text-sm text-gray-700 dark:text-gray-300">Seg</div>
                    <div class="p-4 text-center font-semibold text-sm text-gray-700 dark:text-gray-300">Ter</div>
                    <div class="p-4 text-center font-semibold text-sm text-gray-700 dark:text-gray-300">Qua</div>
                    <div class="p-4 text-center font-semibold text-sm text-gray-700 dark:text-gray-300">Qui</div>
                    <div class="p-4 text-center font-semibold text-sm text-gray-700 dark:text-gray-300">Sex</div>
                    <div class="p-4 text-center font-semibold text-sm text-gray-700 dark:text-gray-300">Sab</div>
                </div>

                <!-- Dias do Calendário -->
                <div id="calendarGrid" class="grid grid-cols-7 border-t border-gray-200 dark:border-gray-700">
                    <!-- Preenchido por JavaScript -->
                </div>
            </div>

            <!-- Legenda -->
            <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded bg-blue-500"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Publicado</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded bg-gray-400"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Rascunho</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded bg-red-500"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Cancelado</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded bg-gray-300"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Encerrado</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Detalhes -->
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
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Tipo de Evento</label>
                            <p id="eventType" class="text-gray-900 dark:text-white/90 mt-1 text-sm"></p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Responsável</label>
                            <p id="eventEntidade" class="text-gray-900 dark:text-white/90 mt-1 text-sm"></p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Data e Hora</label>
                            <p id="eventDateTime" class="text-gray-900 dark:text-white/90 mt-1 text-sm"></p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Local</label>
                            <p id="eventLocal" class="text-gray-900 dark:text-white/90 mt-1 text-sm"></p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Status</label>
                            <div id="eventStatus" class="mt-1"></div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Descrição</label>
                            <p id="eventDescricao" class="text-gray-900 dark:text-white/90 mt-1 text-sm whitespace-pre-wrap"></p>
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

    <script>
        let currentDate = new Date();
        let allEvents = [];

        const statusColors = {
            'publicado': { bg: 'bg-blue-100', text: 'text-blue-800', dot: 'bg-blue-500' },
            'rascunho': { bg: 'bg-gray-100', text: 'text-gray-800', dot: 'bg-gray-400' },
            'cancelado': { bg: 'bg-red-100', text: 'text-red-800', dot: 'bg-red-500' },
            'encerrado': { bg: 'bg-gray-100', text: 'text-gray-800', dot: 'bg-gray-300' }
        };

        // Carregar eventos
        async function loadEvents() {
            try {
                const meusEventos = document.getElementById('meusEventos');
                const params = new URLSearchParams();

                if (meusEventos && meusEventos.checked) {
                    params.append('meus_eventos', '1');
                }

                const selectedEntidades = Array.from(document.querySelectorAll('.filtro-checkbox:checked')).map(cb => cb.value);
                if (selectedEntidades.length > 0) {
                    selectedEntidades.forEach(id => params.append('entidades[]', id));
                }

                const response = await fetch(`{{ route('eventos.calendario.get') }}?${params.toString()}`);
                allEvents = await response.json();
                renderCalendar();
            } catch (error) {
                console.error('Erro ao carregar eventos:', error);
                allEvents = [];
                renderCalendar();
            }
        }

        // Renderizar calendário
        function renderCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            // Atualizar título
            const monthNames = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
            document.getElementById('monthYear').textContent = `${monthNames[month]} ${year}`;

            // Calcular primeiro dia e quantidade de dias
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const daysInPrevMonth = new Date(year, month, 0).getDate();

            const grid = document.getElementById('calendarGrid');
            grid.innerHTML = '';

            // Dias do mês anterior
            for (let i = firstDay - 1; i >= 0; i--) {
                const day = daysInPrevMonth - i;
                const cell = createDayCell(day, month - 1, year, true);
                grid.appendChild(cell);
            }

            // Dias do mês atual
            for (let day = 1; day <= daysInMonth; day++) {
                const cell = createDayCell(day, month, year, false);
                grid.appendChild(cell);
            }

            // Dias do próximo mês
            const totalCells = grid.children.length;
            const remainingCells = 42 - totalCells;
            for (let day = 1; day <= remainingCells; day++) {
                const cell = createDayCell(day, month + 1, year, true);
                grid.appendChild(cell);
            }
        }

        // Criar célula de dia
        function createDayCell(day, month, year, isOtherMonth) {
            const date = new Date(year, month, day);
            const dateStr = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

            const cell = document.createElement('div');
            cell.className = `border border-gray-200 dark:border-gray-700 p-3 min-h-[120px] ${isOtherMonth ? 'bg-gray-50 dark:bg-gray-900/50' : 'bg-white dark:bg-gray-800/50'}`;

            // Número do dia
            const dayNumber = document.createElement('div');
            dayNumber.className = `text-sm font-semibold ${isOtherMonth ? 'text-gray-400' : 'text-gray-900 dark:text-white'}`;
            dayNumber.textContent = day;
            cell.appendChild(dayNumber);

            // Eventos do dia
            const dayEvents = allEvents.filter(event => {
                const eventDate = new Date(event.start);
                return eventDate.toISOString().split('T')[0] === dateStr;
            });

            const eventsContainer = document.createElement('div');
            eventsContainer.className = 'mt-2 space-y-1';

            dayEvents.forEach(event => {
                const eventEl = document.createElement('button');
                const color = statusColors[event.status] || statusColors['publicado'];
                eventEl.className = `block w-full text-left px-2 py-1 rounded text-xs font-medium truncate ${color.bg} ${color.text} hover:opacity-80 transition cursor-pointer`;
                eventEl.textContent = event.title;
                eventEl.onclick = (e) => {
                    e.preventDefault();
                    showEventDetails(event);
                };
                eventsContainer.appendChild(eventEl);
            });

            cell.appendChild(eventsContainer);
            return cell;
        }

        // Mostrar detalhes do evento
        function showEventDetails(event) {
            const modal = document.getElementById('eventModal');

            document.getElementById('eventModalTitle').textContent = event.title;
            document.getElementById('eventType').textContent = event.tipo || 'N/A';
            document.getElementById('eventEntidade').textContent = event.criadora || 'N/A';

            const startDate = new Date(event.start);
            const endDate = new Date(event.end || event.start);
            const dateTimeStr = `${startDate.toLocaleDateString('pt-BR')} até ${endDate.toLocaleDateString('pt-BR')}`;
            document.getElementById('eventDateTime').textContent = dateTimeStr;

            document.getElementById('eventLocal').textContent = event.local || 'Não informado';

            const statusLabels = {
                'rascunho': 'Rascunho',
                'publicado': 'Publicado',
                'encerrado': 'Encerrado',
                'cancelado': 'Cancelado'
            };

            const statusEl = document.getElementById('eventStatus');
            const color = statusColors[event.status] || statusColors['publicado'];
            statusEl.innerHTML = `<span class="inline-block px-3 py-1 rounded-full text-sm font-medium ${color.bg} ${color.text}">
                ${statusLabels[event.status] || 'N/A'}
            </span>`;

            document.getElementById('eventDescricao').textContent = event.description || 'Sem descrição';
            document.getElementById('btnVerDetalhes').href = `/eventos/${event.id}`;

            modal.classList.remove('hidden');
        }

        // Inicializar
        function initialize() {
            loadEvents();

            // Navegação do calendário
            document.getElementById('prevMonth').addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar();
            });

            document.getElementById('nextMonth').addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar();
            });

            // Filtros
            document.querySelectorAll('.filtro-toggle').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.getElementById(this.getAttribute('data-target'));
                    const icon = this.querySelector('.filtro-icon');
                    if (target) {
                        target.classList.toggle('hidden');
                        if (icon) icon.style.transform = target.classList.contains('hidden') ? '' : 'rotate(180deg)';
                    }
                });
            });

            document.querySelectorAll('.filtro-checkbox, #meusEventos').forEach(input => {
                input.addEventListener('change', loadEvents);
            });

            document.getElementById('btnLimpar').addEventListener('click', () => {
                document.getElementById('meusEventos').checked = false;
                document.querySelectorAll('.filtro-checkbox').forEach(cb => cb.checked = false);
                loadEvents();
            });

            // Modal
            document.querySelectorAll('.modal-close-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.getElementById('eventModal').classList.add('hidden');
                });
            });

            document.getElementById('eventModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', initialize);
    </script>
@endsection
