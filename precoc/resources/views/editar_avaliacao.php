<?php require '../../app/controllers/Editar/AvaliacaoController.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Editar avaliação | PRECOC</title>

    <link rel="icon" href="../../../content/logo-titulo.png" type="image/x-icon">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../content/style.css">

</head>
<body>
    
    <form action="re_avaliacao.php" method="POST">
        <div class="container mt-5 border border-dark rounded text-center col col-12 col-xl-4 col-lg-6 col-md-8 col-sm-12" id="meio1">

            <div class="row justify-content-center m-4 pb-3 mt-4">   
                <div class="col-12">
                    <h3>Turmas avaliadas</h3>
                    <select id="turmasAvaliadas" size="3" class="form-control mw-100" style="height: 200px;" required>
                        <?php foreach($turmas_avaliadas as $value): ?>
                        <?php print "<option value=\"{$value['id_turma']}-{$value['id_disciplina']}\">{$value['nome_turma']} - " . iconv("ISO-8859-1", "UTF-8","{$value['nome_disciplina']}") . "</option>" ?>
                        <?php endforeach ?>
                    </select> 
                </div>
                
                <div class="col-12 align-self-center text-center mx-auto mt-4"> 
                    <input type="submit" id="alterar" value="Alterar" class="form-control btn btn-info mx-auto" style="max-width: 200px; height: 50px;">
                </div>

                <input type="hidden" name="dados">

                <div class="d-flex justify-content-center my-4">
                    <input class="btn border border-primary" type="button" value="Voltar" id="voltar" style="width: 20%; height: 50px; min-width: 180px;">
                </div>

            </div>
    
        </div>
    </form>
    
    <footer style="flex-shrink: none;" class="py-4 text-white-50">
        <div class="container text-center">
        <small>© 2019 Copyright: <a href="https://github.com/danielShz" target="_blank"> Daniel Arruda </a> & <a href="https://github.com/muniz034" target="_blank"> Pedro Muniz </a> </small>
        </div>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="../js/editar_avaliacao.js"></script>   

</body>
</html>