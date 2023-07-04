@extends('layouts.app')

@section('breadcrumb')
@breadcrumbs(['title'=>' Aulas Marcadas', 'icon'=>'fa-calendar-check-o', 'route'=>route('agendados.index'),
'subtitle'=>'Aulas Marcadas'])

@endbreadcrumbs
@endsection


@section('content')
<?php
$requestModuleId=request('module_id');
if(!$requestModuleId):
    //pesquisa padrao == current module do student
    $requestModuleId=$student->module_id;
endif;
$modulesList->prepend('Todos','all');
?>

<div class="tile tile-nomargin">

    <div class="form-inline">
        <div class="form-group">
            <label class="text-primary mr-1">Créditos Atuais:</label>
            <button type="button" class="btn btn-outline-info btn-sm mr-3"> {{$student->saldo_atual}}</button>
        </div>

        <div class="form-group">
            <label class="text-primary mr-1">Módulo Corrente:</label>
            <button type="button" class="btn btn-outline-info btn-sm mr-3">
                @if($student->module){{$student->module->nome}}@endif</button>


        </div>

        <div class="form-group">
            <label class="text-primary mr-1">Desmarcações no Mês:</label>
            <button type="button" class="btn btn-outline-info btn-sm mr-3">
                {{$student->countCancellationsByMonth()}}
            </button>

        </div>

        <div class="form-group">
            <label class="text-primary mr-1">Limite Desmarcações no Mês:</label>
            <button type="button" class="btn btn-outline-info btn-sm mr-3">
                {{$limitDesmarcacao}}
            </button>

        </div>
        
    </div>



</div>


<div class="tile tile-nomargin">
    <form action="{{route('agendados.index')}}">
        <div class='row'>
            <div class="col-sm-2">

                {!!Form::bsSelect('module_id', $modulesList,
                $requestModuleId,
                ['onchange'=>"this.form.submit()","class"=>"select2",
                "label"=>"Módulo"]
                )!!}
            </div>

            <div class="col-sm-2">

                {!!Form::bsSelect('teacher_id', $teachersList,
                request('teacher_id'),
                ['onchange'=>"this.form.submit()","class"=>"select2",
                "placeholder"=>"-Selecione-","label"=>"Professor"]
                )!!}
            </div>


            <div class="col-sm-2">

                {!!Form::bsSelect('disciplina_id', $disciplinasList,
                request('disciplina_id'),
                ['onchange'=>"this.form.submit()","class"=>"select2",
                "placeholder"=>"-Selecione-","label"=>"Disciplina"]
                )!!}
            </div>


            <div class="col-sm-3">
                {{ Form::bsDate('start', request('start'),['label'=>'Período >=', 
            'class'=>'form-control-sm','onchange'=>"this.form.submit()"]) }}
            </div>


            <div class="col-sm-3">
                {{ Form::bsDate('end', request('end'),['label'=>'Período <=', 
            'class'=>'form-control-sm','onchange'=>"this.form.submit()"]) }}
            </div>


        </div>



    </form>
</div>

<div class="tile">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Dia</th>
                    <th>Horário</th>
                    <th>Aula Sigla</th>
                    <th>Módulo</th>
                    <th>Disciplina</th>
                    <th>Professor</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach($celulas as $celula)
                <tr>
                    <td>{{$celula->getDiaFormatado()}}</td>
                    <td>{{$celula->horario}}</td>
                    <td>{{$celula->aula_sigla}}</td>
                    <td>{{$celula->module_nome}}</td>
                    <td>{{$celula->disciplina_nome}}</td>
                    <td>{{$celula->teacher_nome}}</td>
                    <td>
                        <a href="#" class="showCelula" data-celula_id="{{$celula->id}}" title="Visualizar Célula">
                            <i class="fa fa-commenting-o"></i> 
                        </a>

                        @if($celula->isOnLimitHoursToStart())
                        <a href="#" class="desmarcarAula text-danger" title="Desmarcar" data-celula_id="{{$celula->id}}"
                            data-aula_sigla="{{$celula->aula_sigla}}" data-horario="{{$celula->horario}}"
                            data-dia="{{$celula->getDiaFormatado()}}" data-teacher_nome="{{$celula->teacher_nome}}">
                            <i class="fa fa-trash"></i>    
                        </a>
                        @endif
                       
                    </td>
                </tr>

                @endforeach

            </tbody>
        </table>
    </div>
</div>

<form id="desmarcar-aula-form" action="" data-route="{{ route('agendados.desmarcar','celula_id') }}" method="POST"
    style="display: none;">
    @csrf
    <input type='hidden' name='_method' value='DELETE'>
