function confirmDelete(callback) {
    swal({
        title: 'Deseja Realmente Excluir?',
        type: "warning",
        showCancelButton: true
    }, function (ok) {
        if (ok) {
            callback();
        }
    });
}

function wxConfirm(callback, title = "Deseja Realmente  ?", content = "") {
    swal({
        title: title,
        type: "warning",
        showCancelButton: true,
        text: content,
    }, function (ok) {
        if (ok) {
            callback();
        }
    });
}

function wxConfirm2(callback, paramsCallback, 
    title = "Deseja Realmente  ?", content = "",fallBack=function(){}) {
    swal({
        html:true,
        title: title,
        type: "warning",
        showCancelButton: true,
        text: content,
    }, function (ok) {
        if (ok) {
            callback(paramsCallback);
        }
        else{
            fallBack()
        }
    });
}



var SPMaskBehavior = function (val) {
    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
},
    spOptions = {
        onKeyPress: function (val, e, field, options) {
            field.mask(SPMaskBehavior.apply({}, arguments), options);
        }
    };

function adminFormSubmit(event) {
    event.preventDefault();
    var btn = event.currentTarget;
    var fechar = !!btn.dataset.close;
    var form = document.getElementById("adminForm");
    if (fechar) {
        var exist = $("input[name=fechar]").length;
        if (!exist) {
            $(form).append('<input type="hidden" name="fechar" value="' + btn.dataset.close + '" >');
        }
    }
    else {
        $("input[name=fechar]").remove();
    }
    form.submit();
    $(btn).attr('disabled', 'disabled');
    $(btn).removeAttr('onclick');
    $(btn).unbind();
}

$(document).ready(function () {
    $('.cep').mask('00000-000');
    $('.cpf').mask('000.000.000-00', { reverse: true });
    $('.phone').mask(SPMaskBehavior, spOptions);
    $('.money').mask('000.000.000.000.000,00', { reverse: true });


    //função busca CEP em Webservice
    $("#cep").keyup(function () {
        if (this.value.length == 9) {
            $('#cep').css({ 'background': "url('../img/preload.GIF') no-repeat right", 'background-size': '30px 30px' });
            $('body').append("<div class='fundo_preload  modal-backdrop fade show'></div>");
            $.getJSON("//viacep.com.br/ws/" + this.value + "/json/?callback=?", function (dados) {

                if (!("erro" in dados)) {
                    var string_endereco = dados.logradouro + ' ' + dados.complemento + ' ';
                    string_endereco += dados.bairro + ' ' + dados.localidade + ' - ' + dados.uf + ' ' + dados.unidade;
                    $("#endereco").val(string_endereco);
                } else {

                    alert("CEP não encontrado.");
                }
            }).done(function () {
                $('#cep').css('background', "url()");
                $('.fundo_preload').remove();
            }).fail(function () {
                $('#cep').css('background', "url()");
                $('.fundo_preload').remove();
                console.log('falha de rede ou erro lançado pelo webservice');

            });
        }
    }); //fim  KEYUP função busca CEP
    //FIM BUSCA CEP

    $('.select2').select2();


});

function limparFormPesquisa() {
    $('#form_pesquisa select, #form_pesquisa input[type=date]').val('');
    $('#form_pesquisa select').trigger('change'); //avisar select2
}

function valorFormatado(valorNumber) {
    var v = Number.parseFloat(valorNumber); //garantindo que param vai ser number
    if (isNaN(v)) {
        //mesmo assim se não form um número para aqui.
        return "";
    }
    var valor_formatado = v.toFixed(2).toString().replace('.', ',');
    return valor_formatado; //string formatada
}

function floatBr(valorNumber) {
    var v = Number.parseFloat(valorNumber); //garantindo que param vai ser number
    if (isNaN(v)) {
        //mesmo assim se não form um número para aqui.
        return 0;
    }
    //mesma coisa que valorFormatado, sem fixar casas.
    var valor_formatado = v.toString().replace('.', ',');
    return valor_formatado; //string formatada
}

function moneyBrToFloat(valor) {
    if (valor) {
        return parseFloat(valor.toString().replace('.', '').replace(',', '.'));

    }
}

function ler_valor(campo) {
    return moneyBrToFloat($(campo).val());

}

function lerInputNumber(campo) {
    var val = $(campo).val();
    if (!val && val !== '0') {
        //alert("nr invalido!");
        $(campo).val('0');
        return false;
    }
    return parseFloat(val);
}

function showGlobalMessage(conteudo, tipo = 'info', larger = true) {
    var row = larger ? 'row' : '';
    var string = ' <div class="' + row + ' tile tile-mensagens">' +
        '<div class="alert alert-' + tipo + ' alert-dismissable " style="width:100%">' +
        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
        conteudo +
        '</div>' +
        '</div>';
    $('.globalMessages').html(string);

}

