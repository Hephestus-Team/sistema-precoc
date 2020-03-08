$(function(){

    sessionController();

    $('#deletar').on("click", function(){
        deletar();
    });

    $('#enviar').on("click", function(){
        enviar();
    });

    $('#voltar').on('click', function(){
        window.location.href = '../views/menu.html';
    });
});


function deletar(){

    let [turma, disciplina] = $('#turmasAdicionadas > option:checked').val().split("-");

    let dados = {
        id_turma : turma, 
        id_disciplina: disciplina, 
        codigo: 1, 
        matricula: $('#matricula').val()
    };

    $.ajax({
        type:"POST",
        url : "../../app/models/EditarTurmas.php",
        data : `dados=${JSON.stringify(dados)}`,  
        success: function(data){},
    });

    $('#turmasAdicionadas > option:checked').remove();

}

function enviar(){

    if(testarInput())
    {
        let dados = {
            id_turma : $('#turmas').val(), 
            id_disciplina: $('#disciplinas').val(), 
            codigo: 0
        };
        
        $.ajax({
            type:"POST",
            url : "../../app/models/EditarTurmas.php",
            data : `dados=${JSON.stringify(dados)}`,  
            success: function(data){
                if(data != '0')
                    alterarLista(dados.codigo);
                else
                    responderErro('Essa turma e disciplina já estão relacionadas com outro professor');
            },
        });
    }
}

function alterarLista(codigo){

    let innerhtml = $('#turmas > option:checked').html() + " - " + $('#disciplinas > option:checked').html();
    let val = $('#turmas').val() + " - " + $('#disciplinas').val();

    if(codigo == 0)
        $('#turmasAdicionadas').append(`<option value="${val}">
            ${innerhtml} </option>`);
    else
    {
        $('#turmasAdicionadas > option:checked').val(val);
        $('#turmasAdicionadas > option:checked').html(innerhtml);
    }
}

function testarInput(){

    let val = $('#turmas').val() + " - " + $('#disciplinas').val();
    
    if($(`#turmasAdicionadas > option[value="${val}"]`).length != 0)
    {
        responderErro('Essa turma e disciplina já estão relacionadas com o seu cadastro');
        return false;
    }
    else
        return true;
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

function responderErro(mensagem){

    $('.toast-body').html(mensagem);
    $('#aviso').fadeIn(2000);
    setTimeout(function(){$('#aviso').fadeOut(3000);}, 4000);
}
