@props([
    'title' => 'Tabela',
    'items',
    'columns',
    'actions' => ['view', 'edit', 'delete'],
    'resourceName' => 'items',
    'emptyMessage' => 'Nenhum item encontrado',
    'pagination' => false,
    'createRoute' => null,
    'createLabel' => 'Novo',
])

<div x-data="{
    selectedRows: [],
    selectAll: false,
    tableData: @json($items->map(fn($item) => [
        'id' => $item->id,
        'data' => $item
    ])->toArray()),
    handleSelectAll() {
        this.selectAll = !this.selectAll;
        if (this.selectAll) {
            this.selectedRows = this.tableData.map(row => row.id);
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
        formData.append('_token', document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content'));

        fetch(`/{{ $resourceName }}/delete-multiple`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content'),
            }
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  window.location.reload();
              } else {
                  alert('Erro ao deletar itens: ' + (data.message || 'Erro desconhecido'));
              }
          });
    }
}">
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
        <!-- Header -->
        <div class="flex flex-col gap-4 px-6 py-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-100 dark:border-white/[0.05]">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    {{ $title }}
                </h3>
            </div>
            <div class="flex items-center gap-3">
                @if(count($selectedRows = []) > 0 || count($items) > 0)
                    <button @if(count($items) > 0) @click="deleteSelected()" @else disabled @endif
                            class="inline-flex items-center gap-2 rounded-lg border border-red-300 bg-white px-4 py-3 text-theme-sm font-medium
                                   @if(count($items) > 0) text-red-600 hover:bg-red-50 cursor-pointer dark:border-red-900 dark:text-red-400 dark:hover:bg-red-950
                                   @else text-gray-400 cursor-not-allowed dark:text-gray-600 @endif">
                        <svg class="stroke-current" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Deletar Selecionados
                    </button>
                @endif

                @if($createRoute)
                    <a href="{{ $createRoute }}" class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-4 py-3 text-theme-sm font-medium hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800">
                        <svg class="fill-current" width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5v14m7-7H5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        {{ $createLabel }}
                    </a>
                @endif
            </div>
        </div>

        <!-- Table -->
        <div class="max-w-full overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-gray-100 dark:border-white/[0.05] bg-gray-50 dark:bg-gray-900">
                    <tr>
                        @if(in_array('select', $actions) || count($actions) === 0)
                            <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">
                                <div class="flex items-center gap-3">
                                    <div @click="handleSelectAll()"
                                        class="flex h-5 w-5 cursor-pointer items-center justify-center rounded-md border-[1.25px]"
                                        :class="selectAll ? 'border-blue-500 dark:border-blue-500 bg-blue-500' : 'bg-white dark:bg-white/0 border-gray-300 dark:border-gray-700'">
                                        <svg :class="selectAll ? 'block' : 'hidden'" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M11.6668 3.5L5.25016 9.91667L2.3335 7" stroke="white" stroke-width="1.94437" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                </div>
                            </th>
                        @endif

                        @foreach($columns as $column)
                            <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">
                                {{ $column['label'] ?? ucfirst($column['name']) }}
                            </th>
                        @endforeach

                        @if(count($actions) > 0)
                            <th class="px-6 py-3 font-medium text-gray-500 text-theme-xs dark:text-gray-400 text-start">
                                Ações
                            </th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr class="border-b border-gray-100 dark:border-white/[0.05] hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                            @if(in_array('select', $actions) || count($actions) === 0)
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div @click="handleRowSelect({{ $item->id }})"
                                            class="flex h-5 w-5 cursor-pointer items-center justify-center rounded-md border-[1.25px]"
                                            :class="selectedRows.includes({{ $item->id }}) ? 'border-blue-500 dark:border-blue-500 bg-blue-500' : 'bg-white dark:bg-white/0 border-gray-300 dark:border-gray-700'">
                                            <svg :class="selectedRows.includes({{ $item->id }}) ? 'block' : 'hidden'" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11.6668 3.5L5.25016 9.91667L2.3335 7" stroke="white" stroke-width="1.94437" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                    </div>
                                </td>
                            @endif

                            @foreach($columns as $column)
                                <td class="px-6 py-3.5">
                                    @php
                                        $value = $item;
                                        $columnName = $column['name'];

                                        foreach(explode('.', $columnName) as $key) {
                                            if ($value !== null) {
                                                $value = is_object($value) ? ($value->{$key} ?? null) : ($value[$key] ?? null);
                                            }
                                        }
                                    @endphp

                                    @if(isset($column['render']))
                                        {!! $column['render']($item) !!}
                                    @elseif(isset($column['badge']))
                                        @if($column['badge'] === 'status')
                                            <span class="inline-block px-3 py-1 rounded-full text-theme-xs font-medium
                                                @if($item->ativo)
                                                    bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500
                                                @else
                                                    bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400
                                                @endif">
                                                {{ $item->ativo ? 'Ativo/a' : 'Inativo/a' }}
                                            </span>
                                        @else
                                            <span class="inline-block px-3 py-1 rounded-full text-theme-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-500">
                                                {{ $value ?? '-' }}
                                            </span>
                                        @endif
                                    @else
                                        <p class="text-gray-700 text-theme-sm dark:text-gray-400">
                                            {{ $value ?? '-' }}
                                        </p>
                                    @endif
                                </td>
                            @endforeach

                            @if(count($actions) > 0)
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-3">
                                        @if(in_array('view', $actions))
                                            <a href="{{ route($resourceName . '.show', $item) }}"
                                               class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10"
                                               title="Visualizar">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                        @endif

                                        @if(in_array('edit', $actions))
                                            <a href="{{ route($resourceName . '.edit', $item) }}"
                                               class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-500/10"
                                               title="Editar">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                        @endif

                                        @if(in_array('delete', $actions))
                                            <form action="{{ route($resourceName . '.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja deletar este item?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10"
                                                        title="Deletar">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) + (in_array('select', $actions) ? 2 : 1) }}" class="px-6 py-12 text-center text-gray-500">
                                {{ $emptyMessage }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pagination && $items instanceof \Illuminate\Pagination\Paginator)
            <div class="px-6 py-4 border-t border-gray-100 dark:border-white/[0.05]">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</div>
