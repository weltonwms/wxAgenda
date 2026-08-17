<div class="row">
    <div class="col-lg-12">
        <x-form.text name="nome" :value="$administrator->nome ?? null" label="Nome *" />

        @include('users.form')
    </div>


</div>
