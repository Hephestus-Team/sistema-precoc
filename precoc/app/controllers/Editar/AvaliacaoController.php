<?php
    require '../../../vendor/autoload.php';
    use Manager\DB;
    $dbManager = new DB('precoc');

    session_start();

    $conexao = 'professor.matricula = professor_turma_disciplina.id_prof AND turma.id = professor_turma_disciplina.id_turma AND professor_turma_disciplina.id_turma = avaliacao_turma.id_turma AND disciplina.id = professor_turma_disciplina.id_disciplina AND professor_turma_disciplina.id_disciplina = avaliacao_turma.id_disciplina';
    @$turmas_avaliadas = $dbManager->DQL(
        ['turma.id as id_turma', 'turma.nome as nome_turma', 'disciplina.id as id_disciplina', 'disciplina.nome as nome_disciplina'],
        ['professor', 'professor_turma_disciplina', 'turma', 'avaliacao_turma', 'disciplina'],
        "$conexao AND professor_turma_disciplina.id_turma IN (SELECT avaliacao_turma.id_turma FROM avaliacao_turma) AND professor_turma_disciplina.id_prof = :matricula",
        ['matricula' => $_SESSION['matricula']],
        PDO::FETCH_BOTH
    );