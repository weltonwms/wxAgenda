<!-- Modal -->
<div class="modal fade" id="ModalFormCredito" tabindex="-1" role="dialog" aria-labelledby="TituloModalFormCredito"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TituloModalFormCredito">Computar Crédito</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route'=>'credits.store','class'=>'','id'=>'adminForm2'])!!}
                
                    @include('students.credits.form')
                {!! Form::close() !!}


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" id="btnSaveNewCredit" class="btn btn-primary">
                    Salvar
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')

<script>
function saveNewCredit(e) {
    
    e.preventDefault();
    $.ajax({
        url: '/credits',
        type: "POST",
        data: $('#adminForm2').serialize(),
        success: function(resp) {
            console.log(resp);
            $("#saldo_atual").val(resp.saldo_posterior);
            closeModal();
        },
        error: function(err) {
            //console.log('errors' , err)
            console.log(err.responseJSON.errors)
            $.each(err.responseJSON.errors, function(i, error) {
                console.log('i ', i)
                console.log('erro ', error)
                var el = $(document).find('[name="' + i + '"]');
                el.after($('<span class="ajaxErros" style="color: red;">' + error[0] + '</span>'));
            });
        },
        beforeSend: function() {
            $(".ajaxErros").remove();
        }
    })

}

function closeModal(){
        $('#adminForm2')[0].reset();
        
        $('#ModalFormCredito').modal('hide');
        console.log('datatable',tableS);
        tableS.ajax.reload();
    }
$("#btnSaveNewCredit").click(saveNewCredit);
</script>
@endpush


