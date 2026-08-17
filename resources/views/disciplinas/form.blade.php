@error('base')
<div class="alert alert-danger">{{ $message }}</div>
@enderror

<div class="row">
    <div class="col-lg-12">
        <x-form.text name="nome" :value="$disciplina->nome ?? null" label="Nome *" />
    </div>

    <div class="col-lg-3">
        <label for="" class="control-label yesno">Disciplina Base *</label>
        <x-form.yesno name="base" :value="$disciplina->base ?? null" default="0" />
    </div>

    <div class="col-lg-3">
        <label for="" class="control-label yesno">Review*</label>
        <x-form.yesno name="review" :value="$disciplina->review ?? null" default="0" />
    </div>


</div>
