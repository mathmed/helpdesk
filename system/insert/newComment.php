<?php
	session_start();

	$id = $_SESSION['id'];


	/* Importando os arquivos de banco de dados */
	include "../../db/config.php";
	require "../../db/connection.php";
	require "../../db/database.php";
	require "../receive/email.class.php";

	/* Recuperando as variáveis vindas do POST */
	$comentario = $_POST['mensagem'];
	$id_chamado = $_POST['id_enviar'];
	$marcados = $_POST['marcados'];

	$date = new DateTime();
	$date = $date->format('Y-m-d H:i:s');

	/* verificando se existem pessoas marcadas */

	$pessoas_marcadas = array();

	foreach($marcados as $key => $value) {
		$explode = explode("-", $value);
		$selecionado = $explode[1];
		$id_pessoa_marcada = $explode[0];

		if($selecionado == 's'){
			
			/* pegando o email no bd */
			$query = "SELECT email FROM cw_usuarios WHERE id = $id_pessoa_marcada";
			$email_pessoa_marcada = mysqli_fetch_assoc(DBExecute($query))['email'];
			
			array_push($pessoas_marcadas, $email_pessoa_marcada);
		}
	}

	/* Query para inserção no banco de dados */

	$query = "INSERT INTO cw_comentarios(id_chamado, comentario, emissor, data_comentario) VALUES ($id_chamado, '$comentario', $id, '$date')";

			/* Caso a query dê certo */

			if(DBExecute($query)){

				/* inserindo alteração no histórico */

				$query = "INSERT INTO cw_historico (responsavel, acao, data_acao, id_chamado) VALUES ($id, 'adicionou um comentário', '$date', $id_chamado ) ";
					DBExecute($query);


				$email = new email();
				$todos_envolvidos = $email->selecionaEnvolvidos($id_chamado);
				$email->sendEmail($todos_envolvidos, $id_chamado, 1, $comentario);

				/* verifica se tem alguma pessoa marcada */

				if($pessoas_marcadas){

					$email->sendEmailMarcacao($pessoas_marcadas, $id_chamado);

				}
				
				header('Location: ../../src/details.php?id='.$id_chamado.'&sucesso=3');


			}else{
				
				header('Location: ../../src/details.php?id='.$id_chamado.'&sucesso=0');
			}

	
?>	