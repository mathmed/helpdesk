<?php

	/* iniciando a sessão e importando arquivos essenciais */

	session_start();
	require "../db/config.php";
	require "../db/connection.php";
	require "../db/database.php";

	/* verifica se o usuário realmente está logado */

	
	if(!isset($_SESSION['usuario'])){		
		header('Location: ../index.php');
	}

	/* recuperando informações sobre o usuário, o tipo do chamado e alguma mensagem de erro */

	$nome_usuario = $_SESSION['nome'];
	$cargo_usuario = $_SESSION['cargo'];
	$error_success = isset($_GET['sucesso']) ? $_GET['sucesso'] : 2;
	$tipo = $_GET['tipo'];

	?>

	<!DOCTYPE html>
		<html>
			<head>
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<title>Novo chamado - Helpdesk</title>
				<link rel="shortcut icon" href="../imagens/spo.png" type="image/x-icon"/>  <!-- Logo helpdesk -->
				<link href="../assets/bootstrap/css/bootstrap.css" rel="stylesheet">
				<link rel="stylesheet" href="../custom-css/home.css">                  <!-- Estilos personalizados -->
				<link rel="stylesheet" href="../assets/bootstrap/css/font-awesome.min.css">  <!-- Icone do font awesome -->
				<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>

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
										<a style = 'color: #ff7f50;' class="nav-link dropdown-toggle" id="dropdownChamado" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#"> <span class = 'fa fa-user-plus'></span> Abrir chamado</a>
										<div class="dropdown-menu" aria-labelledby="dropdownChamado">

											<?php /* Pegando todos os tipos cadastrados no bd */

											$query = DBExecute("SELECT * FROM cw_tipos");

											while ($row = mysqli_fetch_assoc($query)) { $id = $row['id']; ?>

												<a class="dropdown-item" href="chamado.php?tipo=<?php echo $id; ?>"><?php echo $row['tipo_chamado']; ?> </a>

											<?php } ?>

										</div>
									</li>
								</div>


								<?php

								/* VERIFICANDO O CARGO DO USUÁRIO PARA MOSTRAR OU NÃO INFORMAÇÕES */

								if($cargo_usuario > 1){

								?>
			
								<div class="dropdown">
									<li class="nav-item">
										<a class="nav-link dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="cadastros.php">Painel administrativo</a>
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
								<?php } ?>
						</ul>
					</div>
				</div>
			</nav>

			<!-- DIV PRINCIPAL -->

			<div class="container" style="margin-top: 100px;">
			
				<div class="alert alert-success" role="alert">
					Preencha todos os campos de forma mais clara possível, você será notificado via e-mail sempre que houver alteração no status de seu chamado e/ou quando um novo comentário for adicionado. Em caso de dúvidas, contate o administrador do sistema ou seu supervisor.
				</div>

				<!-- TRATATIVA DE ERROS/SUCESSOS -->

				<div>			
					<?php if($error_success == 1){?> 
						<div class="alert alert-success" role="alert">Solicitação enviada com sucesso!.</div>

					<?php }else if($error_success == 0){?>
						<div class="alert alert-danger" role="alert">Erro ao enviar a solicitação.</div>

					<?php } ?>
				</div>

				<div id = 'div_sg'>
					<form id = 'form-enviar-dados-sg' action = '../system/insert/newRequest.php' method = 'post'>

						<!-- INPUT ESCONDIDO PARA ARMAZENAR O TIPO DE REQUISIÇÃO -->
						<div style = 'display: none'>
							<input type="" name="tipo" value = '<?php echo $tipo; ?>'>
						</div>

						<div class="form-group">
							<label for="unidade"><b>Unidade</b></label>
							<select required name = 'unidade' class="form-control" id="unidade">
							<option selected value = ''>Selecionar</option>

								<?php

									/* VERIFICANDO AS UNIDADES DISPONÍVEIS NO BANCO DE DADOS */

									$query = DBExecute("SELECT * FROM cw_unidades");

									while ($row = mysqli_fetch_assoc($query)) {
									  			
									  	echo "<option value = '".$row['id']."'>".$row['unidade']."</option> ";
									} 

								?>
							</select>
						</div>


						<div class="form-group">

							<label for="setor"><b>Setor para qual o chamado será realizado</b></label>
							<select required name = 'setor' class="form-control" id="setor">
								<option selected value = ''>Selecionar</option>
								<?php

								/* VERIFICANDO OS SETORES DISPONÍVEIS NO BANCO DE DADOS */

								$query = DBExecute("SELECT * FROM cw_setores");

								while ($row = mysqli_fetch_assoc($query)) {
									  			
									echo "<option value = '".$row['id']."'>".$row['setor']."</option> ";
								} 

								?>
							</select>
						</div>

						<!-- VERIFICA SE O TIPO DE CONTA É GERAL (CARGO 0) PARA MOSTRAR OU NÃO O INPUT PARA
								INFORMAR O NOME -->

						<?php if($cargo_usuario == 0 ) { ?>

							<div class = 'form-group'>
								<label for = 'solicitante'><b>Solicitante:</b></label>
								<input type="text" required name="solicitante" id = 'solicitante' class ='form-control'>
							</div>

						<?php } ?>

						<div class="form-group">
							<label for="descricao"><b>Descrição do serviço:</b></label>
							<textarea required name = 'descricao' type="text" class="form-control" id="descricao" rows = '10' maxlength="255" style = 'resize: none;'></textarea>
						</div>

						<div class = 'div_enviar'>
							<button type="submit" id = 'btn_enviar-requisicao' class="btn botao_enviar">Enviar</button>
						</div>

					</form>

				</div>
											
			</div>
			<!-- FIM DA DIV PRINCIPAL -->

			<!-- CRÉDITOS -->

			<p style="margin: 0.001% 0% 0% 0%;">
				Desenvolvido por <a href="https://www.facebook.com/mateus.medeiros.142035">Mateus Medeiros</a> - Versão beta
			</p>					
			<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
			<script src="../assets/bootstrap/js/popper.js"></script>
			<script src="../assets/bootstrap/js/bootstrap.min.js"></script>				
		</body>
	</html>
