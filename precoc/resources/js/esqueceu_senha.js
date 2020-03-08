$(function(){
    $('input:button:first').on('click', function(){
        validarMatricula();
    });

    $('#voltar').on('click', function(){window.location.href = '../views/'});

    $('#finishModal').on('hidden.bs.modal', function(){window.location.href = '../views/'});

    $('#finalizar').on('click', function(){window.location.href = '../views/'});
});

function validarMatricula(){

    $.ajax({
        type:"POST",
        url : "../../app/models/EsqueceuSenha.php",
        data : `matricula=${$(':text').val()}`,  
        success: function(data){ data == 1 ? $('#finishModal').modal('show') : responderErro(data);},
    });

}

function responderErro(mensagem){

    $('.toast-header').html(mensagem);
    $('#aviso').fadeIn(2000);
    setTimeout(function(){$('#aviso').fadeOut(3000);}, 4000);

}