$(function(){
    $('form').on('submit', function(e){
        if(!testarInputs())
            e.preventDefault();
    });

    $("input:button:first").on("click", function(){
        buscarDados();
    });

});

function buscarDados(){

    if(testarInputs())
    {
        $.ajax({
            type:"POST",
            url : "../../app/controllers/LoginController.php",
            timeout : "2000",
            data : `usuario=${$("[name=usuario]").val()}&senha=${$('[name=senha]').val()}`,  
            success: function(data){verificarSubmit(data);},
        });
    }
    else
    {
        $('#aviso').fadeIn(1500);
        setTimeout(function(){$('#aviso').fadeOut(1500);}, 1500);
    }
}

function verificarSubmit(data){

    if(data == "true")
        $("form").submit();
    else
    {
        $('#aviso').fadeIn(2000);
        setTimeout(function(){$('#aviso').fadeOut(3000);}, 4000);
    }
        
}

function testarInputs(){

    let usuario = $("[name=usuario]").val().replace(/\s/g,'');
    let usuario_texto = $("[name=usuario]").val();

    let senha = $("[name=senha]").val().replace(/\s/g,'');
    let senha_texto = $("[name=senha]").val(); 

    if(usuario != '' && senha != '')
    {
        if(usuario == usuario_texto && senha == senha_texto)
            return true;
    }     
    else
        return false;
}