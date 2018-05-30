<?php
	/* recuperando arquivos essencias do banco de dados */
	session_start();
	require "../db/config.php";
	require "../db/connection.php";
	require "../db/database.php";

	/* recuperando informações enviadas via post */
	
	$dateIn = $_POST['dateIn'];
	$dateFn = $_POST['dateFn'];
	

	/* verifica se ja existe filtro de data */
	
	if(strpos($_SESSION['url_atual'], 'dateIn') > 0){

		$url = explode("&dateIn", $_SESSION['url_atual']);
		$url = $url[0];
	}else{
		$url = $_SESSION['url_atual'];
	}
	
	$url  = explode("src", $url);
	$location = "Location: ../src".$url[1]."&dateIn=".$dateIn."&dateFn=".$dateFn;
	header($location);
?>
