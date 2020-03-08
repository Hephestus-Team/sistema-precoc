window.turma = [];

$(function(){

    sessionController();

    $("a").on('click', function(e){
        e.preventDefault();
        $("#continueModal").modal('show');
    });

    $("#continuar").on('click', function(e){
        $("#continueModal").modal('hide');
    });

    $('#pesquisar').on('click', function(){
        pesquisar();
    });

    $('#deletarTurma').on('click', function(){
        window.firstEventTarget = this;
        $('#finishModal').modal('show');
    });

    $('#deletarAluno').on('click', function(){
        deletar(0);
    });

    $('#deletarTudo').on('click', function(e){
        window.firstEventTarget = this;
        $('#finishModal').modal('show');
    });

    $('#desmarcar').on('click', function(){
        $('option:checked').prop('selected', false);
    });

    $('#baixarTurma').on('click', function(){
        if($('select > option').length > 0){
            window.open(`../../app/models/Excel/Aluno.php?id=${window.turma.id}&nome=${window.turma.nome}`);
            window.close();
        }
    });

    $('#inserir').on('click', function(){
        inserir();
    });

    $('#alunosDaTurma').on('change', function(e){
        $('#nome').val($('#alunosDaTurma > option:checked').html());
        $('#matricula').val($('#alunosDaTurma > option:checked').val());
    });

    $('#baixarTabela').on('click', function(){
        window.open('../../app/models/Excel/TodosAluno.php');
        window.close();
    });

    $('#sim').on('click', function(e){
        let caller = window.firstEventTarget.id;
        
        if(caller === "deletarTurma")
            deletar(1);
        else if(caller === "deletarTudo")
            deletar(2);
    });

    $('#nao').on('click', function(e){
        $('#finishModal').modal('hide');
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

function responderErro(id){

    $(`#${id}`).addClass("is-invalid");

    setTimeout(function(){
        $(".is-invalid").removeClass("is-invalid");
    }, 3000);

}

function pesquisar(){

    $.ajax({
        type:"POST",
        url : "../../app/controllers/EditarTurmasController.php",
        data : `turma=${$('#turmaNome').val()}`,  
        success: function(data){

            if(data !== "false")
            {
                let dados = data;
                dados = JSON.parse(data);
                
                setAlunoList(dados);
                window.turma = dados[1][0];
            }
            else
                responderErro('turmaNome');
        },
    });
}

function setAlunoList([alunos,]){

    $('#alunosDaTurma').html("");

    for(aluno of alunos)
        $('#alunosDaTurma').append(`<option value="${aluno.matricula}">${aluno.nome}</option>`);

}

function deletar(codigo){

    if(codigo === 0 && $('#alunosDaTurma').val())
    {
        let aluno = {
            matricula: $('#alunosDaTurma').val(),
            turma: window.turma.id
        };

        $.ajax({
            type:"POST",
            url : "../../app/models/Editar/Turmas.php",
            data : `aluno=${JSON.stringify(aluno)}&codigo=0`,  
            success: function(data){
            },
        });
        
        $('#alunosDaTurma > option:checked').remove();
    }
    else if(codigo === 1)
    {
        $.ajax({
            type:"POST",
            url : "../../app/models/Editar/Turmas.php",
            data : `turma=${window.turma.id}&codigo=3`,  
            success: function(data){
            },
        });

        $('#alunosDaTurma').html("");
    }
    else if(codigo === 2)
    {
        $.ajax({
            type:"POST",
            url : "../../app/models/Editar/Turmas.php",
            data : `codigo=4`,
        });

        $('#alunosDaTurma').html("");
    }
}

function inserir(){

    if(verifyInsertItens([$('#nome').val(), $('#matricula').val()]))
    {
        if($('#alunosDaTurma').val())
        {
            let aluno = {
                nome: $('#nome').val(),
                matricula: $('#matricula').val(),
                matricula_antiga: $('#alunosDaTurma').val(),
                turma: window.turma.id
            };
    
            $.ajax({
                type:"POST",
                url : "../../app/models/Editar/Turmas.php",
                data : `aluno=${JSON.stringify(aluno)}&codigo=1`,  
            });
    
            $('#alunosDaTurma > option:checked').attr("value", aluno.matricula);
            $('#alunosDaTurma > option:checked').val(aluno.matricula);
        }
        else
        {
            let aluno = {
                nome: $('#nome').val(),
                matricula: $('#matricula').val(),
                turma: window.turma.id
            };
            
            $.ajax({
                type:"POST",
                url : "../../app/models/Editar/Turmas.php",
                data : `aluno=${JSON.stringify(aluno)}&codigo=2`,  
            });
    
            $('#alunosDaTurma').append(`<option value="${aluno.matricula}">${aluno.nome}</option>`);
        }
    }
}

function verifyInsertItens(aluno){

    for(item of aluno)
        if(item === '')
            return false;

    return true;

}

function enviarTabela(){

    let formdata = new FormData();

    let files = $(':file')[0].files[0]; 
    formdata.append('file', files);

    $('#enviar').attr("disabled", "disabled");
    $('#voltar').attr("disabled", "disabled");

    $('#loading').html(`
    <div class="spinner-border" role="status">
    <span class="sr-only">Loading...</span>
    </div> `).addClass('my-4');

    $.ajax({
        url: "../../app/models/Upload/Turma.php", 
        type: "POST", 
        data: formdata, 
        contentType: false, 
        processData: false, 
        complete: function(){
            $('#enviar').removeAttr("disabled");
            $('#voltar').removeAttr("disabled");

            $('#loading').html('').removeClass('my-4');
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