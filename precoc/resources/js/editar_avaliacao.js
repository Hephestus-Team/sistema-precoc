$(function(){

    sessionController();

    $("#alterar").on("click", function(e){
        if($('#turmasAvaliadas > option:checked').length != 0)
            enviar();
    });

    $('#voltar').on('click', function(){
        window.location.href = '../views/menu.html';
    });

});

function enviar(){

    let [turma, disciplina] = $('#turmasAvaliadas > option:checked').val().split("-");

    let dados = {
        id_turma : turma, 
        id_disciplina: disciplina,  
    };
    
    $('[name=dados]').val(JSON.stringify(dados));

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