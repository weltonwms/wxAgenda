<div class="row">
    <div class="col-sm-3">
       <x-form.time name="horario" :value="isset($horario) ? $horario->horario : null" label="Horario *" />
     </div>

     <div class="col-sm-4">
        <x-form.select name="turno_id" :list="$turnosList" :value="isset($horario) ? $horario->turno_id : null" label="Turno *" placeholder="--Selecione--" class="select2" />
    </div>


</div>
