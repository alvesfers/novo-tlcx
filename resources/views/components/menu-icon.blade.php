@php
    use App\Helpers\MenuHelper;
    $componentName = MenuHelper::getHeroiconComponent($icon);
@endphp

@switch($componentName)
    @case('heroicon-o-home')
        <x-heroicon-o-home class="w-5 h-5" />
        @break
    @case('heroicon-o-building-office-2')
        <x-heroicon-o-building-office-2 class="w-5 h-5" />
        @break
    @case('heroicon-o-users')
        <x-heroicon-o-users class="w-5 h-5" />
        @break
    @case('heroicon-o-calendar-days')
        <x-heroicon-o-calendar-days class="w-5 h-5" />
        @break
    @case('heroicon-o-tag')
        <x-heroicon-o-tag class="w-5 h-5" />
        @break
    @case('heroicon-o-user-plus')
        <x-heroicon-o-user-plus class="w-5 h-5" />
        @break
    @case('heroicon-o-banknotes')
        <x-heroicon-o-banknotes class="w-5 h-5" />
        @break
    @case('heroicon-o-chart-bar')
        <x-heroicon-o-chart-bar class="w-5 h-5" />
        @break
    @case('heroicon-o-clipboard-document-check')
        <x-heroicon-o-clipboard-document-check class="w-5 h-5" />
        @break
    @case('heroicon-o-qr-code')
        <x-heroicon-o-qr-code class="w-5 h-5" />
        @break
    @case('heroicon-o-code-bracket-square')
        <x-heroicon-o-code-bracket-square class="w-5 h-5" />
        @break
    @case('heroicon-o-square-3-stack-3d')
        <x-heroicon-o-square-3-stack-3d class="w-5 h-5" />
        @break
    @default
        <x-heroicon-o-circle-stack class="w-5 h-5" />
@endswitch
