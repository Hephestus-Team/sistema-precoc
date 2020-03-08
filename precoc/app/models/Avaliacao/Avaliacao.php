<?php
    require '../../../../vendor/autoload.php';
    use Manager\DB;
    $precocManager = new DB('precoc');
    $administradorManager = new DB('administrador');

    $ano = date('Y');
    $dados = json_decode($_POST['dados'], true);
    $criterios = json_decode($_POST['criterios'], true);
    $disciplina = json_decode($_POST['disciplina_id'], true);

    $trimestre = $administradorManager->DQL(
        ['trimestre'], 
        ['status'],
        PDO::FETCH_BOTH
    );

    $disciplina_length = count($disciplina);
    $alunos_length = count($dados['alunos']);
    $criterios_length = count($dados['criterio']);
    
    for($i = 0; $i < $disciplina_length; $i++){

        $avaliacao_aluno = "";
        $avaliacao_aluno_criterios = "";
        
        $id_avaliacao = $precocManager->DQL(
            ['MAX(id)'], 
            ['avaliacao_aluno'],
            "1",
            [],
            PDO::FETCH_BOTH
        );

        $auto_increment = $precocManager->DQL(
            ['AUTO_INCREMENT'], 
            ['INFORMATION_SCHEMA.TABLES'],
            "TABLE_SCHEMA = :bd AND TABLE_NAME = :nome",
            ['bd' => 'precoc', 'nome' => 'avaliacao_aluno'],
            PDO::FETCH_ASSOC
        );

        // print "<br> [$i]MAXID »» {$id_avaliacao[0][0]}<br>[$i]AUTOINCREMENT »» {$auto_increment[0]['AUTO_INCREMENT']}<br>";

        for($z = 0; $z < $alunos_length; $z++){

            $avaliacao_aluno .= "('{$dados['alunos'][$z]}', '{$dados['prof_id']}', {$disciplina[$i]}, {$dados['turma_id']}, $ano, {$trimestre[0][0]})";
            
            for($y = 0; $y < $criterios_length; $y++){

                if($criterios[$z][$y][0]){
                    $valor = 1;
                }else{
                    $valor = 0;
                }

                // if($id_avaliacao[0][0] == null && $auto_increment[0]['AUTO_INCREMENT'] == '1'){
                //     $id = intval($z + $auto_increment[0]['AUTO_INCREMENT']);
                //     $avaliacao_aluno_criterios .= "('$valor', $id, {$dados['criterio'][$y]['id']})";
                //     print '1';
                // }elseif($id_avaliacao[0][0] == null && $auto_increment[0]['AUTO_INCREMENT'] != '1'){
                //     $id = intval($auto_increment[0]['AUTO_INCREMENT'] + $z);
                //     $avaliacao_aluno_criterios .= "('$valor', $id, {$dados['criterio'][$y]['id']})";
                //     print '2';
                // }elseif($id_avaliacao[0][0] != null){
                //     $id = intval($id_avaliacao[0][0] + $z + 1);
                //     $avaliacao_aluno_criterios .= "('$valor', $id, {$dados['criterio'][$y]['id']})";
                //     print "3";
                // }

                if($id_avaliacao[0][0] != null){
                    $id = intval($id_avaliacao[0][0] + $z + 1);
                    $avaliacao_aluno_criterios .= "('$valor', $id, {$dados['criterio'][$y]['id']})";
                }else{
                    $id = intval($auto_increment[0]['AUTO_INCREMENT'] + $z);
                    $avaliacao_aluno_criterios .= "('$valor', $id, {$dados['criterio'][$y]['id']})";
                }

                if($y != ($criterios_length - 1)){
                    $avaliacao_aluno_criterios .= ', ';
                }
            }

            if($z != ($alunos_length - 1)){
                $avaliacao_aluno .= ', ';
                $avaliacao_aluno_criterios .= ', ';
            }
        }

        $precocManager->DML(
            'avaliacao_aluno_criterios',
            'valor, id_avaliacao, id_criterio',
            $avaliacao_aluno_criterios,
            DB::LONG_INSERT
        );
    
        $precocManager->DML(
            'avaliacao_aluno',
            'id_aluno, id_professor, id_disciplina, id_turma, ano, trimestre',
            $avaliacao_aluno,
            DB::LONG_INSERT
        );
    
        $precocManager->DML(
            ['avaliacao_turma'], 
            ['id_disciplina', 'observacao', 'id_turma', 'id_professor', 'ano', 'trimestre'], 
                ':id_disciplina, :observacao, :id_turma, :id_professor, :ano, :trimestre',
            ['id_disciplina' => $disciplina[$i], 'observacao' => $_POST['observacao'], 
                'id_turma' => $dados['turma_id'], 'id_professor' => $dados['prof_id'], 'ano' => $ano, 'trimestre' => $trimestre[0][0]],
            DB::INSERT
        );
    }
