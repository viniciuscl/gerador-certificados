<?php 

function buscaBrowser() { 
  $useragent = $_SERVER['HTTP_USER_AGENT'];
 
  if (preg_match('|MSIE ([0-9].[0-9]{1,2})|',$useragent,$matched)) {
    $browser_version=$matched[1];
    $browser = 'IE';
  } elseif (preg_match( '|Opera/([0-9].[0-9]{1,2})|',$useragent,$matched)) {
    $browser_version=$matched[1];
    $browser = 'Opera';
  } elseif(preg_match('|Firefox/([0-9\.]+)|',$useragent,$matched)) {
    $browser_version=$matched[1];
    $browser = 'Firefox';
  } elseif(preg_match('|Chrome/([0-9\.]+)|',$useragent,$matched)) {
    $browser_version=$matched[1];
    $browser = 'Chrome';
  } elseif(preg_match('|Safari/([0-9\.]+)|',$useragent,$matched)) {
    $browser_version=$matched[1];
    $browser = 'Safari';
  } else {
    // browser not recognized!
    $browser_version = 0;
    $browser= 'other';
  }
  return $browser;
}

session_start(); 


if(!isset($_SESSION['user'])) { //nao encontrou usuario, exclui variáveis de sessão e redireciona
	echo "<script>window.location.href='index.php';</script>";
	unset($_SESSION['user']);
} else {

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Gerar Certificados</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

	<!-- jQuery -->
	<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" />	
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>	
	
	<!-- PrimeUI -->
	<link href="resources/libs/primeui/primeui-1.0/development/primeui-1.0.css" rel="stylesheet">	
	<link href="resources/libs/primeui/primeui-1.0/themes/aristo/theme.css" rel="stylesheet">
	<link href="resources/libs/primeui/sh.css" rel="stylesheet">
	<link href="resources/libs/primeui/all.css" rel="stylesheet">
	<script type="text/javascript" src="resources/libs/primeui/primeui-1.0/development/primeui-1.0.js"></script>
	
	<link type="text/css" href="resources/css/style.css" rel="stylesheet"/>	
</head>

<body>

	<div id="opcoes">
		<h2>Gerador de Certificados</h2> <br/>
		<button type="button" id="botao-sair" onclick="window.location.href='logout.php';">Sair</button>
	</div>

	<?php include_once("formGerarCertificados.php"); ?>
	<?php //include_once("formGerenciarUsuarios.php"); ?>
</body>


</html>


<?php
}
?>