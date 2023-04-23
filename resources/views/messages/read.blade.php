<div class="tile">



<a class="btn btn-primary btn-sm" type="button" href="{{route('messages.index', request()->query() )}}">
    <i class="fa fa-reply"></i> Voltar
</a>

<div class="mt-3">
        <h4>{{$message->subject}}</h4>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <b>{{$message->sender->nome}}</b>
    </div>
    <div class="col-md-6">
       <span class="text-muted"> {{$message->created_at->format('d/m/Y H:i')}}</span>
    </div>

</div>

<div class="mt-3">
        <p>{!!nl2br($message->body)!!}</p>
</div>

<div class="replies">

@foreach($message->replies as $reply)
<div class="card  mt-3">
<div class="card-header">
    {{$reply->user->nome}} <span class="text-muted"> {{$reply->created_at->format('d/m/Y H:i')}}</span>
  </div>
  <div class="card-body">
    {!!nl2br($reply->reply_text)!!}
  </div>
</div>
@endforeach



</div>

<div class="mt-3">
<a class="btn btn-outline-secondary btn-sm" 
data-toggle="collapse" data-target="#formReply"
type="button" href="#">
    <i class="fa fa-reply"></i> Responder
</a>

<div class="collapse" id="formReply">
{!! Form::open(['route'=>'replies.store','class'=>'','id'=>'adminForm'])!!}
<input type="hidden" value="{{$message->id}}" name="message_id">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="replyText">Texto</label>
                {{Form::textarea('reply_text',null, ['rows'=>3,'class'=>'form-control','id'=>'reply_text'])}}
                
                @if($errors->has('reply_text'))
                <div class='invalid-feedback'> {{$errors->first('reply_text')}}</div>
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
                <a class="btn btn-outline-secondary btn-sm" 
                data-toggle="collapse" data-target="#formReply">
                    <i class="fa fa-close"></i> Cancelar
                </a>
                
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>

@push('scripts')
<script>

@if($errors->has('reply_text'))
$("#formReply").collapse('show');
$('#formReply').on('shown.bs.collapse', function () {
    $('html, body').scrollTop($('#reply_text').offset().top);

});
@endif

</script>

@endpush



