<?php
    ## CONTROLLER QUE CARREGA OS CRITERIOS ATIVOS ##
    require '../../../vendor/autoload.php';
    use Manager\DB;
    $dbManager = new DB('precoc');

    $criterios = $dbManager->DQL(
        ['*'],
        ['criterios'],
        "status = '1'",
        [],
        PDO::FETCH_BOTH
    );