<?php
    require '../../../vendor/autoload.php';
    use Manager\DB;
    $dbManager = new DB('precoc');
    session_start();

    @$turma = $_POST['turma_id'];
    @$professor = $_SESSION['matricula'];

    $conexao = "(aluno.matricula = turma_aluno.id_aluno AND turma_aluno.id_turma = turma.id)";
    $conexao2 = "(professor.matricula = professor_turma_disciplina.id_prof AND professor_turma_disciplina.id_disciplina = disciplina.id)";
    $conexao3 = "(avaliacao_turma.id_turma = turma.id AND avaliacao_turma.id_professor = professor.matricula)";

    $alunos = $dbManager->DQL(
        ['aluno.nome', 'aluno.matricula'],
        ['aluno', 'turma_aluno', 'turma'],
        "$conexao AND turma.id = :turma_id ORDER BY aluno.nome",
        ['turma_id' => $turma],
        PDO::FETCH_BOTH
    );

    $disciplinas = $dbManager->DQL(
        ['disciplina.id','disciplina.nome'], 
        ["(SELECT disciplina.id, disciplina.nome FROM disciplina, professor_turma_disciplina WHERE (professor_turma_disciplina.id_prof = '$professor' AND 
        professor_turma_disciplina.id_disciplina = disciplina.id AND professor_turma_disciplina.id_turma = '$turma')) as disciplina"], 
        "NOT EXISTS(SELECT * FROM avaliacao_turma WHERE avaliacao_turma.id_disciplina = disciplina.id AND avaliacao_turma.id_professor = :matricula AND avaliacao_turma.id_turma = :id_turma)",
        ['matricula' => $professor, 'id_turma' => $turma],
        PDO::FETCH_ASSOC
    );

    $criterios = $dbManager->DQL(
        ['nome', 'id'],
        ['criterios'],
        "status = '1'",
        [],
        PDO::FETCH_BOTH
    );

    $avaliadas_linha = $dbManager->DQL(
        ['disciplina.id'], 
        ['disciplina'], 
        "disciplina.id IN 
            (SELECT avaliacao_turma.id_disciplina FROM avaliacao_turma, turma, professor 
                WHERE $conexao3 AND turma.id = :turma_id AND id_professor = :id_professor)",
        ['turma_id' => $turma, 'id_professor' => $professor],
        PDO::FETCH_BOTH
    );

    $avaliadas = [];
    $matricula = [];

    for($i = 0; $i < count($alunos); $i++){
        $matricula[] = $alunos[$i]['matricula'];
    }

    if($avaliadas_linha != null){

        foreach ($avaliadas_linha as $avaliada)
        {
            $avaliadas[] = $avaliada[0];
        }
        
    }

    $dados = [
        "alunos" => $matricula,
        "turma_id" => $turma, 
        "prof_id" => $professor, 
        "criterio" => $criterios, 
        "disciplinas" => DB::encodeValues($disciplinas, 'nome')
    ];