function showMessage(alvo, conteudo, tipo = 'info') {

    var string = ' <div>' +
        '<div class="alert alert-' + tipo + ' alert-dismissable " style="width:100%">' +
        '<button type="button" class="close ml-1" data-dismiss="alert" aria-hidden="true">&times;</button>' +
        conteudo +
        '</div>' +
        '</div>';
    $(alvo).html(string);

}

function createPopOverLink(text, size = 30) {
    if (text.length <= size) {
        return text;
    }
    var shortText = text.slice(0, size); // pegar os primeiros 10 caracteres
    shortText = shortText + "..." //Indicativo que Tem mais texto
    var fullText = text; // armazenar o texto completo
    var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    var dataTrigger = isMobile?"click":'focus';
    var link = $("<a>")
        .addClass("pop-over-link")
        .prop("href", "#")
        .attr("data-toggle", "popover")
        .attr("data-trigger", dataTrigger)
        .attr("data-placement", "auto")
        .attr("data-content", fullText)
        .text(shortText);        

    // Obter a string HTML do link
    var linkString = $("<div>").append(link).html();

    return linkString;
}

/**
 * /função para ser usada junto com função createPopOverLink()
 */
function startPopOverLink(){
    $("[data-toggle='popover'].pop-over-link").popover();
    $("[data-toggle='popover'].pop-over-link").on('click', function(e) {
        e.preventDefault();
    });
    // Adicione o event listener para dispositivos móveis, limitado a popovers específicos
    var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    if (isMobile) {
        $(document).on('click touchend', function (e) {
            if (!$(e.target).closest('.popover').length && !$(e.target).closest('.pop-over-link').length) {
                $('.pop-over-link').popover('hide');
            }
        });
    }
}

function isMobile(){
    //consideração imprecisa considerando apenas largura da janela navegador;
    return window.innerWidth < 768;
}


function copyToClipBoard(id, elementEvent) {
    // Get the text field
    var copyText = document.getElementById(id);
    // Select the text field
    copyText.select();
    copyText.setSelectionRange(0, 99999); // For mobile devices
    // Copy the text inside the text field
    navigator.clipboard.writeText(copyText.value);

    var mensagemOriginal = elementEvent.innerHTML;
    var mensagemTemporaria = 'Copiado com Sucesso!.';
    elementEvent.disabled = true;
    elementEvent.innerHTML = mensagemTemporaria;
    var tempoLimite = 3000;
    var limparMensagem = function () {
        elementEvent.innerHTML = mensagemOriginal;
        elementEvent.disabled = false;
    };

    setTimeout(limparMensagem, tempoLimite);
}

$('[data-toggle="sidebar"]').click(function(event) {
    console.log('[data-toggle="sidebar"]',$('.app').hasClass('sidenav-toggled') )
    // event.preventDefault();
    // $('.app').toggleClass('sidenav-toggled');
    var sidenav_toggled=$('.app').hasClass('sidenav-toggled');
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: asset+"sidebar",
        method: 'PATCH',
        data: {
            _token:token,
            sidenav_toggled:sidenav_toggled,
            isMobile:isMobile()
            
        },
        success: function(resp) {
            console.log(resp)
           

        },
        error:function(resp){
            console.log(resp)
        }
    });
});


function ckeckAllOnTable(){
    $("#check-all").click(function() {
        // Verifique se está marcado ou desmarcado
        if ($(this).is(":checked")) {
          // Atualize todas as checkboxes de linha para marcado
          $(".check-item").prop("checked", true);
        } else {
          // Atualize todas as checkboxes de linha para desmarcado
          $(".check-item").prop("checked", false);
        }
      });
    
      // Quando uma checkbox de linha for clicada
      $(".check-item").click(function() {
        // Verifique se todas as checkboxes de linha estão marcadas ou não
        if ($(".check-item:checked").length == $(".check-item").length) {
          // Atualize o checkbox do cabeçalho para marcado
          $("#check-all").prop("checked", true);
        } else {
          // Atualize o checkbox do cabeçalho para desmarcado
          $("#check-all").prop("checked", false);
        }
      });
}



$('.creditos_atuais').popover({
    placement: 'top',
    html: true,
    title: 'Última Recarga (+)',
    content: function(){
        var div_id =  "tmp-id-" + $.now();
        return contentLastCreditByStudent(div_id);
    }
});   

function contentLastCreditByStudent(div_id){
    $.ajax({
        url: asset + "lastCreditByAuthStudent",
        success: function(response){
            var msg= response.qtd+" Créditos em "+response.data_acao_br; 
            $('#'+div_id).html(msg);
        }
    });
    return '<div id="'+ div_id +'">Loading...</div>';
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}


  




