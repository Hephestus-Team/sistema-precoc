<?php
    require '../../../vendor/autoload.php';
    use Manager\DB;
    $dbManager = new DB('precoc');

    $turmas = $dbManager->DQL(
        ['nome'],
        ['turma'],
        "1 ORDER BY nome",
        [],
        PDO::FETCH_ASSOC
    );

    $disciplina = $dbManager->DQL(
        ['nome'],
        ['disciplina'],
        "1 ORDER BY nome",
        [],
        PDO::FETCH_ASSOC
    );

    $disciplinas = DB::encodeValues($disciplina, "nome");