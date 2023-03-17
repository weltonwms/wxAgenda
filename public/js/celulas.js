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
        selectable: true,
        selectMirror: false,
        dayMaxEvents: true, // allow "more" link when too many events,
        allDaySlot: false,
        locale: "pt-br",

        slotMinTime: minMaxHorarioValido.min,
        slotMaxTime: minMaxHorarioValido.max,


        events: {
            url: asset+'getEventsCelula',
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

        selectAllow: function(selectInfo) {
            var horasAllow = getHorariosValidos();
            var horaSelect = selectInfo.start.toLocaleTimeString('pt-BR', {
                hour: '2-digit',
                minute: '2-digit'
            });
            console.log(horaSelect)
            //return true;
            return horasAllow.includes(horaSelect);
        },
        select: function(arg) {
            var start = moment(arg.start);

            var token = $('meta[name="csrf-token"]').attr('content');
            var teacher_id = $("#teacher_id").val();
            $.ajax({
                url: asset+"celulas",
                method: 'POST',
                data: {
                    _token: token,
                    teacher_id: teacher_id,
                    dia: start.format("YYYY-MM-DD"),
                    horario: start.format('HH:mm')

                },
                success: function(resp) {
                    console.log(resp)
                    //calendar.unselect()
                    instanceCalendar.refetchEvents();
                },
                error:function(resp){
                    var resposta= resp.responseJSON.error;
                    $.notify(resposta,{type:'danger'})
                   //showGlobalMessage(resposta,'danger');
                }
            });

        },
        selectLongPressDelay: 1,
        eventClick: function(arg) {            
            limpaSelects();
            $(".message_modal").html('');
            setDadosCelula(arg.event.id);
            $("#modalDeleteCelula").modal('show');
            //arg.event.remove()

        }
    });

    instanceCalendar = calendar;
    calendar.render();

});

function setDadosCelula(celula_id){
    $.ajax({
        url: asset+"celulas/" + celula_id,
        success: function(resp) {
            console.log('resp', resp);
            var aula_id=resp.aula ? resp.aula.id : "";
            $("#modalCelula_celula_id").val(resp.id);
            $("#modalCelula_aula_id").val(aula_id);
            var dia = moment(resp.dia);
            $("#modalCelula_dia").html(dia.format('DD.MM.YYYY'));
            $("#modalCelula_horario").html(resp.horario);
            var aula = resp.aula ? resp.aula.sigla : "";
            $("#modalCelula_aula").html(aula);
            var mapStudents = resp.students.map(function(student) {
                return "<li>" + student.nome + "</li>";
            });
            $("#modalCelula_students").html(mapStudents.join(''));
            $("#info_aula_individual").html("");
            if(resp.aula_individual){
                var msgIndividual='<p class="mb-3"> <b style="color: red">Sala de Aula Individual</b></p>';
                $("#info_aula_individual").html(msgIndividual);
            }

        }
    });
}


$("#teacher_id").on('change', function() {
    instanceCalendar.refetchEvents();
})

$("#btnDeleteCelula").on('click', function() {
    var celula_id = $("#modalCelula_celula_id").val();
    var token = $('meta[name="csrf-token"]').attr('content');
    var countStudents= $("#modalCelula_students li").length;
    function sendAjax(){
        $.ajax({
            url: asset+"celulas/" + celula_id,
            method: "DELETE",
            data: {
                _token: token
            },
            success: function(resp) {
                console.log(resp)
                instanceCalendar.refetchEvents();
                $("#modalDeleteCelula").modal('hide');
                showGlobalMessage(resp.message,'success');
            }
        });
    }
    if(countStudents>0){
        wxConfirm(sendAjax,"Deseja Realmente Excluir Célula?","Existem Alunos Agendados")
    }
    else{
        sendAjax();
    }    
}); //fim Click btnDeleteCelula

function getHorariosValidos() {
    var horarios_validos_json = $('#horarios_validos').val();
    return JSON.parse(horarios_validos_json);
}

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


