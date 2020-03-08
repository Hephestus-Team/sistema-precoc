<?php
    require '../../../vendor/autoload.php';
    use Manager\DB;
    $dbManager = new DB('precoc');
    session_start();
    
    $conexao = "(aluno.matricula = turma_aluno.id_aluno AND turma_aluno.id_turma = turma.id)";

    @$dados = json_decode($_POST['dados'], true);

    $alunos = $dbManager->DQL(
        ['aluno.nome', 'aluno.matricula'],
        ['aluno', 'turma_aluno', 'turma'],
        "$conexao AND turma.id = :turma_id ORDER BY aluno.nome",
        ['turma_id' => $dados['id_turma']],
        PDO::FETCH_BOTH
    );

    @$disciplinas = $dbManager->DQL(
        ['professor_disciplina.id','professor_disciplina.nome'], 
        ["(SELECT disciplina.id, disciplina.nome FROM disciplina, professor_turma_disciplina WHERE (professor_turma_disciplina.id_prof = '{$_SESSION['matricula']}' AND 
        professor_turma_disciplina.id_disciplina = disciplina.id AND professor_turma_disciplina.id_turma = {$dados['id_turma']})) as professor_disciplina"], 
        "NOT EXISTS(SELECT * FROM avaliacao_turma WHERE avaliacao_turma.id_disciplina = professor_disciplina.id AND avaliacao_turma.id_professor = :matricula AND avaliacao_turma.id_turma = :id_turma)",
        ['matricula' => $_SESSION['matricula'], 'id_turma' => $dados['id_turma']],
        PDO::FETCH_BOTH
    );
    
    $criterios = $dbManager->DQL(
        ['nome', 'id'],
        ['criterios'],
        "status = '1'",
        [],
        PDO::FETCH_BOTH
    );

    $matricula = [];

    for($i = 0; $i < count($alunos); $i++){
        $matricula[] = $alunos[$i]['matricula'];
    }
    
    @$dados = ["aluno_id" => $matricula, 
              "turma_id" => $dados['id_turma'], 
              "prof_id" => $_SESSION['matricula'], 
              "criterio" => $criterios, 
              "disciplinas" => $dados['id_disciplina']];