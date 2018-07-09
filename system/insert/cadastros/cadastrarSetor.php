<?php

/* iniciando importações do banco de dados */

include "../../../db/config.php";
require "../../../db/connection.php";
require "../../../db/database.php";


/* recebendo o setor enviado */

$setor = $_POST['setor'];

/* trecho de código para verificar se o setor já existe */

$query = "SELECT * FROM cw_setores WHERE setor = '$setor'";


/* se ja existir o setor, retornará um erro */

if(mysqli_fetch_assoc(DBExecute($query))){

	header("Location: ../../../src/config.php?status=setorexiste");

}else{

	$query = "INSERT INTO cw_setores (setor) VALUES ('$setor')";
	DBExecute($query);

	header("Location: ../../../src/config.php?status=sucessosetor");


}

?>
