<?php
    require '../../../vendor/autoload.php';
    use Manager\DB;
    $dbManager = new DB('precoc');
    
    session_start();
    
    $conexao = 'professor.matricula = professor_turma_disciplina.id_prof AND professor_turma_disciplina.id_turma = turma.id';
    $conexao1 = 'turma.id = professor_turma_disciplina.id_turma 
        AND professor_turma_disciplina.id_prof = professor.matricula 
        AND disciplina.id = professor_turma_disciplina.id_disciplina';

    $turmas = $dbManager->DQL(
        ['nome', 'id'],
        ['turma'],
        ':bind',
        ['bind' => '1'],
        PDO::FETCH_BOTH
    );
    $disciplinas = $dbManager->DQL(
        ['nome', 'id'],
        ['disciplina'],
        ':bind',
        ['bind' => '1'],
        PDO::FETCH_BOTH
    );
    $turmas_prof = $dbManager->DQL(
        ['turma.nome'],
        ['turma', 'professor_turma_disciplina', 'professor'],
        "$conexao AND professor.matricula = :matricula",
        ['matricula' => $_SESSION['matricula']],
        PDO::FETCH_BOTH
    );
    $disciplinas_prof = $dbManager->DQL(
        ['turma.nome', 'disciplina.nome', 'turma.id', 'disciplina.id'],
        ['turma', 'professor_turma_disciplina', 'professor', 'disciplina'],
        "$conexao1 AND professor.matricula = :matricula",
        ['matricula' => $_SESSION['matricula']],
        PDO::FETCH_BOTH
    );
