<?php

	/* iniciando a sessão e importando arquivos necessários */

	session_start();
	require "../db/config.php";
	require "../db/connection.php";
	require "../db/database.php";

	/* verifica se o usuário está realmente logado */

	if(!isset($_SESSION['usuario'])){
		header('Location: ../index.php');
	}
	
	/* recuperando informações sobre o usuário */

	$nome_usuario = $_SESSION['nome'];
	$cargo_usuario = $_SESSION['cargo'];

	/* recebendo mensagem de erro/sucesso */

	$error_success = isset($_GET['sucesso']) ? $_GET['sucesso'] : 2;

	?>

	<!DOCTYPE html>
		<html>
			<head>
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<title>Cadastrar usuário - Disbecol</title>
				<link rel="shortcut icon" href="../imagens/spo.png" type="image/x-icon"/>  <!-- Logo Helpdesk -->
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
											<a class="dropdown-item" href="#"> <span class = 'fa fa-plus-square'></span> Cadastrar novo usuário</a>								
											<a class="dropdown-item" href="del_usuario.php"> <span class = 'fa fa-trash'></span>  Excluir usuário</a>
											<a class="dropdown-item" href="myreq.php"> <span class = 'fa fa-user'></span> Meus chamados</a>
											<a class="dropdown-item" href="estatisticas.php?tipo=geral"> <span class = 'fa fa-line-chart'></span> Estatísticas</a>
											<div class="dropdown-divider"></div>
											<div style ='justify-content: center; display: flex;'>
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

				<!-- INÍCIO DA DIV PRINCIPAL -->

				<div class="container" style="margin-top: 100px;">

					<!-- TRATANDO MENSAGENS DE ERRO/SUCESSO -->	

					<div>
						<?php if($error_success == 1){?> 
							<div class="alert alert-success" role="alert">Cadastro realizado com sucesso.</div>

						<?php }else if($error_success == 0){?>
							<div class="alert alert-danger" role="alert">Usuário ja está cadastrado no sistema.</div>

						<?php } ?>
					</div>
						<div class = 'row'>

							<div class = 'col-md-6'>

								<div class="alert alert-success" role="alert">
									Preencha os campos corretamente.
								</div>

								<div class="alert alert-danger" role="alert">
									É importante que o e-mail seja igual ao usado pelo usuário para que o mesmo receba as notificalçoes.
								</div>
							</div>
							<div class = 'col-md-6'>

								<!-- ENVIANDO OS DADOS PARA O BACKEND -->

								<form id = 'form-enviar-dados' action = '../system/insert/insertNewUser.php' method = 'post'>
									<div class="form-group">
										<label for="exampleInputEmail1">E-mail</label>
									    <input required name = 'email' type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
									</div>

									<div class="form-group">
									    <label for="exampleInputText">Login</label>
									    <input required name = 'login' type="text" class="form-control" id="exampleInputText" aria-describedby="textHelp">
									    <small id="emailHelp" class="form-text text-muted">Esse será o Login para acesso.</small>
									  </div>

									  <div class="form-group">
									    <label for="exampleInputPassword1">Senha</label>
									    <input required name = 'senha' type="password" class="form-control" id="exampleInputPassword1">
									    <small id="emailHelp" class="form-text text-muted">Diferencie letras maiúsculas de minúsculas.</small>

									  </div>

									  <div class="form-group">
									    <label for="exampleInputUser">Nome</label>
									    <input required name = 'nome' type="text" class="form-control" id="exampleInputUser">
									  </div>

									  <div class="form-group">
									    <label for="exampleInputSector">Setor</label>
									    <select name = 'setor' class="form-control" id="exampleInputSector">
									  		<?php

									  		/* verificando os setores existentes no banco de dados*/

									  		$query = DBExecute("SELECT * FROM cw_setores");

									  		while ($row = mysqli_fetch_assoc($query)) {
									  			
									  			echo "<option value = '".$row['id']."'>".$row['setor']."</option> ";
									  		 } 

									  		?>
									    </select>
									  </div>

									  <div class = 'form-group'>
									  	<label for = "exampleInputSede">Sede</label>
									  	<select name = 'sede' class = 'form-control' id = 'exampleInputSede'>
									  		
									  		<?php
											/* verificando as unidades existentes */
											  
									  		$query = DBExecute("SELECT * FROM cw_unidades");

									  		while ($row = mysqli_fetch_assoc($query)) {
									  			
									  			echo "<option value = '".$row['id']."'>".$row['unidade']."</option> ";
									  		 } 

									  		?>

									  	</select>
									  </div>


									  <div class="form-group">
									    <label for="exampleInputCargo">Tipo de usuário</label>
									    <select name = 'cargo' class="form-control" id="exampleInputCargo">

									    	<!-- verificando o level da conta para permitir criação de usuários
												 por padrão, qualquer pessoas que tem acesso à essa página pode 
												 criar um usuário comum ou login geral  -->

									    	<option value = '0'>Login Geral</option>
									    	<option value = '1'>Comum</option>
									    	<?php

									    	if($cargo_usuario == 2){ ?>

									    		<option value = '2'>Administrador</option>

												<?php 
												
									    		$query = DBExecute("SELECT * FROM cw_tipos");
									    		while ($row = mysqli_fetch_assoc($query)){
									    			echo "<option value = '".$row['id']."'>Dono ".$row['tipo_chamado']."</option>";
									    		}
									    	}
									    	?>
									    		
									    </select>
									  </div>
									  <div class = 'div_enviar'>
									  	<button type = "submit" id = 'btn_enviar' class="btn botao_enviar">Cadastrar</button>
									  </div>
									</form>
								</div>
							</div>
							
				</div>

				<!-- FIM DA DIV PRINCIPAL -->

				<!-- CRÉDITOS -->

				<p style="margin: 5% 0% 0% 0%;">
					Desenvolvido por <a href="https://www.facebook.com/mateus.medeiros.142035">Mateus Medeiros</a> - Versão beta
				</p>					
				<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
				<script src="../assets/bootstrap/js/popper.js"></script>
				<script src="../assets/bootstrap/js/bootstrap.min.js"></script>				
				</body>
			</html>
