@if ($paginator->hasPages())
    <nav>
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                @if ($paginator->currentPage() > 1)
                    Mostrando {{ $paginator->firstItem() }} a {{ $paginator->lastItem() }} de {{ $paginator->total() }} resultados
                @else
                    Mostrando 1 a {{ $paginator->lastItem() }} de {{ $paginator->total() }} resultados
                @endif
            </div>

            <ul class="flex gap-2">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li>
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-600 cursor-not-allowed">
                            &lt;
                        </span>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white text-gray-700 hover:bg-gray-50 border border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                            &lt;
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li>
                            <span class="inline-flex items-center justify-center w-9 h-9 text-gray-500">{{ $element }}</span>
                        </li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li>
                                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-600 text-white font-medium">
                                        {{ $page }}
                                    </span>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white text-gray-700 hover:bg-gray-50 border border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                        {{ $page }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li>
                        <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white text-gray-700 hover:bg-gray-50 border border-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                            &gt;
                        </a>
                    </li>
                @else
                    <li>
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-600 cursor-not-allowed">
                            &gt;
                        </span>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
@endif
