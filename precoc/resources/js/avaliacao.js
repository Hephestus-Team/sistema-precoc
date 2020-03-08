$(function(){

    sessionController();

    $('#enviar').on("click", function(e){
        if(verificarInputs()){
            enviarAvaliacoes();
        }
        else
            e.preventDefault();
    });

    $('#finishModal').on('hidden.bs.modal', function(){
        window.location.href = '../views/menu.html';
    });

    $('#finalizar').on('click', function(){
        window.location.href = '../views/menu.html';
    });

    $('#escolher').on('click', function(){
        window.location.href = '../views/turmas.php';
    });

    $('#voltar').on('click', function(){
        window.location.href = '../views/turmas.php'
    });

    $('td > :checkbox').on('click', function(){

        if($(this).prop('checked'))
            $(this).prop('checked', false);
        else
            $(this).prop('checked', true);
    });

    $('td').on('click', function(){

        const checkedboxvalue = $($(this).children()[0]).prop('checked');

        if(checkedboxvalue)
            $($(this).children()[0]).prop('checked', false);
        else
            $($(this).children()[0]).prop('checked', true);

    });
});

//Seta os criterios em um input como um vetor
function getCriterios(){

    let classes = [];
    let checkboxes = [];

    $("input[type=checkbox][name!='disciplina_id[]']").each(function(){
        if(!classes.includes($(this).attr('class')))
            classes.push($(this).attr('class'));
    });

    classes.forEach(function(item){

        let checkvalues = [];

        $(`.${item}`).each(function(){
            if($(this).is(":checked"))
                checkvalues.push([true, $(this).val()]);
            else
                checkvalues.push([false, $(this).val()]);
        });

        checkboxes.push(checkvalues);
    });

    return JSON.stringify(checkboxes);
}

function getDiscipinas(){

    let disciplinas = [];

    $("[name='disciplina_id[]']:checked").each(function(){
        disciplinas.push($(this).val());
    });

    return JSON.stringify(disciplinas);
}

//Verifica se foram preenchidos os campos necessários
function verificarInputs(){

    if($('.disciplinas:checked').length != 0)
    {
        $('#alert').css({"visibility" : "hidden"});
        return true;
    }  
    else
    {
        $('#alert').css({"visibility" : "visible"});
        return false;
    }
}

function enviarAvaliacoes(){

    $('#enviar').attr("disabled", "disabled");
    $('#voltar').attr("disabled", "disabled");

    $('#loading').html(`
    <div class="spinner-border" role="status">
    <span class="sr-only">Loading...</span>
    </div> `).addClass('my-4');

    $.ajax({
        type:"POST",
        url : "../../app/models/Avaliacao/Avaliacao.php",
        data : `dados=${$("[name=dados]").val()}&criterios=${getCriterios()}&disciplina_id=${getDiscipinas()}&observacao=${$("[name='observacao']").val()}`,
        complete: function(){
            $('#enviar').removeAttr("disabled");
            $('#voltar').removeAttr("disabled");

            $('#loading').html('').removeClass('my-4');

            $('#finishModal').modal('show');
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