@if(isset($student))

<div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            <label for="saldo_atual">Créditos Atuais:</label>
            <div class="input-group ">
                <input type="text" class="form-control" id="saldo_atual" readonly value="{{$student->saldo_atual}}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" data-toggle="modal" data-target="#ModalFormCredito"
                        type="button">Add/Tirar Créditos</button>
                </div>
            </div>
            <small class="form-text text-muted">Utilize o Botão para Adicionar ou Retirar Créditos.</small>
        </div>

    </div>

</div>

<hr>
<div class="table-responsive">

<table id="tableCreditsStudent" class="table table-hover table-bordered nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Data Acao</th>
                <th>Qtd</th>
                <th>Operacao</th>
                <th>Saldo Ant.</th>
                <th>Saldo Post</th>
                <th>Obs:</th>
            </tr>
        </thead>
       
    </table>

</div>
@push('scripts')
<script type="text/javascript" src="{{ asset('template/js/plugins/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/plugins/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/plugins/datetime-moment.js') }}"></script>

<script>
   
var tableCreditsStudent=null;
var student_id=$("input[name=student_id]").val();    
tableCreditsStudent = $('#tableCreditsStudent').DataTable( {
        ajax: asset+'credits/'+student_id,
        language:{
            url: asset+"json/languageDataTable.json",
        },
        columns: [
            { data: 'data_acao' },
            { data: 'qtd' },
            { data: 'operacao' },
            { data: 'saldo_anterior' },
            { data: 'saldo_posterior' },
            { data: 'obs' },
        ],
        order: [0, 'desc'],
    });
$('#ModalFormCredito').on('shown.bs.modal', function () {
  var horasContratadas= $('#horas_contratadas').val();
  if(!$('#qtd').val()){
    //facilitar preenchimento caso o campo qtd esteja vazio
    $('#qtd').val(horasContratadas);
  }
  
})
     
</script>


@endpush

@else
<div class="alert alert-info">
  Para Colocar Crédito é necessário salvar o Aluno primeiro!
</div>

@endif