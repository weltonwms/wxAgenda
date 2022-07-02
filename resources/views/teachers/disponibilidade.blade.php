<br>

<?php
$dias_semana=['dom','seg','ter', 'qua','qui','sex','sab'];
$horarios_escola=$horariosList;
?>
<div class="table-responsive col-lg-8">
<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <td>Horários</td>
            @foreach($dias_semana as $dia)
            <td>{{$dia}}</td>

            @endforeach
        </tr>
    </thead>

    <tbody class="disponibilidade">
        @foreach($horarios_escola as $horario)
        <tr>
        <td>{{$horario}}</td>
            @foreach($dias_semana as $keyDay=>$dia)
            <td class=""> 
           
                    
                <input type="checkbox" data-hour="{{$horario}}" data-day="{{$keyDay}}" class="checkbox-lg">
                
            </td>
            @endforeach
        </tr>

        @endforeach
    </tbody>

</table>
</div>


{{ Form::hidden('disponibilidade',null,['class'=>"form-control", 'id'=>'disponibilidade']) }}


@push('scripts')
<script>
function Ctrl() {
    var obj = null;

    function init() {
        if (!obj) {
            obj = {
                "0": [],
                "1": [],
                "2": [],
                "3": [],
                "4": [],
                "5": [],
                "6": []
            }
            
        }
    }

    function getObj() {
        return obj;
    }

    function setObj(json) {
        if(!json){
            return false;
        }
        
        var dias = ["0", '1', '2', '3', '4', '5', '6'];
        var obj2 = JSON.parse(json);
        checkObjWithHorariosDisponiveis(obj2);
        dias.forEach(function(dia) {
            if (!obj2.hasOwnProperty(dia)) {
                obj2[dia] = [];
            }
        });
        obj = obj2;
        writeOnTable();
    }



    function add($day, $hour) {
        if (obj[$day].indexOf($hour) < 0) {
            obj[$day].push($hour);
        }
    }

    function remove($day, $hour) {
        var index = obj[$day].indexOf($hour);
        if (index > -1) {
            obj[$day].splice(index, 1);
        }

    }

    function onChange(e) {
        var input = e.currentTarget;

        var day = input.dataset.day;
        var hour = input.dataset.hour;
        //realizar validações antes
        init();
        if (input.checked) {
            add(day, hour);
        } else {
            remove(day, hour)
        }

        writeOnInput()
    }

    function writeOnInput() {
        var objString = JSON.stringify(obj);
        var input = document.querySelector("#disponibilidade");
        input.value = objString;
    }

    function writeOnTable() {
        var inputs = document.querySelectorAll('.disponibilidade input[type=checkbox]');
        inputs.forEach(function(el) {
            var day = el.dataset.day;
            var hour = el.dataset.hour;
            
            if (obj[day] != undefined) {
                var index = obj[day].indexOf(hour);
                if (index > -1) {
                    el.checked = true;
                } else {
                    el.checked = false;
                }

            }
        })
    }

    function checkObjWithHorariosDisponiveis(newObj){
        //sanitizar newObj baseado nos horários disponiveis.
    }

    return {
        init: init,
        getObj: getObj,
        setObj: setObj,
        onChange: onChange
    }
}

var ctrl = Ctrl();

function startDisponibilidade() {
    var inputs = document.querySelectorAll('.disponibilidade  input[type=checkbox]');
    inputs.forEach(function(el) {
        el.addEventListener("change", ctrl.onChange)
    });

    var inputDisponibilidade=document.querySelector('#disponibilidade');
    var stringObj=inputDisponibilidade.value;
    ctrl.setObj(stringObj);

}

startDisponibilidade();
</script>


@endpush