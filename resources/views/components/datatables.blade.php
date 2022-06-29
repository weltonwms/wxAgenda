{{-- resources/views/components/datatables.blade.php --}}

<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
               
                <div class="table-responsive">
              

                <table class="table table-hover table-bordered nowrap" id="dataTable1">
                    {{ $slot }}
                </table>
               
                </div>
              

                <!--dependencia dataTableSubmit-->
                <form id="adminForm" action="" method="POST" style="display: none;">
                    @csrf
                </form>
                <!--dependencia dataTableSubmit-->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript" src="{{ asset('template/js/plugins/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/plugins/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/plugins/datetime-moment.js') }}"></script>
<!--responsive datatables e datatables selecionavel-->
<!--<script type="text/javascript" src="{{ asset('template/js/plugins/dataTables.responsive.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/js/plugins/responsive.bootstrap.min.js') }}"></script>-->
<script type="text/javascript" src="{{ asset('template/js/plugins/dataTables.select.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/tabela.js') }}"></script>
<script>
    $(document).ready(function() {
    var colId= $("thead [id]").index()
    if(colId >0){
        Tabela.getInstance({colId:colId}); //instanciando dataTable e informando a coluna do id
    }
});
</script>


@endpush