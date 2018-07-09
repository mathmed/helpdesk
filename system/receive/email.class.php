
<?php
	/* classe destinada a enviar emails */

	/* altere as variáveis de email e senha para o seu */
	/* é necessário alterar os links para o seu domínio */

	require '../../assets/PHPMailer/PHPMailerAutoload.php';

	
	class email {

		/* construtor, defina aqui o seu email e senha para o envio das mensagens e o seu domínio (link que o e-mail irá
			redirecionar) */

		private $email = '';
		private $senha = '';
		private $dominio = '';


		public function sendEmail($vetor, $id_chamado, $tipo, $descricao){

			if($tipo == 1){

				$msg = 
				"<div>

					<img src = 'http://visionflow.com/wp-content/uploads/2013/09/helpdesk.png' height = '50' width = '100'>
				</div>

				<div style = 'margin-top:30px;'>
					<text style = 'font-size: 20px;'> Olá, um novo <b>comentário</b> com o texto: </text> <br>

					<div style = 'border: 2px solid grey; padding: 30px; display: flex; margin: 15px 0 15px 0; justify-content: center; align-items: center;'>
						<text style = 'font-size 16px;'> $descricao </text> <br>
					</div>
					<text style = 'font-size 16px;'> foi adicionado no chamado #$id_chamado, para vizualizá-lo </text> <a href = '$this->dominio'> Clique aqui </a>.
				</div>

				<div>
					<br> <i style = 'margin-top: 30px'> Atenciosamente, </i> <br>
					
					<i> Helpdesk </i> <br>
					<i> Sua empresa </i><br>
				</div>";

				$sub = "Novo comentário em um chamado";

			}else if($tipo == 2){

				$msg = 
				"<div>
					<img src = 'http://visionflow.com/wp-content/uploads/2013/09/helpdesk.png' height = '50' width = '100'>
				</div>

				<div style = 'margin-top:30px;'>
					<text style = 'font-size: 20px;'> Olá, um novo <b>chamado</b> com a descrição: </text> <br>

					<div style = 'border: 2px solid grey; padding: 30px; display: flex; margin: 15px 0 15px 0; justify-content: center; align-items: center;'>
						<text style = 'font-size 16px;'> $descricao </text> <br>
					</div>
					<text style = 'font-size 16px;'>Foi adicionado ao sistema, para vizualizá-lo </text> <a href = '$this->dominio'> Clique aqui </a>.
				</div>

				<div>
					<br> <i style = 'margin-top: 30px'> Atenciosamente, </i> <br>
					
					<i> Helpdesk </i> <br>
					<i> Sua empresa </i><br>

				</div>";

				$sub = "Novo chamado";

			}else if($tipo == 3){

				$msg = 
				"<div>

					<img src = 'http://visionflow.com/wp-content/uploads/2013/09/helpdesk.png' height = '50' width = '100'>
				</div>

				<div style = 'margin-top:30px;'>
					<text style = 'font-size: 20px;'> Olá, houve uma <b>alteração</b> em um chamado que você está seguindo. </text> <br>

					<text style = 'font-size 16px;'>Id: #$id_chamado, para vizualizá-lo </text> <a href = '$this->dominio'> Clique aqui </a>.
				</div>

				<div>
					<br> <i style = 'margin-top: 30px'> Atenciosamente, </i> <br>
					
					<i> Helpdesk </i> <br>
					<i> Sua empresa </i><br>

				</div>";

				$sub = "Alteração em um chamado";

			}else{

				$nome = $descricao['nome'];
				$login = $descricao['login'];
				$senha = $descricao['senha'];

				$msg = 
				"<div>

					<img src = 'http://visionflow.com/wp-content/uploads/2013/09/helpdesk.png' height = '50' width = '100'>
				</div>

				<div style = 'margin-top:30px;'>
					<text style = 'font-size: 20px;'> Olá, $nome! Seja bem vindo ao sistema de chamados. <br>

					O seu login de acesso é: <b> $login </b> <br>
					Sua senha de acesso é: <b> $senha </b> </br>

					</text> <br>

					<text style = 'font-size 16px;'>Para acessar o sistema </text> <a href = '$this->dominio'> Clique aqui </a>.
				</div>

				<div>
					<br> <i style = 'margin-top: 30px'> Atenciosamente, </i> <br>
					
					<i> Helpdesk </i> <br>
					<i> Sua empresa </i><br>


				</div>";

				$sub = "Cadastro";

			}

				$mail = new PHPMailer; //faz a instância do objeto PHPMailer

				$mail->isSMTP(); //seta o tipo de protocolo

				$mail->Host = 'smtp.gmail.com'; //define o servidor smtp

				$mail->SMTPAuth = true; //habilita a autenticação via smtp

				$mail->SMTPOptions = [ 'ssl' => [ 'verify_peer' => false ] ];

				$mail->SMTPSecure = 'tls'; //tipo de segurança

				$mail->Port = 587; //porta de conexão

				$mail->CharSet = 'UTF-8';

				$mail->FromName = "Sistema helpdesk";

				//dados de autenticação no servidor smtp

				$mail->Username = $this->email; //usuário do smtp (email cadastrado no servidor)
				$mail->Password = $this->senha; //senha 
				
				//dados de envio de e-mail

				foreach ($vetor as $key => $value) {
					$mail->addCC($value['email'], $value['nome']);

				}

				//configuração da mensagem
				$mail->isHTML(true); //formato da mensagem de e-mail
				$mail->Subject = $sub; //assunto
				$mail->Body = $msg; //Se o formato da mensagem for HTML você poderá utilizar as tags do HTML no corpo do e-mail
				
				//envio e testes
				if(!$mail->send()) { //Neste momento duas ações são feitas, primeiro o send() (envio da mensagem) que retorna true ou false, se retornar false (não enviado) juntamente com o operador de negação "!" entra no bloco if.
					return 0;
				} else {
					return 1;
				}
			}

			public function sendEmailAtraso($vetor){

				$mail = new PHPMailer; //faz a instância do objeto PHPMailer
				$mail->isSMTP(); //seta o tipo de protocolo
				$mail->Host = 'smtp.gmail.com'; //define o servidor smtp
				$mail->SMTPAuth = true; //habilita a autenticação via smtp
				$mail->SMTPOptions = [ 'ssl' => [ 'verify_peer' => false ] ];
				$mail->SMTPSecure = 'tls'; //tipo de segurança
				$mail->Port = 587; //porta de conexão
				$mail->CharSet = 'UTF-8';
				$mail->FromName = "Sistema helpdesk";

				//dados de autenticação no servidor smtp
				$mail->Username = $this->email; //usuário do smtp (email cadastrado no servidor)
				$mail->Password = $this->senha; //senha 

				foreach ($vetor as $key => $value) {

				$msg = 
					"<div>
						<img src = 'http://visionflow.com/wp-content/uploads/2013/09/helpdesk.png' height = '50' width = '100'>
					</div>
					<div style='width=945px; height=1417px; border: 1px solid black; margin-top: 30px;'>
						<p>Identificamos um chamado atrasado em que você faz parte!<br/><br/>
						<strong>Descrição: </strong> <br>".$value['Descrição']."<br/><br/>
						<a href='$this->dominio'>Clique aqui,</a> para acessar o sistema.<br/><br/>
						Atenciosamente,<br/>Sistema de chamados<br/>
					</div>
					";

				$sub = "Chamado em atraso";



				$mail->AddCC($value["Emissor_email"], $value['Emissor_nome']);
				$mail->AddCC($value["Responsavel_email"], $value['Responsavel_nome']);
					
				//configuração da mensagem
				$mail->isHTML(true); //formato da mensagem de e-mail
				$mail->Subject = $sub; //assunto
				$mail->Body  = $msg; //Se o formato da mensagem for HTML você poderá utilizar as tags do HTML no corpo do e-mail
				$mail->AltBody = $msg;  //texto alternativo caso o html não seja suportado
				
				//envio
				$mail->send();
				$mail->ClearCCs();

				}
			}

			public function sendEmailMarcacao($vetor, $id_chamado){

				$mail = new PHPMailer; //faz a instância do objeto PHPMailer
				//$mail->SMTPDebug = true; //habilita o debug se parâmetro for true
				$mail->isSMTP(); //seta o tipo de protocolo
				$mail->Host = 'smtp.gmail.com'; //define o servidor smtp
				$mail->SMTPAuth = true; //habilita a autenticação via smtp
				$mail->SMTPOptions = [ 'ssl' => [ 'verify_peer' => false ] ];
				$mail->SMTPSecure = 'tls'; //tipo de segurança
				$mail->Port = 587; //porta de conexão
				$mail->CharSet = 'UTF-8';
				$mail->FromName = "Sistema helpdesk";

				//dados de autenticação no servidor smtp
				$mail->Username = $this->email; //usuário do smtp (email cadastrado no servidor)
				$mail->Password = $this->senha; //senha 


				$msg = 
					"<div>

						<img src = 'http://visionflow.com/wp-content/uploads/2013/09/helpdesk.png' height = '50' width = '100'>
					</div>
					<div style='width=945px; height=1417px; margin-top: 30px;'>
						<p>Olá! Você foi mencionado em um comentário do chamado com ID <strong>#$id_chamado</strong><br/><br/>
						<a href='$this->dominio'>Clique aqui,</a> para acessar o sistema.<br/><br/>
						Atenciosamente,<br/>Sistema de chamados<br/>
					</div>
					";

				$sub = "Marcação em chamado";


				foreach($vetor as $key => $value){
					$mail->AddCC($value, ' ');
				}
					
				//configuração da mensagem
				$mail->isHTML(true); //formato da mensagem de e-mail
				$mail->Subject = $sub; //assunto
				$mail->Body  = $msg; //Se o formato da mensagem for HTML você poderá utilizar as tags do HTML no corpo do e-mail
				$mail->AltBody = $msg;  //texto alternativo caso o html não seja suportado
				
				//envio
				$mail->send();
				$mail->ClearCCs();

				
			}

			public function selecionaEnvolvidos($id_chamado ){

				/* Trecho de código para selecionar todos os envolvidos com o chamado em questão  */

				$autor = "SELECT emissor FROM cw_chamados WHERE id = $id_chamado";
				$responsavel = "SELECT responsavel FROM cw_chamados WHERE id = $id_chamado ";
				$envolvidos = "SELECT emissor FROM cw_comentarios WHERE id_chamado = $id_chamado ";

				$responsavel = DBExecute($responsavel);
				$envolvidos = DBExecute($envolvidos);
				$autor = DBExecute($autor);


				/* Array para guardar os email's  */

				$todos_envolvidos = array();
				$array_auxiliar = array();

				/* Executanto as querys */

				while ($row = mysqli_fetch_assoc($responsavel)) {
					$id_responsavel = $row['responsavel'];
					$query = "SELECT email, nome FROM cw_usuarios WHERE id = $id_responsavel";

					$auxiliar = mysqli_fetch_assoc(DBExecute($query));
					$array_auxiliar['nome'] = $auxiliar['nome'];
					$array_auxiliar['email'] = $auxiliar['email'];
					array_push($todos_envolvidos, $array_auxiliar);

				}

				while ($row = mysqli_fetch_assoc($envolvidos)) {
					$id_envolvido = $row['emissor'];

					$query = "SELECT email, nome FROM cw_usuarios WHERE id = $id_envolvido ";
					$auxiliar = mysqli_fetch_assoc(DBExecute($query));

					if(!in_array($auxiliar, $todos_envolvidos)){
						$array_auxiliar['nome'] = $auxiliar['nome'];
						$array_auxiliar['email'] = $auxiliar['email'];
						array_push($todos_envolvidos, $array_auxiliar);
					}

				}

				while ($row = mysqli_fetch_assoc($autor)) {
					$id_autor = $row['emissor'];
					$query = "SELECT email,nome FROM cw_usuarios WHERE id = $id_autor ";
					$auxiliar = mysqli_fetch_assoc(DBExecute($query));

					if(!in_array($auxiliar, $todos_envolvidos)){
						$array_auxiliar['nome'] = $auxiliar['nome'];
						$array_auxiliar['email'] = $auxiliar['email'];
						array_push($todos_envolvidos, $array_auxiliar);
					}

				}
				
				return $todos_envolvidos;
			}



	}





?>