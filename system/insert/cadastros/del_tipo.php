<?php

/* iniciando importações do banco de dados */

include "../../../db/config.php";
require "../../../db/connection.php";
require "../../../db/database.php";


/* recebendo o tipo enviado */

$tipo = $_POST['id_excluir'];

/* trecho de código para verificar se a unidade já existe */

$query = "DELETE FROM cw_tipos WHERE id = $tipo";


/* se ja existir o tipo, retornará um erro */

if(DBExecute($query)){

	header("Location: ../../../src/config.php?status=excluirsucesso");

}

?>
