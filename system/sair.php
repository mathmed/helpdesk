<?php
	
	session_start();
	unset($_SESSION['usuario']);
	unset($_SESSION['email']);
	unset($_SESSION['id']);
	unset($_SESSION['nome']);
	unset($_SESSION['setor_usuario']);
	unset($_SESSION['cargo']);

	header('Location: ../index.php')




?>