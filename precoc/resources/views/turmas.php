<?php include '../../app/controllers/TurmasController.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Selecione a turma | PRECOC</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" href="../../../content/logo-titulo.png" type="image/x-icon">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../content/style.css">
</head>
<body>
    <div class="container mt-5 border border-dark rounded text-center col col-12 col-xl-4 col-lg-4 col-md-8 col-sm-12" id="meio1">

    <h2 class="mt-4">Selecione a turma</h2>  <br>

    <form action="avaliacao.php" method="POST">
    <div id="controles">
        <div class="d-flex justify-content-center mb-4">
            <select name="turma_id" class="custom-select btn btn-light border border-dark" style="width: 20%; height: 50px; min-width: 200px">
                <?php foreach ($turmas as $turma): ?>
                <option value="<?php echo $turma['id'];?>"><?php echo $turma['nome'];?></option>
                <?php endforeach;?>
            </select>
        </div>

        <div class="d-flex justify-content-center my-4">
            <input type="submit" value="Continuar" class="btn btn-primary" style="width: 20%; height: 50px; min-width: 200px">
        </div>
    </div>
        
    <div class="d-flex justify-content-center my-4">
        <input class="btn border border-primary" type="button" value="Voltar" id="voltar">
    </div>

    </form>
    </div>

    <footer style="flex-shrink: none;" class="py-4 text-white-50">
        <div class="container text-center">
        <small>© 2019 Copyright: <a href="https://github.com/danielShz" target="_blank"> Daniel Arruda </a> & <a href="https://github.com/muniz034" target="_blank"> Pedro Muniz </a> </small>
        </div>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="../js/turmas.js"></script>
</body>
</html>