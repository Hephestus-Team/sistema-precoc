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

	$turma = [];
	$turma_aluno = [];
	$aluno = [];
	$z = 1;
	$id_turma = 1;
	$count_table = count($table);

	for($i = 0; $i < $count_table; $i++){
		$aluno[$i]['nome'] = $table[$i]['nome'];
		$aluno[$i]['matricula'] = $table[$i]['matricula'];
		$turma_aluno[$i]['id_aluno'] = $table[$i]['matricula'];	

		if($i < $count_table){
			if($table[$i]['turma'] != $table[$i+1]['turma']){
				$turma[$z-1]['nome'] = $table[$i]['turma'];
				$turma[$z-1]['id'] = $z;
				$z++;
			}
			if($i != 0){
				if($table[$i]['turma'] != $table[$i-1]['turma']){
					$id_turma++;
				}
			}
		}

		$turma_aluno[$i]['id_turma'] = $id_turma;
	}

	$query_aluno = iconv("UTF-8", "ISO-8859-1", Spreadsheet::toString($aluno, ['nome', 'matricula']));
	$query_turma_aluno = Spreadsheet::toString($turma_aluno, ['id_aluno', 'id_turma']);
	$query_turma = Spreadsheet::toString($turma, ['nome', 'id']);

	$test1 = $dbManager->SpreadsheetInsert(
		'aluno',
		'nome, matricula',
		"$query_aluno"
	);

	$test2 = $dbManager->SpreadSheetInsert(
		'turma_aluno',
		'id_aluno, id_turma',
		"$query_turma_aluno"
	);

	$test = $dbManager->SpreadsheetInsert(
		'turma',
		'nome, id',
        $query_turma
	);

	// CASO ESTEJA MUITO LENTO O SERVIDOR //

	// $aluno = array_chunk($aluno, 200);
	// $turma_aluno = array_chunk($turma_aluno, 200);

	// for($i = 0; $i < count($aluno); $i++){
	// 	$query_aluno = Spreadsheet::toString($aluno[$i], ['nome', 'matricula']);
	// 	$query_turma_aluno = Spreadsheet::toString($turma_aluno[$i], ['id_aluno', 'id_turma']);

	// 	$dbManager->SpreadsheetInsert(
	// 		'aluno',
	// 		'nome, matricula',
	// 		"$query_aluno"
	// 	);

	// $dbManager->SpreadSheetInsert(
	// 	'turma_aluno',
	// 	'id_aluno, id_turma',
	// 	"$query_turma_aluno"
	// );
	// }

	// ================================ //

	if($test == true && $test1 == true && $test2 == true){
		$helper->log("Arquivo " . $_FILES['file']['name'] . " carregado com sucesso");
	}else {
		$helper->log("Arquivo " . $_FILES['file']['name'] . " não foi carregado com sucesso");
	}
