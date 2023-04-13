<!-- Modal -->


<div class="modal fade" id="modalCelula" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="modalCelulaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCelulaLabel">Dados Células</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="message_modal"></div>
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
                    </div>

<!--Formulário de Edição do Aluno na Célula-->
<div id="content_edit_aluno" style="display:none">
<form class="row" style="background-color:#ddd" id="formEditAluno">
<input type="hidden" id="student_id" name="student_id" value="">
<div class="col-sm-12">
    <br>
<button type="button" class="btn btn-sm btn-outline-secondary my-1 btnCancellEditAluno" >Cancelar</button>
<button type="button" class="btn btn-sm btn-success my-1 btnSaveEditAluno">Salvar</button>

</div>
<div class="col-sm-6">
<div class="form-group">
    <label for="">Aluno</label>
    <input type="text" class="form-control form-control-sm" id="nomeAluno" value="" readonly>
</div>
</div>

<div class="col-sm-6">
<div class="form-group">
    <label for="">Presença</label>
    <div class="form-check">
        <input class="form-check-input position-static" type="checkbox" name="presenca" id="presenca" value="1" >
    </div>
</div>
</div>

<div class="col-sm-4">
<div class="form-group">
    <label for="">N1</label>
    <input type="number" class="form-control form-control-sm" value="" name='n1' id="n1">
</div>
</div>

<div class="col-sm-4">
<div class="form-group">
    <label for="">N2</label>
    <input type="number" class="form-control form-control-sm" value="" name='n2' id="n2">
</div>
</div>

<div class="col-sm-4">
<div class="form-group">
    <label for="">N3</label>
    <input type="number" class="form-control form-control-sm" value="" name='n3' id="n3">
</div>
</div>

<div class="col-sm-12">
<div class="form-group">
    <label for="feedback">FeedBack</label>
    <textarea class="form-control" id="feedback" name="feedback" rows="3"></textarea>
  </div>

</div>

  
</form>
<br>
</div>
<!--Fim Formulário de Edição do Aluno na Célula-->

<!--Table com Students na Célula -->
<div class="table-responsive" id="modalCelula_students">
<table class="table table-bordered table-sm">
  <thead>
    <tr>
    <th >Edit</th>
    <th >Excluir</th>
      <th >Aluno</th>
      <th >Presença</th>
      <th >N1</th>
      <th >N2</th>
      <th >N3</th>
      <th >FeedBack</th>
      
    </tr>
  </thead>
  <tbody>
    
  </tbody>
</table>
</div>
<!--Fim Table com Students na Célula -->

                   <!--Bloco Adicionar Aluno na Célula -->
                    <div class="row mb-3">
                        <div class="col-sm-3 mb-2">
                            <button id="btnAddStudent" class="btn btn-sm btn-outline-success"
                                title="Adicionar Aluno na Célula de Aula">
                                Add Aluno
                            </button>
                        </div>

                        <div class="col-sm-6">
                            <div id="blocoConfirm" class=""></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div id="selectAluno" class="form-group"></div>
                        </div>

                        <div class="col-sm-4">
                            <div id="selectModule" class="form-group"></div>
                        </div>

                        <div class="col-sm-4">
                            <div id="selectDisciplina" class="form-group"></div>
                        </div>

                        <div class="col-sm-4">
                            <div id="selectAula" class="form-group"></div>
                        </div>
                        <div class="col-sm-8">
                                <div id="selectTipoAula" class="form-group"></div>
                        </div>
                    </div>
                     <!--Fim Bloco Adicionar Aluno na Célula -->

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" id="btnDeleteCelula">Apagar Célula</button>
            </div>
        </div>
    </div>
</div>