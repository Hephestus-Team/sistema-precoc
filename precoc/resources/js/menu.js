$(function(){

    sessionController();

    $("#avaliar").on("click", function(){
        window.location.href = "../views/turmas.php";
    });

    $("#inserirTurmas").on("click", function(){
        window.location.href = "../views/editar_turmas.php";
    });

    $("#editarAvaliacao").on("click", function(){
        window.location.href ="../views/editar_avaliacao.php";
    });

    $("#logout").on("click", function(){
        logout();
    });
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