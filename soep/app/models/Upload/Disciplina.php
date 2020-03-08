<?php
	require '../../../../vendor/autoload.php';
	use Manager\DB;
	use Manager\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\IOFactory;
	use PhpOffice\PhpSpreadsheet\Helper\Sample;

	$dbManager = new DB('precoc');
    $helper = new Sample();

	$helper->log('Carregando arquivo ' . $_FILES['file']['name']);
	$sheet = IOFactory::load($_FILES['file']['tmp_name'])->getActiveSheet()->toArray(null, true, true, true);

	$table = Spreadsheet::toArray($sheet, Spreadsheet::getColumn($sheet, Spreadsheet::COLUMN_ARRAY));
    $query = Spreadsheet::toString($table, Spreadsheet::getColumn($sheet, Spreadsheet::COLUMN_ARRAY));

    $test = $dbManager->SpreadsheetInsert(
	    'disciplina',
		Spreadsheet::getColumn($sheet, Spreadsheet::COLUMN_STRING),
		$query
	);

	if($test){
		$helper->log("Arquivo " . $_FILES['file']['name'] . " carregado com sucesso");
	}else {
		$helper->log("Arquivo " . $_FILES['file']['name'] . " não foi carregado com sucesso");
	}