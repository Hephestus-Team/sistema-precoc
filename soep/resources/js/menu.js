$(function(){

    sessionController();

    $('#ativar').on('click', function(){window.location.href = '../views/status.html'});
    $('#desativar').on('click', function(){deletar()});

    $('#consultar').on('click', function(){window.location.href = '../views/consulta.php'});
    $('#editarCriterios').on('click', function(){window.location.href = '../views/editarcriterios.php'});

    $('#professores').on('click', function(){window.location.href = '../views/editarprofessor.html'});
    $('#disciplinas').on('click', function(){window.location.href = '../views/editardisciplinas.php'});
    $('#turmas').on('click', function(){window.location.href = '../views/editarturmas.html'});

    $('#baixarAvaliacoes').on('click', function(){window.location.href = '../views/baixarAvaliacoes.html'});

    $("#logout").on("click", function(){logout()});
});

function deletar(){

    $.ajax({
        type:"POST",
        url : "../../app/models/StatusOff.php",
        data : ``,
    });

    $('#acesso').removeClass("border-success");
    $('#acesso').addClass("border-danger");

}

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

function logout(){

    $.ajax({
        type:"POST",
        url : "../../app/controllers/LogoutController.php",
        data : ``,  
        success: function(data){
            if(data == '1')
                window.location.href = '../views/index.html';
        },
    });
}