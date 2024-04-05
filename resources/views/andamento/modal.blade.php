<!-- Modal -->
<div class="modal fade" id="ModalDetalhesAndamento" tabindex="-1" role="dialog" aria-labelledby="TituloModalDetalhesAndamento"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TituloModalDetalhesAndamento">Andamento do Aluno</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">               
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



@push('scripts')
<script>
   $(".detalhes_andamento").click(function(e){
        e.preventDefault();
        var dados = atob(this.dataset.detalhes);
        var nome = this.dataset.nome;
        dados = dados?JSON.parse(dados):[];
       
        var mp = dados.map(function(dado){
            var classe= dado.value==1?'aula-realizada':'';
            return '<li class="'+classe+'">'+dado.sigla+'</li>';
        }).join('\n');
       
        $("#TituloModalDetalhesAndamento").html("Andamento Aluno(a): "+nome);
        $("#detalhesAndamentoList").html(mp);
        $('#ModalDetalhesAndamento').modal('show');
   });

</script>
@endpush
