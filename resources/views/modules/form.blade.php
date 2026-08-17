<div class="row">
    <div class="col-lg-12">
        <x-form.text name="nome" :value="$module->nome ?? null" label="Nome *" />
        <x-form.number name="ordem" :value="$module->ordem ?? null" label="Ordem *" min="1" />
     </div>


</div>
