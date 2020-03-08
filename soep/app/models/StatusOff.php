<?php
    require '../../../vendor/autoload.php';
    use Manager\DB;
    $dbManager = new DB('administrador');

    $dbManager->DML(
        ['status'], 
        ':all',
        ['all' => '1'],
        DB::DELETE
    );