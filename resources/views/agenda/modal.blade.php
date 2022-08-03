<!-- Modal -->
<h2>Modal Agenda</h2>

<div class="modal fade" id="modalAgenda" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="modalAgendaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgendaLabel">Agendar Aula</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
               
                <div class="container-fluid">
                    <div class="row  p-2 mb-3 bg-light">
                        <div class="col-6 col-sm-4">
                            <b>Dia:</b> <span id="modalAgenda_dia"></span>
                        </div>
                        <div class="col-6 col-sm-4">
                            <b>Hora:</b><span id="modalAgenda_horario"></span>
                        </div>
                        <div class="col-sm-4">
                            <b>Aula: </b> <span id="modalAgenda_aula"></span>
                        </div>
                    </div>

                    <label for="celula_to_agenda">Escolha o Professor</label>
                    <select name="celula_to_agenda" id="celula_to_agenda" class="form-control">

                    </select>

                    <ul id="modalAgenda_teachers">

                    </ul>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success" id="btnAgendar">Agendar Aula</button>
            </div>
        </div>
    </div>
</div>
