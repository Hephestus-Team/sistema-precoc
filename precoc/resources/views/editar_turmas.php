<?php @include '../../app/controllers/Editar/TurmasController.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Turmas | PRECOC</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" href="../../../content/iconeDoProjeto.png" type="image/x-icon">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../content/style.css">
</head>
<body>
      
    <div class="container mt-5 mb-3 border border-dark rounded text-center col col-12 col-xl-5 col-lg-8 col-md-8 col-sm-12" id="meio1">

        <div class="row justify-content-center mx-5 my-4 p-2">   
            <div class="col-12">
                <h2>Turmas Adicionadas</h2>
                <select id="turmasAdicionadas" multiple class="custom-select mx-auto mt-4 mw-80" style="min-height: 180px;">
                        <?php foreach($disciplinas_prof as $value): ?>
                        <?php print "<option value=\"{$value[2]}-{$value[3]}\">" . iconv("ISO-8859-1", "UTF-8", "{$value[0]}") . " - " . iconv("ISO-8859-1", "UTF-8", "{$value[1]}") . "</option>" ?>
                        <?php endforeach ?>
                </select> 
            </div>
            
            <div class="col-12 input-group my-3">                
                <div class="input-group-prepend">
                    <span class="input-group-text text-center" style="padding: 0px 42px 0px 22px;">Turmas</span>
                </div>
                <select id="turmas" class="custom-select">
                    <?php foreach($turmas as $value): ?>
                    <?php print "<option value=\"{$value['id']}\">{$value['nome']}</option>" ?>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="col-12 input-group mb-3">                
                <div class="input-group-prepend">
                    <span class="input-group-text" style="padding: 0px 18px 0px 22px;">Disciplinas</span>
                </div>
                <select id="disciplinas" class="custom-select">
                    <?php foreach($disciplinas as $value): ?>
                    <?php print "<option value=\"{$value['id']}\">" . iconv("ISO-8859-1", "UTF-8", "{$value['nome']}") . "</option>" ?>
                    <?php endforeach ?>
                </select>
            </div>
        </div>

        <div class="d-flex justify-content-center mx-5">
            <div class="toast p-2" id="aviso" role="alert" style="background-color: #7F0600; color: white; min-width: 100px; display: none"> 
                <div class="toast-header">
                    <strong class="mr-auto">Aviso:</strong>
                </div>
                <div class="toast-body">
                    Essa turma e disciplina já estão relacionadas
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center pt-3">
            <input type="button" class="btn btn-primary" value="Enviar" id="enviar" style="min-width: 200px">
        </div>

        <div class="col-12 align-self-center text-center mt-4 mb-2"> 
            <input type="button" value="Deletar" id="deletar" class="btn border border-danger" style="min-width: 50px; height: 50px; width: 100px">
        </div>

        <div class="d-flex justify-content-center my-5">
            <input class="btn border border-primary" type="button" value="Voltar" id="voltar" style="width: 20%; height: 50px; min-width: 180px;">
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
    <script src="../js/editar_turmas.js"></script>   

</body>
</html>