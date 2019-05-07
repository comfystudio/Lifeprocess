@if ($paginator->hasPages())
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link"><i class="fa fa-fast-backward"></i></span>
            </li>
            <li class="page-item disabled">
                <span class="page-link"><i class="fa fa-play fa-rotate-180"></i></span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->url($page = 1) }}" rel="firstItem"><i class="fa fa-fast-backward"></i></a>
            </li>
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"><i class="fa fa-play fa-rotate-180"></i></a>
            </li>
        @endif

        {{-- Pagination Elements
        @foreach ($elements as $element)
            <!- - "Three Dots" Separator - ->
            @if (is_string($element))
                <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
            @endif

            <!- - Array Of Links - ->
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach --}}
        <li class="page-item page-info">
            <a class="page-link" rel="next">({{$paginator->firstItem()}}-{{$paginator->lastItem()}} of {{$paginator->total()}})</a>
        </li>
        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="fa fa-play"></i></a>
            </li>
            <li class="page-item">
                <a href="{{$paginator->url($paginator->lastPage())}}"><i class="fa fa-fast-forward"></i></a>
            </li>            
        @else
            <li class="page-item disabled">
                <span class="page-link"><i class="fa fa-play"></i></span>
            </li>
            <li class="page-item disabled">
                <span class="page-link"><i class="fa fa-fast-forward"></i></span>
            </li>
        @endif
    </ul>
@endif
