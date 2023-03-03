/*
/*Depende do FullCalendar.js
*/
(function(){
let instanceCalendar = null;
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var minMaxHorarioValido=getMinMaxHorarioValido();
    var calendar = new FullCalendar.Calendar(calendarEl, {
        //timeZone: 'UTC',
        height:'auto',
        initialView: 'timeGridWeek',

        nowIndicator: true,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        themeSystem: "bootstrap",
        navLinks: false, // can click day/week names to navigate views
        editable: false,
        selectable: false,
        selectMirror: false,
        dayMaxEvents: true, // allow "more" link when too many events,
        allDaySlot: false,
        locale: "pt-br",

        slotMinTime: minMaxHorarioValido.min,
        slotMaxTime: minMaxHorarioValido.max,


        events: {
            url: asset+'getEventsGrade',
            method: 'GET',
            extraParams: function() {
                var teacher_id = $("#teacher_id").val();
                return {
                    teacher_id: teacher_id
                }
            },
            failure: function() {
                alert('there was an error while fetching events!');
            }
        },

        
       
        eventClick: function(arg) {
            console.log(arg.event.id);
            limpaSelects();
            $(".message_modal").html('');
            setDadosCelula(arg.event.id);
            $("#modalCelula").modal('show');
            //arg.event.remove()

        }
    });

    instanceCalendar = calendar;
    calendar.render();

});

$("#teacher_id").on('change', function() {
    instanceCalendar.refetchEvents();
})





function getMinMaxHorarioValido() {
    var horarios_validos_json = $('#horarios_validos').val();
    var horarios_validos = JSON.parse(horarios_validos_json);
    var obj={min:'00:00',max:'00:00'};
    if(Array.isArray(horarios_validos)){
        var min = horarios_validos.sort()[0];
        var max = horarios_validos.reverse()[0];
        if(min){
            obj.min=min;
        }
        if(max){
            var arrayHour=max.split(':');
            var hour=arrayHour[0];
            var minutos=arrayHour[1];
            var hourMaisUm=Number(hour)+1;
           
            obj.max=hourMaisUm+':'+minutos;
        }
    }
    return obj;
   
}

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
            var mapStudents = resp.students.map(function(student) {
                return "<li>" + student.nome + "</li>";
            });
            $("#modalCelula_students").html(mapStudents.join(''));

            var student_id= parseInt($("#student_id").val());
            var isActiveAgenda= isPossivelAgendar(resp.dia,resp.horario,student_id,resp.students);
            if(isActiveAgenda){
                $("#btnAgendarAula").attr('disabled',false)
            }
            else{
                $("#btnAgendarAula").attr('disabled',true)
            }

        }
    });
   
}

//inicio funcoes agendamento
/*
**************************
*/

function mountSelects(){   
    var aula_id= $("#modalCelula_aula_id").val();   
    if(!aula_id){
     mountSelectModules();
     mountSelectDisciplinas();
    }
    mountBlocoConfirm();    
 }
 
 $("#btnAgendarAula").click(mountSelects);

 function mountSelectModules(){
    $.ajax({
            url: asset+"modulesAjax/",
            beforeSend:function(data){ 
                $("#selectModule").html('Loading...');
            },
            success: function(resp) {
                var string="<select class='form-control form-control-sm'><option value=''>--Módulo--</option>";
                var mapModules = resp.modules.map(function(modulo) {
                    return '<option value="'+modulo.id+'">' + modulo.nome + '</option>';
                });
                string+=mapModules.join('');
                string+="</select>";                   
                $("#selectModule").html(string);
                $("#selectModule select").val(resp.current_module);  
                $("#selectModule select").on('change',gatilhoMountSelectAulas);
                $('#selectModule select').select2({
                    dropdownParent: $('#selectModule'),                    
                     width: '100%'                    
                });     
            }
        });

}

function mountSelectDisciplinas(){
    $.ajax({
            url: asset+"disciplinasAjax/",
            beforeSend:function(data){ 
                $("#selectDisciplina").html('Loading...');
            },
            success: function(resp) {
                var string="<select class='form-control form-control-sm'><option value=''>--Disciplina--</option>";
                var mapDisciplinas = resp.map(function(disciplina) {
                    return '<option value="'+disciplina.id+'" data-base="'+disciplina.base+'">' + disciplina.nome + '</option>';
                });
                string+=mapDisciplinas.join('');
                string+="</select>";                   
                $("#selectDisciplina").html(string); 
                $("#selectDisciplina select").on('change',gatilhoMountSelectAulas);  
                $('#selectDisciplina select').select2({
                    dropdownParent: $('#selectDisciplina'),                    
                     width: '100%'                    
                });     
            }
        });
 }

 function gatilhoMountSelectAulas(){
   
    var module_id= $("#selectModule select").val();
    var disciplina_id= $("#selectDisciplina select").val();
    var base= $("#selectDisciplina select").find(":selected").data('base');
    //console.log('base:', base)
    if(module_id && disciplina_id && base){
        mountSelectAulas(module_id,disciplina_id);
    }else{
        $("#selectAula").html('');
    }
    
}

