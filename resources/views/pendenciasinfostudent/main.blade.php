<input type="hidden" id="is_adm" value="{{auth()->user()->isAdm?1:0}}">
<h2 class="text-center">Pendências de Preenchimento de Presença</h2>
<h5 class="text-center">Verificação de {{$params->startBr}} até {{$params->endBr}}</h5>
<p class="text-center">Não foram inseridos dados nas seguintes células de aula: </p>


<table class="table table-bordered">
    <thead class="thead-light">
        <tr>
            <th>Dia</th>
            <th>Horário</th>
            <th>Professor</th>
            <th>Aula</th>
            <th></th>
        </tr>        
    </thead>
    <tbody>
        @foreach ( $celulasInfo as $info )
        <tr>
            <td>{{$info->getDiaFormatado()}}</td>
            <td>{{$info->horario}}</td>
            <td>{{$info->teacher_nome}}</td>
            <td>{{$info->aula_sigla}}</td>
            <td>
                <button data-celula_id="{{$info->id}}" 
                    class="detalhesPendencia">
                    Ver
                </button>
            </td>
        </tr>        
        @endforeach
        

    </tbody>
</table>

@include('celulas.modal')

@push('scripts')
<script src="{{ asset('js/celulas.js?v2') }}"></script>


@endpush