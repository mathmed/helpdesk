<?php

	/* importando arquivos essenciais */

	session_start();
	include "../../db/config.php";
	require "../../db/connection.php";
	require "../../db/database.php";
	require "../receive/email.class.php";

	/* recuperando informações sobre o usuário logado */	

	$setor_usuario = $_SESSION['setor_usuario'];
	$cargo_logado = $_SESSION['cargo'];
	$id = $_SESSION['id'];

	/* recuperando as informaçoes enviadas por POST */

	$descricao = $_POST['descricao'];
	$tipo = $_POST['tipo'];
	$unidade = $_POST['unidade'];
	$setor = $_POST['setor'];
	$emissor = $_POST['solicitante'];

	/* criando uma data atual */	

	$date = new DateTime();
	$date = $date->format('Y-m-d H:i:s');

	/* criando uma data default (prazo) */

	$prazo = date('Y-m-d', strtotime('+3 days'));
	$prazo = date($prazo);


	/* verificando quem será o responsável por atender o chamado  */
	/* aqui é levado em consideração que só há um atendente por tipo de chamado, se houver mais que um, atualizar este código à vontade
	para definir quem será o responsável pelo chamado */

	$query = "SELECT id FROM cw_usuarios WHERE cargo = $tipo AND sede = $unidade";
	$responsavel = mysqli_fetch_assoc(DBExecute($query))['id'];


	/* verificando se existe responsável disponível */

	if($responsavel){

		/* inicializando e executando querys */

		/* trecho de código para verificar se existe emissor (somente para casos em que é usada conta partilhada) */

		if(!$emissor){
			$query = "INSERT INTO cw_chamados(descricao, tipo, unidade, setor, data_chamado, status_atual, emissor, responsavel, grau, prazo, nota)VALUES('$descricao', '$tipo', $unidade, $setor, '$date', 'Andamento', $id, $responsavel, 0, '$prazo', 0)";
		}else{
			$query = "INSERT INTO cw_chamados(descricao, tipo, unidade, setor, data_chamado, status_atual, outro_emissor, responsavel, emissor,grau, prazo, nota)VALUES('$descricao', '$tipo', $unidade, $setor, '$date', 'Andamento', '$emissor' , $responsavel, 0, 0, '$prazo', 0)";
		}


		if(DBExecute($query)){

			/* trecho de código para verificar quem receberá um e-mail de aviso */

			$envolvidos = "SELECT email, nome FROM cw_usuarios WHERE cargo = $tipo AND sede = $unidade";
				
			$envolvidos = DBExecute($envolvidos);

			/* Array para guardar os emails  e array auxiliar*/

			$todos_envolvidos = array();
			$auxiliar = array();

			while ($row = mysqli_fetch_assoc($envolvidos)) {

				$auxiliar['email'] = $row['email'];
				$auxiliar['nome'] = $row['nome'];

				array_push($todos_envolvidos, $auxiliar);

			}

			/* criando o objeto email para enviar */

			$email = new email();
			$email->sendEmail($todos_envolvidos, '1', 2, $descricao);
			header('Location: ../../src/chamado.php?sucesso=1');

		}else{
			
			header('Location: ../../src/chamado.php?sucesso=0');
		}
		
	}else{
		header('Location: ../../src/chamado.php?sucesso=2');
	}
	
?>