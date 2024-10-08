<!-- Modal -->
<div class="modal fade" id="modalCelula" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="modalCelulaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCelulaLabel">Dados Célula - Agendamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="message_modal"></div>
                <div class="corpo_modal">
                    <input type="hidden" id="modalCelula_celula_id" />
                    <input type="hidden" id="modalCelula_aula_id" />
                    <div class="container-fluid">
                        <div id="info_aula_individual"></div>
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
                            <div class="col-sm-12 mt-1" id="modalCelula_aula_link">

                            </div>
                            <div class="col-sm-12 mt-1" id="modalCelula_reviewInfo">

                            </div>
                        </div>


                        <!--Table com Students na Célula -->
                        <div class="table-responsive" id="modalCelula_students">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Aluno</th>
                                        <th>Presença</th>
                                        <th title="Nota Interaction" data-toggle="tooltip">N1</th>
                                        <th title="Nota Speaking" data-toggle="tooltip">N2</th>
                                        <th title="Nota Listening" data-toggle="tooltip">N3</th>
                                        <th title="Nota Comprehension" data-toggle="tooltip">N4</th>
                                        <th>FeedBack</th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <!--Fim Table com Students na Célula -->

                        <div class="row mb-3">
                            <div class="col-sm-4 mb-2">
                                <button id="btnAgendarAula" class="btn btn-sm btn-outline-success" title="Agendar Aula">
                                    Agendar Aula
                                </button>
                            </div>

                            <div class="col-sm-6">
                                <div id="blocoConfirm" class=""></div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-sm-4">
                                <div id="selectModule" class="form-group"></div>
                            </div>

                            <div class="col-sm-4">
                                <div id="selectDisciplina" class="form-group"></div>
                            </div>

                            <div class="col-sm-4">
                                <div id="selectAula" class="form-group"></div>
                            </div>
                        </div>

                        <div class="row reviewInfo mt-neg-10-mobile" style="display:none">
                            <div class="col-sm-8">
                                <div id="selectTipoReview" class="form-group">
                                    <select class='form-control form-control-sm'>
                                        <option value=''>--Tipo Review--</option>
                                        <option value="1">Revisão de Aula/Matéria</option>
                                        <option value="2">Tema Particular</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div id="fieldDescricaoReview" class="form-group">
                                    <textarea class="form-control" placeholder="Descrição da Review" cols="30"
                                        rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-8">
                                <div id="selectTipoAula" class="form-group"></div>
                            </div>
                        </div>

                        <!--Fim fields de Agendamento -->
                    </div>
                    <!--Fim container fluid -->
                </div>
                <!--Fim .corpo_modal -->
            </div>
            <!--Fim .modal-body -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>

            </div>

        </div>
    </div>
</div>
<!--Atributo ignorável usado durante oferecimento de Aula-->
<input type="hidden" id="atributoOnOferecimentoAula" value="" />