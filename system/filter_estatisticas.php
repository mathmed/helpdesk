<?php
	/* recuperando arquivos essencias do banco de dados */
	require "../db/config.php";
	require "../db/connection.php";
	require "../db/database.php";

	/* recuperando informações enviadas via post */
	$dateIn = $_POST['dateIn'];
	$dateFn = $_POST['dateFn'];

	/* retornando a url */

	$location = "Location: ../src/estatisticas.php?dateIn=".$dateIn."&dateFn=".$dateFn;
	header($location);
?>