<?php

/* iniciando importações do banco de dados */

include "../../../db/config.php";
require "../../../db/connection.php";
require "../../../db/database.php";


/* recebendo a unidade enviado */

$unidade = $_POST['id_excluir'];

/* trecho de código para verificar se a unidade já existe */

$query = "DELETE FROM cw_unidades WHERE id = $unidade";


/* se ja existir o tipo, retornará um erro */

if(DBExecute($query)){

	header("Location: ../../../src/config.php?status=excluirsucesso");

}

?>