</form>
@include('grade.modal')

@endsection

@push('scripts')
<script>
function desmarcarAula(event) {
    event.preventDefault();
    var target = event.currentTarget;
    var dados = target.dataset;
    var content = 'Aula: ' + dados.aula_sigla + '; ' +
        'Dia: ' + dados.dia + ' ; Horário: ' + dados.horario +
        '\n Professor: ' + dados.teacher_nome;
    wxConfirm(function() {
        var form = $('#desmarcar-aula-form');
        var route = form.attr('data-route');
        route = route.replace('celula_id', dados.celula_id);
        form.attr('action', route);
        console.log(route);
        form.submit();
    }, "Deseja Realmente Desmarcar?", content);
}
$('.desmarcarAula').click(desmarcarAula);

/*
**Visualização de Célula
*/
function setDadosCelula(celula_id){
    $.ajax({
        url: asset+"getCelula/" + celula_id,
        beforeSend:function(){             
            $("#modalCelula .corpo_modal").hide();
            $("#modalCelula .modal-body").append( "<p id='modalBodyLoading'>Loading...</p>" );
            
        },
        success: function(resp) {
            $("#modalCelula .corpo_modal").show();
            $("#modalBodyLoading").remove();

            $("#modalCelula_celula_id").val(resp.id);
            $("#modalCelula_aula_id").val(resp.aula_id);
            var dia = moment(resp.dia);
            $("#modalCelula_dia").html(dia.format('DD.MM.YYYY'));
            $("#modalCelula_horario").html(resp.horario);
            var aula = resp.aula ? resp.aula.sigla : "";
            $("#modalCelula_aula").html(aula);

            mountStudentsOnCelula(resp.students);            

            $("#info_aula_individual").html("");
            if(resp.aula_individual){
                var msgIndividual='<p class="mb-3"> <b style="color: red">Sala de Aula Individual</b></p>';
                $("#info_aula_individual").html(msgIndividual);
            }

            $("#modalCelula_aula_link").html("");
            if(resp.aula_link){
                var linkAula='<a target="_blank" href="'+resp.aula_link+'"> Link da Aula </a> ';
                $("#modalCelula_aula_link").html(linkAula);
            }
            
        }
    });
   
}

$(".showCelula").click(function(event){
    event.preventDefault();
    var target = event.currentTarget;
    var dados = target.dataset;
    $(".message_modal").html('');
    $("#btnAgendarAula").hide();
    setDadosCelula(dados.celula_id);
    $("#modalCelula").modal('show');
    
});

function ListStudents() {
    this.alunos = [];
    var $this = this;
    
    function echoX(value) {
        return (value != null && value != undefined) ? value : '';
    }

    this.findAlunoById = function (id) {
        return $this.alunos.find(function (aluno) {
            return aluno.id == id;
        })
    }

    this.updateTable = function () {
        if (!$this.alunos.length) {
            $("#modalCelula_students tbody").html('');
            $("#modalCelula_students").hide();
            return false;
        }
        
        $("#modalCelula_students").show();
        var mapStudents = $this.alunos.map(function (student) {
            var strPresenca = student.pivot.presenca ?
                '<i class="fa fa-check-square-o" aria-hidden="true"></i>' :
                '<i class="fa fa-square-o" aria-hidden="true"></i>';
            var studentModuleName= student.module?student.module.nome:'';
            var string = "<tr>" +
                '<td></td>' +            
                '<td>' + student.nome + ' ('+studentModuleName+')</td>' +
                '<td>' + strPresenca + '</td>' +
                '<td>' + echoX(student.pivot.n1) + '</td>' +
                '<td>' + echoX(student.pivot.n2) + '</td>' +
                '<td>' + echoX(student.pivot.n3) + '</td>' +
                '<td>' + createPopOverLink(echoX(student.pivot.feedback))+ '</td>' +
                "</tr>";
               
            return string;
        });
        $("#modalCelula_students tbody").html(mapStudents.join(''));
        
        $("[data-toggle='popover']").popover();
    }
} //Fim Class ListStudent



var listStudents = 'listagem de students';
function mountStudentsOnCelula(students) {
    listStudents = new ListStudents();
    listStudents.alunos = students;   
    listStudents.updateTable();
    console.log('mountStudentsOnCelula')

}

$('#modalCelula').on('hidden.bs.modal', function (e) {
     $("#modalCelula_celula_id").val('');
})


</script>

@endpush