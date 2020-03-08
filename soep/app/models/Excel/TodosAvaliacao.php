<?php
	## MODEL RESPONSAVEL POR GERAR A TABELA EXCEL COM TODAS AS AVALIACOES CADASTRADAS ##
    require '../../../../vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use Manager\DB;
	$dbManager = new DB('precoc');

	$conexao  = "(professor_turma_disciplina.id_disciplina = disciplina.id AND professor_turma_disciplina.id_turma = turma.id)";
	$conexao1 = "(aluno.matricula = turma_aluno.id_aluno AND turma.id = turma_aluno.id_turma)";
	$conexao3 = "(criterios.id = avaliacao_aluno_criterios.id_criterio AND avaliacao_aluno_criterios.id_avaliacao = avaliacao_aluno.id AND avaliacao_aluno.id_aluno = turma_aluno.id_aluno AND turma_aluno.id_aluno = aluno.matricula AND avaliacao_aluno.id_professor = professor_turma_disciplina.id_prof AND professor_turma_disciplina.id_prof = professor.matricula AND avaliacao_aluno.id_disciplina = professor_turma_disciplina.id_disciplina AND professor_turma_disciplina.id_disciplina = disciplina.id)";
	$conexao4 = "(criterios.id = avaliacao_aluno_criterios.id_criterio AND avaliacao_aluno_criterios.id_avaliacao = avaliacao_aluno.id AND avaliacao_aluno.id_aluno = turma_aluno.id_aluno AND turma_aluno.id_aluno = aluno.matricula AND avaliacao_aluno.id_disciplina = professor_turma_disciplina.id_disciplina AND professor_turma_disciplina.id_disciplina = disciplina.id AND avaliacao_aluno.id_turma = turma_aluno.id_turma AND turma_aluno.id_turma = turma.id)";
	$alphabet = [];
	$total_aluno = 0;
	for ($i = 'C'; $i !== 'AJ'; $i++){
		$alphabet[] = $i;
	}
	
	$turmas = $dbManager->DQL(
		['turma.nome as nome'],
		['turma'],
		PDO::FETCH_ASSOC
	);

	$disciplinas = $dbManager->DQL(
		['disciplina.nome as nome'],
		['disciplina'],
		PDO::FETCH_ASSOC
	);

	$count_turma = count($turmas);
	$count_disciplina = count($disciplinas);

	## $spreadsheet ARMAZENA O OBJETO DE Spreadsheet, SENDO CAPAZ DE ARMAZENAR E MODIFICAR 				           ##
	## AS PROPRIEDADES DO SPREADSHEET CRIADO, COMO DEFINIR O CRIADOR, O TITULO, E CRIAR NOVOS WORKSHEET.           ##
	## $writer ARMAZENA O OBJETO DE Xlsx, SENDO CAPAZ DE TRANSFORMAR O OBJETO DE Spreadsheet PARA O FORMATO Xlsx.  ##

    $spreadsheet = new Spreadsheet();
    $spreadsheet->getProperties()
		->setCreator("SISTEMA PRECOC");

	$spreadsheet->getActiveSheet()
		->setCellvalue('A1', 'NOME')
		->setCellvalue('B1', 'TURMA');

	for($i = 0; $i < $count_disciplina; $i++){
		$spreadsheet->getActiveSheet()
			->setCellValue("{$alphabet[$i]}1", iconv("ISO-8859-1", "UTF-8", $disciplinas[$i]['nome']));
	}

    for($i = 0; $i < $count_turma; $i++){

		$alunos = $dbManager->DQL(
			['aluno.nome as nome'],
			['aluno', 'turma', 'turma_aluno'],
			"$conexao1 AND turma.nome = :turma ORDER BY aluno.nome",
			['turma' => $turmas[$i]['nome']],
			PDO::FETCH_ASSOC
		);

		$count_aluno = count($alunos);

		for($z = 0; $z < $count_aluno; $z++){

			$spreadsheet->getActiveSheet()
				->setCellValue("A" . strval(2+$total_aluno), iconv("ISO-8859-1", "UTF-8", $alunos[$z]['nome']))
				->setCellvalue("B" . strval(2+$total_aluno), $turmas[$i]['nome']);

			for($y = 0; $y < $count_disciplina; $y++){

				// $avaliacoes = $dbManager->DQL(
				// 	['avaliacao_aluno_criterios.valor as valor', 'criterios.nome as criterio'],
				// 	['criterios', 'avaliacao_aluno_criterios', 'avaliacao_aluno', 'professor_turma_disciplina', 'disciplina', 'turma_aluno', 'aluno', 'professor', 'turma'],
				// 	"$conexao3 AND turma.nome = :turma AND disciplina.nome = :disciplina AND avaliacao_aluno.ano = :ano AND avaliacao_aluno.trimestre = :trimestre ORDER BY aluno.nome, criterios.id",
				// 	['turma' => $turmas[$i]['nome'], 'disciplina' => $disciplinas[$z]['nome'], 'trimestre' => $_GET['trimestre'], 'ano' => $_GET['ano']],
				// 	PDO::FETCH_BOTH
				// );

				$criterios = $dbManager->DQL(
					['criterios.nome as nome', 'avaliacao_aluno_criterios.valor as valor'],
					['criterios', 'avaliacao_aluno_criterios', 'avaliacao_aluno', 'turma_aluno', 'aluno', 'professor_turma_disciplina', 'disciplina', 'turma'],
					"$conexao4 AND aluno.nome = :aluno AND disciplina.nome = :disciplina AND turma.nome = :turma AND avaliacao_aluno.ano = :ano AND avaliacao_aluno.trimestre = :trimestre GROUP BY criterios.nome",
					['aluno' => $alunos[$z]['nome'], 'disciplina' => $disciplinas[$y]['nome'], 'turma' => $turmas[$i]['nome'], 'trimestre' => $_GET['trimestre'], 'ano' => $_GET['ano']],
					PDO::FETCH_BOTH
				);

				$count_criterio = count($criterios);

				$string = ',';

				for($x = 0; $x < $count_criterio; $x++){

					if($criterios[$x]['valor'] == '1'){
						$string .= "{$criterios[$x]['nome']}|";
						$spreadsheet->getActiveSheet()
							->setCellValue("{$alphabet[$z]}" . strval($y+2+$total_aluno), $string);
					}
				}

			}
			$total_aluno++;
		}		
	}

	// print_r($disciplinas);

    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="precoc.xlsx"');
    $writer->save("php://output");