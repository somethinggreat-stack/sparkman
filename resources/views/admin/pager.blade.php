@if ($paginator->hasPages())
    @if ($paginator->onFirstPage())
        <span class="muted">Prev</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}">Prev</a>
    @endif

    @foreach ($elements as $element)
        @if (is_string($element))
            <span>{{ $element }}</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="active-page">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}">Next</a>
    @else
        <span class="muted">Next</span>
    @endif
@endif
