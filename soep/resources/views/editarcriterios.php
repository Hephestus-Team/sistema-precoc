<?php require "../../app/controllers/Editar/CriteriosController.php"; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Criterios | SOEP</title>    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" href="../../../content/logo-titulo.png" type="image/x-icon">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../content/style.css">
</head>
<body>
      
    <div class="container mt-5 border border-dark rounded text-center col col-12 col-xl-4 col-lg-8 col-md-10 col-sm-12" id="meio2">

        <div class="row justify-content-center mx-3 my-4 p-5 border border-dark rounded divisoes">   
            <div class="col-12">
                <h3>Critérios Existentes</h3>
                <h5>Selecione um critério abaixo para sobrescrevê-lo</h5>
                <select id="criteriosAdicionados" size="3" class="form-control" style="height: 280px;">
                    <?php foreach($criterios as $criterio): ?>
                        <?php print "<option value=\"{$criterio['id']}\">" . iconv("ISO-8859-1", "UTF-8", "{$criterio['nome']}") . "</option>" ?>
                    <?php endforeach ?>
                </select> 
            </div>
            
            <div class="col-12 align-self-center text-center mx-auto mt-4"> 
                <input type="button" value="Deselecionar" id="desmarcar" class="mt-4 form-control border border-dark" style="min-width: 50px; height: 50px;">
            </div>
        </div>
        
        <div class="accordion mb-5 mx-3" id="accordionExample">
            <div class="card rounded border border-dark divisoes">
                <div class="card-header" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn btn-light" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" id="collapseButton">
                            Inserir/alterar
                        </button>
                    </h2>
                </div>
                        
                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body p-5">
                        <div class="d-flex justify-content-center mb-5 mt-1">
                            <label for="criterioNome"> <h5>Nome do critério</h5>
                                <input type="text" id="criterioNome" class="form-control border border-dark mt-3" style="min-width: 300px;">
                            </label>
                        </div>

                        <div class="d-flex justify-content-center mb-4">
                            <input type="button" class="btn btn-primary" value="Enviar" id="enviar">
                        </div>

                        <div class="col-12 align-self-center text-center mx-auto mt-2"> 
                            <input type="button" value="Deletar" id="deletar" class="btn border border-danger" style="min-width: 50px; height: 40px; width: 100px"> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center my-4">
            <input class="btn border border-primary" type="button" value="Voltar" id="voltar">
        </div>

    </div>

    

    <div class="d-flex justify-content-center m-5">
        <div class="toast p-2" id="aviso" role="alert" style="background-color: #7F0600; color: white; min-width: 100px; display: none"> 
            <div class="toast-header">
                <strong class="mr-auto">Aviso:</strong>
            </div>
            <div class="toast-body">
                Este critério já existe
            </div>
        </div>
    </div>
    
    <footer style="flex-shrink: none;" class="py-4 text-white-50">
        <div class="container text-center">
        <small>© 2019 Copyright: <a href="https://github.com/danielShz" target="_blank"> Daniel Arruda </a> & <a href="https://github.com/muniz034" target="_blank"> Pedro Muniz </a> </small>
        </div>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="../js/editarcriterios.js"></script>   

</body>
</html>