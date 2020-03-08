$(function(){

    sessionController();
    naoTemAvaliacao();

    $('#voltar').on('click', function(){window.location.href = "../views/menu.html";});

});

function sessionController(){
    $.ajax({
        type:"POST",
        url : "../../app/controllers/SessionController.php",
        data : ``,  
        success: function(data){
            if(data != 'true')
                $('#meio1').html(`
                <h1 class="text-center mt-5 pb-4">PRE-COC</h1> 
                <h2 class="text-center pb-5">Matrícula SIAPE</h2>
                <div class="alert alert-danger text-center" role="alert">
                <h3> Você não está logado, vá à pagina principal </h3> </div></div>`);
        },
    });
}

function naoTemAvaliacao(){

    if($('select > option').length == 0){
        $('#controles').html(`
            <div class="alert alert-danger text-center" role="alert">
            <h3> Você já avaliou todas as turmas</h3> </div></div>`).removeClass('form-row');
    }
}