function mountSelectAulas(module_id,disciplina_id){
    $.ajax({
            url: asset+"aulasAjax/?module_id="+module_id+"&disciplina_id="+disciplina_id,
            beforeSend:function(data){ 
                $("#selectAula").html('Loading...');
            },
            success: function(resp) {
                var string="<select class='form-control form-control-sm'><option value=''>--Aula--</option>";
                var mapAulas = resp.map(function(aula) {
                    return '<option value="'+aula.id+'">' + aula.sigla+ '</option>';
                });
                string+=mapAulas.join('');
                string+="</select>";                   
                $("#selectAula").html(string);  
                $('#selectAula select').select2({
                    dropdownParent: $('#selectAula'),                    
                     width: '100%'                    
                });   
            }
        });
}

function limpaSelects(){
    $("#selectModule").html(''); 
    $("#selectDisciplina").html(''); 
    $("#selectAula").html('');
    $("#blocoConfirm").html("");
}

function mountBlocoConfirm(){
    var string="<button id='btnCancell' class='btn-sm btn btn-outline-secondary mr-2'>Cancelar</button>";
    string+="<button id='btnBlocoConfirm' class='btn-sm btn btn-success'>Confirmar</button>";
  
    $("#blocoConfirm").html(string); 
    $('#btnCancell').on('click',limpaSelects);
    $('#btnBlocoConfirm').on('click',sendAgendarAula);
}


function sendAgendarAula(){
    var token = $('meta[name="csrf-token"]').attr('content');
    var celula_id =$("#modalCelula_celula_id").val();
    var aula_id=$("#modalCelula_aula_id").val();
    if(!aula_id){
        aula_id=$("#selectAula select").val();
    }

   var module_id=$("#selectModule select").val() || null;
   var disciplina_id=$("#selectDisciplina select").val() || null;
   
        $.ajax({
            url: asset+"gradeEscola/agenda",
            method: 'POST',
            data: {
                _token: token,
                celula_id:celula_id,
                aula_id:aula_id,
                module_id:module_id,
                disciplina_id:disciplina_id

            },
            beforeSend:function(data){ 
                $(".message_modal").html('Loading...');
            },
            success: function(resp) {
                var textoSuccess="Agendamento realizado com Sucesso!<br>";
                textoSuccess+= "Dia: "+resp.dia+" às "+resp.horario+", Professor: "+resp.teacher;
                setDadosCelula(celula_id);
                limpaSelects();
                instanceCalendar.refetchEvents();
                $.notify(textoSuccess, { type: 'success' });
               showMessage('.message_modal',textoSuccess,'success');
               setDadosAluno();
                
            },
            error:function(resp){
                var resposta= resp.responseJSON.error;
                console.log('resposta erro: ', resposta);
                showMessage('.message_modal',resposta,'danger');
                //$.notify(resposta,{type:'danger'})
               //showGlobalMessage(resposta,'danger');
               setDadosAluno();
            }
        });
        
}

function setDadosAluno() {
    $.ajax({
        url: asset + "getAuthStudent",
        success: function (resp) {
            $("#student_saldo_atual").text(resp.saldo_atual);
            $("#student_id").val(resp.id);
            if (resp.module) {
                $("#student_module_nome").text(resp.module.nome);
            }
        },
        error: function (resp) {
            console.log(resp)
        }
    });
}
setDadosAluno();//load das informações do aluno no cabeçalho


function isPossivelAgendar(dia, horario, student_id, students){
    var dateNow= new Date();
    var dateCelula= new Date(dia+' '+horario);
    var isFuture= dateCelula > dateNow;
    //console.log('isFuture: ', isFuture)

    var studentOnList= students.find(function(student){
        return student.id==student_id;
    })
    //console.log('studentsOnlist ',studentOnList);
    console.log('student_id in valicao', student_id)
    return isFuture && !studentOnList;

}
//fim funcoes agendamento

})();


