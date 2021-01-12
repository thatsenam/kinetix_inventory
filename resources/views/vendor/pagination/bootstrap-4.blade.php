<!-- Pagination -->
<div class="row mt-30">
    <div class="col">
    @if ($paginator->hasPages())
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span aria-hidden="true"><i class="mr-3 fa fa-angle-left"></i>Back</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')"><i class="fa fa-angle-left"></i>Back</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled" aria-disabled="true"><a>{{ $element }}</a></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active" aria-current="page"><a>{{ $page }}</a></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach
            {{-- Next Page Link --}}
            @if($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}">Next <i class="fa fa-angle-right"></i></a>
                </li>
            @else
                <li class="disabled" aria-disabled="true">
                    <span aria-hidden="true">Next <i class="ml-3 fa fa-angle-right"></i></span>
                </li>
            @endif
        </ul>
    @endif
    </div>
</div>

