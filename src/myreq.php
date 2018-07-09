<?php

	/* Requisicoes necessárias */

	session_start();
	require "../db/config.php";
	require "../db/connection.php";
	require "../db/database.php";
	require_once('../system/receive/querys.class.php');

	/*Verifica se o usuário realmente está logado */

	if(!isset($_SESSION['usuario'])){
		header('Location: ../index.php');
	}

	/* Recuperando informações sobre o usuário */

	$nome_usuario = $_SESSION['nome'];
	$id_usuario = $_SESSION['id'];
	$cargo_usuario = $_SESSION['cargo'];

	/* auxiliares para  paginação */

	$total_reg = 10;
	$pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 0; 
	$items_pagina = $pagina * $total_reg;


	/* início das querys */

	$queryLista = "SELECT * FROM cw_chamados WHERE emissor = '$id_usuario' OR responsavel = '$id_usuario' ORDER BY data_chamado DESC LIMIT $items_pagina, $total_reg";
	$queryListaTodos = "SELECT * FROM cw_chamados WHERE emissor = '$id_usuario' OR responsavel = '$id_usuario' ORDER BY data_chamado DESC";

?>

<!DOCTYPE html>
	<html>
		<head>
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<title>Meus chamados - Helpdesk</title>
			<link href="../assets/bootstrap/css/bootstrap.css" rel="stylesheet">
			<link rel="stylesheet" href="../custom-css/home.css">                  <!-- Estilos personalizados -->
			<link rel="stylesheet" href="../assets/bootstrap/css/font-awesome.min.css">  <!-- Icone do font awesome -->
		</head>
		<body>

			<!-- INÍCIO DA NAVBAR -->
			<nav class="navbar navbar-expand-lg fixed-top navbar-light my-nav">
				<div class = 'container'>
					<a class="navbar-brand" href="home.php">
						<img src="http://www.gram.edu/offices/infotech/images/helpdesk_headset.png" class="d-inline-block align-top img-nav" alt="">
					</a>
					<button class="navbar-toggle navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#toggleNavs" aria-controls="toggleNavs" aria-expanded="false" aria-label="Toggle navigation" >
						<span class="navbar-toggler-icon icon-bar"></span>
					</button>
					<div class="collapse navbar-collapse" id="toggleNavs">
						<ul class="navbar-nav mr-auto">
							<?php echo "<p class = 'nome_usuario nav-link'> Olá, $nome_usuario </p>"; ?>

							<li class="nav-item">
								<a class="nav-link" href="home.php"> <span class = 'fa fa-home'></span> Home</a>
							</li>

							<div class="dropdown">
								<li class="nav-item">
									<a class="nav-link dropdown-toggle" id="dropdownChamado" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#"> <span class = 'fa fa-user-plus'></span> Abrir chamado</a>
										<div class="dropdown-menu" aria-labelledby="dropdownChamado">
												<?php
												/* Pegando todos os tipos cadastrados no bd */

													$query = DBExecute("SELECT * FROM cw_tipos");

													while ($row = mysqli_fetch_assoc($query)) { $id = $row['id']; ?>

														<a class="dropdown-item" href="chamado.php?tipo=<?php echo $id; ?>"><?php echo $row['tipo_chamado']; ?> </a>

												<?php } ?>
											</div>
								</li>
							</div>	
				
							<!-- verificando o level da conta para mostrar ou não informações -->
									
							<?php
							if($cargo_usuario > 1 ){
							?>			
									
							<div class="dropdown">
								<li class="nav-item">
									<a style = 'color: #ff7f50;' class="nav-link dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="cadastros.php">Painel administrativo</a>
									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										<a class="dropdown-item" href="novousuario.php"> <span class = 'fa fa-plus-square'></span> Cadastrar novo usuário</a>								
										<a class="dropdown-item" href="del_usuario.php"> <span class = 'fa fa-trash'></span>  Excluir usuário</a>
										<a class="dropdown-item" href="myreq.php"> <span class = 'fa fa-user'></span> Meus chamados</a>
										<a class="dropdown-item" href="estatisticas.php?tipo=geral"> <span class = 'fa fa-line-chart'></span> Estatísticas</a>

										<div class="dropdown-divider"></div>
										<div class = 'div-button-sair'>
										   	<a href="../system/sair.php"><button class="btn btn-danger" type = 'button'>Sair</button></a>
										</div>
									</div>
								</li>
							</div>
							<li class="nav-item">
								<a class="nav-link" id="" data-toggle="" aria-expanded="false" href="config.php"> <span class = 'fa fa-cog'></span> Configurações</a>
							</li>
							<?php }else{ ?>
							<li class="nav-item div-otheruser-button-sair">
								<div class = 'div-button-sair'>
									<a href="../system/sair.php"><button class="btn btn-danger" type = 'button'>Sair</button></a>
							</div>
							</li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</nav>

			<!-- DIV PRINCIPAL -->
			<div class="container espaco-container" style="margin-top: 100px;">

				<!-- DIV DA TABELA -->
				<div class="row">
					<table class="table">
						<thead class="thead-light">
							<tr class = 'head-tr'>
								<th><center>Unidade</center></th>
								<th><center>Setor</center></th>
								<th><center>Solicitante</center></th>
								<th><center>Descrição</center></th>
								<th><center>Data chamado</center></th>
								<th><center>Prazo</center></th>
								<th><center>Status</center></th>
								<th><center>Detalhar</center></th>
							</tr>
						</thead>
						<tbody>
							
						<?php

							/* executando a query */
							$showLista = DBExecute($queryLista);
							
							/* informações acerca da paginação */
							$tr = mysqli_num_rows(DBExecute($queryListaTodos));
							$tp = ceil($tr / $total_reg);
							
							/* percorrendo a query */

							while($row = mysqli_fetch_assoc($showLista)){


								/* verificando se existe algum emissor (se for 0, é conta compartilhada) */
								if($row['emissor'] == 0){
									$emissor = $row['outro_emissor'];

								}else{
									$emissor = $row['emissor'];
									$emissor = "SELECT nome FROM cw_usuarios WHERE id = $emissor ";
									$emissor = mysqli_fetch_assoc(DBExecute($emissor))['nome'];
								}

								/* verificando a unidade */

								$unidade = $row['unidade'];
								$unidade = "SELECT unidade FROM cw_unidades WHERE id = $unidade";
								$unidade = mysqli_fetch_assoc(DBExecute($unidade))['unidade'];

								/*verificando o setor */

								$setor = $row['setor'];
								$setor = "SELECT setor FROM cw_setores WHERE id = $setor";
								$setor = mysqli_fetch_assoc(DBExecute($setor))['setor'];

								/* mostrando o prazo */
								
								$dataArray = explode("-", $row['prazo']);
								$dataPrazo= $dataArray[2].'/'.$dataArray[1].'/'.$dataArray[0];

								/* função da classe que formata a data */
								
								$objDb = new querys();
								$data_chamado = $objDb->formataData($row['data_chamado']);

								/* definiando a cor da linha de acordo com o status */

								if($row['status_atual'] == 'Andamento'){
									$background = "#ffff99";
								}else if($row['status_atual'] == 'Finalizado'){
									$background = "#00ff7f";
								}else{
									$background = "#ff7979";
								}
													
							?>

							<!-- PREENCHENDO A TABELA COM AS INFORMAÇÕES -->

							<tr style = 'background-color: <?php echo $background; ?> '>
								<td><center><?php echo $unidade; ?></center></td>
								<td><center> <?php echo $setor ?> </center></td>
								<td><center> <?php echo $emissor; ?> </center></td>
								<td><center> <?php echo $row['descricao']; ?> </center></td>
								<td><center> <?php echo $data_chamado; ?> </center></td>
								<td><center> <?php echo $dataPrazo; ?> </center></td>
								<td><center> <?php echo $row['status_atual']; ?> </center></td>
								<td>
									<center>
										<!-- chamando a página de detalhes -->
										<a href = 'details.php?id=<?php echo $row['id']; ?>' class="btn btn-info btn-sm"><i class='fa fa-list' aria-hidden='true'></i></a>
									</center>
								</td>
							</tr>

							<?php } ?>
													
						</tbody>
					</table>

					<?php

					/* pegando a url atual */

					if(isset($_GET['dateIn']) || isset($_GET['dateFn'])){

						$urlAtual = $_SERVER['REQUEST_URI'];
						$urlAtual1 = explode("?", $urlAtual);
						$urlAtual2 = explode("%", $urlAtual1[1]);
						if(substr($urlAtual2[0], -1) != '&'){
							$url = $urlAtual2[0].'&';
						}else{
							$url = $urlAtual2[0];
						}
					}else{
						$url = '';
					} ?>

				<!-- INÍCIO DO CÓDIGO DE NAVEGÃÇÃO -->

				<nav>
					<?php if($pagina == 0) $bloq_ant = "style = 'pointer-events: none; color: grey;'"; else $bloq_ant = '';	?>
					<ul class = "pagination">
						<li class = 'page-items'>
							<a <?php echo $bloq_ant; ?> class = 'page-link' href="myreq.php?<?= $url; ?> pagina= <?php echo $pagina-1; ?>" arial-label = 'Previous'>
								<span arial-hidden = 'true'>Anterior</span>
							</a>
						</li>

						<?php 
							for($i=0; $i<$tp; $i++){
								$estilo = '';
								$bloq_prox = '';
								$bloq_ant = '';
								if($pagina == $i) $estilo = "active";
								
								if($pagina+1 == $tp) $bloq_prox = "style = 'pointer-events: none; color: grey;'";	
								
								?>
								<li class = "page-item <?php echo $estilo; ?>" ><a class = 'page-link' href="myreq.php?<?= $url; ?> pagina= <?php echo $i; ?>"><?php echo $i+1; ?></a></li>
						<?php } ?>

							<li clas = 'page-item'>
								<a <?php echo $bloq_prox; ?> class = 'page-link' href="myreq.php?<?= $url; ?> pagina= <?php echo $pagina+1; ?>">
									<span>Próximo</span>
								</a>
							</li>
					</ul>
				</nav>

				</div>
			</div>
			<!-- FIM DA DIV PRINCIPAL -->
			<!-- CRÉDITOS -->

			<p style="margin: 0.001% 0% 0% 0%;">
				Desenvolvido por <a href="https://www.facebook.com/mateus.medeiros.142035">Mateus Medeiros</a> - Versão beta
			</p>					
			<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
			<script src="../assets/bootstrap/js/popper.js"></script>
			<script src="../assets/bootstrap/js/bootstrap.min.js"></script>				
		</body>
	</html>
