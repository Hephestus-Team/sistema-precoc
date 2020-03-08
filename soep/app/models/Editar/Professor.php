<?php
    ## MODEL QUE APAGA, ATUALIZA, INSERE OU APAGA TODOS OS PROFESSORES ##
    require '../../../../vendor/autoload.php';
    use Manager\DB;
    $precocManager = new DB('precoc');
    $admManager = new DB('administrador');

    $conexao = "(avaliacao_aluno.id = avaliacao_aluno_criterios.id_avaliacao)";

    $professor = json_decode($_POST['professorinfo'], true);
    $avaliacoes = $precocManager->DQL(
        ['avaliacao_aluno_criterios.id_avaliacao as avaliacao'],
        ['avaliacao_aluno', 'avaliacao_aluno_criterios'],
        "$conexao AND avaliacao_aluno.id_professor = :matricula",
        ['matricula' => $professor['matricula']],
        PDO::FETCH_BOTH
    );

    ##                     ##
    #  CASE 0: DELETA       #
    #  CASE 1: ATUALIZA     #
    #  CASE 2: INSERE       #
    #  CASE 3: APAGA TODOS  #
    ##                     ##

    switch($_POST['codigo']):
        case '0':
            $precocManager->DML(
                ['professor'],
                "matricula = :matricula",
                ['matricula' => $professor['matricula']],
                DB::DELETE
            );
            $precocManager->DML(
                ['professor_turma_disciplina'],
                "id_prof = :matricula",
                ['matricula' => $professor['matricula']],
                DB::DELETE
            );
            $precocManager->DML(
                ['avaliacao_turma'],
                "id_professor = :matricula",
                ['matricula' => $professor['matricula']],
                DB::DELETE
            );
            $precocManager->DML(
                ['avaliacao_aluno_criterios'],
                "id_avaliacao IN (SELECT avaliacao_aluno_criterios.id_avaliacao 
                    FROM avaliacao_aluno
                        WHERE $conexao AND avaliacao_aluno.id_professor = :matricula)",
                ['matricula' => $professor['matricula']],
                DB::DELETE
            );
            $precocManager->DML(
                ['avaliacao_aluno'],
                "id_professor = :matricula",
                ['matricula' => $professor['matricula']],
                DB::DELETE
            );
            $admManager->DML(
                ['professores'],
                "matricula = :matricula",
                ['matricula' => $professor['matricula']],
                DB::DELETE
            );
            $admManager->DML(
                ['cadastro_professor'],
                "matricula = :matricula",
                ['matricula' => $professor['matricula']],
                DB::DELETE
            );
            break;
        case '1':
            $admManager->DML(
                ['professores'],
                ['where_novo' => 'matricula = :matricula, email = :email, nome = :nome',
                    'params_novo' => ['nome' => $professor['nome'], 'email' => $professor['email'], 'matricula' => $professor['matricula']]],
                ['where_antigo' => 'matricula = :professorid',
                    'params_antigo' => ['professorid' => $_POST['professorId']]],
                DB::UPDATE
            );
            $precocManager->DML(
                ['professor'],
                ['where_novo' => 'matricula = :matricula, nome = :nome',
                    'params_novo' => ['nome' => $professor['nome'], 'matricula' => $professor['matricula']]],
                ['where_antigo' => 'matricula = :professorid',
                    'params_antigo' => ['professorid' => $_POST['professorId']]],
                DB::UPDATE
            );
            $precocManager->DML(
                ['professor_turma_disciplina'],
                ['where_novo' => 'id_prof = :matricula',
                    'params_novo' => ['matricula' => $professor['matricula']]],
                ['where_antigo' => 'id_prof = :professorid',
                    'params_antigo' => ['professorid' => $_POST['professorId']]],
                DB::UPDATE
            );
            $precocManager->DML(
                ['avaliacao_turma'],
                ['where_novo' => 'id_professor = :matricula',
                    'params_novo' => ['matricula' => $professor['matricula']]],
                ['where_antigo' => 'id_professor = :professorid',
                    'params_antigo' => ['professorid' => $_POST['professorId']]],
                DB::UPDATE
            );
            $precocManager->DML(
                ['avaliacao_aluno'],
                ['where_novo' => 'id_professor = :matricula',
                    'params_novo' => ['matricula' => $professor['matricula']]],
                ['where_antigo' => 'id_professor = :professorid',
                    'params_antigo' => ['professorid' => $_POST['professorId']]],
                DB::UPDATE
            );
            break;
        case '2':
            $admManager->DML(
                ['professores'],
                ['nome', 'email', 'matricula'],
                ':nome, :email, :matricula',
                ['nome' => $professor['nome'], 'email' => $professor['email'], 'matricula' => $professor['matricula']],
                DB::INSERT
            );
            break;
        case '3':

            $dbManager->DML(
                ['professor'],
                "1",
                [],
                DB::DELETE
            );

            $dbManager->DML(
                ['professor_turma_disciplina'],
                "1",
                [],
                DB::DELETE
            );

            $dbManager->DML(
                ['avaliacao_turma'],
                "1",
                [],
                DB::DELETE
            );

            $dbManager->DML(
                ['avaliacao_aluno'],
                "1",
                [],
                DB::DELETE
            );

            $dbManager->DML(
                ['avaliacao_aluno_criterios'],
                "1",
                [],
                DB::DELETE
            );
            
        default:
            print 'Erro em EditarProfessor.php';
            break;
        endswitch;