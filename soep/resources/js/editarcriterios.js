$(function(){

    sessionController();

    $('#deletar').on('click', function(){
        deletar();
    });

    $('#desmarcar').on('click', function(){
        $('option:checked').prop('selected', false);
    });

    $('#enviar').on('click', function(){
        enviar();
    });

    $('#voltar').on('click', function(){
        window.location.href = '../views/menu.php';
    });

    $('#headingOne').on('click', function(e){
        if(e.target.tagName !== 'BUTTON')
            $('#collapseButton').click();
    });
});


function deletar(){

    $.ajax({
        type:"POST",
        url : "../../app/models/Editar/Criterios.php",
        data : `criterioId=${$('#criteriosAdicionados').val()}&codigo=0`,  
        success: function(data){},
    });

    $('#criteriosAdicionados > option:checked').remove();
}

function enviar(){

    if($('#criteriosAdicionados').val()){
        
        $.ajax({
            type:"POST",
            url : "../../app/models/Editar/Criterios.php",
            data : `criterioId=${$('#criteriosAdicionados').val()}&criterioNome=${$('#criterioNome').val()}&codigo=1`,  
        });
    
        $('#criteriosAdicionados > option:checked').html($('#criterioNome').val());
        $('#criterioNome').val("");
    }
    else{
        
        $.ajax({
            type:"POST",
            url : "../../app/models/Editar/Criterios.php",
            data : `criterioNome=${$('#criterioNome').val()}&codigo=2`,  
            success: function(id){
                $('#criteriosAdicionados').append(`<option value="${id}">${$('#criterioNome').val()}</option>`);
            },
        });
    }
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