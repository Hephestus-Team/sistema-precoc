<?php
    require '../../../vendor/autoload.php';
    use Manager\DB;
    $dbManager = new DB('administrador');

    $inicio = date('d-m-Y', strtotime($_POST['inicio']));
    $termino = date('d-m-Y', strtotime($_POST['termino']));

    $status = $dbManager->DQL(
        ['*'], 
        ['status'],
		PDO::FETCH_BOTH
    );

    if($status == null){
        $dbManager->DML(
            ['status'], 
            ['trimestre', 'ano', 'abertura', 'fechamento'],
            ':trimestre, :ano, :abertura, :fechamento',
            ['trimestre' => $_POST['trimestre'], 'ano' => date('Y'), 'abertura' => $inicio, 'fechamento' => $termino], 
            DB::INSERT
        );
    }else{
        $dbManager->DML(
            ['status'],
            ['where_novo' => 'ano = :ano, trimestre = :trimestre, abertura = :abertura, fechamento = :fechamento', 
                'params_novo' => ['ano' => date('Y'), 'trimestre' => $_POST['trimestre'], 'abertura' => $inicio, 'fechamento'=> $termino]],
            ['where_antigo' => ':where', 
                'params_antigo' => ['where' => '1']], 
            DB::UPDATE
        );
    }