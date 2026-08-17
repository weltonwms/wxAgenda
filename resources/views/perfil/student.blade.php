<h5 class=" text-center">Dados Pessoais</h5>

@php $student = auth()->user()->student; @endphp

<form method="POST" action="{{ url('perfil/saveDadosStudent') }}">
    @csrf

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="row">
            <div class="col-md-12">
                <x-form.text name="nome" :value="$student->nome ?? null" label="Nome *" />
            </div>
            <div class="col-md-6">
                <x-form.text name="cpf" :value="$student->cpf ?? null" label="CPF " oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
            </div>
            <div class="col-md-6">
                <x-form.text name="telefone" :value="$student->telefone ?? null" label="Telefone *" />
            </div>
            <div class="col-md-12">
                <x-form.text name="endereco" :value="$student->endereco ?? null" label="Endereço" />
            </div>
            <div class="col-md-6">
                <x-form.text name="cidade" :value="$student->cidade ?? null" />
            </div>
            <div class="col-md-6">
            <x-form.select name="uf" :list="config('constants.UFS')" :value="$student->uf ?? null" label="UF" placeholder="--Selecione--" class="select2" />
            </div>
        </div>
    </div>
</div>
<div class="text-center">
    <button type="submit" class="btn btn-primary">
        <i class="fa fa-fw fa-lg fa-check-circle"></i>
        Salvar Dados
    </button>
</div>

</form>
