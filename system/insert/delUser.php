<?php

	/* importando arquivos essenciais */

	session_start();
	include "../../db/config.php";
	require "../../db/connection.php";
	require "../../db/database.php";

	/* recuperando informações sobre o usuário logado */	

	$cargo_logado = $_SESSION['cargo'];
	$setor_logado = $_SESSION['setor_usuario'];

	/* recuperando as informaçoes enviadas por POST */

	$email = $_POST['email'];
	$login = $_POST['login'];

	/* iniciando e executando a query */

	$query = "SELECT usuario,cargo,setor_usuario FROM cw_usuarios WHERE usuario = '$login'";

		$dados = mysqli_fetch_assoc(DBExecute($query));

		/* verifica se existe o usuário informado */

		if(!$dados){

			/* se não existir, retorna um erro */

			header('Location: ../../src/del_usuario.php?sucesso=0');

		}else{

			/* esse trexo de código vai verificar a permissão para excluir uma conta */

			if($cargo_logado == 2){ /* caso seja adm, pode excluir qualquer conta */

				$link = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

				$query = "DELETE FROM cw_usuarios WHERE usuario = '$login'";

				mysqli_query($link, $query);

				header('Location: ../../src/del_usuario.php?sucesso=1');

			}else{

				/* caso contrário, sua deve ser do tipo dono, e só poderia excluir usuarios com cargo menor */

				if($dados['cargo'] <= $cargo_logado && $dados['setor_usuario'] == $setor_logado){
					$link = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
					$query = "DELETE FROM cw_usuarios WHERE usuario = '$login'";

					mysqli_query($link, $query);

					header('Location: ../../src/del_usuario.php?sucesso=1');
				}else{

					header('Location: ../../src/del_usuario.php?sucesso=2');

				}

			}

		}
	
?>