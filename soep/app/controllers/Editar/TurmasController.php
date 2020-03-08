<?php
    require '../../../../vendor/autoload.php';
    use Manager\DB;
    $dbManager = new DB('precoc');

    $conexao = '(turma.id = turma_aluno.id_turma AND turma_aluno.id_aluno = aluno.matricula)';

    $turma = $dbManager->DQL(
        ['nome', 'id'],
        ['turma'],
        "nome = :turma",
        ['turma' => $_POST['turma']],
        PDO::FETCH_BOTH
    );

    $alunos = $dbManager->DQL(
        ['aluno.nome as nome', 'aluno.matricula as matricula'],
        ['turma', 'turma_aluno', 'aluno'],
        "$conexao AND turma.nome = :turma ORDER BY aluno.nome",
        ['turma' => $_POST['turma']],
        PDO::FETCH_ASSOC
    );

    if($turma != null){
        $alunos = DB::encodeValues($alunos, 'nome');
        print json_encode([$alunos, $turma], 256);
    }else{
        print "false";
    }