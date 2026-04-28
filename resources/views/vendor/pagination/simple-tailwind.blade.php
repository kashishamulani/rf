@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex gap-2 items-center justify-between">

        {{-- Previous Button --}}
@if ($paginator->onFirstPage())
    <span class="pg-btn disabled">
        ‹
    </span>
@else
    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pg-btn">
        ‹
    </a>
@endif

{{-- Next Button --}}
@if ($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pg-btn">
        ›
    </a>
@else
    <span class="pg-btn disabled">
        ›
    </span>
@endif
