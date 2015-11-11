<?php

		session_start();

		unset($_SESSION['user']);
		unset($_SESSION['palestrante']);
		unset($_SESSION['participantes']);
		unset($_SESSION['carga']);
		unset($_SESSION['dtaini']);
		unset($_SESSION['dtafim']);
		unset($_SESSION['modelo']);
		unset($_SESSION['nome']);		

		header("Location: http://cpd.cirp.usp.br/certificados");

?>