/*
/*Depende do FullCalendar.js
*/
(function () {
    let instanceCalendar = null;
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var minMaxHorarioValido = getMinMaxHorarioValido();
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


            events: {
                url: asset + 'getEventsGrade',
                method: 'GET',
                extraParams: function () {
                    var teacher_id = $("#teacher_id").val();
                    return {
                        teacher_id: teacher_id
                    }
                },
                failure: function () {
                    alert('there was an error while fetching events!');
                }
            },



            eventClick: function (arg) {
                var hasAuthStudent = arg.event.classNames.includes('eventAuthStudent');
                //console.log("hasAuthStudent",hasAuthStudent)
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

    $("#teacher_id").on('change', function () {
        instanceCalendar.refetchEvents();
    })





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

    function setDadosCelula(celula_id) {
        $.ajax({
            url: asset + "getCelula/" + celula_id,
            beforeSend: function () {
                $("#modalCelula .corpo_modal").hide();
                $("#modalCelula .modal-body").append("<p id='modalBodyLoading'>Loading...</p>");

            },
            success: function (resp) {
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
                if (resp.aula_individual) {
                    var msgIndividual = '<p class="mb-3"> <b style="color: red">Sala de Aula Individual</b></p>';
                    $("#info_aula_individual").html(msgIndividual);
                }

                $("#modalCelula_aula_link").html("");
                if (resp.aula_link) {
                    var linkAula = '<a target="_blank" href="' + resp.aula_link + '"> Link da Aula </a> ';
                    $("#modalCelula_aula_link").html(linkAula);
                }

                $("#modalCelula_reviewInfo").html("");
                if (resp.review_info) {
                    var reviewInfoText = '<b>Tipo de Review:</b> '+resp.review_info.tipo_review_name+'<br>';
                    reviewInfoText += '<b>Descrição de Review:</b> '+createPopOverLink(resp.review_info.descricao_review,65);
                    $("#modalCelula_reviewInfo").html(reviewInfoText);
                    startPopOverLink();      
                }

                var student_id = parseInt($("#student_id").val());
                var isActiveAgenda = isPossivelAgendar(resp.dia, resp.horario, student_id, resp.students) && !resp.aula_individual;
                if (isActiveAgenda) {
                    $("#btnAgendarAula").attr('disabled', false)
                }
                else {
                    $("#btnAgendarAula").attr('disabled', true)
                }

            }
        });

    }

    //inicio funcoes agendamento
    /*
    **************************
    */

    function mountSelects() {
        var aula_id = $("#modalCelula_aula_id").val();
        if (!aula_id) {
            mountSelectModules();
            mountSelectDisciplinas('default');
            mountSelectTipoAula();
        }
        mountBlocoConfirm();
    }

    $("#btnAgendarAula").click(mountSelects);

    function mountSelectModules() {
        $.ajax({
            url: asset + "modulesAjax",
            beforeSend: function (data) {
                $("#selectModule").html('Loading...');
            },
            success: function (resp) {
                var string = "<select class='form-control form-control-sm'><option value=''>--Módulo--</option>";
                var mapModules = resp.modules.map(function (modulo) {
                    return '<option value="' + modulo.id + '">' + modulo.nome + '</option>';
                });
                string += mapModules.join('');
                string += "</select>";
                $("#selectModule").html(string);
                $("#selectModule select").val(resp.current_module);
                $("#selectModule select").on('change', gatilhoMountSelectDisciplinas);
                $("#selectModule select").on('change', gatilhoMountSelectAulas);
                $('#selectModule select').select2({
                    dropdownParent: $('#selectModule'),
                    width: '100%'
                });
            }
        });

    }

    /**
     * método para ver se precisa remontar as disciplinas.
     */
    function gatilhoMountSelectDisciplinas() {
        var module_id = $("#selectModule select").val();
        var base = $("#selectDisciplina select").find(":selected").data('base');
        if (module_id && !base) {
            //condição da base deduzindo que se for base não precisa remontar as disciplinas
            //pois deduz que disciplina base está presente em todos os módulos.
            //Retirar base da condicional se a dedução mudar.
            $("#selectDisciplina select").val('');
            $('#selectDisciplina select').trigger('change');
            mountSelectDisciplinas(module_id);
        }
    }

    /**
     * Discplinas são montadas baseadas num filtro por módulo, pois
     * para alguma associação disciplina/modulo não haverá aulas cadastradas, então
     * tal disciplina não deve constar na lista de disciplinas
     */
    function mountSelectDisciplinas(module_id = '') {
        if (module_id) {
            var url = asset + "disciplinasAjax?module_id=" + module_id;
        } else {
            var url = asset + "disciplinasAjax";
        }

        $.ajax({
            url: url,
            beforeSend: function (data) {
                $("#selectDisciplina").html('Loading...');
            },
            success: function (resp) {
                var string = "<select class='form-control form-control-sm'><option value=''>--Disciplina--</option>";
                var mapDisciplinas = resp.map(function (disciplina) {
                    return '<option value="' + disciplina.id + '" '+
                    'data-review="' + disciplina.review + '" ' +
                    'data-base="' + disciplina.base + '">' + 
                    disciplina.nome + '</option>';
                });
                string += mapDisciplinas.join('');
                string += "</select>";
                $("#selectDisciplina").html(string);
                $("#selectDisciplina select").on('change', gatilhoMountSelectAulas);
                $("#selectDisciplina select").on('change', onChangeDisciplina);
                $('#selectDisciplina select').select2({
                    dropdownParent: $('#selectDisciplina'),
                    width: '100%'
                });
            }
        });
    }

    function gatilhoMountSelectAulas() {
        var module_id = $("#selectModule select").val();
        var disciplina_id = $("#selectDisciplina select").val();
        var base = $("#selectDisciplina select").find(":selected").data('base');
        //console.log('base:', base)
        if (module_id && disciplina_id && base) {
            mountSelectAulas(module_id, disciplina_id);
        } else {
            $("#selectAula").html('');
        }

    }

    function mountSelectAulas(module_id, disciplina_id) {
        $.ajax({
            url: asset + "aulasAjax?module_id=" + module_id + "&disciplina_id=" + disciplina_id,
            beforeSend: function (data) {
                $("#selectAula").html('Loading...');
            },
            success: function (resp) {
                preFilterSelectAulas(module_id, disciplina_id, resp);
            }
        });
    }

    function preFilterSelectAulas(module_id, disciplina_id, aulas) {
        $.ajax({
            url: asset + "getAulasAgendadasStudent?module_id=" + module_id + "&disciplina_id=" + disciplina_id,
            success: function (resp) {
                filterSelectAulas(aulas, resp, module_id)
            }
        });

    }

    function filterSelectAulas(aulas, aulasAgendadas, module_id) {
        var moduleCurrent = $("#student_module_id").val();
        if (moduleCurrent == module_id) {
            var maxOrdem = 0;
            aulasAgendadas.forEach(function (item) {
                if (item.ordem > maxOrdem) {
                    maxOrdem = item.ordem;
                }
            });
            aulas = aulas.filter(function (aula) {
                return aula.ordem <= (maxOrdem + 1)
            })

        }

        aulas.forEach(function (aula) {
            var match = aulasAgendadas.find(function (aulaAgendada) {
                return aulaAgendada.id == aula.id
            })
            if (match) {
                aula.agendada = true;
            }
            else {
                aula.agendada = false;
            }
        })
        outSelectAulas(aulas);
    }

    function outSelectAulas(aulas) {
        var string = "<select class='form-control form-control-sm'><option value=''>--Aula--</option>";
        var mapAulas = aulas.map(function (aula) {
            var agendada = aula.agendada ? 'data-agendada="true" ' : 'data-agendada="false" ';
            return '<option value="' + aula.id + '" ' + agendada + '>' + aula.sigla + '</option>';
        });
        string += mapAulas.join('');
        string += "</select>";
        $("#selectAula").html(string);
        $('#selectAula select').select2({
            dropdownParent: $('#selectAula'),
            width: '100%',
            templateResult: function (state) {
                var aulaAgendada = $(state.element).attr('data-agendada');
                if (aulaAgendada == 'true') {
                    var $state = $(
                        '<span class="aulaAgendada">' + state.text + '</span>'
                    );
                    return $state;
                }
                return state.text;
            }
        });

    }

    function limpaSelects() {
        $("#selectModule").html('');
        $("#selectDisciplina").html('');
        $("#selectAula").html('');
        $("#blocoConfirm").html("");
        $("#selectTipoAula").html("");
        //fields to infoReview
        $("#selectTipoReview select").val('');
        $("#fieldDescricaoReview textarea").val('');
        $(".row.reviewInfo").hide();
    }

    function onChangeDisciplina(event) {
        var review = $("#selectDisciplina select").find(":selected").data('review');
        if (review) {
            $(".row.reviewInfo").show();            
        }
        else {
            $(".row.reviewInfo").hide();
            $("#selectTipoReview select").val('');
            $("#fieldDescricaoReview textarea").val('');
        }
        //alert('mudança em disciplina');
    }

    function mountBlocoConfirm() {
        var string = "<button id='btnCancell' class='btn-sm btn btn-outline-secondary mr-2'>Cancelar</button>";
        string += "<button id='btnBlocoConfirm' class='btn-sm btn btn-success'>Confirmar</button>";

        $("#blocoConfirm").html(string);
        $('#btnCancell').on('click', limpaSelects);
        $('#btnBlocoConfirm').on('click', prepareSendAgendarAula);
    }

    function mountSelectTipoAula() {
        var string = "<select class='form-control form-control-sm'><option value='0'>--Sala de Aula--</option>";
        string += '<option value="0">Turma</option>';
        string += '<option value="1">Individual</option>';
        string += '</select>';
        string += '<small class="form-text text-muted">Sala de Aula Individual descontará 2 créditos!</small>'
        $("#selectTipoAula").html(string);
        $('#selectTipoAula select').select2({
            dropdownParent: $('#selectModule'),
            width: '100%'
        });

    }

    function prepareSendAgendarAula() {
        var celula_id = $("#modalCelula_celula_id").val();
        var aula_id = $("#modalCelula_aula_id").val();
        var isAberturaCelula = aula_id ? false : true;       
        $("#btnBlocoConfirm").attr('disabled', true); //evitar duplicacidade de ação
        if (isAberturaCelula) {
            verificaOferecimentoDeAula(celula_id);

        } else {
            var hasReview = !!$("#modalCelula_reviewInfo").html();             
            if(hasReview){
                //confiando que não é abertura de celula e já é uma aula review pois 
                //possui informação de review: mandar direto para o agendamento
                sendAgendarAula();
            }
            else{
                //verificação não executada para aula review
                VerificaSeAulaJaFeitaByAluno(aula_id);
            }            
        }
        setTimeout(function(){
            //chutando mais ou menos 4s para término do fluxo
            $("#btnBlocoConfirm").attr('disabled', false);
        },4000)
    }

    function verificaOferecimentoDeAula(celula_id) {
        var disciplina_id = $("#selectDisciplina select").val();
        var base = $("#selectDisciplina select").find(":selected").data('base');
        var module_id = $("#selectModule select").val();
        var aula_id = $("#selectAula select").val(); //valor do select e não do modal

        $.ajax({
            url: asset + "getCelulasJaAbertasByTurno",
            method: 'GET',
            data: {
                celula_id: celula_id,
                isBase: base,
                aula_id,
                module_id,
                disciplina_id

            },
            beforeSend: function (data) {
                $(".message_modal").html('Loading...');
            },
            success: function (resp) {
                $(".message_modal").html('');
                var isCelulaJaAberta = !!resp.list.length
                if (isCelulaJaAberta) {
                    oferecerAulaAbertaParaAluno(resp);
                    return; //fim fluxo
                }
                //não tem oferecimento segue o fluxo para tentar agendar normal.
                sendAgendarAula();
            },
            error: function (resp) {
                console.log(resp);
                var resposta = resp.responseJSON.error;
                showMessage('.message_modal', resposta, 'danger');
            }
        });
    }

    function oferecerAulaAbertaParaAluno(dados) {
        //verificar antes de oferecer se é uma celula que já estou; ou
        // se é uma data/horario que já passou       
        var info = dados.list[0];
        $("#atributoOnOferecimentoAula").val(dados.tipo);
        var student_id = parseInt($("#student_id").val());
        var agendamentoValido = isPossivelAgendar(info.dia, info.horario, student_id, info.students);
        if (!agendamentoValido) {
            onRecusaOferecimentoAulaParaAluno(); //recusa automática. Não tem como oferecer ao aluno
            return false;
        }
        //inicio do oferecimento
        var dia = moment(info.dia).format('DD.MM.YYYY');
        var tituloMsg = capitalizeFirstLetter(dados.tipo) + " já Aberta no turno.";
        var textoMsg = "<b>Deseja agendar a aula abaixo?</b> <br> " +
            "Aula: " + info.aula_sigla + ", professor: " + info.teacher_nome +
            ", dia: " + dia + ", " + info.horario + ". ";
        var callbackRecusa = onRecusaOferecimentoAulaParaAluno;
        var callbackOk = sendOfecimentoAula;

        wxConfirm2(callbackOk, dados, tituloMsg, textoMsg, callbackRecusa);
    }

    function sendOfecimentoAula(dados) {
        var info = dados.list[0];
        var token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: asset + "gradeEscola/agenda",
            method: 'POST',
            data: {
                _token: token,
                celula_id: info.id,
                aula_id: info.aula_id
            },
            beforeSend: function (data) {
                $(".message_modal").html('Loading...');
            },
            success: function (resp) {
                var textoSuccess = "Agendamento realizado com Sucesso!<br>";
                textoSuccess += "Dia: " + resp.dia + " às " + resp.horario + ", Professor: " + resp.teacher;
                setDadosAluno();
                $("#modalCelula").modal('hide');
                $.notify(textoSuccess, { type: 'success' });
                $("#teacher_id").val(info.teacher_id).change();

            },
            error: function (resp) {
                var resposta = resp.responseJSON.error;
                showMessage('.message_modal', resposta, 'danger');
                $.notify(resposta, { type: 'danger' })
                //setDadosAluno();
            }
        });
    }

    function onRecusaOferecimentoAulaParaAluno() {
        var xpt = $("#atributoOnOferecimentoAula").val();
        var textoRecusa = "Você só pode abrir uma nova turma desta " +
            xpt + " após essa turma fechar. Você pode esperar ou tentar outra " + xpt + ".";
        showMessage('.message_modal', textoRecusa, 'warning');
    }

    /**
     * Verifica uma aula se já feita pelo Aluno Autenticado.
     * Se for feita emite alerta ao aluno se quer continuar.
     * Se não feita segue o fluxo normal de tentar agendar: sendAgendarAula()
     */
    function VerificaSeAulaJaFeitaByAluno(aula_id) {
        $.ajax({
            url: asset + "isAulaJaFeitaByAuthStudent/" + aula_id,
            method: 'GET',
            beforeSend: function (data) {
                $(".message_modal").html('Loading...');
            },
            success: function (resp) {
                $(".message_modal").html('');
                var isFeita = resp && resp.isFeita;
                if (isFeita) {
                    alertarAlunoAulaJaFeita(resp.info);
                    return; //fim do fluxo
                }
                console.log('Aula Não feita by aluno')
                sendAgendarAula();

            },
            error: function (resp) {
                console.log(resp);
                showMessage('.message_modal', 'Ocorreu um erro no Servidor', 'danger');
            }
        });
    }

    function alertarAlunoAulaJaFeita(info) {
        var textoConfirm = "Você já fez essa aula no dia " +
            info.dia + ", " + info.horario + ", professor " + info.teacher_nome;
        wxConfirm(sendAgendarAula, "Deseja Realmente Continuar?", textoConfirm);
    }


    function sendAgendarAula() {
        var token = $('meta[name="csrf-token"]').attr('content');
        var celula_id = $("#modalCelula_celula_id").val();
        var aula_id = $("#modalCelula_aula_id").val();
        if (!aula_id) {
            aula_id = $("#selectAula select").val();
        }

        var module_id = $("#selectModule select").val() || null;
        var disciplina_id = $("#selectDisciplina select").val() || null;
        var aula_individual = $("#selectTipoAula select").val() || 0;

        
        var tipo_review = $("#selectTipoReview select").val() || null;
        var descricao_review = $("#fieldDescricaoReview textarea").val() || null;
        var sendAjax = function () {
            $.ajax({
                url: asset + "gradeEscola/agenda",
                method: 'POST',
                data: {
                    _token: token,
                    celula_id: celula_id,
                    aula_id: aula_id,
                    module_id: module_id,
                    disciplina_id: disciplina_id,
                    aula_individual: aula_individual,
                    tipo_review: tipo_review,
                    descricao_review: descricao_review

                },
                beforeSend: function (data) {
                    $(".message_modal").html('Loading...');                    
                },
                success: function (resp) {
                    var textoSuccess = "Agendamento realizado com Sucesso!<br>";
                    textoSuccess += "Dia: " + resp.dia + " às " + resp.horario + ", Professor: " + resp.teacher;
                    setDadosCelula(celula_id);
                    limpaSelects();
                    instanceCalendar.refetchEvents();
                    $.notify(textoSuccess, { type: 'success' });
                    showMessage('.message_modal', textoSuccess, 'success');
                    setDadosAluno();
                    $("#btnBlocoConfirm").attr('disabled', false);

                },
                error: function (resp) {
                    var resposta = resp.responseJSON.error;
                    console.log('resposta erro: ', resposta);
                    showMessage('.message_modal', resposta, 'danger');
                    //$.notify(resposta,{type:'danger'})
                    //showGlobalMessage(resposta,'danger');
                    setDadosAluno();
                    $("#btnBlocoConfirm").attr('disabled', false);
                }
            });
        }
        if (aula_individual == '1') {
            wxConfirm(sendAjax, "Deseja Realmente Continuar?", 'Sala de Aula Individual descontará 2 Créditos!')
        }
        else {
            sendAjax();
        }
    }

    function setDadosAluno() {
        $.ajax({
            url: asset + "getAuthStudent",
            success: function (resp) {
                $("#student_saldo_atual").text(resp.saldo_atual);
                $("#student_id").val(resp.id);
                if (resp.module) {
                    $("#student_module_nome").text(resp.module.nome);
                    $("#student_module_id").val(resp.module.id);
                }
            },
            error: function (resp) {
                console.log(resp)
            }
        });
    }
    setDadosAluno();//load das informações do aluno no cabeçalho

    function isPossivelAgendar(dia, horario, student_id, students) {
        var dateNow = new Date();
        var dateCelula = new Date(dia + ' ' + horario);
        var isFuture = dateCelula > dateNow;
        //console.log('isFuture: ', isFuture)

        var studentOnList = students.find(function (student) {
            return student.id == student_id;
        })
        //console.log('studentsOnlist ',studentOnList);
        return isFuture && !studentOnList;

    }
    //fim funcoes agendamento


    /**
       * Inicio das funções de Edit Aluno
   */

    function sendDesmarcarAula() {
        var token = $('meta[name="csrf-token"]').attr('content');
        var celula_id = $("#modalCelula_celula_id").val();
        var sendAjax = function () {
            $.ajax({
                url: asset + "agendados/" + celula_id + "/desmarcar",
                method: 'DELETE',
                data: {
                    _token: token,
                    celula_id: celula_id


                },
                beforeSend: function (data) {
                    $(".message_modal").html('Loading...');
                },
                success: function (resp) {
                    console.log('resp: ', resp)


                    var textoSuccess = resp.message;
                    setDadosCelula(celula_id);
                    //limpaSelects();
                    instanceCalendar.refetchEvents();
                    $.notify(textoSuccess, { type: 'success' });
                    showMessage('.message_modal', textoSuccess, 'success');
                    setDadosAluno();


                },
                error: function (resp) {
                    var resposta = resp.responseJSON.error;
                    console.log('resposta erro: ', resposta);
                    showMessage('.message_modal', resposta, 'danger');
                    setDadosAluno();

                }
            });
        }
        var aula = $("#modalCelula_aula").text();
        var dia = $("#modalCelula_dia").text();
        var horario = $("#modalCelula_horario").text();
        var professor = $("#teacher_id option:selected").text();
        wxConfirm(sendAjax, "Deseja Realmente Desmarcar?", 'Aula: ' + aula + '; Dia: ' + dia + ' ; Horário: ' + horario + '  Professor: ' + professor);

    }


    function ListStudents() {
        this.alunos = [];
        var $this = this;
        var isCelulaFutura = null;

        function echoX(value) {
            return (value != null && value != undefined) ? value : '';
        }

        function getStringAcaoDesmarcar(aluno) {

            var authStudentId = $('#student_id').val();
            if (aluno.id == authStudentId && isCelulaFutura) {
                return '<button data-id="' + aluno.id + '" class="btn btn-outline-danger btn-sm btnDesmarcarAula" title="Desmarcar Aula">' +
                    '<i class="fa fa-trash" aria-hidden="true"></i> ' +
                    '</button>';

            }
            return "";
        }

        this.findAlunoById = function (id) {
            return $this.alunos.find(function (aluno) {
                return aluno.id == id;
            })
        }

        this.setIsCelulaFutura = function () {
            var horario = $("#modalCelula_horario").text();
            var diaArray = $("#modalCelula_dia").text().split('.');
            var dia = diaArray[2] + "-" + diaArray[1] + "-" + diaArray[0];

            var dateNow = new Date();
            var dateCelula = new Date(dia + ' ' + horario);
            isCelulaFutura = dateCelula > dateNow;
        }

        this.updateTable = function () {
            if (!$this.alunos.length) {
                $("#modalCelula_students tbody").html('');
                $("#modalCelula_students").hide();
                return false;
            }

            $("#modalCelula_students").show();
            var mapStudents = $this.alunos.map(function (student) {
                var strPresenca = '';
                if (student.pivot.presenca == '1') {
                    //strPresenca = 'S';
                    strPresenca = '<i class="fa fa-check-square-o" aria-hidden="true"></i>'
                }
                if (student.pivot.presenca == '0') {
                    strPresenca = 'N';
                }
                var studentModuleName = student.module ? student.module.nome : '';
                var string = "<tr>" +
                    '<td>' + getStringAcaoDesmarcar(student) + '</td>' +
                    '<td>' + student.nome + ' (' + studentModuleName + ')</td>' +
                    '<td>' + strPresenca + '</td>' +
                    '<td>' + echoX(student.pivot.n1) + '</td>' +
                    '<td>' + echoX(student.pivot.n2) + '</td>' +
                    '<td>' + echoX(student.pivot.n3) + '</td>' +
                    '<td>' + echoX(student.pivot.n4) + '</td>' +
                    '<td>' + createPopOverLink(echoX(student.pivot.feedback)) + '</td>' +
                    "</tr>";

                return string;
            });
            $("#modalCelula_students tbody").html(mapStudents.join(''));
            $(".btnDesmarcarAula").click(sendDesmarcarAula);
            startPopOverLink()
        }
    } //Fim Class ListStudent



    var listStudents = 'listagem de students';
    function mountStudentsOnCelula(students) {
        listStudents = new ListStudents();
        listStudents.alunos = students;
        listStudents.setIsCelulaFutura();
        listStudents.updateTable();
        console.log('mountStudentsOnCelula')

    }

    $('#modalCelula').on('hidden.bs.modal', function (e) {
        $("#modalCelula_celula_id").val('');
    })

    /**
     * Fim das funções de Edit Alunos
     */


})();