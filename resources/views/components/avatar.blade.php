@props(['model', 'size' => 'md', 'class' => ''])

@php
    $sizes = [
        'sm' => 'w-6 h-6 text-xs',
        'md' => 'w-10 h-10 text-sm',
        'lg' => 'w-16 h-16 text-lg',
        'xl' => 'w-24 h-24 text-2xl',
    ];

    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $name = $model->nome ?? $model->name ?? 'U';
    $initials = strtoupper(substr($name, 0, 1));

    // Map model types to icons
    $iconClass = '';
    if (class_basename($model) === 'Dirigente') {
        $iconClass = 'icon-dirigente';
    } elseif (class_basename($model) === 'Entidade') {
        if ($model->tipo_entidade === 'diocese') {
            $iconClass = 'icon-diocese';
        } elseif ($model->tipo_entidade === 'nucleo') {
            $iconClass = 'icon-nucleo';
        } elseif ($model->tipo_entidade === 'secretaria') {
            $iconClass = 'icon-secretaria';
        }
    }
@endphp

@if($model->foto_arquivo || $model->foto_url)
    <img
        src="{{ $model->getFotoUrl() }}"
        alt="{{ $name }}"
        class="rounded-full object-cover border border-gray-200 {{ $sizeClass }} {{ $class }}"
    >
@else
    <div class="rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold {{ $sizeClass }} {{ $class }}">
        <svg class="w-1/2 h-1/2" fill="currentColor" viewBox="0 0 24 24">
            @if(class_basename($model) === 'Dirigente')
                <!-- Person icon for Dirigente -->
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            @elseif(class_basename($model) === 'Entidade')
                @if($model->tipo_entidade === 'diocese')
                    <!-- Diocese icon (church) -->
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/>
                @elseif($model->tipo_entidade === 'nucleo')
                    <!-- Nucleo icon (network) -->
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/>
                @elseif($model->tipo_entidade === 'secretaria')
                    <!-- Secretaria icon (briefcase) -->
                    <path d="M20 6h-2.15c-.74-1.6-2.49-2.7-4.5-2.7s-3.76 1.1-4.5 2.7H4c-1.1 0-1.99.9-1.99 2L2 19c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-4.5-1c.83 0 1.5.67 1.5 1.5S16.33 7 15.5 7 14 6.33 14 5.5 14.67 4 15.5 4z"/>
                @else
                    <!-- Generic icon -->
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                @endif
            @else
                <!-- Default icon -->
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
            @endif
        </svg>
    </div>
@endif
