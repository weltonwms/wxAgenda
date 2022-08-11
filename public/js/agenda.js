(function () {
    let instanceCalendar = null;
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var minMaxHorarioValido=getMinMaxHorarioValido();
        var calendar = new FullCalendar.Calendar(calendarEl, {
            //timeZone: 'UTC',
            height: 'auto',
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


            selectLongPressDelay: 1,
            eventSources: [
                {
                    url: asset + 'getEventsAgenda',
                    method: 'GET',
                    extraParams: function () {
                        var aula_id = $("#aula_id").val();
                        return {
                            aula_id: aula_id
                        }
                    },
                    failure: function () {
                        alert('there was an error while fetching events!');
                    }
                },
                {
                    url: asset + 'getEventsAgendados',
                    method: 'GET',
                    failure: function () {
                        alert('there was an error while fetching events!');
                    }
                }

            ],
            eventClick: function (calendaInfo) {
                var event = calendaInfo.event;
                var props = event.extendedProps;
                var isAgendados = event.source.url.search('getEventsAgendados') > -1;
                if (!isAgendados) {
                    $.ajax({
                        url: asset + "getDadosToAgenda/",
                        data: {
                            teachers: props.teachers,
                            celulas: props.celulas
                        },
                        success: function (resp) {
                            console.log('resp', resp)
                            setDadosToAgenda(resp);


                        }
                    });
                    $("#modalAgenda").modal('show');

                }
            }

        });

        instanceCalendar = calendar;
        calendar.render();

    });

    $("#teacher_id").on('change', function () {
        instanceCalendar.refetchEvents();
    })
    $('#jstree_list_aulas').on('click', '.aulas',function () {
        var data = this.dataset;
        $("#aula_id").val(data.aula_id);
        instanceCalendar.refetchEvents();
    });

    function setDadosToAgenda(resp) {
        var x = resp.map(function (celula) {
            return '<option value="' + celula.id + '">' + celula.nome_professor + '</option>';
        });
        $('#celula_to_agenda').html(x.join(' '));
        var diaMoment=moment(resp[0].dia);
        $("#modalAgenda_horario").html(resp[0].horario);
        $("#modalAgenda_dia").html(diaMoment.format('DD.MM.YYYY'));
        $("#modalAgenda_aula").html(getNomeAulaAtiva());

    }



    function getHorariosValidos() {
        var horarios_validos_json = $('#horarios_validos').val();
        return JSON.parse(horarios_validos_json);
    }

    function getMinMaxHorarioValido() {
        var horarios_validos_json = $('#horarios_validos').val();
        var horarios_validos = JSON.parse(horarios_validos_json);
        var obj = { min: '00:00', max: '00:00' };
        if (Array.isArray(horarios_validos)) {
            var min = horarios_validos.sort()[0];
            var max = horarios_validos.reverse()[0];
            if (min) {
                obj.min = min;
            }
            if (max) {
                var arrayHour = max.split(':');
                var hour = arrayHour[0];
                var minutos = arrayHour[1];
                var hourMaisUm = Number(hour) + 1;

                obj.max = hourMaisUm + ':' + minutos;
            }
        }
        return obj;

    }
    
    function marcarAula(event){
        var aula_id=$("#aula_id").val();
        var celula_id=$("#celula_to_agenda").val();
        var token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: asset+"agenda",
            method: 'POST',
            data: {
                _token: token,
                aula_id: aula_id,
                celula_id: celula_id
               

            },
            success: function(resp) {
                console.log(resp)
                //calendar.unselect()
                instanceCalendar.refetchEvents();
                $("#modalAgenda").modal('hide');
                $('#jstree_list_aulas').jstree(true).refresh();
                var message="Aula Agendada Com Sucesso:<br>";
                message+='Dia: '+resp.dia+' '+resp.horario+', Professor: '+resp.teacher;
                showGlobalMessage(message,'success');
            }
      
        });
        //alert('marcar aula')
    }

    $("#btnAgendar").on('click',marcarAula)
})();

function getNomeAulaAtiva() {
    var aula_id=$("#aula_id").val();
    if(aula_id){
        var query='[data-aula_id='+aula_id+']';
        return $(query).text();
    }
    return '';
}