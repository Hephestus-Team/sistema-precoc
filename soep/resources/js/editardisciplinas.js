$(function(){

    sessionController();

    $("a").on('click', function(e){
        e.preventDefault();
        $("#continueModal").modal('show');
    });

    $("#continuar").on('click', function(e){
        $("#continueModal").modal('hide');
    });

    $('#deletar').on('click', function(){
        deletar(0);
    });

    $('#deletarTudo').on('click', function(e){
        window.firstEventTarget = this;
        $('#finishModal').modal('show');
    });

    $('#desmarcar').on('click', function(){
        $('option:checked').prop('selected', false);
    });

    $('#inserir').on('click', function(){
        inserir();
    });

    $('#disciplinasAdicionadas').on('change', function(e){
        $('#nome').val($('#disciplinasAdicionadas > option:checked').html());
    });
    
    $('#sim').on('click', function(e){
        let caller = window.firstEventTarget.id;
        
        if(caller === "deletarTudo")
            deletar(1);
    });

    $('#nao').on('click', function(e){
        $('#finishModal').modal('hide');
    });

    $('#baixarTabela').on('click', function(){
        window.open('../../app/models/Excel/Disciplina.php');
        window.close();
    });

    $('#voltar').on('click', function(){
        window.location.href = '../views/menu.php';
    });

    $('#enviar').on('click', function(){
        enviarTabela();
    });

    $('#headingOne').on('click', function(e){
        if(e.target.tagName !== 'BUTTON')
            $('#collapseButton').click();
    });

    $('#headingTwo').on('click', function(e){
        if(e.target.tagName !== 'BUTTON')
            $('#collapseButton2').click();
    });

});

function deletar(codigo){

    if($('#disciplinasAdicionadas').val() && codigo === 0)
    {
        $.ajax({
            type:"POST",
            url : "../../app/models/Editar/Disciplinas.php",
            data : `disciplinaId=${$('#disciplinasAdicionadas').val()}&codigo=0`,  
        });
    
        $('#disciplinasAdicionadas > option:checked').remove();

    }
    else if(codigo === 1)
    {
        $.ajax({
            type:"POST",
            url : "../../app/models/Excel/Disciplinas.php",
            data : `codigo=3`,  
        });

        $('#disciplinasAdicionadas').empty();

    }
}

function inserir(){

    if($('#disciplinasAdicionadas').val())
    {
        $.ajax({
            type:"POST",
            url : "../../app/models/Excel/Disciplinas.php",
            data : `disciplinaId=${$('#disciplinasAdicionadas').val()}&disciplinaNome=${$('#nome').val()}&codigo=1`,  
        });
    
        $('#disciplinasAdicionadas > option:checked').html(`<option value="${$('#disciplinasAdicionadas').val()}">${$('#nome').val()}</option>`);
    }
    else
    {
        $.ajax({
            type:"POST",
            url : "../../app/models/Editar/Disciplinas.php",
            data : `disciplinaNome=${$('#nome').val()}&codigo=2`,  
            success: function(id){
                $('#disciplinasAdicionadas').append(`<option value="${id}">${$('#nome').val()}</option>`);;
            },
        });
    }
}

function enviarTabela(){

    let formdata = new FormData();

    let files = $(':file')[0].files[0]; 
    formdata.append('file', files);

    $('#enviar').attr("disabled", "disabled");
    $('#voltar').attr("disabled", "disabled");

    $('#loading').html(`
    <div class="spinner-border mt-3" role="status">
        <span class="sr-only">Loading...</span>
    </div>
    `).addClass('my-4');

    $.ajax({
        url: "../../app/models/Upload/Disciplina.php", 
        type: "POST", 
        data: formdata, 
        contentType: false, 
        processData: false, 
        complete: function(){

            $('#enviar').removeAttr("disabled");
            $('#voltar').removeAttr("disabled");
            $('#loading').empty().removeClass('my-4');

        },
    });
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