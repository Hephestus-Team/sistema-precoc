<?php
    require '../../../../vendor/autoload.php';
    use Manager\DB;
    $dbManager = new DB('precoc');

    $data = json_decode($_POST['tempo'], true);
    
    $turma = [];
    $filtro['ano'] = $data['ano'];
    $filtro['trimestre'] = $data['trimestre'];
    $where = ' AND avaliacao_aluno.ano = :ano AND avaliacao_aluno.trimestre = :trimestre';

    $conexao  = "(criterios.id = avaliacao_aluno_criterios.id_criterio AND avaliacao_aluno_criterios.id_avaliacao = avaliacao_aluno.id AND avaliacao_aluno.id_aluno = turma_aluno.id_aluno AND turma_aluno.id_aluno = aluno.matricula AND avaliacao_aluno.id_professor = professor_turma_disciplina.id_prof AND professor_turma_disciplina.id_prof = professor.matricula AND avaliacao_aluno.id_disciplina = professor_turma_disciplina.id_disciplina AND professor_turma_disciplina.id_disciplina = disciplina.id AND professor_turma_disciplina.id_turma = turma.id AND avaliacao_aluno.id_turma = professor_turma_disciplina.id_turma)";
    $conexao1 = "(avaliacao_turma.id_professor = professor_turma_disciplina.id_prof AND professor_turma_disciplina.id_prof = professor.matricula AND avaliacao_turma.id_disciplina = professor_turma_disciplina.id_disciplina AND professor_turma_disciplina.id_disciplina = disciplina.id)";
    $conexao2 = "(aluno.matricula = turma_aluno.id_aluno AND turma.id = turma_aluno.id_turma)";
    $conexao3 = "(avaliacao_turma.id_professor = professor_turma_disciplina.id_prof AND avaliacao_turma.id_turma = professor_turma_disciplina.id_turma AND avaliacao_turma.id_disciplina = professor_turma_disciplina.id_disciplina AND professor_turma_disciplina.id_prof = professor.matricula AND professor_turma_disciplina.id_disciplina = disciplina.id AND professor_turma_disciplina.id_turma = turma.id)";
    $conexao4 = "(avaliacao_turma.id_turma = professor_turma_disciplina.id_turma AND professor_turma_disciplina.id_turma = turma.id AND avaliacao_turma.id_disciplina = professor_turma_disciplina.id_disciplina AND professor_turma_disciplina.id_prof = professor.matricula AND professor_turma_disciplina.id_disciplina = disciplina.id)";
    $conexao5 = "(criterios.id = avaliacao_aluno_criterios.id_criterio AND avaliacao_aluno_criterios.id_avaliacao = avaliacao_aluno.id AND avaliacao_aluno.id_professor = professor_turma_disciplina.id_prof AND avaliacao_aluno.id_disciplina = professor_turma_disciplina.id_disciplina AND avaliacao_aluno.id_turma = professor_turma_disciplina.id_turma AND professor_turma_disciplina.id_disciplina = disciplina.id AND professor_turma_disciplina.id_turma = turma.id AND professor_turma_disciplina.id_prof = professor.matricula AND avaliacao_turma.id_professor = professor_turma_disciplina.id_prof)";

    $qtd_avaliacoes = $dbManager->DQL(
        ['COUNT(avaliacao_turma.id_turma) as quantidade'],
        ['avaliacao_turma', 'professor_turma_disciplina', 'turma', 'professor', 'disciplina'],
        "$conexao4 AND avaliacao_turma.ano = :ano AND avaliacao_turma.trimestre = :trimestre",
        $filtro,
        PDO::FETCH_BOTH
    );

    if($qtd_avaliacoes[0]['quantidade'] != '0'){
        $turmas = $dbManager->DQL(
            ['disciplina.nome as disciplina', 'professor.nome as professor', 'turma.nome as turma'],
            ['avaliacao_turma', 'professor_turma_disciplina', 'professor', 'turma', 'disciplina'],
            "$conexao3 AND avaliacao_turma.ano = :ano AND avaliacao_turma.trimestre = :trimestre",
            $filtro,
            PDO::FETCH_BOTH
        );

        $where .= ' AND disciplina.nome = :disciplina AND turma.nome = :turma AND professor.nome = :professor';
        $filtro['disciplina'] = $turmas[$_POST['index']]['disciplina'];
        $filtro['turma'] = $turmas[$_POST['index']]['turma'];
        $filtro['professor'] = $turmas[$_POST['index']]['professor'];

        $alunos = $dbManager->DQL(
            ['aluno.nome as nome', 'aluno.matricula as matricula'],
            ['aluno', 'turma', 'turma_aluno'],
            "$conexao2 AND turma.nome = :turma ORDER BY aluno.nome",
            ['turma' => $filtro['turma']],
            PDO::FETCH_ASSOC
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
            ['criterios', 'avaliacao_aluno_criterios', 'avaliacao_aluno', 'professor_turma_disciplina', 'disciplina', 'turma_aluno', 'aluno', 'professor', 'turma'],
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
        $where .= ' AND aluno.matricula = :matricula';

        for($i = 0; $i < $qtd_alunos[0]['quantidade']; $i++)
        {
            $filtro['matricula'] = $alunos[$i]['matricula'];

            $avaliacoes = $dbManager->DQL(
                ['avaliacao_aluno_criterios.valor as valor'],
                ['criterios', 'avaliacao_turma', 'avaliacao_aluno_criterios', 'avaliacao_aluno', 'professor_turma_disciplina', 'disciplina', 'turma_aluno', 'aluno', 'professor', 'turma'],
                "$conexao $where ORDER BY aluno.nome, criterios.id",
                $filtro,
                PDO::FETCH_BOTH
            );

            $valores = [];

            for($z = 0; $z < $criterios_length; $z++)
            {
                $valores[] = $avaliacoes[$z]['valor'];
            }

            $turma[$i] = [
                'nome' => $alunos[$i]['nome'],
                'valores' => $valores
            ];
        }



        $dados['disciplina'] = iconv("ISO-8859-1", "UTF-8", "{$filtro['disciplina']}");
        $dados['professor'] = iconv("ISO-8859-1", "UTF-8", "{$filtro['professor']}");
        $dados['qtd_avaliacoes'] = $qtd_avaliacoes[0]['quantidade'];
        $dados['turma'] = $filtro['turma'];
        
        $turma = DB::encodeValues($turma, 'nome');
        print json_encode([$dados, $turma], 256);

    }else{
        print 'false';
    }