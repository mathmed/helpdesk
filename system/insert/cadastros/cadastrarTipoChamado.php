<?php

/* iniciando importações do banco de dados */

include "../../../db/config.php";
require "../../../db/connection.php";
require "../../../db/database.php";


/* recebendo o tipo enviado */

$tipo = $_POST['tipo'];

/* trecho de código para verificar se o tipo já existe */

$query = "SELECT * FROM cw_tipos WHERE tipo_chamado = '$tipo'";


/* se ja existir o tipo, retornará um erro */

if(mysqli_fetch_assoc(DBExecute($query))){

	header("Location: ../../../src/config.php?status=tipoexiste");

}else{

	$query = "INSERT INTO cw_tipos (tipo_chamado) VALUES ('$tipo')";
	DBExecute($query);

	header("Location: ../../../src/config.php?status=sucessotipo");


}





?>
