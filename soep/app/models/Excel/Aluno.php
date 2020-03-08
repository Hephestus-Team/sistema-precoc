<?php
	## MODEL RESPONSAVEL POR GERAR A TABELA EXCEL COM OS ALUNOS CADASTRADOS DE TAL TURMA ##
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
		
	$spreadsheet->getActiveSheet()->setTitle($_GET['nome']);

	$alunos = $dbManager->DQL(
		['aluno.nome as nome'],
		['aluno', 'turma', 'turma_aluno'],
		"$conexao1 AND turma.id = :turma ORDER BY aluno.nome",
		['turma' => $_GET['id']],
		PDO::FETCH_ASSOC
    );
	
	$alunos = DB::encodeValues($alunos, 'nome');

	$spreadsheet->getActiveSheet()
		->setCellValue('A1', '#')
		->setCellvalue('B1', 'NOME');

	for($count = 0; $count < count($alunos); $count++){
		$spreadsheet->getActiveSheet()
			->setCellValue("A" . strval($count+2), $count+1)
			->setCellvalue("B" . strval($count+2), $alunos[$count]['nome']);
	}

	$writer = new Xlsx($spreadsheet);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header("Content-Disposition: attachment; filename=\"{$_GET['nome']}\".xlsx");
	$writer->save("php://output");