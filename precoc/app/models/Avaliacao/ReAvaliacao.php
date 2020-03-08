<?php
    require '../../../../vendor/autoload.php';
    use Manager\DB;
    $dbManager = new DB('precoc');

    $dados = json_decode($_POST['dados'], true);
    $criterios = json_decode($_POST['criterios'], true);

        for($i = 0; $i < count($dados['aluno_id']); $i++)
        {
            for($count1 = 0; $count1 < count($dados['criterio']); $count1++)
            {
                $avaliacao_id = $dbManager->DQL(
                    ['id'], 
                    ['avaliacao_aluno'],
                    "id_aluno = :id_aluno AND id_professor = :id_professor 
                        AND id_disciplina = :id_disciplina",
                    ['id_aluno' => $dados['aluno_id'][$i], 'id_professor' => $dados['prof_id'], 'id_disciplina' => $dados['disciplinas']],
                    PDO::FETCH_BOTH
                );

                if($criterios[$i][$count1][0]){
                    $valor = 1;
                }else{
                    $valor = 0;
                }

                $dbManager->DML(
                    ['avaliacao_aluno_criterios'],
                    ['where_novo' => 'valor = :valor',
                     'params_novo' => ['valor' => $valor]],
                    ['where_antigo' => 'id_avaliacao = :id_avaliacao AND id_criterio = :id_criterio',
                     'params_antigo' => ['id_avaliacao' => $avaliacao_id[0][0], 'id_criterio' => $dados['criterio'][$count1][1]]],
                    DB::UPDATE
                );
            }
        }

        $dbManager->DML(
            ['avaliacao_turma'],
            ['where_novo' => 'observacao = :observacao',
                'params_novo' => ['observacao' => $_POST['observacao']]],
            ['where_antigo' => 'id_disciplina = :id_disciplina AND id_turma = :id_turma AND id_professor = :id_professor',
                'params_antigo' => ['id_disciplina' => $dados['disciplinas'], 'id_turma' => $dados['turma_id'], 'id_professor' => $dados['prof_id']]],
            DB::UPDATE
        );