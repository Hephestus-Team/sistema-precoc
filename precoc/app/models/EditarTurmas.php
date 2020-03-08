<?php
    require '../../../vendor/autoload.php';
    use Manager\DB;
    $dbManager = new DB('precoc');

    session_start();
    
    $dados = json_decode($_POST['dados'], true);
    
    if($dados['codigo'] == '0'){

        $conexao = '(professor.matricula = professor_turma_disciplina.id_prof AND professor_turma_disciplina.id_turma = turma.id)';
        $conexao1 = '(turma.id = professor_turma_disciplina.id_turma AND professor_turma_disciplina.id_disciplina = disciplina.id)';

        $turmas_prof = $dbManager->DQL(
            ['turma.id'],
            ['turma', 'professor_turma_disciplina', 'professor'],
            "$conexao AND professor.matricula = :matricula AND professor_turma_disciplina.id_turma = :id_turma AND professor_turma_disciplina.id_disciplina = :id_disciplina",
            ['matricula' => $_SESSION['matricula'], 'id_turma' => $dados['id_turma'], 'id_disciplina' => $dados['id_disciplina']],
            PDO::FETCH_BOTH
        );

        $turmas_lecionadas = $dbManager->DQL(
            ['professor_turma_disciplina.id_disciplina', 'professor_turma_disciplina.id_turma'],
            ['turma', 'professor_turma_disciplina', 'disciplina'],
            "$conexao1 AND professor_turma_disciplina.id_disciplina = :id_disciplina AND professor_turma_disciplina.id_turma = :id_turma",
            ['id_turma' => $dados['id_turma'], 'id_disciplina' => $dados['id_disciplina']],
            PDO::FETCH_BOTH
        );

        if($turmas_prof == null && $turmas_lecionadas == null){
            $dbManager->DML(
                ['professor_turma_disciplina'],
                ['id_prof', 'id_disciplina', 'id_turma'],
                ':matricula, :id_disciplina, :id_turma',
                ['matricula' => $_SESSION['matricula'], 'id_disciplina' => $dados['id_disciplina'], 'id_turma' => $dados['id_turma']],
                DB::INSERT
            );

            print '1';
            
        }else{
            print '0';
        }
    }elseif($dados['codigo'] == '1'){

        $dbManager->DML(
            ['avaliacao_turma'],
            'id_professor = :matricula AND id_disciplina = :id_disciplina AND id_turma = :id_turma',
            ['matricula' => $_SESSION['matricula'], 'id_disciplina' => $dados['id_disciplina'], 'id_turma' => $dados['id_turma']],
            DB::DELETE
        );

        $dbManager->DML(
            ['avaliacao_aluno'],
            'id_professor = :matricula AND id_disciplina = :id_disciplina',
            ['matricula' => $_SESSION['matricula'], 'id_disciplina' => $dados['id_disciplina']],
            DB::DELETE
        );

        $dbManager->DML(
            ['avaliacao_aluno_criterios'],
            'id_avaliacao NOT IN (SELECT id FROM avaliacao_aluno)',
            [],
            DB::DELETE
        );

        $dbManager->DML(
            ['professor_turma_disciplina'],
            'id_prof = :matricula AND id_disciplina = :id_disciplina AND id_turma = :id_turma',
            ['matricula' => $_SESSION['matricula'], 'id_disciplina' => $dados['id_disciplina'], 'id_turma' => $dados['id_turma']],
            DB::DELETE
        );

        print '1';

    }else{
        print 'ERROR>>> CODIGO INVALIDO EM EditarTurmas.php';
    }
    