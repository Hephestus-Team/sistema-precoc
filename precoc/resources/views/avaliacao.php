<?php require '../../app/controllers/Avaliacao/AvaliacaoController.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Avaliação | PRECOC</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" href="../../../content/iconeDoProjeto.png" type="image/x-icon">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../content/style.css">
</head>
<body>
    <div class="container mt-5 mb-5 border border-dark rounded mw-80 col col-12 col-xl-8 col-lg-10 col-md-10 col-sm-12" id="meio1">
    <div class="row">

    <div class="col-12 text-center mt-4 p-5">
        <h2>Observações sobre a turma</h2>
        <h5 class="pb-4">Escreva suas considerações gerais sobre esta turma</h5>

        <textarea name="observacao" cols="30" rows="10" class="mw-100 form-control" required='required'></textarea>
    </div>

    <div class="col-12 align-self-center text-center mx-auto">

        <h4>Selecione a(s) disciplina</h4>

        <?php foreach ($disciplinas as $disciplina): ?>
 
            <?php 
            if(in_array($disciplina['id'], $avaliadas)):

                echo "<label style='font-size: 130%'> (Avaliado) " . iconv("ISO-8859-1", "UTF-8", "{$disciplina['nome']}");
                echo "<input type='checkbox' class='disciplinas align-middle' name='disciplina_id[]' value='{$disciplina['id']}' style='width:20px;height:20px;' disabled>";

            else:

                echo "<label style='font-size: 130%'>" . iconv("ISO-8859-1", "UTF-8", "{$disciplina['nome']}");
                echo "<input type='checkbox' class='disciplinas align-middle ml-1' name='disciplina_id[]' value='{$disciplina['id']}' style='width:20px;height:20px;'>";

            endif;

                echo "</label> <br>";     
            ?>

        <?php endforeach;?>
        
        <div class="alert alert-danger" role="alert" style="visibility: hidden" id="alert">
            Selecione a/as disciplina(s)
        </div>
    </div>
    </div>

    <div class="table-responsive" style="overflow-y: scroll; max-height: 600px">
        <table class="table table-striped table-hover text-center">
            <thead>
                <tr>
                    <th scope="col" class="align-middle" style="position: sticky; top: 0; background-color: #FAF9F5;">#</th>

                    <?php foreach ($criterios as $criterio): ?>

                    <th scope="col" class="align-middle" style="position: sticky; top: 0; background-color: #FAF9F5;">
                        <?php echo iconv("ISO-8859-1", "UTF-8", "{$criterio['nome']}"); ?>
                    </th>

                    <?php endforeach;?>
                </tr>
            </thead>
            <tbody>
            <?php for($i = 0; $i < count($alunos); $i++): ?>
                <tr>
                    <?php echo "<th scope='row' class='nome'> [" . intval($i+1) . "] " . iconv("ISO-8859-1", "UTF-8","{$alunos[$i]['nome']}") . "</th>";?>

                    <?php for ($j = 0; $j < count($criterios); $j++)
                        echo "<td class='align-self-center align-middle'><input type='checkbox' class='checkbox{$i}' value='{$criterios[$j]['id']}' style='width:25px;height:25px;'></td>"; ?>
                </tr>
            <?php endfor; ?>
            </tbody>
        </table>
    </div>
    
    <input type="hidden" name="dados" value='<?php echo json_encode($dados, 256); ?>'>

    <div class="d-flex justify-content-center pt-5">
        <input type="button" class="btn btn-primary" value="Enviar" id="enviar">
    </div>

    <div class="d-flex justify-content-center" id="loading">   
    </div>

    <div class="d-flex justify-content-center my-4">
        <input class="btn border border-primary" type="button" value="Voltar" id="voltar" style="width: 5px">
    </div>

    <div class="modal fade" id="finishModal" tabindex="-1" role="dialog" aria-labelledby="finishModalLabel" aria-hidden="false">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h3 class="modal-title" id="finishModalLabel" style="text-align: center;">Turma avaliada com sucesso
                        <img src="../../../content/precoc/checked.png">
                </h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info mr-auto" id="finalizar">Menu</button>
                    <button type="button" class="btn btn-primary" id="escolher">Escolher outra turma</button>
                </div>
            </div>
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
    <script src="../js/avaliacao.js"></script>
</body>
</html>