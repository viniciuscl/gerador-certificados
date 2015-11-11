
<html>
<head></head>

<body bgcolor="white">

<?php

include_once("../banco.php");

$hash = trim($_POST['codigo']);

$sql = "SELECT * FROM certificado WHERE hash_validacao LIKE '$hash';";

$result = mysql_query($sql);
$qtd_reg = mysql_num_rows($result);
$linhas = mysql_fetch_assoc($result);

echo "<br/><br/><center><font color='red' size='4'><b>Centro de Produção Digital - DTI - RP</b></font></center><br/>";


if($qtd_reg == 1) {
	echo "<center><h2>Certificado Válido!</h2>"."<br/><br/>";
	echo "<b>Código de Validação: </b>".$linhas['hash_validacao']."<br/><br/>";
	echo "<b>Nome do Evento: </b>".utf8_encode($linhas['evento'])."<br/><br/>";
	echo "<b>Participante: </b>".$linhas['nome_participante']."<br/><br/>";
	echo "<b>Certificado gerado em: </b>".$linhas['data']."<br/><br/></center>";
} else {
	echo "<center><font color='red'><h2>Certificado inválido!</h2></font>"."<br/>";
	echo "<b>Código de Validação: </b>".$hash."<br/><br/></center>";
		
}

echo "<br/><br/><center><button onclick='window.history.back();'>Voltar</button></center>";


?>

</body>
</html>