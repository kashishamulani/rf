@if ($paginator->hasPages())
<nav class="mt-4">

    {{-- MOBILE PAGINATION --}}
    <div class="d-flex justify-content-between d-sm-none">
        <ul class="pagination w-100">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled w-50">
                    <span class="page-link text-center">‹ Previous</span>
                </li>
            @else
                <li class="page-item w-50">
                    <a class="page-link text-center" href="{{ $paginator->previousPageUrl() }}">‹ Previous</a>
                </li>
            @endif

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li class="page-item w-50">
                    <a class="page-link text-center" href="{{ $paginator->nextPageUrl() }}">Next ›</a>
                </li>
            @else
                <li class="page-item disabled w-50">
                    <span class="page-link text-center">Next ›</span>
                </li>
            @endif
        </ul>
    </div>

    {{-- DESKTOP PAGINATION --}}
    <div class="d-none d-sm-flex justify-content-between align-items-center">

        {{-- Showing Results --}}
        <p class="small text-muted mb-0">
            Showing
            <strong>{{ $paginator->firstItem() }}</strong>
            to
            <strong>{{ $paginator->lastItem() }}</strong>
            of
            <strong>{{ $paginator->total() }}</strong>
            results
        </p>

        {{-- Page Numbers --}}
        <ul class="pagination mb-0">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">‹</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}">‹</a>
                </li>
            @endif

            {{-- Pages --}}
            @foreach ($elements as $element)

                @if (is_string($element))
                    <li class="page-item disabled">
                        <span class="page-link">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif

            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}">›</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">›</span>
                </li>
            @endif

        </ul>
    </div>

</nav>
@endif
