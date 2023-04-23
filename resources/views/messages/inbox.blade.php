<div class="tile">
    <input type="hidden" id="sentMessage" value="{{request('sent')?1:0}}">
    <div class="mailbox-controls">
        <div class="animated-checkbox">
            <label>
                <input type="checkbox" id="check-all"><span class="label-text"></span>
            </label>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary btn-sm" id="deleteMessages" type="button"><i class="fa fa-trash-o"></i>Apagar</button>
            
        </div>
    </div>
    <div class="table-responsive mailbox-messages">
        <table class="table table-hover">
            <tbody>
                @foreach($messages as $message)
                <tr @class(['message','is_not_read'=>!$message->is_read])>
                    <td>
                        <div class="animated-checkbox">
                            <label>
                                <input type="checkbox" class="check-item" data-id="{{$message->id}}">
                                <span class="label-text"> </span>
                            </label>
                        </div>
                    </td>
                   <?php 
                    $nome=request('sent')?"Para: {$message->recipient->nome}":$message->sender->nome;
                    $paramsRoute= array_merge([$message->id], request()->query() ); //juntando id message com query Strings
                    ?>
                    <td class="mail_user"><a href="{{route('messages.show',$paramsRoute) }}">{{$nome}}</a></td>
                    <td class="mail-subject">{!!$message->getSubjectBody()!!}</td>
                    
                    <td>{{$message->getShortCreatedAt()}}</td>
                </tr>
                @endforeach


               
            </tbody>
        </table>

    </div>
    
    <!-- Adiciona as query strings à URL dos links da paginação -->   
    {!! $messages->appends(request()->query())->render('pagination.custom2') !!}   
</div>
@push('scripts')
<script>
ckeckAllOnTable();

$("#deleteMessages").click(function() {
    // Crie um array para armazenar os IDs dos registros selecionados
    var ids = [];
    var sent = $('#sentMessage').val();
    var token = $('meta[name="csrf-token"]').attr('content');
    // Percorra todas as checkboxes de linha
    $(".check-item:checked").each(function() {
      // Adicione o ID de cada registro selecionado ao array
      ids.push($(this).data("id"));
    });
   
    $.ajax({
      url: asset + "messages_bath",
      type: "POST",
      data: {
        ids: ids,
        sent: sent,
        _token:token
        
      },
      success: function(response) {
        // Se a exclusão foi bem-sucedida, atualize a tabela
        //alert("Registros excluídos com sucesso!");
        //console.log(response);
        location.reload();
      },
      error: function() {
        // Se ocorreu um erro na exclusão, exiba uma mensagem de erro
        alert("Ocorreu um erro na exclusão dos registros.");
      }
    });
  });




</script>

@endpush