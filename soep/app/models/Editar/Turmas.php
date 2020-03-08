<?php
    ## MODEL QUE APAGA UM ALUNO, UMA TURMA OU TODAS AS TURMAS, ATUALIZA OU INSERE UM ALUNO ##
    require '../../../../vendor/autoload.php';
    use Manager\DB;
    $dbManager = new DB('precoc');

    $conexao = '(avaliacao_aluno.id = avaliacao_aluno_criterios.id_avaliacao)';
    $conexao1 = '(turma.id = turma_aluno.id_turma AND turma_aluno.id_aluno = aluno.matricula)';

    ##                            ##
    #  CASE 0: DESATIVA            #
    #  CASE 1: ATUALIZA            #
    #  CASE 2: INSERE              #
    #  CASE 3: APAGA TURMA         #
    #  CASE 4: APAGA TODAS TURMAS  #    
    ##                            ##

    switch($_POST['codigo']):
        case '0':
            $aluno = json_decode($_POST['aluno'], true);
            $dbManager->DML(
                ['aluno'],
                "matricula = :matricula",
                ['matricula' => $aluno['matricula']],
                DB::DELETE
            );
            $dbManager->DML(
                ['turma_aluno'],
                "id_aluno = :id_aluno",
                ['id_aluno' => $aluno['matricula']],
                DB::DELETE
            );
            $dbManager->DML(
                ['avaliacao_aluno_criterios'],
                "id_avaliacao IN (SELECT avaliacao_aluno_criterios.id_avaliacao 
                    FROM avaliacao_aluno, avaliacao_aluno_criterios
                        WHERE $conexao AND id_aluno = :id_aluno)",
                ['id_aluno' => $aluno['matricula']],
                DB::DELETE
            );
            $dbManager->DML(
                ['avaliacao_aluno'],
                "id_aluno = :id_aluno",
                ['id_aluno' => $aluno['matricula']],
                DB::DELETE
            );
            break;
        case '1':
            $aluno = json_decode($_POST['aluno'], true);
            $dbManager->DML(
                ['aluno'],
                ['where_novo' => 'matricula = :matricula, nome = :nome',
                    'params_novo' => ['nome' => $aluno['nome'], 'matricula' => $aluno['matricula']]],
                ['where_antigo' => 'matricula = :alunoid',
                    'params_antigo' => ['alunoid' => $aluno['matricula_antiga']]],
                DB::UPDATE
            );
            $dbManager->DML(
                ['turma_aluno'],
                ['where_novo' => 'id_aluno = :id_aluno',
                    'params_novo' => ['id_aluno' => $aluno['matricula']]],
                ['where_antigo' => 'id_aluno = :alunoid',
                    'params_antigo' => ['alunoid' => $aluno['matricula_antiga']]],
                DB::UPDATE
            );
            $dbManager->DML(
                ['avaliacao_aluno'],
                ['where_novo' => 'id_aluno = :id_aluno',
                    'params_novo' => ['id_aluno' => $aluno['matricula']]],
                ['where_antigo' => 'id_aluno = :alunoid',
                    'params_antigo' => ['alunoid' => $aluno['matricula_antiga']]],
                DB::UPDATE
            );
            break;
        case '2':
            $aluno = json_decode($_POST['aluno'], true);
            $dbManager->DML(
                ['aluno'],
                ['nome', 'matricula'],
                ':nome, :matricula',
                ['nome' => $aluno['nome'], 'matricula' => $aluno['matricula']],
                DB::INSERT
            );
            $dbManager->DML(
                ['turma_aluno'],
                ['id_aluno', 'id_turma'],
                ':id_aluno, :id_turma',
                ['id_aluno' => $aluno['matricula'], 'id_turma' => $aluno['turma']],
                DB::INSERT
            );
            break;
        case '3':
            $alunos = $dbManager->DQL(
                ['aluno.nome as nome'],
                ['turma', 'turma_aluno', 'aluno'],
                "$conexao1 AND turma.nome = :turma",
                ['turma' => $_POST['turma']],
                PDO::FETCH_BOTH
            );

            if($alunos != null){

                $avaliacao_aluno = $dbManager->DQL(
                    ['id'],
                    ['avaliacao_aluno'],
                    "id IN (SELECT avaliacao_aluno_criterios.id_avaliacao 
                        FROM avaliacao_aluno, avaliacao_aluno_criterios
                        WHERE $conexao AND id_turma = :id_turma)",
                    ['id_turma' => $_POST['turma']]
                );
    
                $avaliacao_turma = $dbManager->DQL(
                    ['id_turma'],
                    ['avaliacao_turma'],
                    "id_turma = :id_turma",
                    ['id_turma' => $_POST['turma']]
                );

                $dbManager->DML(
                    ['turma'],
                    "id = :id",
                    ['id' => $_POST['turma']],
                    DB::DELETE
                );

                $dbManager->DML(
                    ['aluno'],
                    "matricula IN (SELECT matricula FROM aluno, turma_aluno, turma 
                        WHERE (aluno.matricula = turma_aluno.id_aluno AND turma_aluno.id_turma = turma.id) AND turma.id = :turma)",
                    ['turma' => $_POST['turma']],
                    DB::DELETE
                );

                $dbManager->DML(
                    ['turma_aluno'],
                    "id_turma = :id_turma",
                    ['id_turma' => $_POST['turma']],
                    DB::DELETE
                );

                if($avaliacao_turma != null){
                    $dbManager->DML(
                        ['avaliacao_aluno_criterios'],
                        "id_avaliacao IN (SELECT avaliacao_aluno_criterios.id_avaliacao 
                            FROM avaliacao_aluno
                                WHERE $conexao AND id_turma = :id_turma)",
                        ['id_turma' => $_POST['turma']],
                        DB::DELETE
                    );
                    $dbManager->DML(
                        ['avaliacao_aluno'],
                        "id_turma = :id_turma",
                        ['id_turma' => $_POST['turma']],
                        DB::DELETE
                    );
                    $dbManager->DML(
                        ['avaliacao_turma'],
                        "id_turma = :id_turma",
                        ['id_turma' => $_POST['turma']],
                        DB::DELETE
                    );
                }
            }else{

                $dbManager->DML(
                    ['turma'],
                    "id = :id",
                    ['id' => $_POST['turma']],
                    DB::DELETE
                );
                
            }
            break;
        case '4':

        $dbManager->DML(
            ['aluno'],
            "1",
            [],
            DB::DELETE
        );

        $dbManager->DML(
            ['turma'],
            "1",
            [],
            DB::DELETE
        );

        $dbManager->DML(
            ['turma_aluno'],
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
            print 'Erro em EditarTurmas.php';
        break;
        endswitch;