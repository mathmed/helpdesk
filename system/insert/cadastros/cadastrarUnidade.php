<?php

/* iniciando importações do banco de dados */

include "../../../db/config.php";
require "../../../db/connection.php";
require "../../../db/database.php";


/* recebendo a unidade enviado */

$unidade = $_POST['unidade'];

/* trecho de código para verificar se a unidade já existe */

$query = "SELECT * FROM cw_unidades WHERE unidade = '$unidade'";


/* se ja existir o tipo, retornará um erro */

if(mysqli_fetch_assoc(DBExecute($query))){

	header("Location: ../../../src/config.php?status=unidadeexiste");

}else{

	$query = "INSERT INTO cw_unidades (unidade) VALUES ('$unidade')";
	DBExecute($query);

	header("Location: ../../../src/config.php?status=sucessounidade");


}

?>
