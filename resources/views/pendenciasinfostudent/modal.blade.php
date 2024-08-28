

<!-- Modal -->
<div class="modal fade" id="modalAvisoPendencias" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="modalAvisoPendenciasLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="modalAvisoPendenciasLabel">Aviso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="text-center">
                    <h4>Existem {{$celulasInfo->count()}} células de aula
                         com pendências de 
                        preenchimento de Presença
                        
                    </h4>
                    <p>Verificação de {{$params->startBr}} até {{$params->endBr}}</p>
                    <a class="btn btn-primary" href="{{route('pendenciasInfo.main')}}">Visualizar</a>
                    <br>
                </div>
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar Aviso</button>

            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
  //util se usar modal em cima de modal  
 /*   
$('#modalCelula [data-dismiss="modal"]').attr('closeModalCelula', 'true');
$('#modalCelula [data-dismiss="modal"]').removeAttr('data-dismiss');
$('#modalCelula [closeModalCelula="true"]').click(function() {
    $('#modalCelula').modal('hide');
})
*/

$('#modalAvisoPendencias').modal('show');//disparo automático do aviso
</script>
@endpush