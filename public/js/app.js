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

function confirm(callback, title="Deseja Realmente  ?", content="") {
    swal({
        title: title,
        type: "warning",
        showCancelButton: true,
        text:content,
    }, function (ok) {
        if (ok) {
            callback();
        }
    });
}


var SPMaskBehavior = function (val) {
    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
  },
  spOptions = {
    onKeyPress: function(val, e, field, options) {
        field.mask(SPMaskBehavior.apply({}, arguments), options);
      }
  };

function adminFormSubmit(event) {
    event.preventDefault();
    var btn= event.currentTarget;
    var fechar= !!btn.dataset.close;
    var form = document.getElementById("adminForm");
    if(fechar){
       var exist= $("input[name=fechar]").length;
       if(!exist){
        $(form).append('<input type="hidden" name="fechar" value="'+btn.dataset.close+'" >');
       }
    }
    else{
        $("input[name=fechar]").remove();   
    }
    form.submit();
    $(btn).attr('disabled','disabled');
    $(btn).removeAttr('onclick');
    $(btn).unbind();
}

$(document).ready(function () {
    $('.cep').mask('00000-000');
    $('.cpf').mask('000.000.000-00', { reverse: true });
    $('.phone').mask(SPMaskBehavior, spOptions);
    $('.money').mask('000.000.000.000.000,00', {reverse: true});

    //função busca CEP em Webservice
    $("#cep").keyup(function () {
        if (this.value.length == 9) {
            $('#cep').css({'background': "url('../img/preload.GIF') no-repeat right", 'background-size': '30px 30px'});
            $('body').append("<div class='fundo_preload  modal-backdrop fade show'></div>");
            $.getJSON("//viacep.com.br/ws/" + this.value + "/json/?callback=?", function (dados) {

                if (!("erro" in dados)) {
                    var string_endereco= dados.logradouro+' '+dados.complemento+' ';
                    string_endereco+=dados.bairro+' '+dados.localidade+' - '+dados.uf+' '+dados.unidade;
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
    $('#formProduto_produto_id').select2({
         dropdownParent: $('#ModalFormProduto'),
         //theme: "bootstrap",
          width: 'style' 
        //width:auto
     });

});

function limparFormPesquisa(){
    $('#form_pesquisa select, #form_pesquisa input[type=date]').val('');
    $('#form_pesquisa select').trigger('change'); //avisar select2
}

function valorFormatado(valorNumber){
    var v= Number.parseFloat(valorNumber); //garantindo que param vai ser number
    if(isNaN(v)){
        //mesmo assim se não form um número para aqui.
        return "";
    }
    var valor_formatado = v.toFixed(2).toString().replace('.', ',');
    return valor_formatado; //string formatada
}

function floatBr(valorNumber){
    var v= Number.parseFloat(valorNumber); //garantindo que param vai ser number
    if(isNaN(v)){
        //mesmo assim se não form um número para aqui.
        return 0;
    }
    //mesma coisa que valorFormatado, sem fixar casas.
    var valor_formatado = v.toString().replace('.', ',');
    return valor_formatado; //string formatada
}

function moneyBrToFloat(valor){
    if(valor){
        return parseFloat( valor.toString().replace('.', '').replace(',', '.') );
       
    }
}

function ler_valor(campo) {
    return moneyBrToFloat($(campo).val());
  
}

function lerInputNumber(campo){
    var val = $(campo).val();
    if(!val && val!=='0'){
        //alert("nr invalido!");
        $(campo).val('0');
        return false;	
    }
    return parseFloat(val);
}


