$(function(){

    sessionController();

    $("a").on('click', function(e){
        e.preventDefault();
        $("#continueModal").modal('show');
    });

    $("#continuar").on('click', function(e){
        $("#continueModal").modal('hide');
    });

    $('#baixarTurma').on('click', function(){

        if($('#ano').val() == '')
            responderErro('ano');

        else if($('#turma').val() == '')
            responderErro('turma');
            
        else{
            window.open(`../../app/models/Excel/Avaliacao.php?turma=${$('#turma').val()}&trimestre=${$('#trimestre').val()}&ano=${$('#ano').val()}`);
            window.close();
        }
    });

    $('#baixarTodas').on('click', function(){
        if($('#ano').val() == '')
            responderErro('ano');
        else{
            window.open(`../../app/models/Excel/TodosAvaliacao.php?trimestre=${$('#trimestre').val()}&ano=${$('#ano').val()}`);
            window.close();
        }
    });

    $('#voltar').on('click', function(){
        window.location.href = '../views/menu.php';
    });
});

function responderErro(id){

    $(`#${id}`).addClass("is-invalid");

    setTimeout(function(){
        $(".is-invalid").removeClass("is-invalid");
    }, 3000);

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