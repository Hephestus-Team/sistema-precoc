<?php
    require '../../../vendor/autoload.php';
    use Manager\DB;
    $dbManager = new DB('administrador');
    
    $data = json_decode(file_get_contents('../../../lib/Manager/ServerSettings.json'), true);

    date_default_timezone_set($data['serverconfig']['DEFAULT_TIMEZONE']);

    $status = $dbManager->DQL(
        ['*'],
        ['status'], 
        PDO::FETCH_BOTH
    );

    $abertura = date_create($status[0]['abertura']);
    $fechamento = date_create($status[0]['fechamento']);
    $hoje = date_create(date('d-m-Y'));

    if($status == NULL){
        echo "false";
    }else{
        if(($hoje >= $abertura) && ($hoje <= $fechamento)){
            echo "true";
        }else{
            echo "false";
        }
    }