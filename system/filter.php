<?php

	/* recuperando arquivos essencias do banco de dados */

	session_start();
	require "../db/config.php";
	require "../db/connection.php";
	require "../db/database.php";

	/* recuperando informações enviadas via post */

	$dateIn = $_POST['dateIn'];
	$dateFn = $_POST['dateFn'];
	$setor = $_POST['setor'];
	$status = $_POST['status'];
	$tipo = $_POST['tipo'];

	/* início de verificações para retornar a url com filtro */

	if($setor == 'Todos'){
		if($status != 0){
			$location = "Location: ../src/home.php?dateIn=".$dateIn."&dateFn=".$dateFn."&status=".$status."&tipo=".$tipo;
		}else{
			$location = "Location: ../src/home.php?dateIn=".$dateIn."&dateFn=".$dateFn."&tipo=".$tipo;
		}
	}else{
		$query = "SELECT id FROM cw_setores WHERE setor = '$setor'";
		$setor = mysqli_fetch_assoc(DBExecute($query))['id'];

		if($status !=0 ){
			$location = "Location: ../src/home.php?dateIn=".$dateIn."&dateFn=".$dateFn."&setor=".$setor."&status=".$status."&tipo=".$tipo;
		}else{
			$location = "Location: ../src/home.php?dateIn=".$dateIn."&dateFn=".$dateFn."&setor=".$setor."&status=".$status."&tipo=".$tipo;
		}

	}
	header($location);
?>