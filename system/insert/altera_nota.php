<?php

/* importando arquivos essenciais */

	session_start();
	include "../../db/config.php";
	require "../../db/connection.php";
	require "../../db/database.php";

	/* recebendo informação via post */

	$nota = $_POST['nota'];
	$id = $_POST['id'];

	/* iniciando a query */
	$query = "UPDATE cw_chamados SET nota = $nota WHERE id = $id";

	/* executanto a query */

	DBExecute($query);

?>