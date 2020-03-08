<?php require "../../app/controllers/StatusController.php"; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Menu | SOEP</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" href="../../../content/logo-titulo.png" type="image/x-icon">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../content/style.css">

</head>
<body>
    <div class="container mt-5 p-5 border border-dark rounded" style="max-width: 500px" id="meio1">

        <h1 class="text-center mt-3">PRÉ-COC</h1> 
        <h2 class="text-center pb-4 mb-4">SOEP</h2>
        
        <div class="container" id="logo" style="padding: 50px 0px 35px 0px;">
        <div class="d-flex justify-content-center mb-4">
            <div class="dropdown border <?php echo $border;?> rounded" id="acesso">
                <button class="btn btn-light dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 20%; height: 50px; min-width: 200px;">
                <img src="../../../content/soep/menu-acesso.png" class="pr-1 pb-1">
                    Abrir o sistema
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <button class="dropdown-item" id="ativar" style="width: 20%; height: 50px; min-width: 200px; text-align: center;">
                    <img src="../../../content/soep/status-on.png" class="pr-1 pb-1">
                        Abrir
                    </button>

                    <button class="dropdown-item" id="desativar" style="width: 20%; height: 50px; min-width: 200px; text-align: center;">
                    <img src="../../../content/soep/status-off.png" class="pr-1 pb-1">
                        Fechar
                    </button>
                </div>
              </div>
        </div>
        
        <div class="d-flex justify-content-center mb-4">
            <button class="btn btn-light border border-dark" style="width: 20%; height: 50px; min-width: 200px;" id="consultar">
                <img src="../../../content/soep/menu-consulta.png" class="pr-1 pb-1">
                Consultar 
            </button>
        </div>

        <div class="d-flex justify-content-center mb-5">
            <div class="dropdown border border-dark rounded" id="acesso">
                <button class="btn btn-light dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 50px; min-width: 200px;">
                <img src="../../../content/soep/menu-config.png" class="pr-1 pb-1">
                    Configurações
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="text-align: center;">
                    <button class="dropdown-item" id="professores" style="height: 50px; min-width: 200px;">
                    <img src="../../../content/soep/menu-professor.png" class="pr-1 pb-1">
                        Professores
                    </button>

                    <button class="dropdown-item" id="disciplinas" style="height: 50px; min-width: 200px;">
                    <img src="../../../content/soep/menu-disciplina.png" class="pr-1 pb-1">
                        Disciplinas
                    </button>

                    <button class="dropdown-item" id="turmas" style="height: 50px; min-width: 200px;">
                    <img src="../../../content/soep/menu-turma.png" class="pr-1 pb-1">
                        Turmas
                    </button>

                    <button class="dropdown-item" id="editarCriterios" style="height: 50px; min-width: 200px;">
                    <img src="../../../content/soep/menu-criterio.png" class="pr-1 pb-1">
                        Critérios
                    </button>

                    <button class="dropdown-item" id="baixarAvaliacoes" style="height: 50px; min-width: 200px;">
                    <img src="../../../content/soep/menu-download.png" class="pr-1 pb-1">
                        Avaliações
                    </button>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center m-4">
            <button type="button" class="btn btn-dark btn-sm" id="logout">
            <img src="../../../content/menu-logout.png" class="pr-1 pb-1">    
                Logout
            </button>
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
    <script src="../js/menu.js"></script>
</body>
</html>