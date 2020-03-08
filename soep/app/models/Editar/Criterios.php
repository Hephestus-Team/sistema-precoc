<?php
    ## MODEL QUE DESATIVA, ATUALIZA OU INSERE UM CRITERIO ## 
    require '../../../../vendor/autoload.php';
    use Manager\DB;
    $dbManager = new DB('precoc');

    ##                  ##
    #  CASE 0: DESATIVA  #
    #  CASE 1: ATUALIZA  #
    #  CASE 2: INSERE    #
    ##                  ##

    switch($_POST['codigo']):
        case '0':
            $dbManager->DML(
                ['criterios'],
                ['where_novo' => 'status = :status',
                    'params_novo' => ['status' => '0']],
                ['where_antigo' => 'id = :id',
                    'params_antigo' => ['id' => $_POST['criterioId']]],
                DB::UPDATE
            );
            break;
        case '1':
            $dbManager->DML(
                ['criterios'],
                ['where_novo' => 'nome = :nome',
                    'params_novo' => ['nome' => $_POST['criterioNome']]],
                ['where_antigo' => 'id = :id',
                    'params_antigo' => ['id' => $_POST['criterioId']]],
                DB::UPDATE
            );
            break;
        case '2':
            $dbManager->DML(
                ['criterios'],
                ['nome', 'status'],
                ':nome, :status',
                ['nome' => $_POST['criterioNome'], 'status' => '1'],
                DB::INSERT
            );
            break;
        default:
            print 'Erro em EditarCriterios.php';
            break;
        endswitch;