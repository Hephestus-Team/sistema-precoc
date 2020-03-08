$(function(){

    sessionController();

    $(':button').on('click', function(e){
        if(!testarDatas()){
            $('#aviso').fadeIn(2000);
            setTimeout(function(){$('#aviso').fadeOut(3000);}, 4000);
        }
        else
            enviar();    
    });

    $('#voltar').on('click', function(){
        window.location.href = '../views/menu.php';
    });
});

function testarDatas(){

    let inicio = new Date($('input[type=date]:first').val() + "T00:00:00");
    let termino = new Date($('input[type=date]:last').val() + "T00:00:00");
    let ontem = new Date();
    ontem.setDate(ontem.getDate() - 1);

    if(inicio && termino)
    {
        inicio = new Date(inicio);
        termino = new Date(termino);

        if(inicio < termino && ontem < inicio)
            return true;
        else
            return false;
    }
    else
        return false;
}

function enviar(){

    $.ajax({
        type:"POST",
        url : "../../app/models/Status.php",
        data : `inicio=${$('[name=inicio]').val()}&termino=${$('[name=termino]').val()}&trimestre=${$('[name=trimestre]').val()}`,
        success: function(){window.location.href = '../views/menu.php'}  

    });
    
}

function sessionController(){
    $.ajax({
        type:"POST",
        url : "../../app/controllers/SessionController.php",
        data : ``,  
        success: function(data){
            if(data != 'true')
                $('body').html(`
                <div class="container mt-5 border border-dark rounded pb-5" style="background-color: #F3FFC6; max-width: 800px;">
                <h1 class="text-center mt-5 pb-4">PRE-COC</h1> 
                <h2 class="text-center pb-5">Matrícula SIAPE</h2>
                <div class="alert alert-danger text-center" role="alert">
                <h3> Você não está logado, vá à pagina principal </h3> </div></div>`);
        },
    });
}