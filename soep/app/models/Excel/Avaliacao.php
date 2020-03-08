<?php
	## MODEL RESPONSAVEL POR GERAR A TABELA EXCEL COM AS AVALIACOES CADASTRADAS DE UMA DADA TURMA ##
	require '../../../../vendor/autoload.php';
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	use Manager\DB;
	$dbManager = new DB('precoc');

	$conexao  = "(professor_turma_disciplina.id_disciplina = disciplina.id AND professor_turma_disciplina.id_turma = turma.id)";
	$conexao1 = "(aluno.matricula = turma_aluno.id_aluno AND turma.id = turma_aluno.id_turma)";
	$conexao3 = "(criterios.id = avaliacao_aluno_criterios.id_criterio AND avaliacao_aluno_criterios.id_avaliacao = avaliacao_aluno.id AND avaliacao_aluno.id_aluno = turma_aluno.id_aluno AND turma_aluno.id_aluno = aluno.matricula AND avaliacao_aluno.id_professor = professor_turma_disciplina.id_prof AND professor_turma_disciplina.id_prof = professor.matricula AND avaliacao_aluno.id_disciplina = professor_turma_disciplina.id_disciplina AND professor_turma_disciplina.id_disciplina = disciplina.id)";
	$conexao4 = "(criterios.id = avaliacao_aluno_criterios.id_criterio AND avaliacao_aluno_criterios.id_avaliacao = avaliacao_aluno.id AND avaliacao_aluno.id_aluno = turma_aluno.id_aluno AND turma_aluno.id_aluno = aluno.matricula AND avaliacao_aluno.id_disciplina = professor_turma_disciplina.id_disciplina AND professor_turma_disciplina.id_disciplina = disciplina.id AND avaliacao_aluno.id_turma = turma_aluno.id_turma AND turma_aluno.id_turma = turma.id)";
	$alphabet = "CDEFGHIJKLMNOPQRSTUWXYZ";

	## $spreadsheet ARMAZENA O OBJETO DE Spreadsheet, SENDO CAPAZ DE ARMAZENAR E MODIFICAR 				           ##
	## AS PROPRIEDADES DO SPREADSHEET CRIADO, COMO DEFINIR O CRIADOR, O TITULO, E CRIAR NOVOS WORKSHEET.           ##
	## $writer ARMAZENA O OBJETO DE Xlsx, SENDO CAPAZ DE TRANSFORMAR O OBJETO DE Spreadsheet PARA O FORMATO Xlsx.  ##

	$spreadsheet = new Spreadsheet();
	$spreadsheet->getProperties()
		->setCreator("SISTEMA PRECOC");
		
	$spreadsheet->setActiveSheetIndex(0)->setTitle($_GET['turma']);

	$alunos = $dbManager->DQL(
		['aluno.nome as nome'],
		['aluno', 'turma', 'turma_aluno'],
		"$conexao1 AND turma.nome = :turma ORDER BY aluno.nome",
		['turma' => $_GET['turma']],
		PDO::FETCH_BOTH
	);

	$disciplinas = $dbManager->DQL(
		['disciplina.nome as nome'],
		['professor_turma_disciplina', 'turma', 'disciplina'],
		"$conexao AND turma.nome = :turma",
		['turma' => $_GET['turma']],
		PDO::FETCH_BOTH
	);

	$spreadsheet->getActiveSheet()
		->setCellValue('A1', '#')
		->setCellvalue('B1', 'NOME');

	for($count_disciplina = 0; $count_disciplina < count($disciplinas); $count_disciplina++){
		
		$avaliacoes = $dbManager->DQL(
			['aluno.nome as nome', 'avaliacao_aluno_criterios.valor as valor', 'criterios.nome as criterio'],
			['criterios', 'avaliacao_aluno_criterios', 'avaliacao_aluno', 'professor_turma_disciplina', 'disciplina', 'turma_aluno', 'aluno', 'professor', 'turma'],
			"$conexao3 AND turma.nome = :turma AND disciplina.nome = :disciplina AND avaliacao_aluno.ano = :ano AND avaliacao_aluno.trimestre = :trimestre ORDER BY aluno.nome, criterios.id",
			['turma' => $_GET['turma'], 'disciplina' => $disciplinas[$count_disciplina]['nome'], 'trimestre' => $_GET['trimestre'], 'ano' => $_GET['ano']],
			PDO::FETCH_BOTH
		);
		
		$spreadsheet->getActiveSheet()
			->setCellValue("{$alphabet[$count_disciplina]}1", iconv("ISO-8859-1", "UTF-8", "{$disciplinas[$count_disciplina]['nome']}"));
		
		for($count_aluno = 0; $count_aluno < count($alunos); $count_aluno++){

			$spreadsheet->getActiveSheet()
				->setCellValue("A" . strval($count_aluno+2), $count_aluno+1)
				->setCellvalue("B" . strval($count_aluno+2), iconv("ISO-8859-1", "UTF-8", "{$alunos[$count_aluno]['nome']}"));
			
			$criterios = $dbManager->DQL(
				['criterios.nome as nome', 'avaliacao_aluno_criterios.valor as valor'],
				['criterios', 'avaliacao_aluno_criterios', 'avaliacao_aluno', 'turma_aluno', 'aluno', 'professor_turma_disciplina', 'disciplina', 'turma'],
				"$conexao4 AND aluno.nome = :aluno AND disciplina.nome = :disciplina AND turma.nome = :turma AND avaliacao_aluno.ano = :ano AND avaliacao_aluno.trimestre = :trimestre GROUP BY criterios.nome",
				['aluno' => $alunos[$count_aluno]['nome'], 'disciplina' => $disciplinas[$count_disciplina]['nome'], 'turma' => $_GET['turma'], 'trimestre' => $_GET['trimestre'], 'ano' => $_GET['ano']],
				PDO::FETCH_BOTH
			);
			
			$string = '|';
			
			for($count_criterio = 0; $count_criterio < count($criterios); $count_criterio++){
				
				if($criterios[$count_criterio]['valor'] == '1'){
					$string .= iconv("ISO-8859-1", "UTF-8", "{$criterios[$count_criterio]['nome']}|");
					$spreadsheet->getActiveSheet()
					->setCellValue("{$alphabet[$count_disciplina]}" . strval($count_aluno+2), $string);
				}
			}
		}
	}

	$writer = new Xlsx($spreadsheet);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header("Content-Disposition: attachment; filename=\"{$_GET['turma']}\".xlsx");
	$writer->save("php://output");