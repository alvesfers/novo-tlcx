<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} | TLC Admin</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Table Component -->
    <script>
        window.initTable = (resourceName) => ({
            selectedRows: [],
            selectAll: false,
            resourceName: resourceName,
            handleSelectAll() {
                this.selectAll = !this.selectAll;
                if (this.selectAll) {
                    document.querySelectorAll('[data-row-id]').forEach(el => {
                        const id = parseInt(el.dataset.rowId);
                        if (!this.selectedRows.includes(id)) this.selectedRows.push(id);
                    });
                } else {
                    this.selectedRows = [];
                }
            },
            handleRowSelect(id) {
                if (this.selectedRows.includes(id)) {
                    this.selectedRows = this.selectedRows.filter(rowId => rowId !== id);
                } else {
                    this.selectedRows.push(id);
                }
            },
            deleteSelected() {
                if (this.selectedRows.length === 0) {
                    alert('Selecione pelo menos um item');
                    return;
                }
                if (!confirm('Tem certeza que deseja deletar os itens selecionados?')) {
                    return;
                }
                const formData = new FormData();
                formData.append('ids', JSON.stringify(this.selectedRows));
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                fetch(`/${this.resourceName}/delete-multiple`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                }).then(response => response.json())
                  .then(data => {
                      if (data.success) window.location.reload();
                      else alert('Erro ao deletar itens: ' + (data.message || 'Erro desconhecido'));
                  });
            }
        });
    </script>

    <!-- Theme Store -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                init() {
                    const savedTheme = localStorage.getItem('theme');
                    const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' :
                        'light';
                    this.theme = savedTheme || systemTheme;
                    this.updateTheme();
                },
                theme: 'light',
                toggle() {
                    this.theme = this.theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', this.theme);
                    this.updateTheme();
                },
                updateTheme() {
                    const html = document.documentElement;
                    const body = document.body;
                    if (this.theme === 'dark') {
                        html.classList.add('dark');
                        body.classList.add('dark', 'bg-gray-900');
                    } else {
                        html.classList.remove('dark');
                        body.classList.remove('dark', 'bg-gray-900');
                    }
                }
            });

            Alpine.store('sidebar', {
                // Initialize based on screen size
                isExpanded: window.innerWidth >= 1280, // true for desktop, false for mobile
                isMobileOpen: false,
                isHovered: false,

                toggleExpanded() {
                    this.isExpanded = !this.isExpanded;
                    // When toggling desktop sidebar, ensure mobile menu is closed
                    this.isMobileOpen = false;
                },

                toggleMobileOpen() {
                    this.isMobileOpen = !this.isMobileOpen;
                    // Don't modify isExpanded when toggling mobile menu
                },

                setMobileOpen(val) {
                    this.isMobileOpen = val;
                },

                setHovered(val) {
                    // Only allow hover effects on desktop when sidebar is collapsed
                    if (window.innerWidth >= 1280 && !this.isExpanded) {
                        this.isHovered = val;
                    }
                }
            });
        });
    </script>

    <!-- Apply dark mode immediately to prevent flash -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const theme = savedTheme || systemTheme;
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                document.body.classList.add('dark', 'bg-gray-900');
            } else {
                document.documentElement.classList.remove('dark');
                document.body.classList.remove('dark', 'bg-gray-900');
            }
        })();
    </script>
    
</head>

<body
    x-data="{ 'loaded': true}"
    x-init="$store.sidebar.isExpanded = window.innerWidth >= 1280;
    const checkMobile = () => {
        if (window.innerWidth < 1280) {
            $store.sidebar.setMobileOpen(false);
            $store.sidebar.isExpanded = false;
        } else {
            $store.sidebar.isMobileOpen = false;
            $store.sidebar.isExpanded = true;
        }
    };
    window.addEventListener('resize', checkMobile);">

    {{-- preloader --}}
    <x-common.preloader/>
    {{-- preloader end --}}

    @php
        // Detectar se estamos em contexto de evento
        $eventId = null;
        $isEventContext = false;

        // Verificar se a rota tem um parâmetro 'evento'
        if (request()->route('evento')) {
            $eventId = request()->route('evento')?->id ?? request()->route('evento');
            $isEventContext = true;
        }
        // Alternativa: verificar pelo padrão de URL
        elseif (preg_match('/^eventos\/(\d+)/', request()->path())) {
            preg_match('/^eventos\/(\d+)/', request()->path(), $matches);
            $eventId = $matches[1];
            $isEventContext = true;
        }
    @endphp

    <div class="min-h-screen xl:flex">
        @include('layouts.backdrop')
        @if($isEventContext && $eventId)
            @include('components.sidebar-evento', ['evento' => $eventId])
        @else
            @include('layouts.sidebar')
        @endif

        <div class="flex-1 transition-all duration-300 ease-in-out"
            :class="{
                'xl:ml-[290px]': $store.sidebar.isExpanded || $store.sidebar.isHovered,
                'xl:ml-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
                'ml-0': $store.sidebar.isMobileOpen
            }">
            <!-- app header start -->
            @include('layouts.app-header')
            <!-- app header end -->
            <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
                @yield('content')
            </div>
        </div>

    </div>

</body>

@stack('scripts')

</html>
