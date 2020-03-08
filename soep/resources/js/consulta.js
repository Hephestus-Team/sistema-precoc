window.exibirTodos = false;

sessionController();

function loadEvents(){

    $('#pesquisar').unbind().on('click', function(e){
        $('pagination').html("");
        window.exibirTodos = false;
        pesquisar(false);
    });

    $('#exibirTodos').unbind().on('click', function(){
        $('pagination').html("");
        window.exibirTodos = true;
        pesquisar(true);
    });

    $('#paginaAtual').unbind().on('change', function(e){
        if(e.target != '')
            pesquisar(window.exibirTodos, $('#paginaAtual').val() - 1);
    });

    $('#proximo').unbind().on('click', function(e){
        let valorAtual = parseInt($('#paginaAtual').val());

        if(valorAtual + 1 <= $(`#paginaAtual > option:last`).val() && e.target != ''){

            $(`#paginaAtual > option[value=${valorAtual + 1}]`).prop({selected: true});
            pesquisar(window.exibirTodos, $('#paginaAtual').val() - 1);
        } 
    });

    $('#anterior').unbind().on('click', function(e){
        let valorAtual = parseInt($('#paginaAtual').val());

        if(valorAtual - 1 >= $(`#paginaAtual > option:first`).val() && e.target != ''){

            $(`#paginaAtual > option[value=${valorAtual - 1}]`).prop({selected: true});
            pesquisar(window.exibirTodos, $('#paginaAtual').val() - 1);
        } 
    });

    $('#voltar').unbind().on('click', function(){
        window.location.href = '../views/menu.php';
    });
}

$(function(){
    loadEvents();
});

function getFiltros(){

    const turma = $('#turma').val();
    const professor = $('#professor').val().trim();
    const disciplina = $('#disciplina').val();

    let filtro = {};

    let tempo = {
        ano: $('#ano').val(),
        trimestre: $('#trimestre').val() 
    };

    if(turma !== 'todas')
        filtro = { ...filtro, turma};

    if(professor !== '')
        filtro = { ...filtro, professor};

    if(disciplina !== 'todas') 
        filtro = { ...filtro, disciplina};
    
    return [JSON.stringify(filtro), JSON.stringify(tempo)];

}

function testarInputs(){

    if($('#ano').val() === '')
    {
        responderErro('ano');
        return false;
    }
    else
        return true;
        
}

function responderErro(id){

    $(`#${id}`).addClass("is-invalid");

    setTimeout(function(){
        $(".is-invalid").removeClass("is-invalid");
    }, 3000);

}

function pesquisar(exibirTodos = false, index = 0){

    let [filtro, tempo] = getFiltros();

    let url, data;

    if(exibirTodos)
    {
        url = "../../app/controllers/Consulta/TodosController.php";
        data = `tempo=${tempo}&index=${index}`; 
    }
    else
    {
        url = "../../app/controllers/Consulta/TurmaController.php";
        data = `filtros=${filtro}&tempo=${tempo}&index=${index}`; 
    }

    if(testarInputs())
    {
        $('#loading').html(`
            <div class="spinner-border mt-3" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        `);

        $('#avaliacaoInfo').empty();

        $.ajax({
            type:"POST",
            url,
            data,  
            success: function(data){

                let dados = data;

                if(data != "false")
                {
                    try
                    {
                        dados = JSON.parse(data);
                    }
                    catch
                    {
                        console.log(data);
                    }
    
                    setAvaliacaoInfo(dados);
                    setPagination(dados[0].qtd_avaliacoes);
                    
                    $(`#paginaAtual > option[value=${index + 1}]`).prop({selected: true});

                    loadEvents();

                    $('#loading').empty();
                }
                else
                {
                    $('#loading').empty();
                    $('#loading').append("<div class='alert alert-danger text-center mt-4' role='alert' style='margin-top: 16px;'> <h4> Nenhuma avaliação encontrada </h4> </div>");
                }
            },
        });
    }
}

function setPagination(numPaginas){

    $('#pagination').html(
        `<ul class="pagination">
            <li class="page-item" id="anterior">
                <p class="page-link">&laquo;</p>
            </li>
            <li class="page-item active">
                <select id="paginaAtual" class="form-control">
                </select>
            </li>
            <li class="page-item" id="proximo">
                <p class="page-link">&raquo;</p>
            </li>
        </ul>`);

    if(numPaginas != $('#paginaAtual > option').length)
    {
        let options = '';

        $('#pagination').css('visibility', 'visible');

        for(let i = 1; i <= numPaginas; i++)
            options += `<option value="${i}"> Página ${i} </option>`;

        $('#paginaAtual').html(options);

    }
}

function setAvaliacaoInfo(dados){

    let avaliacaoInfo = '';

    avaliacaoInfo  += getDescricao(dados);
    
    avaliacaoInfo += `<div class="table-responsive" style="overflow-y: scroll; max-height: 600px">
        <table class="table table-striped table-hover text-center">
            <thead>
            <tr>
                <th scope="col" class="align-middle" style="position: sticky; top: 0; background-color: #FAF9F5;">#</th>`;

    avaliacaoInfo += getCriterios(dados);
     
    avaliacaoInfo +=`
        </tr>
        </thead>
        <tbody>
    `;

    avaliacaoInfo += getTableRows(dados);

    avaliacaoInfo += `
                </tbody>
            </table>
        </div>`;

    $('#avaliacaoInfo').html(avaliacaoInfo);
}

function getDescricao([{turma, professor, disciplina}]){

    return `<div class="row my-5">
        <div class="col-4">
            <h3> Turma </h3>
            <h5>${turma}</h5>
        </div>
        <div class="col-4">
            <h3> Disciplina </h3>
            <h5>${disciplina}</h5>
        </div>
        <div class="col-4">
            <h3> Professor </h3>
            <h5>${professor}</h5>
        </div>
    </div>`;

}

function getCriterios([{criterios}]){

    let criteriosInfo = '';

    for(criterio of criterios){
        criteriosInfo += `
            <th scope="col" class="align-middle" style="position: sticky; top: 0; background-color: #FAF9F5;">
                ${criterio}
            </th>`;
    }

    return criteriosInfo;
}

function getTableRows([, alunoDados]){

    let rows = '';

    for(let i = 0; i < alunoDados.length; i++)
    {
        rows +=`
            <tr>
            <th scope='row' class='nome'> [${i + 1}] ${alunoDados[i].nome} </th>`;

            for(let j = 0; j < alunoDados[i].valores.length; j++)
            {
                if(parseInt(alunoDados[i].valores[j]))
                    rows += `<td class='align-self-center align-middle'> <input type='checkbox' style='width:20px;height:20px;' checked disabled> </td>`;
                else
                    rows += `<td class='align-self-center align-middle'> <input type='checkbox' style='width:20px;height:20px;' disabled> </td>`;
            }

        rows += `</tr>`;
    }

    return rows;

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