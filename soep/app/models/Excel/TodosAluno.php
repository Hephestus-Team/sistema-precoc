<?php
	## MODEL RESPONSAVEL POR GERAR A TABELA EXCEL COM TODOS ALUNOS CADASTRADOS ##
	require '../../../../vendor/autoload.php';
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	use Manager\DB;
	$dbManager = new DB('precoc');

	$conexao1 = "(aluno.matricula = turma_aluno.id_aluno AND turma.id = turma_aluno.id_turma)";

	## $spreadsheet ARMAZENA O OBJETO DE Spreadsheet, SENDO CAPAZ DE ARMAZENAR E MODIFICAR 				           ##
	## AS PROPRIEDADES DO SPREADSHEET CRIADO, COMO DEFINIR O CRIADOR, O TITULO, E CRIAR NOVOS WORKSHEET.           ##
	## $writer ARMAZENA O OBJETO DE Xlsx, SENDO CAPAZ DE TRANSFORMAR O OBJETO DE Spreadsheet PARA O FORMATO Xlsx.  ##

	$spreadsheet = new Spreadsheet();
	$spreadsheet->getProperties()
		->setCreator("SISTEMA PRECOC");
	
	## DESTE JEITO CRIARA UM EXCEL COM CADA TURMA DIVIDIDA ##

	// $turmas = $dbManager->DQL(
	// 	['nome'],
	// 	['turma'],
	// 	'1 ORDER BY turma.id',
	// 	[],
	// 	PDO::FETCH_ASSOC
	// );

	// for($count_turma = 0; $count_turma < count($turmas); $count_turma++){

	// 	$spreadsheet->setActiveSheetIndex($count_turma);

	// 	$spreadsheet->getActiveSheet()
	// 	->setCellValue('A1', '#')
	// 	->setCellValue('B1', 'MATRICULA')
	// 	->setCellValue('C1', 'NOME')
	// 	->setCellValue('D1', 'TURMA');

	// 	$alunos = $dbManager->DQL(
	// 		['aluno.nome as nome', 'aluno.matricula as matricula'],
	// 		['aluno', 'turma', 'turma_aluno'],
	// 		"$conexao1 AND turma.nome = :turma ORDER BY aluno.nome",
	// 		['turma' => $turmas[$count_turma]['nome']],
	// 		PDO::FETCH_ASSOC
	// 	);

	// 	for($count_aluno = 0; $count_aluno < count($alunos); $count_aluno++){
	// 		$spreadsheet->getActiveSheet()
	// 			->setCellValue("A" . strval($count_aluno+2), $count_aluno+1)
	// 			->setCellValue("B" . strval($count_aluno+2), $alunos[$count_aluno]['matricula'])			
	// 			->setCellValue("C" . strval($count_aluno+2), iconv("ISO-8859-1", "UTF-8", "{$alunos[$count_aluno]['nome']}"))
	// 			->setCellValue("D" . strval($count_aluno+2), $turmas[$count_turma]['nome']);       
	// 	}
	
	// 	$spreadsheet->getActiveSheet()
	// 		->setTitle($turmas[$count_turma]['nome']);
	
	// 	if($count_turma != count($turmas)-1){
	// 		$spreadsheet->createSheet();	
	// 	}	
	// }

	## DESTE JEITO CRIARA UM EXCEL COM TODAS AS TURMAS JUNTAS ##

	$spreadsheet->getActiveSheet()
		->setCellValue('A1', '#')
		->setCellValue('B1', 'MATRICULA')
		->setCellValue('C1', 'NOME')
		->setCellValue('D1', 'TURMA');

	$alunos = $dbManager->DQL(
		['aluno.nome as nome', 'aluno.matricula as matricula', 'turma.nome as turma'],
		['aluno', 'turma', 'turma_aluno'],
		"$conexao1 ORDER BY turma.nome, aluno.nome",
		[],
		PDO::FETCH_ASSOC
	);

	for($count_aluno = 0; $count_aluno < count($alunos); $count_aluno++){
		$spreadsheet->getActiveSheet()
			->setCellValue("A" . strval($count_aluno+2), $count_aluno+1)
			->setCellValue("B" . strval($count_aluno+2), $alunos[$count_aluno]['matricula'])			
			->setCellValue("C" . strval($count_aluno+2), iconv("ISO-8859-1", "UTF-8", "{$alunos[$count_aluno]['nome']}"))
			->setCellValue("D" . strval($count_aluno+2), $alunos[$count_aluno]['turma']);    
	}

	$writer = new Xlsx($spreadsheet);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header("Content-Disposition: attachment; filename=turmas.xlsx");
	$writer->save("php://output");