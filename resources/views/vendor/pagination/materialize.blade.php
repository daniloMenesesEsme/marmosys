@if ($paginator->hasPages())
    <div class="row">
        <div class="col s12">
            <ul class="pagination center-align">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li>
                        <a href="#!" class="disabled"><i class="material-icons">chevron_left</i></a>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev"><i class="material-icons">chevron_left</i></a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li>
                            <a href="#!" class="disabled">{{ $element }}</a>
                        </li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li>
                                    <a href="#!" class="active blue">{{ $page }}</a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li>
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="material-icons">chevron_right</i></a>
                    </li>
                @else
                    <li>
                        <a href="#!" class="disabled"><i class="material-icons">chevron_right</i></a>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col s12 center-align">
            <p class="grey-text">
                {!! __('Mostrando') !!}
                @if ($paginator->firstItem())
                    <span class="bold">{{ $paginator->firstItem() }}</span>
                    {!! __('até') !!}
                    <span class="bold">{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                {!! __('de') !!}
                <span class="bold">{{ $paginator->total() }}</span>
                {!! __('resultados') !!}
            </p>
        </div>
    </div>
@endif 