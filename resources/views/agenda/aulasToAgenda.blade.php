
        <ul >
            @foreach($disciplinasWithAulas as $disciplina)

            <li class="jstree-open" data-jstree='{"disabled":true}'>
                <span class="" href="#">{{$disciplina->nome}}</span>
                <ul class="">
                    @foreach($disciplina->aulas as $aula)
                    <li @if($aula->current) data-jstree='{"icon":"fa fa-caret-square-o-right"}' @endif>
                        <a  @class(['aulas','aula_realizada'=>$aula->realizada]) 
                            href="#" data-aula_id="{{$aula->id}}" >{{$aula->sigla}}
                        </a>
                    </li>
                    @endforeach
                </ul>

            </li>
            @endforeach
         
        </ul>
  