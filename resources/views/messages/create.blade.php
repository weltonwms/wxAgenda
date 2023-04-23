<div class="tile">
    {!! Form::open(['route'=>['messages.store',request()->query()],'class'=>'','id'=>'adminForm'])!!}
    <div class="row">
        <div class="col-lg-12">
        {{ Form::bsSelect('recipient_id',$usersList,null,['label'=>"Para *", 'placeholder' => '--Selecione--','class'=>'select2']) }}
        {{ Form::bsText('subject',null,['label'=>"Assunto *",'class'=>""]) }}

            <div class="form-group">
                <label for="body">Texto</label>
                {{Form::textarea('body',null, ['rows'=>3,'class'=>'form-control','id'=>'body'])}}
                
                @if($errors->has('body'))
                <div class='invalid-feedback'> {{$errors->first('body')}}</div>
                @endif
            </div>

           
        </div>



    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="btn-group">
                <button class="btn btn-primary btn-sm mr-2" type="submit">
                    <i class="fa fa-share"></i> Enviar
                </button>
                <a class="btn btn-outline-secondary btn-sm" href="{{route('messages.index',request()->query())}}">
                    <i class="fa fa-close"></i> Cancelar
                </a>
                
            </div>
        </div>
    </div>


    {!! Form::close() !!}
</div>