<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Gerador de Certificados</title>

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

	<script type="text/javascript">
		$(function() {
			$('#acesso,#verificar-autenticidade').puifieldset();			
			$('#usuario').puiinputtext(); 
			$('#senha').puiinputtext(); 
			$('#codigo').puiinputtext(); 
			$('#entrar').puibutton();
			$('#admin').puibutton();
			$('#verificar').puibutton();
		});
	</script>
	
</head>

<body>

	<div id="login">
		
		<h2>Emissor de Certificados</h2> <br/><br/>
		<form method="post" action="validaUsuario.php">
			<fieldset id="acesso">
				<legend>Acesso</legend>
				<table>	
					<tr>
						<td><label for="usuario">Usuário</label></td>
						<td><input type="text" id="usuario" name="usuario" size="15" required/></td>
					</tr>

					<tr>
						<td><label for="senha">Senha</label></td>
						<td><input type="password" id="senha" name="senha" size="15" required/></td>
					</tr>
				</table>
				
				<br/><br/>
				<button type="submit" id="entrar">Entrar</button>				
			</fieldset>	
		</form>
		
		<br/><br/>		
		<form method="post" action="index.php">
			<fieldset id="verificar-autenticidade">
				<legend>Verificar Certificado</legend>
				<table>	
					<tr>
						<td><label for="usuario">Código</label></td>
						<td><input type="text" id="codigo" name="codigo" size="30" required/></td>
					</tr>
				</table>
				
				<button type="submit" id="verificar">Verificar</button>				
															
			</fieldset>	
		</form>
				
		
		<?php
					if(isset($_POST["codigo"])) {
						
						include_once("banco.php");
						
						/**
							BUSCA NO BANCO DE DADOS O ENDEREÇO DO SEU SITE E O CAMINHO NO SERVIDOR PARA ARMAZENAR OS CERTIFICADOS GERADOS
						**/		
						$sql = "SELECT url_site FROM config;";
						$query = $conn->query($sql);
						$result = $query->fetch_array();
						
						$url_site = $result["url_site"];
						
						$hash = trim($_POST['codigo']);
						
						$sql = "SELECT * FROM certificado WHERE hash_validacao LIKE '$hash';";
						$query = $conn->query($sql);
						$result = $query->fetch_all(MYSQL_BOTH);
						
						if($query->num_rows == 1) {
							extract($result[0]);							
							echo "<div id='div-info-certificado'>";
								echo "<h2 style='color:red;'>Certificado Válido</h2><br/>";
								echo "<strong>Código de Verificação:</strong> $hash_validacao <br/>";
								echo "<strong>Evento:</strong> $evento <br/>";
								echo "<strong>Organizador: </strong> $organizador_evento <br/>";
								echo "<strong>Participante:</strong> $nome_participante <br/>";
								echo "<strong>Data de Emissão:</strong> $data <br/><br/>";
								
								$evento_  = str_replace(" ", "_", $evento);
								$participante_ = str_replace(" ", "_", $nome_participante);
								echo "<a href='$url_site/arquivos/$evento_/$participante_.pdf' target='_blank'>Visualizar Certificado</a>";
							echo "</div>";
							
						} else {
							echo "<br/><br/><h2 style='color:red;'>Certificado Inválido</h2>";
						}
					}			
		?>

		
	</div>

</body>
</html>
