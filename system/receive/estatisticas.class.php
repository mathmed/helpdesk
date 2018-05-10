<?php

/* classe responsável por tratar as informações necessárias para a página estatísticas */
class estatisticas {

	public function infoGerais($dateIn, $dateFn) {

			/* iniciando a query para pegar todos os chamados */

			$query = "SELECT * FROM cw_chamados WHERE prazo BETWEEN '$dateIn' AND '$dateFn'";
			$query = DBExecute($query);

			/* guardando informações relevantes */

			$total = 0; $atrasados = 0; $andamento = 0; $finalizados = 0; $finalizados_normal = 0; $finalizados_atraso = 0;

			/* criando arrays auxiliares */

			$array_status = array('Atrasados' => 0, 'Andamento' => 0, 'Finalizados' => 0);
			$array_finalizados =  array("Finalizados em tempo determinado" => 0, "Finalizados com remarcações" => 0);

			$array_pessoas =  array();
			$array_pessoas_atrasadas = array();
			$array_pessoas_finalizadas = array();
			$array_pessoas_andamento = array();

			$array_setores =  array();
			$array_setores_atrasadas = array();
			$array_setores_finalizadas = array();
			$array_setores_andamento = array();

			$array_tipos = array();
			$array_tipos_atrasadas = array();
			$array_tipos_finalizadas = array();
			$array_tipos_andamento = array();

			/* iniciando a verificação de todos os chamados */

			while ($row = mysqli_fetch_assoc($query)) {

				/* guardando a quantidade total */

				/* verificando quem foi o emissor */

				$total++;

				if($row['emissor'] != 0){
					$emissor = $row['emissor'];
					$emissor = mysqli_fetch_assoc(DBExecute("SELECT nome FROM cw_usuarios WHERE id = $emissor"))['nome'];
				}else{

					$emissor = $row['outro_emissor'] ;
				}

				/* verificando qual o setor */

				$setor = $row['setor'];
				$setor = mysqli_fetch_assoc(DBExecute("SELECT setor FROM cw_setores WHERE id = $setor"))['setor'];

				/* verificando qual o tipo */

				$tipo = $row['tipo'];
				$tipo = "SELECT tipo_chamado FROM cw_tipos WHERE id = $tipo";
				$tipo = mysqli_fetch_assoc(DBExecute($tipo))['tipo_chamado'];

				/* verificando qual o status */

				if($row['status_atual'] == 'Andamento'){

					$andamento++;
					$array_status['Andamento']++;

					/* colocando no array das pessoas andamento */

					if(!array_key_exists($emissor, $array_pessoas_andamento)){

						$array_pessoas_andamento[$emissor] = 1;

					}else{
						$array_pessoas_andamento[$emissor]++;
					}

					/* colocando no array dos setores andamento */

					if(!array_key_exists($setor, $array_setores_andamento)){

						$array_setores_andamento[$setor] = 1;

					}else{
						$array_setores_andamento[$setor]++;
					}

					/* colocando no array dos tipos andamento */

					if(!array_key_exists($tipo, $array_tipos_andamento)){

						$array_tipos_andamento[$tipo] = 1;

					}else{
						$array_tipos_andamento[$tipo]++;
					}

				}else if ($row['status_atual'] == 'Finalizado'){

					$finalizados++;
					$array_status['Finalizados']++;

					/* colocando no array das pessoas finalizadas */

					if(!array_key_exists($emissor, $array_pessoas_finalizadas)){

						$array_pessoas_finalizadas[$emissor] = 1;

					}else{
						$array_pessoas_finalizadas[$emissor]++;
					}


					/* colocando no array dos setores finalizadas */

					if(!array_key_exists($setor, $array_setores_finalizadas)){

						$array_setores_finalizadas[$setor] = 1;

					}else{
						$array_setores_finalizadas[$setor]++;
					}

					/* colocando no array dos tipos finalizadas */

					if(!array_key_exists($tipo, $array_tipos_finalizadas)){

						$array_tipos_finalizadas[$tipo] = 1;

					}else{
						$array_tipos_finalizadas[$tipo]++;
					}

					/* verificando se já foi remarcada ou não */

					if($row['nremarcadas'] > 1){
						$finalizados_atraso++;
						$array_finalizados["Finalizados com remarcações"]++;
					}else{

						$finalizados_normal++;
						$array_finalizados["Finalizados em tempo determinado"]++;
					}
					

				}else{

					$array_status['Atrasados']++;
					$atrasados++;

					/* colocando no array das pessoas atrasadas */

					if(!array_key_exists($emissor, $array_pessoas_atrasadas)){

						$array_pessoas_atrasadas[$emissor] = 1;

					}else{
						$array_pessoas_atrasadas[$emissor]++;
					}

					/* colocando no array dos setores atrasadas */

					if(!array_key_exists($setor, $array_setores_atrasadas)){

						$array_setores_atrasadas[$setor] = 1;

					}else{
						$array_setores_atrasadas[$setor]++;
					}

					/* colocando no array dos tipos atrasados */

					if(!array_key_exists($tipo, $array_tipos_atrasadas)){

						$array_tipos_atrasadas[$tipo] = 1;

					}else{
						$array_tipos_atrasadas[$tipo]++;
					}

				}


				/* guardando o total de chamados de pessoas, setores e tipos */

				if(!array_key_exists($emissor, $array_pessoas)){

					$array_pessoas[$emissor] = 1;

				}else{
					$array_pessoas[$emissor]++;
				}

				if(!array_key_exists($setor, $array_setores)){

					$array_setores[$setor] = 1;

				}else{
					$array_setores[$setor]++;
				}

				if(!array_key_exists($tipo, $array_tipos)){

					$array_tipos[$tipo] = 1;

				}else{
					$array_tipos[$tipo]++;
				}
			}


			/* criação da string para o gráfico */

			$string = $this->transformaEmString($array_status, $array_pessoas, $array_finalizados, $array_pessoas_atrasadas, $array_pessoas_finalizadas, $array_pessoas_andamento, $array_setores, $array_setores_atrasadas, $array_setores_finalizadas, $array_setores_andamento, $array_tipos, $array_tipos_finalizadas, $array_tipos_atrasadas, $array_tipos_andamento);

			/* criação do array para retorno */

			$array_retorno  = array('Grafico' => $string, 'Total' => $total, 'Atrasados' =>  $atrasados, 'Finalizados' => $finalizados,'Andamento' => $andamento);

			return $array_retorno;

		}


