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
            $.ajax({
                url: asset+"getCelula/" + arg.event.id,
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
})();