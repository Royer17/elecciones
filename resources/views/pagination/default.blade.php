{{-- @if ($paginator->lastPage() > 1)
<ul class="pagination">
    <li class="{{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
        <a href="{{ ($paginator->currentPage() == 1) ? '' : $paginator->url(1) }}" style="margin-right: 16px;"><<</a>
    </li>
    @for ($i = 1; $i <= $paginator->lastPage(); $i++)
        <li class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
            <a href="{{ ($paginator->currentPage() == $i) ? '' : $paginator->url($i) }}" style="margin-right: 16px;">{{ $i }}</a>
        </li>
    @endfor
    <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
        <a href="{{ ($paginator->currentPage() == $paginator->lastPage()) ? '' : $paginator->url($paginator->currentPage()+1) }}" >>></a>
    </li>
</ul>
@endif --}}

@if ($paginator->hasPages())
    <ul class="pagination">
        {{-- Previous Page Link --}}
         @if ($paginator->currentPage() == 1)
            <li class="disabled"><span style="margin-right: 16px;">«</span></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="margin-right: 16px;">«</a></li>
        @endif


        @if($paginator->currentPage() >= 3)
            <li class="hidden-xs"><a href="{{ $paginator->url(1) }}" style="margin-right: 16px;">1</a></li>
        @endif
        @if($paginator->currentPage() == 4)
            <li class="hidden-xs"><a href="{{ $paginator->url(2) }}" style="margin-right: 16px;">2</a></li>
        @endif
        @if($paginator->currentPage() > 4)
            <li><span style="margin-right: 16px;">...</span></li>
        @endif
        @foreach(range(1, $paginator->lastPage()) as $i)
            @if($i >= $paginator->currentPage() - 1 && $i <= $paginator->currentPage() + 1)
                @if ($i == $paginator->currentPage())
                    <li class="active"><span style="margin-right: 16px;">{{ $i }}</span></li>
                @else
                    <li><a href="{{ $paginator->url($i) }}" style="margin-right: 16px;">{{ $i }}</a></li>
                @endif
            @endif
        @endforeach
        @if($paginator->currentPage() < $paginator->lastPage() - 3)
            <li><span style="margin-right: 16px;">...</span></li>
        @endif
        @if($paginator->currentPage() < $paginator->lastPage() - 2)
            <li class="hidden-xs"><a href="{{ $paginator->url($paginator->lastPage()) }}" style="margin-right: 16px;">{{ $paginator->lastPage() }}</a></li>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">»</a></li>
        @else
            <li class="disabled"><span>»</span></li>
        @endif
    </ul>
@endif