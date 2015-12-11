<?php

session_start();
include_once("banco.php");

$usuario = $_POST['usuario'];
$passwd = md5($_POST['senha']);

//PROCURA O USUARIO NO BANCO
$sql = "SELECT * FROM usuario WHERE login LIKE '$usuario';";
$query = $conn->query($sql);
$result = $query->fetch_array();
	
//VALIDA DADOS DO USUARIO
if($usuario != $result["login"] || $passwd != $result["senha"]) {
	echo "<script> alert('Usuário ou Senha Inválido!');</script>";
	echo "<script type='text/javascript'> parent.window.location.href='index.php' </script>";
} else { 
	$_SESSION['user'] = $usuario;
	echo "<script type='text/javascript'> parent.window.location.href='principal.php' </script>";
}

	


?>