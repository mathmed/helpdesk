<?php

	/* iniciando a sessão e importando arquivos necessários */

	session_start();	
	require_once('../system/receive/querys.class.php');
	require "../db/config.php";
	require "../db/connection.php";
	require "../db/database.php";

	/* verifica se o usuário está logado */

	if(!isset($_SESSION['usuario'])){	
		header('../Location: index.php');
		}

	/* recuperando informações sobre o usuário */

	$nome_usuario = $_SESSION['nome'];
	$id_usuario = $_SESSION['id'];
	$cargo_usuario = $_SESSION['cargo'];

	/* recuperando o id do chamado */

	$id_chamado = $_GET['id'];

	/* verificando mensagens de erro/sucesso*/

	$error_success = isset($_GET['sucesso']) ? $_GET['sucesso'] : 10;

	/* criando um objeto da classe query e retornando todas as informações acerca do chamado */

	$objDb = new querys();                   
	$dados_chamado = $objDb->dadosChamado($id_chamado);

	/* tratando as pessoas que serão marcadas no comentário */
	$pessoas = $objDb->returnPessoas();
	$pessoas_marcadas = array();

							
	/* o trecho de código a seguir irá fazer verificações para habilitar ou não
		edições no formulário, lembrando que a conta de administrador tem cargo 2, usuários gerais 0, usuários comuns 1, e, donos de chamados tem cargo superior à 2 */

	if($cargo_usuario == 2){

		if($dados_chamado['dados_chamado']['emissor'] == $id_usuario){
			$usuario_logado = "enabled";
			$acesso_nota = true;
		}else{
			$usuario_logado = "readonly";
			$acesso_nota = false;
		}

		$acesso_responsavel = true;
		$acesso_prazo = "enabled";
		$botao = 'flex';
		$acesso_status = "enabled";
		$acesso_grau = true;

	}else{

		if($dados_chamado['dados_chamado']['emissor'] == $id_usuario){

			if($cargo_usuario > 1){

				$acesso_status = 'enabled';
				$acesso_prazo = "enabled";
				$acesso_grau = true;

			}else{

				$acesso_status = "disabled";
				$acesso_prazo = "readonly";
				$acesso_grau = false;

			}
			$acesso_nota = true;						
			$acesso_responsavel = false;
			$usuario_logado = "enabled";
			$botao = 'flex';

		}else if($cargo_usuario == $dados_chamado['dados_chamado']['tipo']){

			$acesso_status = "enabled";
			$acesso_responsavel = false;
			$usuario_logado = "readonly";
			$acesso_prazo = "enabled";
			$acesso_grau = true;
			$acesso_nota = false;

		}else{
			$acesso_status = "disabled";
			$acesso_responsavel = false;
			$usuario_logado = "readonly";
			$acesso_prazo = "readonly";
			$botao = "none";
			$acesso_grau = false;
			$acesso_nota = false;

		}

	}

	/* verificando o grau do chamado para definir uma cor de texto */

	if($dados_chamado['dados_chamado']['grau'] == 1){
		$grau_urgencia = '1 - Emergência';
		$color = "red";
	}else if($dados_chamado['dados_chamado']['grau'] == 2){
		$grau_urgencia = '2 - Urgência';
		$color = 'orange';
	}else if($dados_chamado['dados_chamado']['grau'] == 3){
		$grau_urgencia = '3 - Compra de produtos';
		$color = '#e7d748';
	}else if($dados_chamado['dados_chamado']['grau'] == 4){
		$grau_urgencia = '4 - Serviços terceirizados';
		$color = 'green';
	}else{
		$grau_urgencia = "Não definido";
		$color = "green";
	}

	/* formatando as datas */

	$dataArray = explode("-", $dados_chamado['dados_chamado']['prazo']);
	$dataPrazo= $dataArray[2].'/'.$dataArray[1].'/'.$dataArray[0];
							
	$data_chamado = $objDb->formataData($dados_chamado['dados_chamado']['data_chamado']);

	/* Verificando qual cor colocar no status */


	if($dados_chamado['dados_chamado']['status_atual'] == 'Andamento'){
		$background = 'orange';
	}else if($dados_chamado['dados_chamado']['status_atual'] == 'Atrasado'){
		$background = 'red';
	}else if($dados_chamado['dados_chamado']['status_atual'] == 'Finalizado'){
		$background = 'green';
	}

	/* verificando o tipo de chamado */

	$tipo_chamado = $dados_chamado['dados_chamado']['tipo'];
	$query = "SELECT tipo_chamado from cw_tipos WHERE id = $tipo_chamado";
	$tipo_chamado = mysqli_fetch_assoc(DBExecute($query))['tipo_chamado'];

	/*Recuperando quantidade de comentários */

	$dados_mensagem = $objDb->dadosMensagem($id_chamado);
	$qtd_comentario = mysqli_num_rows($dados_mensagem);

	/* variável para definir quem pode dar a nota */

	$atribuir_nota = ($acesso_nota) ? 'false' : 'true';

	?>

	<!DOCTYPE html>
		<html>
			<head>
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<title>Detalhes chamado - Helpdesk</title>
				<link rel="shortcut icon" href="imagens/spo.png" type="image/x-icon"/>  <!-- Logo Helpdesk -->
				<link href="../assets/bootstrap/css/bootstrap.css" rel="stylesheet">
				<link rel="stylesheet" href="../custom-css/home.css">                  <!-- Estilos personalizados -->
				<link rel="stylesheet" href="../custom-css/details.css">                  <!-- Estilos personalizados -->
				<link rel="stylesheet" href="../assets/bootstrap/css/font-awesome.min.css">  <!-- Icone do font awesome -->
				<script src="https://code.jquery.com/jquery-3.1.1.js"></script>
				<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.quicksearch/2.3.1/jquery.quicksearch.js"></script>

				<!-- script para tratar do sistema de avaliação do chamado -->
				<script type="text/javascript">

					/* iniciando o documento */
					$(document).ready(function(){
						$(function () {
						  $("#rateYo").rateYo({
						    starWidth: "40px",

						    /* atribuindo valor atual de avaliação */
						    rating: <?php echo $dados_chamado['dados_chamado']['nota']; ?>,
						    readOnly: <?php echo $atribuir_nota; ?>
						  });
						 
						});

						/* função chamada cada vez que a avaliação é mudada */
						$(function () {
						 
						  $("#rateYo").rateYo()
						    .on("rateyo.set", function (e, data) {
						 		
						        $.ajax({
								url: '../system/insert/altera_nota.php',
                                method: 'post',
                                data: { nota: data.rating, id: <?php echo $id_chamado; ?> }

						        })

						     });
						});

						/* função para selecionar uma pessoas para marcar (gambiarra pura, não mecha aqui) */
						$(".pessoas").each(function(){
							$(this).click(function(){

								/* guarda a ultima posicao do string, que é s/n, dizendo se está ou nao selecionado */
								var auxiliar = $(this).children().val().split("-");
								selecionado = auxiliar[1];

								/* alterando o valor no clique */
								if(selecionado == 's'){
									$(this).children().val(auxiliar[0]+'-n');
									$(this).css({"background": "white", "color": "#212529"});

								}else{
									$(this).children().val(auxiliar[0]+'-s');
									$(this).css({"background": "#28e57a", "color": "snow"});

								}
							})
						})

					})

				</script>
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
											<?php /* Pegando todos os tipos cadastrados no bd */

											$query = DBExecute("SELECT * FROM cw_tipos");
											while ($row = mysqli_fetch_assoc($query)) { $id = $row['id']; ?>

												<a class="dropdown-item" href="chamado.php?tipo=<?php echo $id; ?>"><?php echo $row['tipo_chamado']; ?> </a>

											<?php } ?>

										</div>
									</li>
								</div>

								<!-- VERIFICA O CARGO DA CONTA PARA MOSTRAR OU NÃO INFORMAÇÕES -->
									
								<?php
								if($cargo_usuario > 1 ){
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

						<div class="container espaco-container-details">
							<!-- TRATANDO MENSAGENS DE ERRO/SUCESSO -->
							<div>
								
								<?php if($error_success == 1){?> 
									<div class="alert alert-success" role="alert">Alteração realizada com sucesso.</div>

								<?php }else if($error_success == 0){?>
									<div class="alert alert-danger" role="alert">Erro ao realizar a alteração.</div>

								<?php }else if($error_success == 3){ ?>
									<div class="alert alert-success" role="alert">Comentário enviado com sucesso.</div>
								<?php } ?>

							</div>

							<div class = 'div-tipo-chamado'>
								<h1>Chamado de <?php echo $tipo_chamado. " - #".$dados_chamado['dados_chamado']['id']; ?></h1>
							</div>


							<div class = 'row espaco-titulo' id = 'cabecario'> 
								
								<div>
									<h4>Solicitante:</h4>
									<span><?php echo $dados_chamado['nome']; ?></span>
								</div>


								<div >
									<h4>Setor solicitante: </h4> <span><?php echo $dados_chamado['setor']; ?></span>
								</div>
								<div>
									<h4>Status:</h4> <span style = 'font-weight: bold; color: <?php echo $background; ?>' ><?php echo $dados_chamado['dados_chamado']['status_atual']; ?></span>
								</div>

								<div>
									<h4>Data do chamado:</h4> <span><?php echo $data_chamado; ?></span>
								</div>

							</div>

							<div class ='row espaco-titulo' id = 'cabecario'>
								<div>
									<h4>Prioridade:</h4> <span style=" font-weight: bold;color: <?php echo $color; ?>"><?php echo $grau_urgencia; ?></span>
								</div>
							</div>

							<div class ='row espaco-titulo' id = 'cabecario' >
								<div>
									<h4>Unidade:</h4> <span><?php echo $dados_chamado['unidade']; ?></span>
								</div>
							</div>

							<!-- DIV DOS BOTÕES E COMENTÁRIOS -->
							<div class = 'row margem-cabecario'>

								<div class = 'col-md-4'>
								
									<button type = 'submit' class = 'btn botao_enviar cor-botao' data-toggle="modal" data-target="#modalComentarios">Comentários<span class = 'fa fa-comments span-info'></span><span class = 'n-comentarios'><?php echo $qtd_comentario; ?> </span></button>
								</div>

								<div class = 'col-md-4'>
									<button type = 'submit' class = 'btn botao_enviar cor-botao' data-toggle="modal" data-target="#modalMessage">Novo comentário<span class = 'fa fa-comment span-info'></span></button>
								</div>

								<div class = 'col-md-4' style = 'display: <?php echo $botao; ?>'>
									<button type = "submit" form = 'form-alterar-dados-chamado' id = 'btn_enviar' class="btn botao_enviar">Salvar alterações<span class = 'fa fa-check span-info' ></span></button>
								</div>

								<!-- MODAL PARA INSERIR NOVA MENSAGEM -->
								<form id = 'form-enviar-comentario' action = '../system/insert/newComment.php' method = 'post'>
								<div class="modal fade modal-nova-msg" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
								  <div class="modal-dialog" role="document">
								    <div class="modal-content">
								      <div class="modal-header">
									  	<div class = "novo-comentario">
								        	<h5 class="modal-title" id="exampleModalLabel">Novo comentário</h5>
										</div>
										<button type = 'submit' class = 'btn botao_enviar cor-botao' data-toggle = "collapse" data-target = "#marcar-pessoas">Marcar alguém<span class = 'fa fa-user-plus span-info'></span></button>
								        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
								          <span aria-hidden="true">&times;</span>
								        </button>
								      </div>

										<!-- DIV PARA MARCAR PESSOAS -->
										<div id="marcar-pessoas" class="collapse">
										<div class = 'div-txt-consulta'>
											<input name="consulta" id="txt_consulta" placeholder="Consultar nome" type="text" class="form-control">
										</div>
											<table class = 'table' id = 'table-pessoas'>
												<tbody>
													<?php
													/* código php para preencher os usuários */
													foreach($pessoas as $key => $value){ ?>
													<tr class = 'clickable-row' >
														<td id = "<?php echo $value['id'].'-n';?> " class = 'pessoas'><?php echo $value['nome']; ?><input name = 'marcados[]' type = 'hidden' value = "<?php echo $value['id'].'-n';?> "> </td>
													</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>

										<!-- FIM DA DIV DE MARCAR PESSOAS -->
								      <div class="modal-body">
								          <div class="form-group">
								            <label for="mensagem" class="col-form-label">Comentário:</label>
								            <textarea name = 'mensagem' class="form-control" id="mensagem" rows = '10' maxlength="255" required></textarea>
										  </div>

								          <!-- Id oculto para enviar -->

								         <div style = 'display: flex;'>
											<textarea style = 'display: none;' name = 'id_enviar' id = 'id_enviar' type = 'text' class = 'form-control'><?php echo $_GET['id']; ?></textarea>
										</div>
										<div class="modal-footer form-group">
								        	<button type="button" class="btn btn-secondary fechar" data-dismiss = 'modal' >Fechar</button>
								        	<button type="submit" id = 'enviar_comentario' class="btn botao_enviar">Enviar comentário</button>
								      	</div>
								        </form>
								      </div>

								    </div>
								  </div>
								</div>

								<!-- FIM DO MODAL PARA INSERIR NOVA MENSAGEM -->

								<!-- MODAL PARA VISUALIZAR TODAS AS MENSAGENS  -->
								<div class="modal fade" id = 'modalComentarios' tabindex="-1" role = 'dialog'>
								  <div class="modal-dialog" role="document">
								    <div class="modal-content">
								      <div class="modal-header">
								        <h5 class="modal-title">Comentários sobre esse chamado</h5>
								        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
								          <span aria-hidden="true">&times;</span>
								        </button>
								      </div>
								      <div class="modal-body">
								        
								      <!-- TRECHO DE CÓDIGO PARA RECUPERAR OS COMENTÁRIOS DO BANCO DE DADOS -->

									      <?php 

									      	/* chamando uma função da classe que retornas as mensagems */
											$dados_mensagem = $objDb->dadosMensagem($id_chamado);
											
											while ($row = mysqli_fetch_array($dados_mensagem)) {
												
												/* verificando o nome de quem postou o comentário */

												$responsavel = "SELECT nome FROM cw_usuarios WHERE id = '$row[3]'";
												$responsavel = mysqli_fetch_array(DBExecute($responsavel))[0];

												/* formatando a data */

												$data = $objDb->formataData($row[4]);

												?>

												<!-- INÍCIO DA DIV COM AS INFORMAÇÕES DO COMENTÁRIO -->

												<div class = 'row' id = 'janela-comentarios'>

													<div id = 'cabecario-janela-comentarios'>
														<div>
															<h4> Autor: </h4> <span><?php echo $responsavel ?></span>
														</div>
														<div>
															<h4> Data: </h4> <span><?php echo $data ?></span>
														</div>
													</div>

													<div id = 'corpo-janela-comentarios	'>

														<div id = 'h3-div'>
															
															<h3><?php echo $row[2]; ?></h3>

														</div>
													</div>
												</div>

											<?php } ?>
								      </div>
								      <div class="modal-footer">
								        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
								      </div>
								    </div>
								  </div>
								</div>

								<!-- FIM DO MODAL PARA VISUALIZAR OS COMENTÁRIOS -->

							</div>

							<!-- FIM DA DIV DOS BOTÕES -->

							<div class="clearfix linha-divisoria"></div>

							<!-- INÍCIO DA DIV DOS FORMULÁRIOS-->

							<form id = 'form-alterar-dados-chamado' action = '../system/insert/updateRequest.php' method = 'post'>

							<div class = 'row' id = 'formulario'>
							
								<div class = 'col-md-6 margem-cabecario'>

									<div class = 'form-group'>
									
										<h4>Descrição </h4>
										<textarea required name = 'descricao'  id = 'descricao' type="text" class="form-control" rows = '10' maxlength="255" style = 'resize: none;' <?php echo $usuario_logado; ?>><?php echo $dados_chamado['dados_chamado']['descricao']; ?></textarea>

										<div style = 'display: flex;'>
											<textarea style = 'display: none;' name = 'id_enviar' id = 'id_enviar' type = 'text' class = 'form-control'><?php echo $dados_chamado['dados_chamado']['id']; ?></textarea>
										</div>

									</div>
									<div class = 'row historico_alteracoes'>
									
										<h2 class = 'historico'>Histórico de alterações </h2>

										<!-- função para recuperar o histórico de alterações -->

										<?php

										$alteracoes = $objDb->returnHistorico($id_chamado);

										while($row = mysqli_fetch_assoc($alteracoes)){
											
											$responsavel = $row['responsavel'];
											$responsavel = "SELECT nome FROM cw_usuarios WHERE id = $responsavel";
											$responsavel = mysqli_fetch_assoc(DBExecute($responsavel))['nome'];

											$data = $data_chamado = $objDb->formataData($row['data_acao']);

											echo "<h4 style = 'margin-bottom: 15px; margin-left: 0px;' >"
											."<x style = 'color: red;'>".$responsavel."</x>".
											" "."<y style = 'font-weight: bold;'>".$row['acao']."</y>"." em ". $data. "</h4>";
										}

									?>
									</div>
								</div>

								<div class = 'col-md-6 margem-cabecario'>
									
									<div class = 'form-group'>
									
										<h4>Prazo máximo:</h4>
										<input value = "<?php echo $dados_chamado['dados_chamado']['prazo']; ?>" type = 'date' name ='prazo' id ='prazo' class="form-control" style = 'resize: none;' <?php echo $acesso_prazo; ?>></input>
									</div>

									<!-- verifica se o usuário tem acesso ao grau -->

									<?php

									/* array auxiliar com grau's */
									$graus = array(0 => "Não definido", 1 => "Emergência", 2 => "Urgência", 3 => "Compra de Produtos", 4 => "Serviços Terceirizados");

									 if($acesso_grau){

									?>

									<div class = 'form-group'>
										<h4>Prioridade</h4>
										<select class = 'form-control' name = 'grau' id = 'grau' <?php echo $acesso_grau; ?>>
											<?php foreach ($graus as $key => $value) {
												
												if($key == $dados_chamado['dados_chamado']['grau']){
													echo "<option selected value = $key>$value</option>";
												}else{
													echo "<option value = $key>$value</option>";
												}

											} 
										?>

										</select>
									</div>

									<?php }else{ ?>

										<!-- se o usuário não tiver acesso, irá mostrar um textarea não editável -->

										<div class = 'form-group'>
											<h4>Prioridade</h4>
											<textarea readonly name="grau" id = "grau" type = "text" class = "form-control"  ><?php echo $graus[$dados_chamado['dados_chamado']['grau']]; ?></textarea>
										</div>

									<?php } ?>


									<div class = 'form-group'>
									<h4>Encaminhado para:</h4>

									<!-- verifica se o usuário tem acesso para o encaminhamento -->

									<?php if ($acesso_responsavel) { ?>
									<select name ='responsavel' id ='responsavel' type="text" class="form-control" maxlength="100" <?php echo $acesso_responsavel; ?>>

									<?php

									/* selecionando possíveis responsáveis */

									$tipo_verificar = $dados_chamado['dados_chamado']['tipo'];
									$responsavel_chamado = $dados_chamado['dados_chamado']['responsavel'];

									$query = "SELECT * FROM cw_usuarios WHERE cargo = 2 OR cargo = $tipo_verificar";
									$query = DBExecute($query);

									while ($row = mysqli_fetch_assoc($query)){ 

										if($row['id'] == $responsavel_chamado) {

											echo "<option selected value = '".$row['id']."'>".$row['nome']."</option>";

										}else{
											echo "<option value = '".$row['id']."'>".$row['nome']."</option>";
										}
										

									} ?>

									</select>

									<!-- se o usuário não tiver acesso, irá mostrar um textarea não editável -->
									<?php } else { ?>

										<textarea readonly name="responsavel" id = "responsavel" type = "text" class = "form-control"><?php echo $dados_chamado['responsavel']; ?></textarea>

									<?php } ?>

									</div>
									<div class="checkbox form-group" style="margin-top: 20px;">
										<label><input name = 'finalizado' id = 'finalizado' class = '' type="checkbox" <?php echo $acesso_status." ".$dados_chamado['status']; ?>> Marcar como finalizado</label>
									</div>

									<!-- verifica se já está finalizado para mostrar a barra de avaliação -->
									<?php
									if($dados_chamado['dados_chamado']['status_atual'] == 'Finalizado'){ ?>
										<div class = 'row' >
											<div class = 'col-md-6 centralizar-texto'>
												<h5>Nota do atendimento:</h5>
											</div>
											<div class = 'col-md-6'>
												<div id="rateYo"></div>
											</div>
										</div>

									<?php } ?>


								    <div style = 'display: flex;'>
										<textarea style = 'display: none;' name = 'nremarcadas' id = 'nremarcadas' type = 'text' class = 'form-control'><?php echo $dados_chamado['dados_chamado']['nremarcadas']; ?></textarea>
									</div>

								</div>

							</div> 
							<!-- FIM DA DIV DOS FORMULÁRIOS -->
							</form>

						</div>
						<!-- FIM DO CONTAINER PRINCIPAL -->


					<!-- CRÉDITOS -->
					<p style="margin: 15% 0% 0% 0%;">
						Desenvolvido por <a href="https://www.facebook.com/mateus.medeiros.142035">Mateus Medeiros</a> - Versão beta
					</p>					
				</div>
				<script src="../assets/bootstrap/js/popper.js"></script>
				<script src="../assets/bootstrap/js/bootstrap.min.js"></script>	
				<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
				<!-- Latest compiled and minified JavaScript -->
				<script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
				<script>
					$('input#txt_consulta').quicksearch('table#table-pessoas tbody tr');
				</script>	
				</body>
			</html>
