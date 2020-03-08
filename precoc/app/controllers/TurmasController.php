<?php
    require '../../../vendor/autoload.php';
    use Manager\DB;
    $dbManager = new DB('precoc');
    $admManager = new DB('administrador');

    session_start();

    @$matricula = $_SESSION['matricula'];
    $trimestre = $admManager->DQL(
        ['trimestre'], 
        ['status'],
        PDO::FETCH_BOTH
    );

    $conexao = "(professor.matricula = professor_turma_disciplina.id_prof AND professor_turma_disciplina.id_turma = turma.id)";
    $conexao2 = "(avaliacao_turma.id_turma = turma.id AND avaliacao_turma.id_professor = professor.matricula)";

    $professor = $dbManager->DQL(
        ['nome', 'matricula'], 
        ['professor'], 
        "matricula = :matricula",
        ['matricula' => $matricula],
        PDO::FETCH_BOTH
    );

    @$turmas = $dbManager->DQL(
        ['turma.nome', 'turma.id'], 
        ['turma', "(SELECT turmas_professor.id_turma FROM (SELECT * FROM professor_turma_disciplina WHERE professor_turma_disciplina.id_prof = '{$professor[0]['matricula']}') as turmas_professor"], 
        "NOT EXISTS (SELECT * FROM avaliacao_turma WHERE avaliacao_turma.id_turma = turmas_professor.id_turma AND avaliacao_turma.id_disciplina = turmas_professor.id_disciplina AND avaliacao_turma.trimestre = '{$trimestre[0][0]}')) as turma_nao_avaliadas
        WHERE turma.id = turma_nao_avaliadas.id_turma GROUP BY turma.nome",
        [],
        PDO::FETCH_BOTH
    );
