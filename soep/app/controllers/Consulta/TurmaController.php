<?php
    require '../../../../vendor/autoload.php';
    use Manager\DB;
    $dbManager = new DB('precoc');

    $filtro = json_decode($_POST['filtros'], true);
    $data = json_decode($_POST['tempo'], true);
    $where = '';
    $turma = [];

    $conexao  = "(criterios.id = avaliacao_aluno_criterios.id_criterio AND avaliacao_aluno_criterios.id_avaliacao = avaliacao_aluno.id AND avaliacao_aluno.id_aluno = turma_aluno.id_aluno AND turma_aluno.id_aluno = aluno.matricula AND avaliacao_aluno.id_professor = professor_turma_disciplina.id_prof AND professor_turma_disciplina.id_prof = professor.matricula AND avaliacao_aluno.id_disciplina = professor_turma_disciplina.id_disciplina AND professor_turma_disciplina.id_disciplina = disciplina.id AND professor_turma_disciplina.id_turma = turma.id AND avaliacao_aluno.id_turma = professor_turma_disciplina.id_turma)";
    $conexao1 = "(avaliacao_turma.id_professor = professor_turma_disciplina.id_prof AND professor_turma_disciplina.id_prof = professor.matricula AND avaliacao_turma.id_disciplina = professor_turma_disciplina.id_disciplina AND professor_turma_disciplina.id_disciplina = disciplina.id)";
    $conexao2 = "(aluno.matricula = turma_aluno.id_aluno AND turma.id = turma_aluno.id_turma)";
    $conexao3 = "(avaliacao_turma.id_professor = professor_turma_disciplina.id_prof AND avaliacao_turma.id_turma = professor_turma_disciplina.id_turma AND avaliacao_turma.id_disciplina = professor_turma_disciplina.id_disciplina AND professor_turma_disciplina.id_prof = professor.matricula AND professor_turma_disciplina.id_disciplina = disciplina.id AND professor_turma_disciplina.id_turma = turma.id)";
    $conexao4 = "(avaliacao_turma.id_turma = professor_turma_disciplina.id_turma AND professor_turma_disciplina.id_turma = turma.id AND avaliacao_turma.id_disciplina = professor_turma_disciplina.id_disciplina	AND professor_turma_disciplina.id_disciplina = disciplina.id AND avaliacao_turma.id_professor = professor_turma_disciplina.id_prof AND professor_turma_disciplina.id_prof = professor.matricula)";
    $conexao5 = "(criterios.id = avaliacao_aluno_criterios.id_criterio AND avaliacao_aluno_criterios.id_avaliacao = avaliacao_aluno.id AND avaliacao_aluno.id_professor = professor_turma_disciplina.id_prof AND avaliacao_aluno.id_disciplina = professor_turma_disciplina.id_disciplina AND avaliacao_aluno.id_turma = professor_turma_disciplina.id_turma AND professor_turma_disciplina.id_disciplina = disciplina.id AND professor_turma_disciplina.id_turma = turma.id AND professor_turma_disciplina.id_prof = professor.matricula AND avaliacao_turma.id_professor = professor_turma_disciplina.id_prof)";

    foreach($filtro as $key => $value):
        $where .= " AND $key.nome LIKE :$key";
        $filtro[$key] = "%$value%";
    endforeach;

    $where .= ' AND avaliacao_turma.ano = :ano AND avaliacao_turma.trimestre = :trimestre';
    $filtro['ano'] = $data['ano'];
    $filtro['trimestre'] = $data['trimestre'];

    $qtd_avaliacoes = $dbManager->DQL(
        ['COUNT(avaliacao_turma.id_turma)'],
        ['avaliacao_turma', 'professor_turma_disciplina', 'turma', 'professor', 'disciplina'],
        "$conexao4 $where",
        $filtro,
        PDO::FETCH_BOTH
    );

    if($qtd_avaliacoes[0][0] != '0'){

        if(array_key_exists('turma', $filtro)){

            if(array_key_exists('disciplina', $filtro)){
                $professor = $dbManager->DQL(
                    ['professor.nome as professor'],
                    ['avaliacao_turma', 'professor_turma_disciplina', 'professor', 'turma', 'disciplina'],
                    "$conexao3 $where",
                    $filtro,
                    PDO::FETCH_ASSOC
                );

                $dados['disciplina'] = str_replace("%", "", $filtro['disciplina']);
                $dados['professor'] = iconv("ISO-8859-1", "UTF-8","{$professor[0]['professor']}");
            }else{
                $disciplinas = $dbManager->DQL(
                    ['disciplina.nome as disciplina', 'professor.nome as professor'],
                    ['avaliacao_turma', 'professor_turma_disciplina', 'professor', 'turma', 'disciplina'],
                    "$conexao3 $where",
                    $filtro,
                    PDO::FETCH_ASSOC
                );
    
                $where .= " AND disciplina.nome = :disciplina";

                $filtro['disciplina'] = $disciplinas[$_POST['index']]['disciplina'];
                $dados['professor'] = iconv("ISO-8859-1", "UTF-8","{$disciplinas[$_POST['index']]['professor']}");
                $dados['disciplina'] = iconv("ISO-8859-1", "UTF-8", "{$filtro['disciplina']}");
            }

            $alunos = $dbManager->DQL(
                ['aluno.nome as nome'],
                ['aluno', 'turma', 'turma_aluno'],
                "$conexao2 AND turma.nome LIKE :turma ORDER BY aluno.nome",
                ['turma' => $filtro['turma']],
                PDO::FETCH_BOTH
            );

            $qtd_alunos = $dbManager->DQL(
                ['COUNT(aluno.nome) as quantidade'],
                ['aluno', 'turma', 'turma_aluno'],
                "$conexao2 AND turma.nome LIKE :turma",
                ['turma' => $filtro['turma']],
                PDO::FETCH_ASSOC
            );

            $criterios = $dbManager->DQL(
                ['criterios.nome as nome'],
                ['criterios'],
                "criterios.nome IN(SELECT criterios.nome FROM criterios, avaliacao_aluno_criterios, 
                    avaliacao_aluno, professor_turma_disciplina, turma, disciplina, avaliacao_turma, professor WHERE $conexao5 $where)",
                $filtro,
                PDO::FETCH_BOTH
            );
        
            foreach($criterios as $key => $value):
                $dados['criterios'][] = $value['nome'];
            endforeach;

            $criterios_length = count($criterios);

            for($i = 0; $i < $qtd_alunos[0]['quantidade']; $i++)
            {
                $filtro['aluno_nome'] = $alunos[$i]['nome'];

                $avaliacoes = $dbManager->DQL(
                    ['avaliacao_aluno_criterios.valor as valor'],
                    ['criterios', 'avaliacao_turma', 'avaliacao_aluno_criterios', 'avaliacao_aluno', 'professor_turma_disciplina', 'disciplina', 'turma_aluno', 'aluno', 'professor', 'turma'],
                    "$conexao $where AND aluno.nome = :aluno_nome ORDER BY aluno.nome, criterios.id",
                    $filtro,
                    PDO::FETCH_ASSOC
                );

                $valores = [];

                for($z = 0; $z < $criterios_length; $z++)
                {
                    $valores[] = $avaliacoes[$z]['valor'];
                }

                $turma[$i] = [
                    'nome' => iconv("ISO-8859-1", "UTF-8","{$filtro['aluno_nome']}"),
                    'valores' => $valores
                ];
            }
            
            // $dados['disciplina'] = iconv("ISO-8859-1", "UTF-8", "{$filtro['disciplina']}");
            // $dados['professor'] = iconv("ISO-8859-1", "UTF-8","{$disciplinas[0]['professor']}");
            $dados['qtd_avaliacoes'] = $qtd_avaliacoes[0][0];
            $dados['turma'] = str_replace('%', '',$filtro['turma']);

        }else{

            $turmas = $dbManager->DQL(
                ['disciplina.nome as disciplina', 'turma.nome as turma', 'professor.nome as professor'],
                ['avaliacao_turma', 'professor_turma_disciplina', 'professor', 'turma', 'disciplina'],
                "$conexao3 $where",
                $filtro,
                PDO::FETCH_BOTH
            );

            if(!(array_key_exists('disciplina', $filtro))){
                $where .= " AND disciplina.nome = :disciplina";
                $filtro['disciplina'] = $turmas[$_POST['index']]['disciplina'];
            }
            if(!(array_key_exists('professor', $filtro))){
                $where .= " AND professor.nome = :professor";
                $filtro['professor'] = $turmas[$_POST['index']]['professor'];
            }

            $where .= " AND turma.nome = :turma";
            $filtro['turma'] = $turmas[$_POST['index']]['turma'];

            $alunos = $dbManager->DQL(
                ['aluno.nome as nome'],
                ['aluno', 'turma', 'turma_aluno'],
                "$conexao2 AND turma.nome = :turma ORDER BY aluno.nome",
                ['turma' => $filtro['turma']],
                PDO::FETCH_BOTH
            );

            $qtd_alunos = $dbManager->DQL(
                ['COUNT(aluno.nome) as quantidade'],
                ['aluno', 'turma', 'turma_aluno'],
                "$conexao2 AND turma.nome = :turma",
                ['turma' => $filtro['turma']],
                PDO::FETCH_BOTH
            );

            $avaliacoes = $dbManager->DQL(
                ['aluno.nome as nome', 'avaliacao_aluno_criterios.valor as valor'],
                ['criterios', 'avaliacao_turma', 'avaliacao_aluno_criterios', 'avaliacao_aluno', 'professor_turma_disciplina', 'disciplina', 'turma_aluno', 'aluno', 'professor', 'turma'],
                "$conexao $where ORDER BY aluno.nome, criterios.id",
                $filtro,
                PDO::FETCH_BOTH
            );

            $criterios = $dbManager->DQL(
                ['criterios.nome as nome'],
                ['criterios'],
                "criterios.nome IN(SELECT criterios.nome FROM criterios, avaliacao_aluno_criterios, 
                    avaliacao_aluno, professor_turma_disciplina, turma, disciplina, professor, avaliacao_turma WHERE $conexao5 $where)",
                $filtro,
                PDO::FETCH_BOTH
            );
        
            foreach($criterios as $key => $value):
                $dados['criterios'][] = $value['nome'];
            endforeach;

            $criterios_length = count($criterios);

            for($i = 0; $i < $qtd_alunos[0]['quantidade']; $i++)
            {
                $filtro['aluno_nome'] = $alunos[$i]['nome'];

                $avaliacoes = $dbManager->DQL(
                    ['avaliacao_aluno_criterios.valor as valor'],
                    ['criterios', 'avaliacao_turma', 'avaliacao_aluno_criterios', 'avaliacao_aluno', 'professor_turma_disciplina', 'disciplina', 'turma_aluno', 'aluno', 'professor', 'turma'],
                    "$conexao $where AND aluno.nome = :aluno_nome ORDER BY aluno.nome, criterios.id",
                    $filtro,
                    PDO::FETCH_ASSOC
                );

                $valores = [];

                for($z = 0; $z < $criterios_length; $z++)
                {
                    $valores[] = $avaliacoes[$z]['valor'];
                }

                $turma[$i] = [
                    'nome' => iconv("ISO-8859-1", "UTF-8","{$filtro['aluno_nome']}"),
                    'valores' => $valores
                ];
            }

            $dados['disciplina'] = iconv("ISO-8859-1", "UTF-8", str_replace('%', '', $filtro['disciplina']));
            $dados['professor'] = iconv("ISO-8859-1", "UTF-8","{$turmas[0]['professor']}");
            $dados['qtd_avaliacoes'] = $qtd_avaliacoes[0][0];
            $dados['turma'] = $filtro['turma'];

        }

        print json_encode([$dados, $turma]);
    }else{
        print 'false';
    }