window.firstEventTarget = 0;

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

    $('#professoresPesquisados').on('change', function(e){

        let [nome, email] = $('#professoresPesquisados > option:checked').html().split('-');

        $('#nome').val(nome);
        $('#email').val(email);
        $('#matricula').val($('#professoresPesquisados').val());

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
        window.open('../../app/models/Excel/Professor.php');
        window.close();
    });

    $('#enviar').on('click', function(){
        enviarTabela();
    });

    $('#voltar').on('click', function(){
        window.location.href = '../views/menu.php';
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

function getFiltros(){

    const matricula = $('.ativador:checked').closest('.input-group').children('#tMatricula').val();
    const nome = $('.ativador:checked').closest('.input-group').children('#tNome').val();
    const email = $('.ativador:checked').closest('.input-group').children('#tEmail').val();

    let filtro = {};

    if(typeof matricula !== 'undefined')
        filtro = {...filtro, matricula};

    if(typeof nome !== 'undefined')
        filtro = {...filtro, nome};

    if(typeof email !== 'undefined') 
        filtro = {...filtro, matricula};
    
    return JSON.stringify(filtro);

}

function testarInputs(){

    const matricula = [$('.ativador:checked').closest('.input-group').children('#tMatricula'), 'tMatricula'];
    const nome = [$('.ativador:checked').closest('.input-group').children('#tNome'), 'tNome'];
    const email = [$('.ativador:checked').closest('.input-group').children('#tEmail'), 'tEmail'];

    let inputArray = [matricula, nome, email];

    for(input of inputArray)
    {
        if(typeof input[0] != 'undefined' && input[0].val() == '')
        {
            responderErro(input);
            return false;
        } 
    }

    return true;

}

function responderErro([,id]){

    $(`#${id}`).addClass("is-invalid");

    setTimeout(function(){
        $(".is-invalid").removeClass("is-invalid");
    }, 3000);

}

function pesquisar(){

    let filtro = getFiltros();

    if(testarInputs() && $('.ativador:checked').length != 0){
        $.ajax({
            type:"POST",
            url : "../../app/controllers/Editar/ProfessorController.php",
            data : `filtros=${filtro}`,  
            success: function(data){

                let dados = data;

                dados = JSON.parse(data);

                setProfessorList(dados);

            },
        });
    }
}

function setProfessorList(professores){

    $('#professoresPesquisados').html("");

    for(professor of professores)
        $('#professoresPesquisados').append(`<option value="${professor.matricula}">${professor.nome} - ${professor.email}</option>`);

}

function deletar(codigo){

    let professor = {
        nome: $('#nome').val(),
        email: $('#email').val(),
        matricula: $('#matricula').val(),
    };

    if($('#professoresPesquisados').val() && codigo === 0)
    {
        $.ajax({
            type:"POST",
            url : "../../app/models/Editar/Professor.php",
            data : `professorinfo=${JSON.stringify(professor)}&codigo=0`,  
        });
    
        $('#professoresPesquisados > option:checked').remove();
    }
    else if(codigo === 1)
    {
        $.ajax({
            type:"POST",
            url : "../../app/models/Editar/Disciplinas.php",
            data : `codigo=3`,  
        });

        $('#disciplinasAdicionadas').html('');
    }
}

function inserir(){

    let professor = {
        nome: $('#nome').val(),
        email: $('#email').val(),
        matricula: $('#matricula').val(),
    };

    if(verificarItensInseridos(professor))
    {
        if($('#professoresPesquisados').val())
        {
            $.ajax({
                type:"POST",
                url : "../../app/models/Editar/Professor.php",
                data : `professorinfo=${JSON.stringify(professor)}&professorId=${$('#professoresPesquisados').val()}&codigo=1`,  
            });
        
            $('#professoresPesquisados > option:checked').html(`<option value="${professor.matricula}">${professor.nome}/${professor.email}</option>`);
        }
        else
        {
            $.ajax({
                type:"POST",
                url : "../../app/models/Editar/Professor.php",
                data : `professorinfo=${JSON.stringify(professor)}&codigo=2`,  
            });
    
            $('#professoresPesquisados > option:checked').append(`<option value="${professor.matricula}">${professor.nome}/${professor.email}</option>`);
        }
    }
}

function verificarItensInseridos(professor){

    for(key in professor)
        if(professor[key] === '')
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
        url: "../../app/models/Upload/Professor.php", 
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