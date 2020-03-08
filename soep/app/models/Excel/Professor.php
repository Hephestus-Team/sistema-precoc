<?php
	## MODEL RESPONSAVEL POR GERAR A TABELA EXCEL COM TODOS PROFESSORES CADASTRADOS ##
    require '../../../../vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\IOFactory;
    use Manager\DB;
	$dbManager = new DB('administrador');
	
	$professores = $dbManager->DQL(
		['nome, matricula, email'],
		['professores'],
		"1 ORDER BY nome",
		[],
		PDO::FETCH_BOTH
	);

	## $spreadsheet ARMAZENA O OBJETO DE Spreadsheet, SENDO CAPAZ DE ARMAZENAR E MODIFICAR 				           ##
	## AS PROPRIEDADES DO SPREADSHEET CRIADO, COMO DEFINIR O CRIADOR, O TITULO, E CRIAR NOVOS WORKSHEET.           ##
	## $writer ARMAZENA O OBJETO DE Xlsx, SENDO CAPAZ DE TRANSFORMAR O OBJETO DE Spreadsheet PARA O FORMATO Xlsx.  ##

    $spreadsheet = new Spreadsheet();
    $spreadsheet->getProperties()
	  ->setCreator("SISTEMA PRECOC");

	$spreadsheet->getActiveSheet()
	  ->setCellValue('A1', 'MATRICULA')
	  ->setCellValue('B1', 'NOME')
	  ->setCellValue('C1', 'EMAIL');
	  
	for($count_professor = 0; $count_professor < count($professores); $count_professor++){
		$spreadsheet->getActiveSheet()
		->setCellValue('A' . strval($count_professor+2), $professores[$count_professor]['matricula'])
		->setCellValue('B' . strval($count_professor+2), $professores[$count_professor]['nome'])
		->setCellValue('C' . strval($count_professor+2), $professores[$count_professor]['email']);
	}

    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="professores.xlsx"');
    $writer->save("php://output");