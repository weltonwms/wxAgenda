<!-- Modal -->


<div class="modal fade" id="modalDeleteCelula" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="modalDeleteCelulaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDeleteCelulaLabel">Dados Células</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modalCelula_celula_id" />
                <div class="container-fluid">
                    <div class="row  p-2 mb-3 bg-light">
                        <div class="col-6 col-sm-4">
                            <b>Dia:</b> <span id="modalCelula_dia"></span>
                        </div>
                        <div class="col-6 col-sm-4">
                            <b>Hora:</b><span id="modalCelula_horario"></span>
                        </div>
                        <div class="col-sm-4">
                            <b>Aula: </b> <span id="modalCelula_aula"></span>
                        </div>
                    </div>




                    <ul id="modalCelula_students">

                    </ul>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" id="btnDeleteCelula">Cancelar Célula</button>
            </div>
        </div>
    </div>
</div>
