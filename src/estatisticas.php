<?php

	/* iniciando a sessão e importando arquivos necessários  */

	session_start();

	require_once('../system/receive/estatisticas.class.php');
	require "../db/config.php";
	require "../db/connection.php";
	require "../db/database.php";

	/* salvando a url atual */

	$_SESSION['url_atual'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	/* verifica se o usuários realmente está logado */

	if(!isset($_SESSION['usuario'])){	
		header('Location: ../index.php');
		}


	/* recuperando informações sobre o usuário */

	$nome_usuario = $_SESSION['nome'];
	$id_usuario = $_SESSION['id'];
	$cargo_usuario = $_SESSION['cargo'];

	/* pegando data atual e anterior */

	if(isset($_GET['dateIn']) && isset($_GET['dateFn'])){
		$dateIn = $_GET['dateIn'];
		$dateFn = $_GET['dateFn'];

	}else{
		$dateIn = date('Y-m-d', strtotime('-30 days'));
		$dateFn = date('Y-m-d', strtotime('+30 days'));
	}


	/* verificando se existe algum tipo selecionado (filtro) */

	(isset($_GET['tipo'])) ? $tipo = $_GET['tipo'] : $tipo = 'geral';


	/* estilo para o tipo que estiver selecionado no menu */

	$estilo_selecionado = "style = 'background: #ff7f50; padding: 5px; border-radius: 7px; font-weight: bold'";

	/* criando o objeto da classe */

	$objDb = new estatisticas();

	/* retornando informações gerais */

	$info_gerais = $objDb->infoGerais($dateIn, $dateFn);
	
	?>

	<!DOCTYPE html>
		<html>
			<head>
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<title>Estatísticas chamado - Helpdesk</title>

				<link href="../assets/bootstrap/css/bootstrap.css" rel="stylesheet">
				<link rel="stylesheet" href="../custom-css/home.css">                  <!-- Estilos personalizados -->
				<link rel="stylesheet" href="../custom-css/details.css">                  <!-- Estilos personalizados -->
				<link rel="stylesheet" href="../custom-css/estatisticas.css">                  <!-- Estilos personalizados -->
				<link rel="stylesheet" href="../assets/bootstrap/css/font-awesome.min.css">  <!-- Icone do font awesome -->
				<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
				<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
				<script type="text/javascript">

				/* Início de trexo de código para criação dos gráficos */

				/****************** gráficos gerais **********************/

			      google.charts.load("current", {packages:["corechart"]});
			      google.charts.setOnLoadCallback(drawChartGeral);

			      function drawChartGeral() {
			        var data = google.visualization.arrayToDataTable([
			          <?php echo $info_gerais['Grafico']['string_status']; ?>
			        ]);

			        var options = {
			          title: 'Gráfico geral',
			          is3D: true,
			          colors: ['red', 'orange', 'green']
			        };

			        var chart = new google.visualization.PieChart(document.getElementById('chart_geral'));
			        chart.draw(data, options);
			      }

			      google.charts.setOnLoadCallback(drawChartFinalizados);

			      function drawChartFinalizados() {
			        var data = google.visualization.arrayToDataTable([
			          <?php echo $info_gerais['Grafico']['string_finalizados']; ?>
			        ]);

			        var options = {
			          title: 'Gráfico geral atrasados',
			          is3D: true,
			          colors: ['green', 'red']
			        };

			        var chart = new google.visualization.PieChart(document.getElementById('chart_finalizados'));
			        chart.draw(data, options);
			      }


			      /*********************** gráficos por pessoa ****************************/

			      google.charts.setOnLoadCallback(drawChartPessoasGeral);

			      function drawChartPessoasGeral() {
			        var data = google.visualization.arrayToDataTable([
			          <?php echo $info_gerais['Grafico']['string_pessoas']; ?>
			        ]);

			        var options = {
			          title: 'Chamados realizadas',
			          is3D: true
			        };

			        var chart = new google.visualization.PieChart(document.getElementById('chart_pessoas_geral'));
			        chart.draw(data, options);
			      }

			      google.charts.setOnLoadCallback(drawChartPessoasAtrasadas);

			      function drawChartPessoasAtrasadas() {
			        var data = google.visualization.arrayToDataTable([
			          <?php echo $info_gerais['Grafico']['string_pessoas_atrasadas']; ?>
			        ]);

			        var options = {
			          title: 'Chamados atrasados',
			          is3D: true
			        };

			        var chart = new google.visualization.PieChart(document.getElementById('chart_pessoas_atrasadas'));
			        chart.draw(data, options);
			      }

			      google.charts.setOnLoadCallback(drawChartPessoasFinalizadas);
			      function drawChartPessoasFinalizadas() {
			        var data = google.visualization.arrayToDataTable([
			          <?php echo $info_gerais['Grafico']['string_pessoas_finalizadas']; ?>
			        ]);

			        var options = {
			          title: 'Chamados finalizados',
			          is3D: true
			        };

			        var chart = new google.visualization.PieChart(document.getElementById('chart_pessoas_finalizadas'));
			        chart.draw(data, options);
			      }

			      google.charts.setOnLoadCallback(drawChartPessoasAndamento);
			      function drawChartPessoasAndamento() {
			        var data = google.visualization.arrayToDataTable([
			          <?php echo $info_gerais['Grafico']['string_pessoas_andamento']; ?>
			        ]);

			        var options = {
			          title: 'Chamados andamento',
			          is3D: true
			        };

			        var chart = new google.visualization.PieChart(document.getElementById('chart_pessoas_andamento'));
			        chart.draw(data, options);
			      }

			      /*********************** gráficos por setor ****************************/

			      google.charts.setOnLoadCallback(drawChartSetoresGeral);

			      function drawChartSetoresGeral() {
			        var data = google.visualization.arrayToDataTable([
			          <?php echo $info_gerais['Grafico']['string_setores']; ?>
			        ]);

			        var options = {
			          title: 'Chamados realizados',
			          is3D: true
			        };

			        var chart = new google.visualization.PieChart(document.getElementById('chart_setor_geral'));
			        chart.draw(data, options);
			      }

			      google.charts.setOnLoadCallback(drawChartSetoresAtrasadas);

			      function drawChartSetoresAtrasadas() {
			        var data = google.visualization.arrayToDataTable([
			          <?php echo $info_gerais['Grafico']['string_setores_atrasadas']; ?>
			        ]);

			        var options = {
			          title: 'Chamados atrasados',
			          is3D: true
			        };

			        var chart = new google.visualization.PieChart(document.getElementById('chart_setores_atrasadas'));
			        chart.draw(data, options);
			      }

			      google.charts.setOnLoadCallback(drawChartSetoresFinalizadas);
			      function drawChartSetoresFinalizadas() {
			        var data = google.visualization.arrayToDataTable([
			          <?php echo $info_gerais['Grafico']['string_setores_finalizadas']; ?>
			        ]);

			        var options = {
			          title: 'Chamados finalizados',
			          is3D: true
			        };

			        var chart = new google.visualization.PieChart(document.getElementById('chart_setores_finalizadas'));
			        chart.draw(data, options);
			      }

			      google.charts.setOnLoadCallback(drawChartSetoresAndamento);
			      function drawChartSetoresAndamento() {
			        var data = google.visualization.arrayToDataTable([
			          <?php echo $info_gerais['Grafico']['string_setores_andamento']; ?>
			        ]);

			        var options = {
			          title: 'Chamados andamento',
			          is3D: true
			        };

			        var chart = new google.visualization.PieChart(document.getElementById('chart_setores_andamento'));
			        chart.draw(data, options);
			      }

				  /*********************** gráficos por tipo ****************************/

			      google.charts.setOnLoadCallback(drawChartTipoGeral);

			      function drawChartTipoGeral() {
			        var data = google.visualization.arrayToDataTable([
			          <?php echo $info_gerais['Grafico']['string_tipos']; ?>
			        ]);

			        var options = {
			          title: 'Chamados realizados',
			          is3D: true
			        };

			        var chart = new google.visualization.PieChart(document.getElementById('chart_tipo_geral'));
			        chart.draw(data, options);
			      }

			      google.charts.setOnLoadCallback(drawChartTiposAtrasadas);

			      function drawChartTiposAtrasadas() {
			        var data = google.visualization.arrayToDataTable([
			          <?php echo $info_gerais['Grafico']['string_tipos_atrasadas']; ?>
			        ]);

			        var options = {
			          title: 'Chamados atrasados',
			          is3D: true
			        };

			        var chart = new google.visualization.PieChart(document.getElementById('chart_tipos_atrasadas'));
			        chart.draw(data, options);
			      }

			      google.charts.setOnLoadCallback(drawChartTiposFinalizadas);
			      function drawChartTiposFinalizadas() {
			        var data = google.visualization.arrayToDataTable([
			          <?php echo $info_gerais['Grafico']['string_tipos_finalizadas']; ?>
			        ]);

			        var options = {
			          title: 'Chamados finalizados',
			          is3D: true
			        };

			        var chart = new google.visualization.PieChart(document.getElementById('chart_tipos_finalizadas'));
			        chart.draw(data, options);
			      }

			      google.charts.setOnLoadCallback(drawChartTiposAndamento);
			      function drawChartTiposAndamento() {
			        var data = google.visualization.arrayToDataTable([
			          <?php echo $info_gerais['Grafico']['string_tipos_andamento']; ?>
			        ]);

			        var options = {
			          title: 'Chamados andamento',
			          is3D: true
			        };

			        var chart = new google.visualization.PieChart(document.getElementById('chart_tipos_andamento'));
			        chart.draw(data, options);
			      }


				</script>

				<!-- FIM DO TRECHO DE CÓDIGO PARA CRIAÇÃO DOS GRÁFICOS -->

			</head>

			<body>

				<!-- INÍCIO NAVBAR -->

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

							</ul>
						</div>
					</div>
				</nav>

				<!-- FIM DA NAV -->

				<!-- INÍCIO DO MENU COM OS TIPOS -->

				<div class = 'menu-estatisticas'>
					<div class = 'menu-items'>
						<a <?php if($tipo == 'geral') echo $estilo_selecionado; ?> href="estatisticas.php?tipo=geral">Geral</a>
						<a <?php if($tipo == 'pessoas') echo $estilo_selecionado; ?> href="estatisticas.php?tipo=pessoas">Pessoas</a>
						<a <?php if($tipo == 'setor') echo $estilo_selecionado; ?> href="estatisticas.php?tipo=setor">Setor</a>
						<a <?php if($tipo == 'tipo') echo $estilo_selecionado; ?> href="estatisticas.php?tipo=tipo">Tipo</a>
					</div>

				</div>

				<!-- DIV PRINCIPAL -->

				<div class = ' container espaco-container-estatisticas'>

					<!-- DIV DOS FILTROS -->
					
					<!-- FORMUMÁRIO PARA ENVIAR OS DADOS PARA O BACKEND (FILTRO) -->

					<form action="../system/filter_estatisticas.php" method="post">
					<div class = 'row'>			
							<div class = 'col-md-4'>
								<label>Data inicial:</label>
								<input type="date" name="dateIn" class="form-control" value="<?php echo $dateIn; ?>" autofocus required>
							</div>

							<div class="col-md-4">
								<label>Data final:</label>
								<input type="date" name="dateFn" class="form-control" value="<?php echo $dateFn; ?>" required>	
													
							</div>

							<div class = 'col-md-4 botao-filtro-estatistica'>
									<a href="home.php">Limpar filtros</a>
									<button class="btn btn-primary btn-block" type="submit"><i class="fa fa-filter" aria-hidden="true"></i></button>
							</div>
					</div>
					</form>

					<div class = 'cabecario-estatisticas'>
						<div class = 'div-titulo'>
							<h3 class = 'h3-estatisticas'>Estatísticas nesse período</h3>
						</div>
					</div>

					<!-- aqui verifica-se o tipo atual para mostrar determinados gráficos -->

					<?php if($tipo == 'geral'){ ?>

					<div class = 'row info-texto'>

						<div class = "col-md-3">
							<h4 class = 'h4-estatisticas'>Chamados realizados: <span><?php echo $info_gerais['Total']; ?></span></h4>
						</div>

						<div class = "col-md-3">
							<h4 class = 'h4-estatisticas'>Chamados em andamento: <span><?php echo $info_gerais['Andamento']; ?></span></h4>
						</div>

						<div class = "col-md-3">
							<h4 class = 'h4-estatisticas'>Chamados finalizados: <span><?php echo $info_gerais['Finalizados']; ?></span></h4>
						</div>

						<div class = "col-md-3">
							<h4 class = 'h4-estatisticas'>Chamados atrasados: <span><?php echo $info_gerais['Atrasados']; ?></span></h4>
						</div>

					</div>

					<div class="clearfix linha-divisoria"></div>
					
					<div class = 'row'>
						<div class = 'col-md-6'>
							<div id = 'chart_geral' class = 'chart'></div>
						</div>
						<div class = 'col-md-6'>
							<div id = 'chart_finalizados' class = 'chart'></div>
						</div>
					</div>

					<?php }else if ($tipo == 'pessoas'){ ?>

					<div class="clearfix linha-divisoria"></div>
					<div class = 'row'>
						<div class = 'col-md-6'>
							<div id = 'chart_pessoas_geral' class = 'chart'></div>
						</div>
						<div class = 'col-md-6'>
							<div id = 'chart_pessoas_atrasadas' class = 'chart'></div>
						</div>
					</div>

					<div class = 'row'>
						<div class = 'col-md-6'>
							<div id = 'chart_pessoas_finalizadas' class = 'chart'></div>
						</div>
						<div class = 'col-md-6'>
							<div id = 'chart_pessoas_andamento' class = 'chart'></div>

						</div>
					</div>

					<?php }else if($tipo == 'setor'){?>

					<div class="clearfix linha-divisoria"></div>
					<div class = 'row'>
						<div class = 'col-md-6'>
							<div id = 'chart_setor_geral' class = 'chart'></div>
						</div>
						<div class = 'col-md-6'>
							<div id = 'chart_setores_atrasadas' class = 'chart'></div>
						</div>
					</div>

					<div class = 'row'>
						<div class = 'col-md-6'>
							<div id = 'chart_setores_finalizadas' class = 'chart'></div>
						</div>
						<div class = 'col-md-6'>
							<div id = 'chart_setores_andamento' class = 'chart'></div>

						</div>
					</div>

				<?php }else{ ?>

					<div class="clearfix linha-divisoria"></div>
					<div class = 'row'>
						<div class = 'col-md-6'>
							<div id = 'chart_tipo_geral' class = 'chart'></div>
						</div>
						<div class = 'col-md-6'>
							<div id = 'chart_tipos_atrasadas' class = 'chart'></div>
						</div>
					</div>

					<div class = 'row'>
						<div class = 'col-md-6'>
							<div id = 'chart_tipos_finalizadas' class = 'chart'></div>
						</div>
						<div class = 'col-md-6'>
							<div id = 'chart_tipos_andamento' class = 'chart'></div>

						</div>
					</div>

				<?php } ?>


				</div>
				<!-- FIM DO CONTAINER PRINCIPAL -->


				<!-- CRÉDITOS -->

				<p style="margin: 15% 0% 0% 0%;">
					Desenvolvido por <a href="https://www.facebook.com/mateus.medeiros.142035">Mateus Medeiros</a> - Versão beta
				</p>					
				<script src="../assets/bootstrap/js/popper.js"></script>
				<script src="../assets/bootstrap/js/bootstrap.min.js"></script>				
				</body>
			</html>
