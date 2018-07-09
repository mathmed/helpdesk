<?php


	/* iniciando a sessão e importanto arquivos importantes */

	session_start();
	require "../db/config.php";
	require "../db/connection.php";
	require "../db/database.php";
	require_once('../system/receive/querys.class.php');

	/* salvando a url atual */

	$_SESSION['url_atual'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";


	/* verifica se o usuário está logado */

	if(!isset($_SESSION['usuario'])){
		header('Location: ../index.php');
		}

	if(!isset($_GET['check'])){
		$_GET['check'] = 0;
	}

	/* recuperando o nome, id e cargo do usuário */

	$nome_usuario = $_SESSION['nome'];
	$id_usuario = $_SESSION['id'];
	$cargo_usuario = $_SESSION['cargo'];


	/* variáveis para auxilar na paginação */

	$total_reg = 20;
	$pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 0; 
	$items_pagina = $pagina * $total_reg;



	/* verificandos e existe filtro de data */

	if(isset($_GET['dateIn']) && isset($_GET['dateFn'])){
		$dateIn = $_GET['dateIn'];
		$dateFn = $_GET['dateFn'];

	}else{
		$dateIn = date('Y-m-d', strtotime('-30 days'));
		$dateFn = date('Y-m-d', strtotime('+30 days'));
	}


	/* verificando se existe filtro de status */

	isset($_GET['status']) ? $status = $_GET['status'] : $status = '';

	/* verificando se existe filtro de tipo */

	isset($_GET['tipo']) ? $tipo = $_GET['tipo'] : $tipo = 'todas';

	/* criando o objeto da classe querys */

	$objQuery = new querys();

	/* recuperando as querys que serão usadas de acordo com o filtro */
	$dados_query = $objQuery->returnQuery($dateIn, $dateFn, $status, $items_pagina, $total_reg, $tipo);
	/* recuperando informações sobre número de chamados */
	$dados_usuario = $objQuery->nchamados($id_usuario);


	/* adicionando as informações à supervariável */

	$_SESSION['dateIn'] = $dateIn;
	$_SESSION['dateFn'] = $dateFn;


	if(isset($_GET['status'])){
		$_SESSION['status'] = $_GET['status'];
	}else{
		if(isset($_SESSION['status'])){
			unset($_SESSION['status']);
		}	
	}

	?>

	<!DOCTYPE html>
		<html>
			<head>
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<title>Home - Helpdesk</title>
				<link href="../assets/bootstrap/css/bootstrap.css" rel="stylesheet">
				<link rel="stylesheet" href="../custom-css/home.css">
				<link rel="stylesheet" href="../assets/bootstrap/css/font-awesome.min.css">
				<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
				<script type="text/javascript">

					/* iniciando o documento */
					$(document).ready(function(){

						/* para cada elemento na tabela, essa função será chamada (mostrando notas dos chamados) */
						$(".rateYo").each(function(){

						  /* iniciando o rateyo no elemento */
						  $(this).rateYo({

						    starWidth: "20px",
						    /* atribuindo valor atual de avaliação (vindo do atributo nota, preenchido pelo php*/
						    rating: $(this).attr('nota'),
						    readOnly: true
						  });

						})
					})

				</script>
				<script type="text/javascript">

				/* funções para abrir e fechar a nav lateral */ 

					function openNav() {
					    document.getElementById("mySidenav").style.width = "250px";
					    document.getElementById("main").style.marginLeft = "250px";
					    document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
					}
					function closeNav() {
					    document.getElementById("mySidenav").style.width = "0";
					    document.getElementById("main").style.marginLeft = "0";
					    document.body.style.backgroundColor = "white";
					}
				</script>
			</head>

	<body>

		<!-- INÍCIO DA NAVBAR -->

		<nav class="navbar navbar-expand-lg fixed-top navbar-light my-nav">

			<!-- MENU LATERAL -->

			<div id="mySidenav" class="sidenav">
			<h1>Mostrar os chamados</h1>

			<?php

			 /* código para auxiliar na cor dos botões  */

			 	$tipo_background = "#ff7f50"; 
			 	$tipo_color = "snow";

			?>

				<a href="javascript:void(0)" class="closebtn a" onclick="closeNav()">&times;</a>
				<div class = 'row row-botao'>

					<!-- verifica qual o tipo está selecionado para colocar cor no botão -->

					<a style = "<?php if($tipo == 'todas') echo "background:".$tipo_background.";". "color:".$tipo_color.";" ?>"
						type = "button" id = 'side' class="btn botao-side" href = "home.php?tipo=todas">Todos</a>
				</div>

				<?php

				/* Pegando todos os tipos cadastrados no bd */

				$query = DBExecute("SELECT * FROM cw_tipos");

				while ($row = mysqli_fetch_assoc($query)) { $id = $row['id']; ?>

					<div class = 'row row-botao'>
						<a style = "<?php if($row['id'] == $tipo) echo "background:".$tipo_background.";". "color:".$tipo_color.";" ?>"
							type = "button" id = 'side' class="btn botao-side" href = "home.php?tipo=<?php echo $id; ?>"><?php echo $row['tipo_chamado']; ?></a>
					</div>

				<?php } ?>		

			</div>

			<div class = 'container'>
				<a class="navbar-brand" href="home.php">
					<img src="http://www.gram.edu/offices/infotech/images/helpdesk_headset.png" class="d-inline-block align-top img-nav" alt="">
				</a>

				<button class="navbar-toggle navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#toggleNavs" aria-controls="toggleNavs" aria-expanded="false" aria-label="Toggle navigation" >
					<span class="navbar-toggler-icon icon-bar"></span>
				</button>

			<div class="collapse navbar-collapse my-collapse" id="toggleNavs">

			<ul class="navbar-nav mr-auto">
				<?php echo "<p class = 'nome_usuario nav-link'> Olá, $nome_usuario </p>"; ?>

				<li class="nav-item">
					<a class="nav-link" href="home.php" style = 'color: #ff7f50;'> <span class = 'fa fa-home'></span> Home</a>
				</li>

				<li class="nav-item">
					<a class="nav-link" onclick="openNav()" href = '#'> <span class = 'fa fa-navicon'></span> Áreas</a>
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

				<?php
				if($_SESSION['cargo'] > 1 ){
				
				?>

				<div class="dropdown">
					<li class="nav-item">
						<a class="nav-link dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">Painel</a>
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
				</div>
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

	<div class="container espaco-container">

		<!-- DIV PARA INFORMAR SE HÁ CHAMADOS EM ATRASO -->

		<!-- MOSTRAR SOMENTE PARA OS DONOS SEUS CHAMADOS EM ANDAMENTO, ATRADO E FINALIZADOS -->

		<?php if($cargo_usuario > 1){ ?>
		<div class = 'div-info'>
			<h4>Você tem <span class = 'cor-atraso'><?php echo $dados_usuario['atrasado']; ?></span> chamado(s) em <span class = 'cor-atraso'>atraso</span>.</h4>
			<h4>Você tem <span class = 'cor-andamento'><?php echo $dados_usuario['andamento']; ?></span> chamado(s) em <span class = 'cor-andamento'>andamento</span>.</h4>
			<h4>Você tem <span class = 'cor-finalizado'><?php echo $dados_usuario['finalizado']; ?></span> chamado(s)  <span class = 'cor-finalizado'>finalizados</span>.</h4>
			<a class = 'btn btn-danger space-btn hvr-grow-shadow' type = 'button' href = "myreq.php">Visualizar</a>
		</div>

		<?php } ?>

		<!-- DIV DE FILTROS -->
		<div class="row div-filtro"> 

			<div class="col-md-12">
				<form action="../system/filter.php" method="post">
					<div style = 'display: none;'>
						<input type="" name="tipo" value = "<?php echo $tipo ?>">
					</div>
					<div class="row">
						<div class="col-md-3">
							<label>Data inicial:</label>
							<input type="date" name="dateIn" class="form-control" value="<?php echo $dateIn; ?>" autofocus required>
						</div>

						<div class="col-md-3">
							<label>Data final:</label>
							<input type="date" name="dateFn" class="form-control" value="<?php echo $dateFn; ?>" required>	
						</div>

						<div class = 'col-md-2'>
							<label>Status:</label>
							<select type="text" class="form-control" maxlength="100" name="status" placeholder="Selecione o status...">

							<?php

							/* verificando os status possíveis */

								$status_array = array(0 =>  'Todos', 1 => 'Finalizado', 2 => 'Andamento', 3 => 'Atrasado');
								foreach ($status_array as $key => $value) {
									if($value == $_GET['status']){
										echo "<option selected>$value</option>";
									}else{
										echo "<option>$value</option>";
									} 
								}
							?>
							</select>
						</div>

						<div class="col-md-3" style="margin-top: 10px;">
							<a href="home.php">Limpar filtros</a>
							<button class="btn btn-primary btn-block" type="submit"><i class="fa fa-filter" aria-hidden="true"></i></button>
						</div>
					</div>
					</form>
				</div>

			</div> 
			<!-- FIM DA DIV DE FILTROS -->


			<!-- DIV PARA LEGENDAS -->
			<div class = 'row row-legenda'>

				<div class = 'col-md-4 col-legenda'>
					<div class = 'row row-legenda-2'>
						<p class = 'p-andamento'></p>
						<p class = 'p'>Em andamento</p>										
					</div>
				</div>

				<div class = 'col-md-4 col-legenda'>
					<div class = 'row row-legenda-2'>
						<p class = 'p-finalizada'></p>
						<p class = 'p'>Finalizado</p>									
					</div>
				</div>

				<div class = 'col-md-4 col-legenda'>
					<div class = 'row row-legenda-2'>
						<p class = 'p-atrasada'></p>
						<p class = 'p'>Atrasado</p>								
					</div>
				</div>

			</div>

			<!-- DIV DA TABELA -->

			<div class="row">
				<table class="table">
					<thead class="thead-light">
						<tr class = 'head-tr'>
							<th><center>ID</center></th>
							<th><center>Unidade</center></th>
							<th><center>Setor</center></th>
							<th><center>Solicitante</center></th >
							<th><center>Descrição</center></th>
							<th><center>Data chamado</center></th>
							<th><center>Prazo</center></th>
							<th><center>Status</center></th>
							<th><center>Nota</center></th>
							<th><center>Detalhar</center></th>
						</tr>
					</thead>

					<tbody>
										
					<?php

						/* informações para auxiliar na paginação */

						$tr = mysqli_num_rows(DBExecute($dados_query['queryListaTodos']));
						$tp = ceil($tr / $total_reg);

						/* pegando os dados do do banco de dados (utilizando a query pegada no início do arquivo) */
						$showLista = DBExecute($dados_query['queryLista']);
											
						while($row = mysqli_fetch_assoc($showLista)){

							/* verificando se existe algum emissor (se for 0, é conta compartilhada) */
							if($row['emissor'] == 0){
								$emissor = $row['outro_emissor'];

							}else{
								$emissor = $row['emissor'];
								$emissor = "SELECT nome FROM cw_usuarios WHERE id = $emissor ";
								$emissor = mysqli_fetch_assoc(DBExecute($emissor))['nome'];
							}

							/* verificando qual a unidade */

							$unidade = $row['unidade'];
							$unidade = "SELECT unidade FROM cw_unidades WHERE id = $unidade";
							$unidade = mysqli_fetch_assoc(DBExecute($unidade))['unidade'];

							/* verificando qual o setor */

							$setor = $row['setor'];
							$setor = "SELECT setor FROM cw_setores WHERE id = $setor";
							$setor = mysqli_fetch_assoc(DBExecute($setor))['setor'];

							/* formatando a data de prazo */

							$dataArray = explode("-", $row['prazo']);
							$dataPrazo= $dataArray[2].'/'.$dataArray[1].'/'.$dataArray[0];

							/* função da classe que formata a data (com horário) */
							
							$data_chamado = $objQuery->formataData($row['data_chamado']);

							/* definindo a cor da linha de acordo com o status */

							if($row['status_atual'] == 'Andamento'){
								$background = "#ffff99";
							}else if($row['status_atual'] == 'Finalizado'){
								$background = "#00ff7f";
							}else{
								$background = "#ff7979";
							}	
						?>

						<!-- PREENCHENDO AS LINHAS DA TABELA -->

						<tr style = 'background-color: <?php echo $background; ?> '>
							<td><center><?php echo $row['id']; ?></center></td>
							<td><center><?php echo $unidade; ?></center></td>
							<td><center> <?php echo $setor ?> </center></td>
							<td><center> <?php echo $emissor; ?> </center></td>
							<td><center> <?php echo $row['descricao']; ?> </center></td>
							<td><center> <?php echo $data_chamado; ?> </center></td>
							<td><center> <?php echo $dataPrazo; ?> </center></td>
							<td><center> <?php echo $row['status_atual']; ?> </center></td>
							<td><center><div nota = "<?php echo $row['nota']; ?> " class="rateYo" id = "<?php echo $row['id']; ?>"></div></center></td>
							<td>
								<center>
									<!-- botão para chamar a página de detalhes -->
									<a href = 'details.php?id=<?php echo $row['id']; ?>' class="btn btn-info btn-sm"><i class='fa fa-list' aria-hidden='true'></i></a>
								</center>
							</td>
						</tr>

						<?php } ?>
													
					</tbody>
				</table>

				<!-- INÍCIO DO CÓDIGO DE NAVEGÃÇÃO -->

				<nav>
					<?php if($pagina == 0) $bloq_ant = "style = 'pointer-events: none; color: grey;'"; else $bloq_ant = '';	?>
					<ul class = "pagination">
						<li class = 'page-items'>
							<a <?php echo $bloq_ant; ?> class = 'page-link' href="home.php?<?= $url; ?> pagina= <?php echo $pagina-1; ?>" arial-label = 'Previous'>
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
								<li class = "page-item <?php echo $estilo; ?>" ><a class = 'page-link' href="home.php?<?= $url; ?> pagina= <?php echo $i; ?>"><?php echo $i+1; ?></a></li>
						<?php } ?>

							<li clas = 'page-item'>
								<a <?php echo $bloq_prox; ?> class = 'page-link' href="home.php?<?= $url; ?> pagina= <?php echo $pagina+1; ?>">
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

	<script src="../assets/bootstrap/js/popper.js"></script>
	<script src="../assets/bootstrap/js/bootstrap.min.js"></script>		
	<script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>		
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">	
	</body>
</html>