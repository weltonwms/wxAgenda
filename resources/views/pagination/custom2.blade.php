@if ($paginator->hasPages())
    <div class="text-right">
        <span class="text-muted mr-2">Mostrando {{ $paginator->firstItem() }}-{{ $paginator->lastItem() }}  de {{ $paginator->total() }}</span>
        <div class="btn-group">
            <!-- Anterior Page Link -->
            @if ($paginator->onFirstPage())
                <button class="btn btn-primary btn-sm" type="button" disabled><i class="fa fa-chevron-left"></i></button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-primary btn-sm" rel="prev"><i class="fa fa-chevron-left"></i></a>
            @endif

            <!-- Próxima Page Link -->
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-primary btn-sm" rel="next"><i class="fa fa-chevron-right"></i></a>
            @else
                <button class="btn btn-primary btn-sm" type="button" disabled><i class="fa fa-chevron-right"></i></button>
            @endif
        </div>
    </div>
    
@endif
