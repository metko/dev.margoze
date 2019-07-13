@if ($paginator->hasPages())
    <ul class="pagination flex" role="navigation">
        {{-- Previous Page Link --}}
        
        <div class="px-3">
            @if ($paginator->onFirstPage())
                <li class="disabled " aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span aria-hidden="true">&lsaquo;</span>
                </li>
            @else
                <li >
                    <a href="{{ $paginator->previousPageUrl() }}" class="hover:text-orange-600" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                </li>
            @endif
        </div>

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active px-2 rounded-full bg-orange-600 text-white" aria-current="page"><span>{{ $page }}</span></li>
                    @else
                        <li class="px-2 hover:text-orange-600"><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        <div class="px-3">
            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" class="hover:text-orange-600" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                </li>
            @else
                <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span aria-hidden="true">&rsaquo;</span>
                </li>
            @endif
        </div>
    </ul>
@endif
