
<?php

$estadosBrasileiros = config("constants.UFS");

?>


<div class="row">
    <div class="col-md-12">
        <label for="" class="control-label yesno">Aluno Ativo *</label>
        <x-form.yesno name="active" :value="$student->active ?? null" default="1" />
    </div>

    <div class="col-md-6">
        <x-form.text name="nome" :value="$student->nome ?? null" label="Nome *" />
    </div>

    <div class="col-md-4">
        <x-form.text name="email" :value="$student->email ?? null" label="Email *" />
    </div>

    <div class="col-md-2">
        <x-form.text name="cpf" :value="$student->cpf ?? null" label="CPF " oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
    </div>


    <div class="col-md-6">
        <x-form.text name="telefone" :value="$student->telefone ?? null" label="Telefone *" />

    </div>

    <div class="col md-4">
        <div style="width:97%">
        <x-form.select name="module_id" :list="$modulesList" :value="$student->module_id ?? null" label="Módulo Corrente" placeholder="--Selecione--" class="select2" />
        </div>
    </div>
    <div class="col-md-2">
        <x-form.number name="horas_contratadas" :value="$student->horas_contratadas ?? null" />
    </div>

    <div class="col-md-6">
        <x-form.text name="endereco" :value="$student->endereco ?? null" label="Endereço" />

    </div>
    <div class="col-md-4">
        <x-form.text name="cidade" :value="$student->cidade ?? null" label="Cidade" />

    </div>
    <div class="col-md-2">
        <x-form.select name="uf" :list="$estadosBrasileiros" :value="$student->uf ?? null" label="UF" placeholder="--Selecione--" class="select2" />

    </div>

    <div class="col-md-12">
        <x-form.text name="chat_id" :value="$student->chat_id ?? null" label="Telegram Chat Id" readonly="readonly" />
    </div>


</div>
