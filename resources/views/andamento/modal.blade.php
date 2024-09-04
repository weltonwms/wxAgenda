<!-- Modal -->
<div class="modal fade" id="ModalDetalhesAndamento" tabindex="-1" role="dialog"
    aria-labelledby="TituloModalDetalhesAndamento" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TituloModalDetalhesAndamento">Andamento do Aluno</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="detailClasseDone" style="display: none;">
                </div>
                <ul id="detalhesAndamentoList">
                </ul>

                <div class="card p-2">
                    <h5>Legenda:</h5>
                    <p class="">
                        <span class="badge legenda2 ">Verde</span>: Aula Realizada
                    </p>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>

            </div>
        </div>
    </div>
</div>
<!-- Fim Modal -->

<style>
.popover {
    max-width: 95% !important;
    width: auto;
    min-width: 300px;
}

.popover .close {
    position: absolute;
    top: 0;
    right: 6px;
    font-size: 20px;
    z-index: 10;
}

.table-sm.t12 {
    font-size: 12px;
    margin-top: 10px;
}
</style>

@push('scripts')
<script>
$(".detalhes_andamento").click(function(e) {
    e.preventDefault();
    var dados = atob(this.dataset.detalhes);
    var nome = this.dataset.nome;
    var student_id = this.dataset.student_id;
    dados = dados ? JSON.parse(dados) : [];

    var mp = dados.map(function(dado) {
        var classe = dado.value == 1 ? 'show aula-realizada' : '';
        var attrs = 'data-student_id="' + student_id + '" data-aula_id="' + dado.aula_id + '" ';
        return '<li class="' + classe + '" ' + attrs + ' >' + dado.sigla + '</li>';
    }).join('\n');

    $("#TituloModalDetalhesAndamento").html("Andamento Aluno(a): " + nome);
    $("#detalhesAndamentoList").html(mp);
    $(".show.aula-realizada").click(showDetailClassDone);
    $(".show.aula-realizada").popover({
                trigger: 'manual',
                html: true,
                placement: "bottom",
                content: function() {
                    return $('#detailClasseDone').html();
                },
                sanitize: false
            });
    $('#ModalDetalhesAndamento').modal('show');
});

var lastAulaId = null;
function showDetailClassDone(e) {
    e.preventDefault();
    var target = e.currentTarget;
    var student_id = target.dataset.student_id;
    var aula_id = target.dataset.aula_id;
    var $this = $(this);   
   
    // Verifica se o item clicado é o mesmo que já está com o popover aberto    
    if (lastAulaId === aula_id) {       
        $(".show.aula-realizada").popover('hide');
        lastAulaId = null;  // Reseta o último `aula_id` ao fechar
        return;
    }

    // Atualiza o `lastAulaId` com o novo `aula_id`
    lastAulaId = aula_id;
    

    $.ajax({
        url: asset + "getPivotsByStudentAndAula",
        data: {
            student_id: student_id,
            aula_id: aula_id
        },
        beforeSend: function() {

        },
        success: function(resp) {
            //console.log(resp);
            var tableContent = `
                <button type='button' class='close' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                </button>
                <table class="table table-sm table-bordered t12">
                <thead>
                <tr>
                    <th>Dia</th>
                    <th>Horário</th>
                    <th>N1</th>
                    <th>N2</th>
                    <th>N3</th>
                    <th>N4</th>
                    <th>Feedback</th>
                </tr>
                </thead>
                <tbody>`;

            // Construir as linhas da tabela
            $.each(resp, function(index, item) {       
                var dia = moment(item.dia);
                var horario = item.horario.split(':').slice(0, 2).join(':');
                var n1 = item.n1 ? item.n1 : '';
                var n2 = item.n2 ? item.n2 : '';
                var n3 = item.n3 ? item.n3 : '';
                var n4 = item.n4 ? item.n4 : '';
                var feedback = item.feedback ? item.feedback : '';
                
               
                tableContent += `<tr>
                    <td>${dia.format('DD.MM.YYYY')}</td>
                    <td>${horario}</td>
                    <td>${n1}</td>
                    <td>${n2}</td>
                    <td>${n3}</td>
                    <td>${n4}</td>
                    <td>${feedback}</td>
                    </tr>`;
            });

            // Fechar a tabela
            tableContent += `</tbody></table>`;
            $('#detailClasseDone').html(tableContent);
           
           $this.popover('show');
            // Fechar o popover ao clicar no botão "X"
            $(document).on('click', '.popover .close', function() {
                $this.popover('hide');
                lastAulaId = null;
            });
        }
    });
} //fim showDetailClassDone

// Fechar popovers ao clicar fora deles
$(document).on('click', function (e) {       
    $('.show.aula-realizada').popover('hide');
    if (!$(e.target).hasClass('aula-realizada') && $(e.target).data('aula_id') != lastAulaId) {             
        lastAulaId = null;
    }   
});

</script>
@endpush