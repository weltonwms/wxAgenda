<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
            aria-selected="true">Geral</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="usuario_t-tab" data-toggle="tab" href="#usuario_t" role="tab" aria-controls="usuario_t"
            aria-selected="false">Usuário</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="disponibilidade_t-tab" data-toggle="tab" href="#disponibilidade_t" role="tab"
            aria-controls="disponibilidade_t" aria-selected="false">Disponibilidade</a>
    </li>

</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <br>
        <div class="row">
            <div class="col-sm-12">
                <label for="" class="control-label yesno">Professor Ativo *</label>
                <x-form.yesno name="active" :value="$teacher->active ?? null" default="1" />
            </div>

            <div class="col-lg-12">
                <x-form.text name="nome" :value="$teacher->nome ?? null" label="Nome *" />
                <x-form.text name="email" :value="$teacher->email ?? null" label="Email " />
                <x-form.text name="telefone" :value="$teacher->telefone ?? null" label="Telefone " />

            </div>

            <div class="col-md-12">
                <x-form.text name="chat_id" :value="$teacher->chat_id ?? null" label="Telegram Chat Id" readonly="readonly" />
            </div>


        </div>
    </div>

    <div class="tab-pane fade" id="usuario_t" role="tabpanel" aria-labelledby="usuario_t-tab">
        <br>
        <div class="col-sm-6"> @include('users.form') </div>
    </div>

    <div class="tab-pane fade" id="disponibilidade_t" role="tabpanel" aria-labelledby="disponibilidade_t-tab">
        @include('teachers.disponibilidade')
    </div>

</div>

@push('scripts')
<script>
$('input#email').change(function(e) {

    $('input#username').val(e.currentTarget.value)
});
</script>

@endpush
