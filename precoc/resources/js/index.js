$(function(){
    $("input:button:first").on("click", function(){
        validarLogin();
    });
});

function validarLogin(){

    let inputTeste = testarInputs();

    if(inputTeste.isValid){
        $.ajax({
            type:"POST",
            url : "../../app/models/Login.php",
            data : `user=${$("[name=usuario]").val().trim()}&senha=${$("[name=senha]").val().trim()}`,  
            success: function(data){
                data == 'false' ? respondeErro() : window.location.href = "../views/menu.html";
            },
        });
    }
    else
        respondeErro(inputTeste);
}


function testarInputs(){

    let inputdata = {errorNum: 0, isValid: true};

    let user = $("[name=usuario]").val().replace(/\s/g,'');
    let usertxt = $("[name=usuario]").val();

    let senha = $("[name=senha]").val().replace(/\s/g,'');
    let senhatxt = $("[name=senha]").val();

    if((user == '' && senha == '') || (user != usertxt && senha != senhatxt)){
        inputdata.errorNum = 3;
        inputdata.isValid = false;
    }    
    else{
        if(user == '' || user != usertxt){
            inputdata.errorNum = 1;
            inputdata.isValid = false;
        }
        else if(senha == '' || senha != senhatxt){
            inputdata.errorNum = 2;
            inputdata.isValid = false;
        }
        else{
            inputdata.errorNum = 0;
            inputdata.isValid = true;
        }
    }
    
    return inputdata;

}

function respondeErro(inputdata = ''){

    if(respondeErro.caller.name == "BuscarDado")
    {
        switch(inputdata.errorNum)
        {
            case 1:
                $(".toast-body").html("Usuário Inválido");
                break;

            case 2:
                $("#ttsenha").html("Senha Inválida");
                break;

            case 3:
                $(".toast-body").html("Usuário Inválido e Senha Inválida");
                break;
        }
    }
    else
        $(".toast-body").html("Login inválido, tente novamente");
    
    $('#aviso').fadeIn(1500);
        setTimeout(function(){$('#aviso').fadeOut(1500);}, 1500);
}

function getUsertype(user){

    let regexEmail = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    if(regexEmail.test(user))
        return 1;
    else
        return 0;
}
