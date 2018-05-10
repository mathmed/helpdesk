<?php

	session_start();


	/* Importando os arquivos de banco de dados */

	include "../../db/config.php";
	require "../../db/connection.php";
	require "../../db/database.php";
	require "../receive/email.class.php";

	/* Recuperando as variáveis vindas do POST */

	$descricao = $_POST['descricao'];
	$id = $_POST['id_enviar'];
	$prazo = $_POST['prazo'];
	$responsavel = $_POST['responsavel'];
	$finalizado = $_POST['finalizado'];
	$prioridade = $_POST['grau'];
	$nremarcadas = $_POST['nremarcadas'];

	/* Verificando a pessoa que fez alteração no chamado */

	$pessoa_que_alterou = $_SESSION['id'];


	/* verificando qual tipo de prioridade foi enviado (verificação do isnumeri por conta de ter dois inputs possívels, sendo um textarea com valor string e um select com valor inteiro) */

	if(!is_numeric($prioridade)){
		$graus = array("Não definido" => 0, "Emergência" => 1 , "Urgência" => 2 , "Compra de Produtos" => 3 ,"Serviços Terceirizados" => 4);
		$prioridade = $graus[$prioridade];
	}

	/* verificando qual o responsável (verificação do isnumeri por conta de ter dois inputs possívels, sendo um textarea com valor string e um select com valor inteiro) */

	if(!is_numeric($responsavel)){

		$responsavel = "SELECT id FROM cw_usuarios WHERE nome = '$responsavel'";
		$responsavel = mysqli_fetch_assoc(DBExecute($responsavel))['id'];
	}

	/* iniciando a query  */

	$query = "SELECT * FROM cw_chamados WHERE id = $id";
	$query = mysqli_fetch_assoc(DBExecute($query));

	/* Verificando quais variáveis foram alteradas (alteradas recebem false) */

	$old_descricao = ($query['descricao'] == $descricao) ? true : false;
	$old_prazo = ($query['prazo'] == $prazo || !$prazo) ? true : false;
	$old_responsavel = ($query['responsavel'] == $responsavel) ? true : false;
	$old_grau = ($query['grau'] == $prioridade) ? true : false;

	/* verificando se o status ja foi finalizado */

	if($finalizado){

		if($query['status_atual'] == 'Finalizado'){

			$old_status = true;

		}else{
			$old_status = false;
		}

	}else{

		if($query['status_atual'] == 'Finalizado'){

			$old_status = false;

		}else{
			$old_status = true;
		}

	}

	/* Pegando a data atual */

	$date = new DateTime();
	$date = $date->format('Y-m-d H:i:s');

	/* Array auxiliar com ações para o histórico */

	$acoes  = array(1 => "alterou a descrição", 2 => "alterou o prazo", 3 => "encaminhou o chamado", 4 => "alterou o status", 5 => "alterou a prioridade" );


	($finalizado) ? $status = "status_atual = 'Finalizado'" : $status = "status_atual = 'Andamento'";

	
	$query = "UPDATE cw_chamados SET descricao = '$descricao', prazo = '$prazo', responsavel = $responsavel, grau = $prioridade,".$status." WHERE id = $id";


			if(DBExecute($query)){

				/* Caso a mudança seja feita, esse trecho de código tratará de inserir o histórico no banco de dados */

				if(!$old_descricao){

					$query = "INSERT INTO cw_historico (responsavel, acao, data_acao, id_chamado) VALUES ($pessoa_que_alterou, '$acoes[1]', '$date', $id ) ";
					DBExecute($query);
				}

				if(!$old_prazo){

					$query = "INSERT INTO cw_historico (responsavel, acao, data_acao, id_chamado) VALUES ($pessoa_que_alterou, '$acoes[2]', '$date', $id ) ";
					DBExecute($query);
					$nremarcadas++;

					$query = "UPDATE cw_chamados SET nremarcadas = $nremarcadas WHERE id = $id";
					DBExecute($query);
				}

				if(!$old_responsavel){

					$query = "INSERT INTO cw_historico (responsavel, acao, data_acao, id_chamado) VALUES ($pessoa_que_alterou, '$acoes[3]', '$date', $id ) ";
					DBExecute($query);			
				}

				if(!$old_status){
					$query = "INSERT INTO cw_historico (responsavel, acao, data_acao, id_chamado) VALUES ($pessoa_que_alterou, '$acoes[4]', '$date', $id ) ";
					DBExecute($query);
				}
				if(!$old_grau){
					$query = "INSERT INTO cw_historico (responsavel, acao, data_acao, id_chamado) VALUES ($pessoa_que_alterou, '$acoes[5]', '$date', $id ) ";
					DBExecute($query);
				}



				/* Trecho de código para selecionar todos os envolvidos com a requisição em questão */

				
				$email = new email();
				$todos_envolvidos = $email->selecionaEnvolvidos($id);
				$email->sendEmail($todos_envolvidos, $id, 3, '');

				

				header('Location: ../../src/details.php?id='.$id.'&sucesso=1');


			}else{

				header('Location: ../../src/details.php?id='.$id.'&sucesso=0');
			}
	
?>