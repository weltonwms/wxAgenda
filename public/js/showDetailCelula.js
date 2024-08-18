/**
 * Depende de HTML MODAL. views/grade/modal
 * Depende de carregamento anterior de scripts bootstrap e app.js
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
                '<td>' + echoX(student.pivot.n4) + '</td>' +
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