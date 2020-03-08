<?php
    ## CONTROLLER QUE GERENCIA A COR DO BOTÃO DE STATUS ##
    require "../../../vendor/autoload.php";
    use Manager\DB;
    $dbManager = new DB('administrador');

    $status = $dbManager->DQL(
        ['*'],
        ['status'],
        PDO::FETCH_BOTH
    );

    if($status != null){
        $border = 'border-success';
    }else{
        $border = 'border-danger';
    }