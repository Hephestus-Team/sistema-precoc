<?php
    require '../../../vendor/autoload.php';
    use Manager\DB;
    $dbManager = new DB('precoc');
 
    $disciplinas = $dbManager->DQL(
        ['id as id, nome as nome'],
        ['disciplina'],
        "status = '1'",
        [],
        PDO::FETCH_BOTH
	);