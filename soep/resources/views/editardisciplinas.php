<?php require '../../app/controllers/Editar/DisciplinasController.php'; ?>

<html lang="pt-br">
<head>
    <title>Disciplinas | SOEP</title> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" href="../../../content/logo-titulo.png" type="image/x-icon">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../content/style.css">

    <style>
        .custom-file-input ~ .custom-file-label::after {
            content: "Buscar";
        }
    </style>

</head>
<body>
      
    <div class="container my-5 border border-dark rounded text-center col col-12 col-xl-5 col-lg-8 col-md-10 col-sm-12" id="meio2">
    
        <div class="form-row m-3 p-5 rounded border border-dark divisoes">

            <h2 class="mb-5 col-12">Disciplinas Disponíveis</h2>

            <div class="col-12">
                <select id="disciplinasAdicionadas" size="3" class="form-control" style="height: 280px;">
                <?php foreach ($disciplinas as $disciplina): ?>
                    <?php echo "<option value='{$disciplina['id']}'>" . iconv("ISO-8859-1", "UTF-8", "{$disciplina['nome']}") . "</option>"; ?>
                <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-12 align-self-center text-center mx-auto mt-4"> 
                <input type="button" value="Deselecionar" id="desmarcar" class="form-control border border-dark" style="min-width: 50px; height: 50px;">
            </div>
        </div>

        <div class="accordion" id="accordionExample2">
            <div class="card m-3 rounded border border-dark divisoes">
                <div class="card-header" id="headingTwo">
                    <h2 class="mb-0">
                    <button class="btn" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo" id="collapseButton2">
                        Configurar Manualmente
                    </button>
                    </h2>
                </div>
            
                <div id="collapseTwo" class="collapse" aria-labelledby="headineTwo" data-parent="#accordionExample2">
                    <div class="card-body p-5 row">
            
                        <div class="col-12">
                            <div class="input-group mb-3">                
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="padding: 0px 22px 0px 22px;">Nome</span>
                                </div>
                                <input type="text" class="form-control" placeholder="Digite o nome" id="nome">
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <input type="button" class="btn btn-primary" value="Inserir/alterar" id="inserir" style="min-width: 50px; height: 50px; width: 200px">
                        </div>

                        <div class="col-12 align-self-center text-center mt-4"> 
                            <input type="button" value="Deletar" id="deletar" class="btn border border-danger" style="min-width: 50px; height: 40px; width: 100px">
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="accordion mb-5" id="accordionExample">
            <div class="card m-3 rounded border border-dark divisoes">
                <div class="card-header" id="headingOne">
                    <h2 class="mb-0">
                    <button class="btn" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" id="collapseButton">
                        Configurar via Excel
                    </button>
                    </h2>
                </div>
            
                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body p-5">
                        <h2 class="mx-auto mb-5">Selecionar arquivo</h2>
                        <form id="data" method="post" enctype="multipart/form-data" class="mx-auto">
                            <div class="col-12 mb-5 mt-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="fileInput">
                                    <label class="custom-file-label" for="customFile">Selecionar arquivo</label>
                                    <a style="color: #1e10ba;" href="">Como configuro meu Excel ?</a>
                                </div>
                            
                                <input type="button" class="btn btn-primary mt-4" value="Enviar" id="enviar">                            
                                <div class="d-flex justify-content-center" id="loading">
                                </div>
                            
                            </div>
                        </form>            
                    
                        <div class="col-12 my-auto mt-5">
                            <input type="button" class="btn btn-light border border-success" value="Baixar tabela" id="baixarTabela" style="min-width: 50px; height: 40px; width: 150px">
                        </div>
                    
                        <div class="col-12 mt-4">
                            <input type="button" value="Deletar tudo" id="deletarTudo" class="btn border border-danger" id="deletarTudo" style="min-width: 50px; height: 40px; width: 150px">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center my-4">
            <input class="btn border border-primary" type="button" value="Voltar" id="voltar">
        </div>
    </div>

    <footer style="flex-shrink: none;" class="py-4 text-white-50">
        <div class="container text-center">
        <small>© 2019 Copyright: <a href="https://github.com/danielShz" target="_blank"> Daniel Arruda </a> & <a href="https://github.com/muniz034" target="_blank"> Pedro Muniz </a> </small>
        </div>
    </footer>

    <div class="modal fade" id="finishModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="text-align: center;">Tem certeza que deseja deletar tudo?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer mx-auto">
                    <button type="button" class="btn btn-danger" id="sim" style="min-width: 120px;">SIM</button>
                    <button type="button" class="btn btn-info" id="nao" style="min-width: 120px;">NÃO</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="continueModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="text-align: center;">
                    <h4 class="modal-title" style="text-align: center;">Exemplo de tabela</h4>
                </div>
                <div class="modal-body mx-auto">
                      <img src="../../../content/soep/tabela-disciplina.png">
                </div>
                <div class="modal-footer mx-auto">
                    <button type="button" class="btn btn-info" id="continuar" style="min-width: 120px;">Continuar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="../js/editardisciplinas.js"></script>
</body>
</html>