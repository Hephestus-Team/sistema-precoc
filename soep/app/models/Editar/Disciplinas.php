<?php
    ## MODEL QUE DESATIVA, ATUALIZA, INSERE OU APAGA TODAS DISCIPLINAS ##
    require '../../../vendor/autoload.php';
    use Manager\DB;
    $dbManager = new DB('precoc');

    $conexao  = "(disciplina.id = professor_turma_disciplina.id_disciplina AND professor_turma_disciplina.id_prof = professor.matricula)";
    $conexao1 = "(avaliacao_turma.id_disciplina = professor_turma_disciplina.id_disciplina AND professor_turma_disciplina.id_disciplina = disciplina.id)";
    
    ##                     ##
    #  CASE 0: DESATIVA     #
    #  CASE 1: ATUALIZA     #
    #  CASE 2: INSERE       #
    #  CASE 3: APAGA TODOS  #
    ##                     ##

    switch($_POST['codigo']):
        case '0':
            $dbManager->DML(
                ['disciplina'],
                ['where_novo' => 'status = :status',
                    'params_novo' => ['status' => '0']],
                ['where_antigo' => 'id = :id',
                    'params_antigo' => ['id' => $_POST['disciplinaId']]],
                DB::UPDATE
            );
            break;
        case '1':
            $dbManager->DML(
                ['disciplina'],
                ['where_novo' => 'nome = :nome',
                    'params_novo' => ['nome' => iconv("UTF-8", "ISO-8859-1", "{$_POST['disciplinaNome']}")]],
                ['where_antigo' => 'id = :id',
                    'params_antigo' => ['id' => $_POST['disciplinaId']]],
                DB::UPDATE
            );
            break;
        case '2':
            $dbManager->DML(
                ['disciplina'],
                ['nome', 'status'],
                ':nome, :status',
                ['nome' => $_POST['disciplinaNome'], 'status' => '1'],
                DB::INSERT
            );
            print $dbManager->database->lastInsertId();
            break;
        case '3':

            $dbManager->DML(
                ['disciplina'],
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

        break;
        default:
            print 'Erro em EditarDisciplinas.php';
        break;
        endswitch;