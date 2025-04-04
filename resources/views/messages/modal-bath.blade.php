<!-- Modal -->
<div class="modal fade" id="modalMessageBath" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="modalMessageBathLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center">Enviar Email Coletivo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="loading" class="text-center" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Enviando...</span>
                    </div>
                </div>

                <div id="message-status" class="alert d-none mt-3"></div>

                <div class="row p-2">
                    <div class="col-lg-12">

                        <div class="form-group">
                            <label for="subject">Assunto *</label>
                            {{ Form::text('subject', null, ['class' => "form-control", 'id' => 'subject']) }}
                            <div class="text-danger error-message" id="error-subject"></div>
                        </div>

                        <div class="form-group">
                            <label for="body">Texto *</label>
                            {{Form::textarea('body', null, ['rows' => 3, 'class' => 'form-control', 'id' => 'body'])}}
                            <div class="text-danger error-message" id="error-body"></div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary mr-2" type="button" id="btnSubmitEnviarEmail">
                        <i class="fa fa-share"></i> Enviar
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>

            </div>
        </div>
    </div>
@push('scripts')
<script>
function configurarEnvioEmail(seletorAbrir, seletorEnviar, getIdsFunc) {
    $(seletorAbrir).click(function() {
        if(!getIdsFunc().length){
            alert('Nenhum registro selecionado');
            return false;
        }
        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
        $("#message-status").addClass("d-none");
        $("#loading").hide(); 
        $('#modalMessageBath').modal('show');
    });

    $(seletorEnviar).click(function() {
        var ids = getIdsFunc();
        if(!ids.length){
            alert('Nenhum registro selecionado');
            return false;
        }

        var token = $('meta[name="csrf-token"]').attr('content');
        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
        var subject = $("#subject").val();
        var body = $("#body").val();

        $.ajax({
            url: asset + "messages/send_bath",
            type: "POST",
            data: { ids, _token: token, subject, body },
            beforeSend: function() {
                $("#loading").show();
                $("#message-status").addClass("d-none");
            },      
            success: function(response) {
                console.log(response);
                $("#message-status")
                    .removeClass("d-none alert-danger")
                    .addClass("alert alert-success")
                    .text("📨 E-mails enviados com sucesso!");
            },
            error: function(response) {  
                console.log(response);
                if (response.status == 422 && response.responseJSON?.errors) {
                    Object.keys(response.responseJSON.errors).forEach(field => {
                        document.getElementById(`error-${field}`).textContent = response.responseJSON.errors[field][0];
                    });
                }
                if(response.status != 422){
                    $("#message-status")
                    .removeClass("d-none alert-success")
                    .addClass("alert alert-danger")
                    .text("❌ Ocorreu um erro ao enviar os e-mails.");
                }        
            },
            complete: function() {
                $("#loading").hide();
            }
        });
    });
}
</script>
@endpush
