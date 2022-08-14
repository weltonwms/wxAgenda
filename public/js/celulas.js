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
            console.log(arg.event.id);
            $.ajax({
                url: asset+"celulas/" + arg.event.id,
                success: function(resp) {
                    console.log('resp', resp)
                    $("#modalCelula_celula_id").val(resp.id);
                    var dia = moment(resp.dia);
                    $("#modalCelula_dia").html(dia.format('DD.MM.YYYY'));
                    $("#modalCelula_horario").html(resp.horario);
                    var aula = resp.aula ? resp.aula.sigla : "";
                    $("#modalCelula_aula").html(aula);
                    var mapStudents = resp.students.map(function(student) {
                        return "<li>" + student.nome + "</li>";
                    });
                    $("#modalCelula_students").html(mapStudents.join(''));

                }
            });
            $("#modalDeleteCelula").modal('show');
            //arg.event.remove()

        }
    });

    instanceCalendar = calendar;
    calendar.render();

});

$("#teacher_id").on('change', function() {
    instanceCalendar.refetchEvents();
})

$("#btnDeleteCelula").on('click', function() {
    var celula_id = $("#modalCelula_celula_id").val();
    var token = $('meta[name="csrf-token"]').attr('content');
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
});

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
})();