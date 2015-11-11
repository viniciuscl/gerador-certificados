<?php

session_start();

$usuario = $_POST['login'];
$passwd = $_POST['password'];

$usuarioValido = "cpd";
$senhaValida = "cl3li4#123";

//Verifica Dados do Usuário
if($usuario != $usuarioValido || $passwd != $senhaValida) { //usuário não existe
	echo "<script> alert('Usuário ou Senha Inválidos!');
				   window.parent.location.href='http://cpd.cirp.usp.br/certificados/index.html'
		  </script>";
} else {
	$_SESSION['user'] = $usuario;
	echo "<script language='javascript'> parent.window.location.href='http://cpd.cirp.usp.br/certificados/solicitaDados.php' </script>";
}

	


?>