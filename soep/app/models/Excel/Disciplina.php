<?php
	## MODEL RESPONSAVEL POR GERAR A TABELA EXCEL COM TODAS DISCIPLINAS CADASTRADAS ##
	require '../../../../vendor/autoload.php';
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	use Manager\DB;
	$dbManager = new DB('precoc');
	
	$disciplina = $dbManager->DQL(
		['id, nome, status'],
		['disciplina'],
		PDO::FETCH_ASSOC
	);

	$disciplina = DB::encodeValues($disciplina, 'nome');

	## $spreadsheet ARMAZENA O OBJETO DE Spreadsheet, SENDO CAPAZ DE ARMAZENAR E MODIFICAR 				           ##
	## AS PROPRIEDADES DO SPREADSHEET CRIADO, COMO DEFINIR O CRIADOR, O TITULO, E CRIAR NOVOS WORKSHEET.           ##
	## $writer ARMAZENA O OBJETO DE Xlsx, SENDO CAPAZ DE TRANSFORMAR O OBJETO DE Spreadsheet PARA O FORMATO Xlsx.  ##
	
	$spreadsheet = new Spreadsheet();
	$spreadsheet->getProperties()
		->setCreator("SISTEMA PRECOC");
	
	$spreadsheet->getActiveSheet()
		->setCellValue('A1', 'NOME');
	
	for($count_disciplina = 0; $count_disciplina < count($disciplina); $count_disciplina++){
		
		$spreadsheet->getActiveSheet()
			->setCellValue('A' . strval($count_disciplina+2), $disciplina[$count_disciplina]['nome']);
		
	}
	
	$writer = new Xlsx($spreadsheet);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment; filename="disciplinas.xlsx"');
	$writer->save("php://output");