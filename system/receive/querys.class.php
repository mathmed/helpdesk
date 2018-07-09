
<?php

	/* classe com intuito de fornecer informações para a página de detalhes/home */
	
	class querys {

		public function dadosChamado($id_chamado) {

			/* pegando todos os dados do bd */

			$query = "SELECT * FROM cw_chamados WHERE id = $id_chamado";
			$query = DBExecute($query);
			$dados_chamado = mysqli_fetch_assoc($query);

			/* verificando o nome do emissor (caso seja 0, significa que é conta geral) */

			if($dados_chamado['emissor'] != 0){

				$nome = $dados_chamado['emissor'];
				$nome = "SELECT nome FROM cw_usuarios WHERE id = $nome";
				$nome = mysqli_fetch_assoc(DBExecute($nome))['nome'];

			}else{
				$nome = $dados_chamado['outro_emissor'];
			}

			$responsavel = $dados_chamado['responsavel'];
			$responsavel = "SELECT nome FROM cw_usuarios WHERE id = $responsavel";
			$responsavel = mysqli_fetch_assoc(DBExecute($responsavel))['nome'];

			/* verificando de qual setor partiu o chamado */

			$setor = $dados_chamado['setor'];
			$setor = "SELECT setor FROM cw_setores WHERE id = $setor";
			$setor = mysqli_fetch_assoc(DBExecute($setor));

			/* verificando o status do chamado */

			if($dados_chamado['status_atual'] == "Finalizado"){
				$status = "checked";

			}else{

				$status = '';
			}

			/* verificando qual a unidade do chamado */

			$sede = $dados_chamado['unidade'];
			$sede = "SELECT unidade from cw_unidades WHERE id = $sede";
			$sede = mysqli_fetch_assoc(DBExecute($sede))['unidade'];

			/* array com as informações */

			$array_dados = array('setor' => $setor['setor'] , 'responsavel' => $responsavel, 'nome' => $nome, 'dados_chamado' => $dados_chamado, 'unidade' => $sede, 'status' => $status );

			return $array_dados;

		}

		public function dadosMensagem($id_chamado){

			$array_mensagens = array();

			/* Recuperando todas as mensagems do chamado*/

			$query = "SELECT * FROM cw_comentarios WHERE id_chamado = $id_chamado ORDER BY data_comentario DESC ";
			$query = DBExecute($query);

			return $query;

		}

		/* função para retornar os usuários do sistema */

		public function returnPessoas() {

			/* iniciando e executando a query */

			$query = "SELECT * FROM cw_usuarios";
			$query = DBExecute($query);

			/* iniciando e preenchendo a array de retorno */

			$retorno = array();

			while($row = mysqli_fetch_assoc($query)) {

				array_push($retorno, $row);
			}

			return $retorno;

		}

		public function returnQuery($dateIn, $dateFn, $status, $items_pagina, $total_reg, $tipo){

			/* função que retorna algumas querys que são utilizadas durante a aplicação */
		
			($status == 'Todos' || !$status) ? $string_status = 'setor > 0' : $string_status = "status_atual = '$status'";
			($tipo == 'todas') ? $string_tipo = "setor > 0" : $string_tipo = "tipo = '$tipo'";

			$queryLista = "SELECT * FROM cw_chamados WHERE  $string_status AND  $string_tipo AND prazo BETWEEN '$dateIn' AND '$dateFn' ORDER BY id DESC LIMIT $items_pagina, $total_reg";

			$queryListaTodos = "SELECT * FROM cw_chamados WHERE $string_status AND $string_tipo AND  prazo BETWEEN '$dateIn' AND '$dateFn' ORDER BY id DESC";

			$array_dados = array("queryLista" => $queryLista, "queryListaTodos" => $queryListaTodos);

			return $array_dados;

			}

		public function returnHistorico($id_chamado){

			/* função para retornar o histórico de alterações no chamado */

			$query = "SELECT * FROM cw_historico WHERE id_chamado = $id_chamado";
			$dados = DBExecute($query);

			return $dados;

		}

		public function formataData($data){

			/* função que formata a data do formato datetime para um string */

			$dataArray = explode(" ", $data);
			$data_chamado_dias = $dataArray[0]; 
			$data_chamado_hora = $dataArray[1];
			$data_chamado_dias = explode("-", $data_chamado_dias);
			$data_chamado_dias = $data_chamado_dias[2].'/'.$data_chamado_dias[1].'/'.$data_chamado_dias[0];
			$data_chamado = $data_chamado_dias . " ás " . $data_chamado_hora;

			return $data_chamado;
		}

		/* função que fornece a página home a quantidade de chamados em andamento e atraso do dono logado */

		public function nchamados($id_usuario) {

			/* iniciando a query */

			$query = "SELECT * FROM cw_chamados WHERE responsavel = $id_usuario";
			$query = DBExecute($query);

			/* variáveis auxiliares */

			$andamento = 0; $atrasado = 0; $finalizado = 0;

			/* iniciando as verificações */

			while ($row = mysqli_fetch_assoc($query)) {

				if($row['status_atual'] == 'Andamento'){
					$andamento++;

				}else if($row['status_atual'] == 'Atrasado'){
					$atrasado++;
				}else{
					$finalizado++;
				}

			}

			/* array para retorno */

			return array('andamento' => $andamento, 'atrasado' => $atrasado, 'finalizado' => $finalizado);

		}
	}
?>