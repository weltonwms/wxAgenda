@error('base')
<div class="alert alert-danger">{{ $message }}</div>
@enderror

<div class="row">
    <div class="col-lg-12">
        {{ Form::bsText('nome',null,['label'=>"Nome *",'class'=>""]) }}
    </div>

    <div class="col-lg-3">
        <label for="" class="control-label yesno">Disciplina Base *</label>
        {{ Form::bsYesno('base','0') }}
    </div>

    <div class="col-lg-3">
        <label for="" class="control-label yesno">Review*</label>
        {{ Form::bsYesno('review','0') }}
    </div>


</div>