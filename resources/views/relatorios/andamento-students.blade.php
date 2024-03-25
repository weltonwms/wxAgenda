@extends('layouts.app')

@section('breadcrumb')
@breadcrumbs(['title'=>' Relatório: Alunos - Andamento', 'icon'=>'fa-circle-o', 'route'=>route('relatorio.andamento'),
'subtitle'=>'Andamento das Aulas pelos Alunos'])

@endbreadcrumbs
@endsection

@section('toolbar')
@toolbar
<button class="btn btn-sm btn-primary mr-1 mb-1" onclick="document.getElementById('form_pesquisa').submit()">
    <i class="fa fa-search" aria-hidden="true"></i> Executar Pesquisa
</button>
<button class="btn btn-sm btn-outline-secondary mr-1 mb-1" type="button" onclick="limparFormPesquisa()">
    <i class="fa fa-undo"></i>Limpar Form Pesquisa
</button>
@endtoolbar
@endsection


@section('content')

<div class="tile tile-nomargin">
{!! Form::open(['route'=>'relatorio.andamento','id'=>'form_pesquisa'])!!}
        <div class='row'>

            <div class="col-sm-2">

                {!!Form::bsSelect('module_id', $modulesList,
                    request('module_id'),
                    ["class"=>"select2",
                    "label"=>"Módulo"]
                )!!}
            </div>           


            <div class="col-sm-2">

                {!!Form::bsSelect('disciplina_id', $disciplinasList,
                request('disciplina_id'),
                ["class"=>"select2",
                "placeholder"=>"-Selecione-","label"=>"Disciplina"]
                )!!}
            </div>

        </div>
    </form>
</div>

<div class="tile">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Aluno</th>
                    <th>Total Aulas</th>
                    <th>Aulas Feitas</th>
                    <th>% Completado</th> 
                    <th>Detalhes</th>    
                </tr>
            </thead>
            <tbody>
                @foreach($relatorio->getAlunos() as $key=>$aluno)
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{$aluno->nome}}</td>
                        <td>{{$aluno->countAulas}}</td>
                        <td>{{$aluno->countFeitas}}</td>
                        <td class="@if($aluno->percentualComplete > 99) bg-success text-white @endif">
                        {{number_format($aluno->percentualComplete,0)}}%
                        </td>
                        <td>
                            <a href="#" class="detalhes_andamento"
                            data-nome="{{$aluno->nome}}"
                            data-detalhes="{{base64_encode($relatorio->mapeamento($aluno->aulasTarget)->toJson() )}}"
                            >
                            <i class="fa fa-eye"></i>
                            </a>
                           
                        </td>
                    </tr>
                @endforeach     
            </tbody>
        </table>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="ModalDetalhesAndamento" tabindex="-1" role="dialog" aria-labelledby="TituloModalDetalhesAndamento"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TituloModalDetalhesAndamento">Andamento do Aluno</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">               
                <ul id="detalhesAndamentoList">
                </ul>

                <div class="card p-2">
                    <h5>Legenda:</h5>
                    <p class="">
                        <span class="badge legenda2 ">Verde</span>: Aula Realizada
                    </p>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
               
            </div>
        </div>
    </div>
</div>
<!-- Fim Modal -->

@endsection

@push('scripts')
<script>
   $(".detalhes_andamento").click(function(e){
        e.preventDefault();
        var dados = atob(this.dataset.detalhes);
        var nome = this.dataset.nome;
        dados = dados?JSON.parse(dados):[];
       
        var mp = dados.map(function(dado){
            var classe= dado.value==1?'aula-realizada':'';
            return '<li class="'+classe+'">'+dado.sigla+'</li>';
        }).join('\n');
       
        $("#TituloModalDetalhesAndamento").html("Andamento Aluno(a): "+nome);
        $("#detalhesAndamentoList").html(mp);
        $('#ModalDetalhesAndamento').modal('show');
   });

</script>

@endpush

