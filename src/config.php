<?php


	/* iniciando a sessão e importando arquivos necessários */

	session_start();
	require "../db/config.php";
	require "../db/connection.php";
	require "../db/database.php";
	require_once('../system/receive/querys.class.php');

	/* verifica se o usuário está logado */

	if(!isset($_SESSION['usuario'])){
		header('Location: ../index.php');
	}

	/* recuperando algum erro ou sucesso */


	$status = (!isset($_GET['status'])) ? '' : $_GET['status'];

	/* recuperando o nome do usuário */

	$nome_usuario = $_SESSION['nome'];

	/* criando um objeto da classe querys */

	$objQuery = new querys();

	?>

	<!DOCTYPE html>
		<html>
			<head>
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<title>Configurações - Helpdesk</title>
				<link rel="shortcut icon" href="imagens/spo.png" type="image/x-icon"/> 
				<link href="../assets/bootstrap/css/bootstrap.css" rel="stylesheet">
				<link rel="stylesheet" href="../custom-css/home.css">
				<link rel="stylesheet" href="../custom-css/config.css">
				<link rel="stylesheet" href="../assets/bootstrap/css/font-awesome.min.css">
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

			<div class="collapse navbar-collapse my-collapse" id="toggleNavs">
				
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
					<a style = 'color: #ff7f50;' class="nav-link" id="" data-toggle="" aria-expanded="false" href="#"> <span class = 'fa fa-cog'></span> Configurações</a>
				</li>
				</div>
			</ul>
		</div>
	</div>
	</nav>

	<!-- DIV PRINCIPAL -->

	<div class = 'espaco-container container'>

		<!-- TRATATIVA DE ERROS E SUCESSOS -->

		<?php if($status == 'setorexiste'){ ?>

			<div class = 'alert alert-danger' role = 'alert'>Setor já cadastrado</div>

		<?php }else if ($status == 'sucessosetor'){ ?>

			<div class = 'alert alert-success' role = 'alert'>Setor cadastrado com sucesso</div>

		<?php }else if ($status == 'tipoexiste'){ ?>

			<div class = 'alert alert-danger' role = 'alert'>Tipo de chamado já cadastrado</div>

		<?php }else if ($status == 'sucessotipo'){ ?>

			<div class = 'alert alert-success' role = 'alert'>Tipo de chamado cadastrado com sucesso</div>

		<?php }else if ($status == 'unidadeexiste'){ ?>

			<div class = 'alert alert-danger' role = 'alert'>Unidade já cadastrada</div>

		<?php }else if ($status == 'sucessounidade'){ ?>

			<div class = 'alert alert-success' role = 'alert'>Unidade cadastrada com sucesso</div>

		<?php }else if ($status == 'excluirsucesso'){ ?>

			<div class = 'alert alert-success' role = 'alert'>Excluído com sucesso</div>

		<?php } ?>


		<div class="row">
			<div class="col-md-2">
				<h6><b>Módulo Primário</b></h6>
				<a href="#" class="hvr-bubble-float-left" data-toggle="collapse" data-target="#collapseUnidades" aria-expanded="false" aria-controls="collapseUnidades">Unidades</a>
				<a href="#" class="hvr-bubble-float-left" data-toggle="collapse" data-target="#collapseSetores" aria-expanded="false" aria-controls="collapseSetores">Setores</a>
				<a href="#" class="hvr-bubble-float-left" data-toggle="collapse" data-target="#collapseChamados" aria-expanded="false" aria-controls="collapseChamados">Chamados</a>
			</div>
			<div class = 'col-md-10'>
				<div class = 'row'>
					<div class = 'col-md-12'>
						<!-- COLLAPSE DAS UNIDADES -->
						<div class = 'collapse' id = 'collapseUnidades'>
							<a href="#" class=" btn hvr-pulse" data-toggle="modal" data-target="#cadastroUnidades"><i class="fa fa-plus i-color" aria-hidden="true"></i></a>

							<!-- MODAL PARA ADICIONAR UMA UNIDADE -->
							<div class="modal fade" id="cadastroUnidades" tabindex="-1" role="dialog" aria-labelledby="cadastroUnidadeLabel" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title" id="cadastroUnidadeLabel">Cadastrar unidade</h4>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<form action="../system/insert/cadastros/cadastrarUnidade.php" method="POST">
											<div class="modal-body">

												<div class = 'form-group'>
													<label>Nome da unidade</label>
													<input type="text" name="unidade" class = 'form-control' required>
												</div>

											</div>
											<div class="modal-footer">
												<button type="submit" class="btn btn-primary">Cadastrar</button>
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>								      	
										</form>
									</div>
								</div>
							</div>
							<!-- FIM DO MODAL PARA ADICIONAR UMA UNIDADE -->

							<!-- TABELA COM INFORMAÇÕES SOBRE AS UNIDADES -->

							<table class = 'table'>
								<thead class = "thead-dark">
									<tr>
										<th><center>Id</center></th>
										<th><center>Unidade</center></th>
										<th><center>Excluir</center></th>
									</tr>
								</thead>

								<tbody>
									<?php

									/* Código para carregar as informações do banco de dados */

									$query = DBExecute("SELECT * FROM cw_unidades");
									while ($row = mysqli_fetch_assoc($query)) { ?>
										
										<tr>
											<td><center><?php echo $row['id']; ?></center></td>
											<td><center><?php echo $row['unidade']; ?></center></td>

										<!-- CHAMAR O MODAL -->
											<td>
												<center>
													<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#<?php echo 'modal_del_unidade'.$row['id']; ?>"><i class='fa fa-times' aria-hidden='true'></i></button>
												</center>
												<div class="modal fade" id="<?php echo 'modal_del_unidade'.$row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo 'modal_del_unidade'.$row['id']; ?>" aria-hidden="true">
												<form method = 'POST' action = '../system/insert/cadastros/del_unidade.php'>
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title">Atenção!</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																    <span aria-hidden="true">&times;</span>
																</button>
															</div>
															<div class="modal-body">
																<p>Realmente deseja excluir essa unidade? Após excluída não poderá ser recuperada</p>
																<input type="" name="id_excluir" id = 'id_excluir' value = '<?php echo $row['id']; ?>' style="display: none;">
															</div>

															<div class="modal-footer">
																<button type="submit" class="btn btn-primary">Confirmar</button>
																<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar.</button>
															</div>
														</div>
													</div>
												</form>
												</div>
											</td>

										</tr>

									<?php } ?>
								</tbody>
							</table>	
						</div>

						<!-- FIM DO COLLAPSE DAS UNIDADES E INÍCIO DO COLLAPSE  DOS SETORES -->
						<div class = 'collapse' id = 'collapseSetores'>

							<a href="#" class=" btn hvr-pulse" data-toggle="modal" data-target="#cadastroSetores"><i class="fa fa-plus i-color" aria-hidden="true"></i></a>

							<!-- MODAL PARA ADICIONAR UM SETOR -->
							<div class="modal fade" id="cadastroSetores" tabindex="-1" role="dialog" aria-labelledby="cadastroSetorLabel" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title" id="">Cadastrar setor</h4>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<form action="../system/insert/cadastros/cadastrarSetor.php" method="POST">
											<div class="modal-body">
												<div class = 'form-group'>
													<label>Nome do setor</label>
													<input type="text" name="setor" class = 'form-control' required>
												</div>
											</div>
											<div class="modal-footer">
												<button type="submit" class="btn btn-primary">Cadastrar</button>
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>								      	
										</form>
									</div>
								</div>
							</div>
							<!-- FIM DO MODAL PARA ADICIONAR UM SETOR -->

							<!-- INÍCIO DA TABELA COM INFORMAÇÕES -->

							<table class = 'table'>
								<thead class = "thead-dark">
									<tr>
										<th><center>Id</center></th>
										<th><center>Setor</center></th>
										<th><center>Excluir</center></th>
									</tr>
								<tbody>
									<?php
									/* Código para carregar as informações do banco de dados */

									$query = DBExecute("SELECT * FROM cw_setores");
									while ($row = mysqli_fetch_assoc($query)) { ?>
										
										<tr>
											<td><center><?php echo $row['id']; ?></center></td>
											<td><center><?php echo $row['setor']; ?></center></td>

										<!-- CHAMAR O MODAL -->

											<td><center>
												<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#<?php echo 'modal_del_setor'.$row['id']; ?>"><i class='fa fa-times' aria-hidden='true'></i></button>
												</center>
												<div class="modal fade" id="<?php echo 'modal_del_setor'.$row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo 'modal_del_setor'.$row['id']; ?>" aria-hidden="true">
												<form method = 'POST' action = '../system/insert/cadastros/del_setor.php'>
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title">Atenção!</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																    <span aria-hidden="true">&times;</span>
																</button>
															</div>
															<div class="modal-body">
																<p>Realmente deseja excluir esse setor? Após excluído não poderá ser recuperado</p>
																<input type="" name="id_excluir" id = 'id_excluir' value = '<?php echo $row['id']; ?>' style="display: none;">
															</div>

															<div class="modal-footer">
																<button type="submit" class="btn btn-primary">Confirmar</button>
																<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar.</button>
															</div>
														</div>
													</div>
												</form>
												</div>
											</td>
										</tr>

									<?php } ?>
								</tbody>
								</thead>
							</table>	
						</div>

						<!-- FIM DO COLLAPSE DOS SETORES E INÍCIO DO COLLAPSE DOS CHAMADOS -->
						<div class = 'collapse' id = 'collapseChamados'>

							<a href="#" class=" btn hvr-pulse" data-toggle="modal" data-target="#cadastroChamados"><i class="fa fa-plus i-color" aria-hidden="true"></i></a>

							<!-- MODAL PARA ADICIONAR UM TIPO DE CHAMADO -->
							<div class="modal fade" id="cadastroChamados" tabindex="-1" role="dialog" aria-labelledby="cadastroChamadoLabel" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title" id="">Cadastrar tipo de chamado</h4>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<form action="../system/insert/cadastros/cadastrarTipoChamado.php" method="POST">
											<div class="modal-body">

												<div class = 'form-group'>
													<label>Tipo de chamado</label>
													<input type="text" name="tipo" class = 'form-control' required>
												</div>
											</div>
											<div class="modal-footer">
												<button type="submit" class="btn btn-primary">Cadastrar</button>
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											</div>								      	
										</form>
									</div>
								</div>
							</div>
							<!-- FIM DO MODAL PARA ADICIONAR UM TIPO DE CHAMADO-->

							<!-- INÍCIO DA TABLE COM INFORMAÇÕES-->

							<table class = 'table'>
								<thead class = "thead-dark">
									<tr>
										<th><center>Id</center></th>
										<th><center>Tipo de chamado</center></th>
										<th><center>Excluir</center></th>
									</tr>
									<tbody>
									<?php
									/* Código para carregar as informações do banco de dados */

									$query = DBExecute("SELECT * FROM cw_tipos");
									while ($row = mysqli_fetch_assoc($query)) { ?>
										
										<tr>
											<td><center><?php echo $row['id']; ?></center></td>
											<td><center><?php echo $row['tipo_chamado']; ?></center></td>

										<!-- CHAMAR O MODAL -->

											<td><center>
												<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#<?php echo 'modal_del_tipo'.$row['id']; ?>"><i class='fa fa-times' aria-hidden='true'></i></button>
												</center>
												<div class="modal fade" id="<?php echo 'modal_del_tipo'.$row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo 'modal_del_tipo'.$row['id']; ?>" aria-hidden="true">
												<form method = 'POST' action = '../system/insert/cadastros/del_tipo.php'>
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title">Atenção!</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																    <span aria-hidden="true">&times;</span>
																</button>
															</div>
															<div class="modal-body">
																<p>Realmente deseja excluir esse tipo de chamado? Após excluído não poderá ser recuperado</p>
																<input type="" name="id_excluir" id = 'id_excluir' value = '<?php echo $row['id']; ?>' style="display: none;">
															</div>

															<div class="modal-footer">
																<button type="submit" class="btn btn-primary">Confirmar</button>
																<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar.</button>
															</div>
														</div>
													</div>
												</form>
												</div>
											</td>
										</tr>
									<?php } ?>
								</tbody>
								</thead>
							</table>	
						</div>		
					</div>
				</div>
			</div>

		</div>
	</div>
</body>
<!-- FIM -->


	<!-- CRÉDITOS -->

	<p style="margin: 200px 0% 0% 0%;">
		Desenvolvido por <a href="https://www.facebook.com/mateus.medeiros.142035">Mateus Medeiros</a> - Versão beta
	</p>

	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script>window.jQuery || document.write('<script src="../../../../assets/js/vendor/jquery.min.js"><\/script>')</script>
	<script src="../assets/bootstrap/js/popper.js"></script>
	<script src="../assets/bootstrap/js/bootstrap.min.js"></script>				
	</body>
</html>
