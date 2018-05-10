<?php
	
	/* recuperando arquivos essencias do banco de dados */

	session_start();
	require "../db/config.php";
	require "../db/connection.php";
	require "../db/database.php";

	/* recuperando informações enviadas via post */

	$usuario = $_POST['usuario'];
	$senha = $_POST['senha'];

	/* iniciando a query para verificar se o usuario existe */

	$query = "SELECT * from cw_usuarios WHERE usuario = '$usuario' AND senha = '$senha' ";

	/* Executando a query */

	$object = DBExecute($query);

	/* verificando se existe o usuário com os dados informados (verificação com > 0) */

	if($object->num_rows > 0){

		$dados_usuario = mysqli_fetch_array($object);

		if($dados_usuario){

			/* caso exista, atribuir suas informações às variáveis super globais */

			$_SESSION['setor_usuario'] = $dados_usuario['setor'];
			$_SESSION['usuario'] = $dados_usuario['usuario'];
			$_SESSION['nome'] = $dados_usuario['nome'];
			$_SESSION['email'] = $dados_usuario['email'];
			$_SESSION['id'] = $dados_usuario['id'];
			$_SESSION['cargo'] = $dados_usuario['cargo'];
			$_SESSION['sede'] = $dados_usuario['sede'];
			
			/* se tudo ocorrer bem, redirecionar para a página inicial */

			header('Location: ../src/home.php');

		}

	}else{

		/* caso não existe o usuário, retornar uma mensagem de erro */

		header('Location: ../index.php?erro=1'); 

	}
	

?>