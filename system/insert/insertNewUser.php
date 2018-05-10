<?php

	/* importando arquivos essenciais */

	session_start();
	
	include "../../db/config.php";
	require "../../db/connection.php";
	require "../../db/database.php";
	require "../receive/email.class.php";

	/* recuperando as informaçoes enviadas por POST */

	$email = $_POST['email'];
	$login = $_POST['login'];
	$senha = $_POST['senha'];
	$nome = $_POST['nome'];
	$setor = $_POST['setor'];
	$cargo = $_POST['cargo'];
	$sede = $_POST['sede'];


	if($setor) {

		/* verificando se o usuário já existe */

		$query = "SELECT usuario FROM cw_usuarios WHERE usuario = '$login'";


		/* se existe, enviar uma mensagem de erro */

		if((mysqli_fetch_assoc(DBExecute($query)))){
			header('Location: ../../src/novousuario.php?sucesso=0');

		}else{

			$query = "INSERT INTO cw_usuarios(email, usuario, senha, nome, setor, cargo, sede)VALUES
			('$email', '$login', '$senha', '$nome', $setor, $cargo, $sede)";

			/* caso contrário, adicionar o usuário */
			DBExecute($query);

			/* enviar um email informando que o usuário foi cadastrado */
			
			$obj = new email();
			$todos_envolvidos = array();
			$aux = array();

			$aux['email'] = $email;
			$aux['nome'] = $nome;

			array_push($todos_envolvidos, $aux);
			$dados = array('login' => $login, 'senha' => $senha, 'nome' => $nome);
			$obj->sendEmail($todos_envolvidos, $id_chamado, 4, $dados);

			/* returning with a success message */

			header('Location: ../../src/novousuario.php?sucesso=1');
		}
	}
?>