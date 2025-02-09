@extends('layouts.app')
@section('breadcrumb')
@breadcrumbs(['title'=>'Relatório: Alunos - Presença', 'icon'=>'fa-circle-o',
'route'=>route('relatorio.students'),'subtitle'=>'Atividades de Agendamento'])

@endbreadcrumbs
@endsection

@section('toolbar')
@toolbar
<button class="btn btn-sm btn-primary mr-1 mb-1" onclick="document.getElementById('form_pesquisa').submit()">
    <i class="fa fa-search" aria-hidden="true"></i> Executar Pesquisa
</button>
<button class="btn btn-sm btn-outline-secondary mr-1 mb-1" type="button" onclick="limparFormPesquisa()">
    <i class="fa fa-undo"></i>Limpar Form Pesquisa
</button>
@endtoolbar
@endsection
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="tile">
            {!! Form::open(['route'=>'relatorio.students','id'=>'form_pesquisa'])!!}
            <div class="row">
                <div class="col-md-3">
                    {{ Form::bsDate('periodo_inicio', request('periodo_inicio'),['label'=>'Período Início >=']) }}
                </div>
                <div class="col-md-3">
                    {{ Form::bsDate('periodo_fim',request('periodo_fim'),['label'=>'Período Fim <=']) }}
                </div>

                <div class="col-md-4">
                    {{ Form::bsSelect('atividade',[0=>'Inativo no Período',1=>'Ativo no Período'],
                        request('atividade'),['label'=>"Atividade Agendamento", 
                    'class'=>'select2']) }}
                </div>

                
            </div>
            </form>

            <div class="row">
                <div class="col-md-12 ">
                    @if($relatorio->items)
                    <span class="text-primary d-block d-md-inline"><b>Mostrando {{$relatorio->items->count()}} Registro(s)</b></span>
                    @endif
                    <button class="btn btn-outline-success float-md-right"> Total Alunos:
                        {{$relatorio->total_alunos}}
                    </button>

                    @if($relatorio->total_alunos)
                    <button class="btn btn-outline-primary float-md-right mr-2" id="btnOpenEnviarEmail">
                        <i class="fa fa-envelope-o fa-lg" aria-hidden="true"></i> Enviar Email
                    </button>
                    @endif

                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <th width="1%">
                            @if($relatorio->total_alunos)
                            <div class="animated-checkbox">
                                <label>
                                    <input type="checkbox" id="check-all"><span class="label-text"></span>
                                </label>
                            </div>
                            @endif
                        </th>
                        <th width="2%">#</th>
                        <th width="15%">Cód Aluno</th>
                        <th>Nome Aluno</th>
                        <th>Qtd Células de Aula</th>

                       

                    </thead>

                    <tbody>
                        @foreach($relatorio->items as $key=>$item)
                        <tr>
                            <td>
                                <div class="animated-checkbox">
                                    <label>
                                        <input type="checkbox" class="check-item" data-id="{{$item->id}}">
                                        <span class="label-text"> </span>
                                    </label>
                                </div>
                            </td>
                            <td>{{++$key}}</td>
                            <td>{{$item->id}}</td>
                            <td>
                                <a target="_blank" href="{{route('students.edit', $item->id)}}">
                                    {{$item->nome}}
                                </a>
                            </td>
                            <td>{{$item->celulas_count}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

@include('messages.modal-bath')
@endsection

@push('scripts')
<script>
ckeckAllOnTable();

$("#btnOpenEnviarEmail").click(function() {
    if(!getIdsSelecionados().length){
        alert('nenhum registro Selecionado');
        return false;
    }
    // Limpa mensagens de erro anteriores
    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
    $("#message-status").addClass("d-none");
    $("#loading").hide(); 
    $('#modalMessageBath').modal('show');
});

$("#btnSubmitEnviarEmail").click(function() {
    var ids = getIdsSelecionados();
    if(!ids.length){
        alert('nenhum registro Selecionado');
        return false;
    }

    var token = $('meta[name="csrf-token"]').attr('content');
    // Limpa mensagens de erro anteriores
    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
    var subject = $("#subject").val();
    var body = $("#body").val();

    $.ajax({
      url: asset + "messages/send_bath",
      type: "POST",
      data: {
        ids: ids,
        _token:token,
        subject: subject,
        body: body
        
      },
      beforeSend: function() {
        $("#loading").show(); // Exibe o preloader antes de iniciar a requisição
        $("#message-status").addClass("d-none"); // Esconde a mensagem anterior
      },      
      success: function(response) {
        console.log(response);
        $("#message-status")
            .removeClass("d-none alert-danger")
            .addClass("alert alert-success")
            .text("📨 E-mails enviados com sucesso!");
      },
      error: function(response) {  
        console.log(response)     
        if (response.status == 422 && response.responseJSON && response.responseJSON.errors) {
            var errors = response.responseJSON.errors;
            Object.keys(errors).forEach(field => {
                document.getElementById(`error-${field}`).textContent = errors[field][0];
            });
        }
        if(response.status != 422){
            $("#message-status")
            .removeClass("d-none alert-success")
            .addClass("alert alert-danger")
            .text("❌ Ocorreu um erro ao enviar os e-mails.");
        }        
      },
      complete: function() {
            $("#loading").hide(); // Oculta o preloader após a resposta
      }
    });
   
});

function getIdsSelecionados(){
    var ids = [];  
    // Percorra todas as checkboxes de linha
    $(".check-item:checked").each(function() {
      // Adicione o ID de cada registro selecionado ao array
      ids.push($(this).data("id"));
    });
    return ids;
}

</script>

@endpush