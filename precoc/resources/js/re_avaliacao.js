$(function(){
    $('#enviar').on("click", function(){enviarAvaliacoes();});

    $('#finishModal').on('hidden.bs.modal', function(){window.location.href = '../views/menu.html'});

    $('#finalizar').on('click', function(){window.location.href = '../views/menu.html'});

    $('#editar').on('click', function(){window.location.href = '../views/editar_avaliacao.php'});

    $('#voltar').on('click', function(){
        window.history.back();
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

    sessionController();

});

function getCriterios(){

    let classes = [];
    let checkboxes = [];

    $("input[type=checkbox]").each(function(){
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

function enviarAvaliacoes(){

    $('#enviar').attr("disabled", "disabled");
    $('#voltar').attr("disabled", "disabled");

    $('#loading').html(`
    <div class="spinner-border" role="status">
    <span class="sr-only">Loading...</span>
    </div> `).addClass('my-4');

    $.ajax({
        type:"POST",
        url : "../../app/models/Avaliacao/ReAvaliacao.php",
        data : `dados=${$("[name=dados]").val()}&criterios=${getCriterios()}&observacao=${$("[name='observacao']").val()}`,
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