		/* função que transforma os arrays em string no formato aceito pelo google charts */

		public function transformaEmString ($vetor_status, $vetor_pessoas, $vetor_finalizados, $vetor_pessoas_atrasadas, $vetor_pessoas_finalizadas, $vetor_pessoas_andamento, $vetor_setores, $vetor_setores_atrasadas, $vetor_setores_finalizadas, $vetor_setores_andamento, $vetor_tipos, $vetor_tipos_finalizados, $vetor_tipos_atrasados, $vetor_tipos_andamento){


			$string_status = "['Status', 'Porcentagem'],";

			foreach ($vetor_status as $key => $value) {
				
				$string_status .= "['".$key."',".$value."],";

			}

			$string_pessoas = "['Pessoa', 'Porcentagem'],";

			foreach ($vetor_pessoas as $key => $value) {
				
				$string_pessoas .= "['".$key."',".$value."],";

			}

			$string_tipos = "['Tipo', 'Porcentagem'],";

			foreach ($vetor_tipos as $key => $value) {
				
				$string_tipos .= "['".$key."',".$value."],";

			}

			$string_pessoas_atrasadas = "['Pessoa', 'Porcentagem'],";

			foreach ($vetor_pessoas_atrasadas as $key => $value) {
				
				$string_pessoas_atrasadas .= "['".$key."',".$value."],";

			}
			$string_pessoas_finalizadas = "['Pessoa', 'Porcentagem'],";

			foreach ($vetor_pessoas_finalizadas as $key => $value) {
				
				$string_pessoas_finalizadas .= "['".$key."',".$value."],";

			}

			$string_pessoas_andamento = "['Pessoa', 'Porcentagem'],";

			foreach ($vetor_pessoas_andamento as $key => $value) {
				
				$string_pessoas_andamento .= "['".$key."',".$value."],";

			}

			$string_finalizados = "['Status', 'Porcentagem'],";
			foreach ($vetor_finalizados as $key => $value) {
				
				$string_finalizados .= "['".$key."',".$value."],";

			}


			$string_setores = "['Status', 'Porcentagem'],";
			foreach ($vetor_setores as $key => $value) {
				
				$string_setores .= "['".$key."',".$value."],";

			}

			$string_setores_atrasadas = "['Status', 'Porcentagem'],";
			foreach ($vetor_setores_atrasadas as $key => $value) {
				
				$string_setores_atrasadas .= "['".$key."',".$value."],";

			}


			$string_setores_finalizadas = "['Status', 'Porcentagem'],";
			foreach ($vetor_setores_finalizadas as $key => $value) {
				
				$string_setores_finalizadas .= "['".$key."',".$value."],";

			}

			$string_setores_andamento = "['Status', 'Porcentagem'],";
			foreach ($vetor_setores_andamento as $key => $value) {
				
				$string_setores_andamento .= "['".$key."',".$value."],";

			}

			$string_tipos_finalizados = "['Status', 'Porcentagem'],";
			foreach ($vetor_tipos_finalizados as $key => $value) {
				
				$string_tipos_finalizados .= "['".$key."',".$value."],";

			}

			$string_tipos_atrasados = "['Status', 'Porcentagem'],";
			foreach ($vetor_tipos_atrasados as $key => $value) {
				
				$string_tipos_atrasados .= "['".$key."',".$value."],";

			}

			$string_tipos_andamento = "['Status', 'Porcentagem'],";
			foreach ($vetor_tipos_andamento as $key => $value) {
				
				$string_tipos_andamento .= "['".$key."',".$value."],";

			}

			/* array de arrays, para o retorno */

			return array('string_status' => $string_status, 'string_pessoas' => $string_pessoas,'string_finalizados' => $string_finalizados, 'string_pessoas_atrasadas' => $string_pessoas_atrasadas, 'string_pessoas_finalizadas' => $string_pessoas_finalizadas, 'string_pessoas_andamento' => $string_pessoas_andamento, 'string_setores' => $string_setores, 'string_setores_atrasadas' => $string_setores_atrasadas, 'string_setores_finalizadas' => $string_setores_finalizadas, 'string_setores_andamento' => $string_setores_andamento, 'string_tipos' => $string_tipos, 'string_tipos_finalizadas' => $string_tipos_finalizados, 'string_tipos_atrasadas' => $string_tipos_atrasados, 'string_tipos_andamento' => $string_tipos_andamento) ;
			

		}
	}