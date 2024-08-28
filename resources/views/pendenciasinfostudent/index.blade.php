@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="tile">
            @include("pendenciasinfostudent.main")
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
$('#modalCelula').on('hidden.bs.modal', function(e) {
    if (window.location.href.includes('pendenciasInfoStudentOnCelula')) {
        // Recarrega a página
        window.location.reload();
    }
})
</script>
@endpush