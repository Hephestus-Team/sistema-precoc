<?php
    require '../../../../vendor/autoload.php';
    use Manager\DB;
    $precocManager = new DB('precoc');
    $admManager = new DB('administrador');

    $filtro = json_decode($_POST['filtros'], true);
    $where = '';

    foreach($filtro as $key => $value):
        $where .= " AND $key LIKE :$key";
        $filtro[$key] = "%$value%";
    endforeach;

    $professores = $admManager->DQL(
        ['nome, matricula, email'],
        ['professores'],
        "1 $where",
        $filtro,
        PDO::FETCH_BOTH
    );

    print json_encode($professores);