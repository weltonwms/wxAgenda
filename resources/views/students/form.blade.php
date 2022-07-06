<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
            aria-selected="true">Geral</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="usuario_t-tab" data-toggle="tab" href="#usuario_t" role="tab"
            aria-controls="usuario_t" aria-selected="false">Usuário</a>
    </li>

    <li class="nav-item" role="presentation">
        <a class="nav-link" id="creditos_t-tab" data-toggle="tab" href="#creditos_t" role="tab"
            aria-controls="creditos_t" aria-selected="false">Créditos</a>
    </li>

</ul>
<div class="tab-content" id="myTabContent">

    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
    <br>
        @include('students.form-geral')
    </div>

    <div class="tab-pane fade" id="usuario_t" role="tabpanel" aria-labelledby="usuario_t-tab">
    <br>
    <div class="col-sm-6"> @include('users.form') </div>
       
    </div>

    <div class="tab-pane fade" id="creditos_t" role="tabpanel" aria-labelledby="creditos_t-tab">
    <br>
        @include('students.credits.index')
    </div>

</div>



@push('scripts')
<script>
$('input#email').change(function(e) {

    $('input#username').val(e.currentTarget.value)
});
</script>

@endpush