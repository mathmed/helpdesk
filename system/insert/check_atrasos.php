#!/usr/bin/php -q
<?php

	/* Esse script será executado a partir do evento crontabs do servidor linux, enviando e-mail para
	usuários com atraso em chamados */

	include "../receive/email.class.php";
	include "../../db/config.php";
	include "../../db/connection.php";
	include "../../db/database.php";


	/* array para as pessoas que vai ser enviado o email */
	$envolvidos = array();
	$auxiliar = array('Descrição' => '', 'Responsavel' => '', 'Emissor' => '',  'Id' => '');


	/* iniciando a query */

	$query = "SELECT * FROM cw_chamados";
	$query = DBExecute($query);

	/* verificando a data atual */
	$data = date('Y-m-d');


	/* percorrendo a query */
	while ($row = mysqli_fetch_assoc($query)) {

		/* informações úteis */

		$descricao = $row['descricao'];
		$responsavel = $row['responsavel'];
		$emissor = $row['emissor'];
		$id = $row['id'];

		/* verifica se algum prazo ja foi colocado */


			if(strtotime($data) > strtotime($row['prazo']) && $row['status_atual'] != "Finalizado") {
				
				/* atualizando ação */

				$query_auxiliar = "UPDATE cw_chamados SET status_atual = 'Atrasado' WHERE id = $id ";
				DBExecute($query_auxiliar);

				/* selecionando responsaveis */

				$responsavel = mysqli_fetch_assoc(DBExecute("SELECT email, nome from cw_usuarios WHERE id = $responsavel"));

				$emissor = mysqli_fetch_assoc(DBExecute("SELECT email, nome from cw_usuarios WHERE id = $emissor"));

				/* colocando dados no array auxiliar */

				$auxiliar['Responsavel_email'] = $responsavel['email'];
				$auxiliar['Responsavel_nome'] = $responsavel['nome'];
				$auxiliar['Emissor_email'] = $emissor['email']; 
				$auxiliar['Emissor_nome'] = $emissor['nome']; 
				$auxiliar['Id'] = $id;
				$auxiliar['Descrição'] = $descricao;

				array_push($envolvidos, $auxiliar);

		}
	}

	/* enviando o email */
	
	$email = new email();
	$email->sendEmailAtraso($envolvidos);	


?>