/**
 * Inicio das funções para agendamento de aula pelo administrador * 
 */
 function sendAddStudent(){
    var token = $('meta[name="csrf-token"]').attr('content');
    var student_id = $("#selectAluno select").val();
    var celula_id =$("#modalCelula_celula_id").val();
    var aula_id=$("#modalCelula_aula_id").val();
    if(!aula_id){
        aula_id=$("#selectAula select").val();
    }
    var aula_individual=$("#selectTipoAula select").val() || 0;
        $.ajax({
            url: asset+"celulas/storeStudent",
            method: 'POST',
            data: {
                _token: token,
                student_id:student_id,
                celula_id:celula_id,
                aula_id:aula_id,
                aula_individual:aula_individual

            },
            success: function(resp) {
                console.log(resp)
                setDadosCelula(celula_id);
                limpaSelects();
                instanceCalendar.refetchEvents();
                showMessage('.message_modal',"Agendamento realizado com Sucesso!",'success');
                
            },
            error:function(resp){
                var resposta= resp.responseJSON.error;
                showMessage('.message_modal',resposta,'danger');
                //$.notify(resposta,{type:'danger'})
               //showGlobalMessage(resposta,'danger');
            }
        });
}

function mountSelectAlunos(){
    $.ajax({
            url: asset+"getStudentsAjax",
            success: function(resp) {
                var string="<select class='form-control form-control-sm'><option value=''>--Aluno--</option>";
                var mapStudents = resp.map(function(student) {
                    return '<option value="'+student.id+'">' + student.nome + '</option>';
                });
                string+=mapStudents.join('');
                string+="</select>";                   
                $("#selectAluno").html(string); 
                $('#selectAluno select').select2({
                    dropdownParent: $('#selectAluno'),                    
                     width: '100%'                    
                });  
            }
        });
}

function mountSelectModules(){
    $.ajax({
            url: asset+"getModulesAjax",
            success: function(resp) {
                var string="<select class='form-control form-control-sm'><option value=''>--Módulo--</option>";
                var mapModules = resp.map(function(modulo) {
                    return '<option value="'+modulo.id+'">' + modulo.nome + '</option>';
                });
                string+=mapModules.join('');
                string+="</select>";                   
                $("#selectModule").html(string); 
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
            url: asset+"getDisciplinasAjax",
            success: function(resp) {
                var string="<select class='form-control form-control-sm'><option value=''>--Disciplina--</option>";
                var mapDisciplinas = resp.map(function(disciplina) {
                    return '<option value="'+disciplina.id+'">' + disciplina.nome + '</option>';
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
    if(module_id && disciplina_id){
        mountSelectAulas(module_id,disciplina_id);
    }else{
        $("#selectAula").html('');
    }
}

function mountSelectAulas(module_id,disciplina_id){
    $.ajax({
            url: asset+"getAulasAjax?module_id="+module_id+"&disciplina_id="+disciplina_id,
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
    $("#selectAluno").html(''); 
    $("#selectModule").html(''); 
    $("#selectDisciplina").html(''); 
    $("#selectAula").html('');
    $("#blocoConfirm").html("");
    $("#selectTipoAula").html("");
}

function mountBlocoConfirm(){
    var string="<button id='btnCancell' class='btn-sm btn btn-outline-secondary mr-2'>Cancelar</button>";
    string+="<button id='btnBlocoConfirm' class='btn-sm btn btn-success'>Confirmar</button>";
  
    $("#blocoConfirm").html(string); 
    $('#btnCancell').on('click',limpaSelects);
    $('#btnBlocoConfirm').on('click',sendAddStudent);
}

function mountSelectTipoAula(){
    var string="<select class='form-control form-control-sm'><option value='0'>--Sala de Aula--</option>";
    string+='<option value="0">Turma</option>';
    string+='<option value="1">Individual</option>';
    string+='</select>';
    string+='<small class="form-text text-muted">Sala de Aula Individual descontará 2 créditos!</small>'
    $("#selectTipoAula").html(string);
    $('#selectTipoAula select').select2({
        dropdownParent: $('#selectModule'),                    
         width: '100%'                    
    });   

}


function mountSelects(){
   var aula_id= $("#modalCelula_aula_id").val();
   mountSelectAlunos();
   if(!aula_id){
    mountSelectModules();
    mountSelectDisciplinas();
    mountSelectTipoAula();
   }
   mountBlocoConfirm();
}

$("#btnAddStudent").click(mountSelects);
/**
 * Fim das funções para agendamento de aula pelo adm
 */
})();

