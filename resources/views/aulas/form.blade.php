<div class="form-row">
    <div class="col-md-12">
        <x-form.select name="module_id" :list="$modulesList" :value="$aula->module_id ?? null" label="Módulo *" placeholder="--Selecione--" class="select2" />
    </div>
    <div class="col-md-12">
        <x-form.select name="disciplina_id" :list="$disciplinasList" :value="$aula->disciplina_id ?? null" label="Disciplina *" placeholder="--Selecione--" class="select2" />
    </div>

    <div class="col-md-6">
        <x-form.text name="sigla" :value="$aula->sigla ?? null" label="Sigla *" />
    </div>

    <div class="col-md-6">
        <x-form.number name="ordem" :value="$aula->ordem ?? null" label="Ordem *" min="1" />
    </div>


</div>
