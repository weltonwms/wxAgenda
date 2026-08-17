<div class="form-row">
    <div class="col-md-12">
        <x-form.select name="module_id" :list="$modulesList" :value="$restriction->module_id ?? null" label="Módulo *" placeholder="--Selecione--" class="select2" />
    </div>
    <div class="col-md-12">
        <x-form.select name="disciplina_id" :list="$disciplinasList" :value="$restriction->disciplina_id ?? null" label="Disciplina *" placeholder="--Selecione--" class="select2" />
    </div>

    <div class="col-md-6">
        <x-form.number name="level_start" :value="$restriction->level_start ?? null" label="Nível Início*" min="1" />
    </div>

    <div class="col-md-6">
        <x-form.number name="level_end" :value="$restriction->level_end ?? null" label="Nível Fim*" min="2" />
    </div>


</div>
