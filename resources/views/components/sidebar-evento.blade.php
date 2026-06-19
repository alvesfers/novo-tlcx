@props(['evento'])

@php
    use App\Models\Evento;

    // Se evento é um ID, carrega o modelo
    if (is_numeric($evento)) {
        $evento = Evento::find($evento);
    }

    $eventoId = $evento->id ?? null;
    $eventoNome = $evento->nome ?? 'EVENTO';
@endphp

<aside id="sidebar-evento"
    class="fixed flex flex-col mt-0 top-0 px-5 left-0 bg-white dark:bg-gray-900 dark:border-gray-800 text-gray-900 h-screen transition-all duration-300 ease-in-out z-40 border-r border-gray-200"
    x-data="{
        isActive(path) {
            return window.location.pathname === path || window.location.pathname.startsWith(path);
        }
    }"
    :class="{
        'w-[290px]': $store.sidebar.isExpanded || $store.sidebar.isMobileOpen || $store.sidebar.isHovered,
        'w-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
        'translate-x-0': $store.sidebar.isMobileOpen,
        '-translate-x-full xl:translate-x-0': !$store.sidebar.isMobileOpen
    }"
    @mouseenter="if (!$store.sidebar.isExpanded) $store.sidebar.setHovered(true)"
    @mouseleave="$store.sidebar.setHovered(false)">

    <!-- Logo Section -->
    <div class="pt-8 pb-7 flex"
        :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
        'xl:justify-center' :
        'justify-start'">
        <a href="/">
            <img x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                class="dark:hidden" src="/images/logo/logo.svg" alt="Logo" width="150" height="40" />
            <img x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                class="hidden dark:block" src="/images/logo/logo-dark.svg" alt="Logo" width="150"
                height="40" />
            <img x-show="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen"
                src="/images/logo/logo-icon.svg" alt="Logo" width="32" height="32" />
        </a>
    </div>

    <!-- Navigation Menu -->
    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar flex-1">
        <nav class="mb-6">
            <!-- Evento Header -->
            <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-800">
                <h2 class="text-xs uppercase flex leading-[20px] text-gray-400 font-semibold"
                    :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                    'lg:justify-center' : 'justify-start'">
                    <template
                        x-if="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen">
                        <span>EVENTO</span>
                    </template>
                </h2>
                <h3 x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                    class="mt-2 text-sm font-bold text-gray-900 dark:text-white truncate">
                    {{ $eventoNome }}
                </h3>
            </div>

            <!-- Menu Items -->
            <div class="flex flex-col gap-4">
                <ul class="flex flex-col gap-1">
                    <!-- Detalhes -->
                    <li>
                        <a href="{{ route('eventos.show', $eventoId) }}" class="menu-item group"
                            :class="[
                                isActive('/eventos/{{ $eventoId }}') ? 'menu-item-active' : 'menu-item-inactive',
                                (!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                                'xl:justify-center' :
                                'justify-start'
                            ]">
                            <span :class="isActive('/eventos/{{ $eventoId }}') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'">
                                <x-menu-icon icon="document" />
                            </span>
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                class="menu-item-text">Detalhes</span>
                        </a>
                    </li>

                    <!-- Preços -->
                    <li>
                        <a href="{{ route('eventos.valores.index', $eventoId) }}" class="menu-item group"
                            :class="[
                                isActive('/eventos/{{ $eventoId }}/valores') ? 'menu-item-active' : 'menu-item-inactive',
                                (!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                                'xl:justify-center' :
                                'justify-start'
                            ]">
                            <span :class="isActive('/eventos/{{ $eventoId }}/valores') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'">
                                <x-menu-icon icon="banknotes" />
                            </span>
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                class="menu-item-text">Preços</span>
                        </a>
                    </li>

                    <!-- Tipos de Camiseta -->
                    <li>
                        <a href="{{ route('eventos.tipos-camiseta.index', $eventoId) }}" class="menu-item group"
                            :class="[
                                isActive('/eventos/{{ $eventoId }}/tipos-camiseta') ? 'menu-item-active' : 'menu-item-inactive',
                                (!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                                'xl:justify-center' :
                                'justify-start'
                            ]">
                            <span :class="isActive('/eventos/{{ $eventoId }}/tipos-camiseta') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'">
                                <x-menu-icon icon="square-3-stack-3d" />
                            </span>
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                class="menu-item-text">Tipos de Camiseta</span>
                        </a>
                    </li>

                    <!-- Barzinhos -->
                    <li>
                        <a href="{{ route('barzinhos.index') }}" class="menu-item group"
                            :class="[
                                isActive('/barzinhos') ? 'menu-item-active' : 'menu-item-inactive',
                                (!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                                'xl:justify-center' :
                                'justify-start'
                            ]">
                            <span :class="isActive('/barzinhos') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'">
                                <x-menu-icon icon="shopping-bag" />
                            </span>
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                class="menu-item-text">Barzinhos</span>
                        </a>
                    </li>

                    <!-- Participantes -->
                    <li>
                        <a href="{{ route('eventos.edit', $eventoId) }}" class="menu-item group"
                            :class="[
                                isActive('/eventos/{{ $eventoId }}/edit') ? 'menu-item-active' : 'menu-item-inactive',
                                (!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                                'xl:justify-center' :
                                'justify-start'
                            ]">
                            <span :class="isActive('/eventos/{{ $eventoId }}/edit') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'">
                                <x-menu-icon icon="users" />
                            </span>
                            <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                class="menu-item-text">Participantes</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Voltar para Eventos -->
        <div x-data x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-transition class="mt-auto">
            <a href="{{ route('eventos.index') }}" class="menu-item group w-full border-t border-gray-200 dark:border-gray-800 pt-4 menu-item-inactive"
                :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ?
                'xl:justify-center' :
                'justify-start'">
                <span class="menu-item-icon-inactive">
                    <x-menu-icon icon="arrow-left" />
                </span>
                <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                    class="menu-item-text">Voltar para Eventos</span>
            </a>
        </div>
    </div>
</aside>
