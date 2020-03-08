<?php require "../../app/controllers/ConsultaController.php"; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Consulta | SOEP</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" href="../../../content/logo-titulo.png" type="image/x-icon">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../content/style.css">
</head>
<body>
      
    <div class="container mt-5 border border-dark rounded text-center col col-12 col-xl-5 col-lg-8 col-md-8 col-sm-12" id="meio1">
            
        <h2 class="mt-5">Consultar avaliações</h2>
    
        <div class="form-row m-5">
            <div class="col-12">
                <div class="input-group mb-3 d-flex justify-content-center">
                    <div class="input-group-prepend">
                        <span class="input-group-text" style="width: 110px">Professor(a)</span>
                    </div>
                    <input type="text" class="form-control" placeholder="Digite o nome" id="professor">
                </div>
            </div> 

            <div class="col-12">
                <div class="input-group mb-3 d-flex justify-content-center">
                    <div class="input-group-prepend">
                        <span class="input-group-text" style="width: 110px">Turma</span>
                    </div>
                    <select id="turma" class="custom-select">
                        <option value="todas">Todas</option>
                        <?php foreach($turmas as $turma):?>
                        <?php echo "<option>{$turma['nome']}</option>";?>
                        <?php endforeach;?>  
                    </select>
                </div>
            </div>

            <div class="col-12">
                <div class="input-group mb-3 d-flex justify-content-center">
                    <div class="input-group-prepend">
                        <span class="input-group-text" style="width: 110px">Disciplina</span>
                    </div>
                    <select id="disciplina" class="custom-select">
                        <option value="todas">Todas</option>
                        <?php foreach($disciplinas as $disciplina):?>
                        <?php echo "<option>{$disciplina['nome']}</option>";?>
                        <?php endforeach;?>        
                    </select>
                </div>
            </div>

            <div class="col-6 mt-3">
                <h5>Ano</h5>
                <div class="input-group">
                    <input type="number" class="btn btn-light border border-secondary form-control" id="ano">
                    <div class="invalid-tooltip">
                        Preencha com um valor ou desmarque a caixa
                    </div>
                </div>    
            </div>

            <div class="col-6 mt-3">
                <h5>Trimestre</h5>
                <select id="trimestre" class="custom-select">
                    <option value="1">1° Trimestre</option>
                    <option value="2">2° Trimestre</option>
                    <option value="3">3° Trimestre</option>
                </select>
            </div>
            
            <div class="col-12">
                <input type="button" class="btn btn-primary border border-dark align-middle mt-4" value="Pesquisar" id="pesquisar">
                <input type="button" class="btn btn-dark border border-dark align-middle mt-4" value="Exibir Todos" id="exibirTodos">
            </div>

            <div class="col-12" id="loading"></div>

        </div>

        <div id="avaliacaoInfo">
        </div>

        <div class="d-flex justify-content-center mt-5" id="pagination"></div>

        <div class="d-flex justify-content-center my-4">
            <input class="btn border border-primary" type="button" value="Voltar" id="voltar">
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
    <script src="../js/consulta.js"></script>
</body>
</html>