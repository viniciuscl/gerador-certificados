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


if(!isset($_SESSION['user'])) 
{ //nao encontrou usuario, exclui variáveis de sessão e redireciona
	echo "<script>window.location='http://www.iea.rp.usp.br';</script>";
	unset($_SESSION['user']);
} 

else 
{

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
<head>
<title>Gerar Certificado</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">

.style7 {font-family: Verdana, Arial, Helvetica, sans-serif; padding: 15px 0 0 0; font-size: 14px;}
.style8 {font-family: Verdana, Arial, Helvetica, sans-serif; margin: 0 5px 0 -50px; padding: 15px 0 0 0;}
.style9 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; }
.style11 {
	font-size: 13px;
	color: #CC6600;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
.style12 {
	font-weight: bold;
	color: black;
}

</style>

<script language="javascript">
		function mascara(src, mask){
			var i = src.value.length;
			var saida = mask.substring(0,1);
			var texto = mask.substring(i)
			if (texto.substring(0,1) != saida)
			{
			src.value += texto.substring(0,1);
			}
		} 
		
		function modificaCampo() {
			var estado = document.getElementById("palestrante").disabled;
			if(estado == false) {
				document.getElementById("palestrante").disabled = true;
			} else if (estado == true){
				document.getElementById("palestrante").disabled = false;
			}
		}
		
		
</script>



</head>

<body bgcolor="white">

<div align="center" class="style11"></div>
<br><br>
<table width="629" border="0" align="center">
    <tr>
      <td colspan="4"><div align="center" class="style12"><span class="style9"><font color="red">Centro de Produção Digital - DTI - RP</font>	<br><br>Gerador de Certificados</span></div><br/></td>
    </tr>

<form name="form1" method="post" action="certificado.php">

	<tr>
      <td width="151" class="style9"><div align="right" class="style8">Modelo do Certificado<font color="red">*</font>
      </div></td>
      <td colspan="3" class="style7"><label>
        <div align="left">
          <select size="1" name="modelo">
			<option selected value="iflalac">IFLALAC Webinar</option>
  		</select>
          </label>
        </div></td>
    </tr>

	<tr>
      <td width="151" class="style9"><div align="right" class="style8">Nome do Evento<font color="red">*</font>
      </div></td>
      <td colspan="3" class="style7"><label>
        <div align="left">
          <input name="nome" required type="text"  size="75" maxlength="100" id="nome" value="<?php if(isset($_SESSION['nome'])) { echo $_SESSION['nome']; } ?>">
          </label>
        </div></td>
    </tr>

	<tr>
      <td width="151" class="style9"><div align="right" class="style8">Palestrante<font color="red">*</font>
      </div></td>
      <td colspan="3" class="style7"><label>
        <div align="left">
          <input name="palestrante" required type="text"  size="50" maxlength="50" id="palestrante" <?php if(isset($_SESSION['palestrante']) && $_SESSION['palestrante']  == "") echo "disabled";?> value="<?php if(isset($_SESSION['palestrante'])) { echo $_SESSION['palestrante']; } ?>"> 
		  <input type="checkbox" name="varios" value="varios" onclick="modificaCampo();" <?php if(isset($_SESSION['palestrante']) && $_SESSION['palestrante']  == "") echo "checked";?>> V&aacute;rios
          </label>
        </div></td>
    </tr>
   
   
    <tr>
      <td class="style9"><div align="right" class="style8">Data Inicial<font color="red">*</font>
      </div></td>
      <td class="style7"><label>
        <div align="left">
          <input name="dtaini" required id="dtaini" type="date" maxlength="10" onkeypress="mascara(this, '##-##-####');" size="10" value="<?php if(isset($_SESSION['dtaini'])) { echo $_SESSION['dtaini']; } ?>"> 		 
         </label><font size="1"><?php if(buscaBrowser() == "Firefox" || buscaBrowser() == "IE") { ?>Ex: DD-MM-AAAA <?php } ?></font> 
			
        </div></td>
      
    </tr>  
	
    <tr>
      <td class="style9"><div align="right" class="style8">Data Final<font color="red">*</font>
      </div></td>
      <td class="style7"><label>
        <div align="left">
          <input name="dtafim" required id="dtafim" type="date" maxlength="10" onkeypress="mascara(this, '##-##-####');" size="10" value="<?php if(isset($_SESSION['dtafim'])) { echo $_SESSION['dtafim']; } ?>"> 		 
         </label><font size="1"><?php if(buscaBrowser() == "Firefox" || buscaBrowser() == "IE") { ?>Ex: DD-MM-AAAA <?php } ?></font> 
			
        </div></td>
      
    </tr>  	
	
	<tr>
      <td width="151" class="style9"><div align="right" class="style8">Carga Hor&aacute;ria<font color="red">*</font>
      </div></td>
      <td colspan="3" class="style7"><label>
        <div align="left">
          <input name="carga" required type="text"  size="1" maxlength="3" id="carga" value="<?php if(isset($_SESSION['carga'])) { echo $_SESSION['carga']; } ?>"> horas
          </label>
        </div></td>
    </tr>	
	
	<tr>
      <td width="151" class="style9"><div align="right" class="style8">E-mail do Responsável<font color="red">*</font>
      </div></td>
      <td colspan="3" class="style7"><label>
        <div align="left">
          <input name="emailResp" required type="text"  size="30" maxlength="100" id="emailResp" value="<?php if(isset($_SESSION['emailResp'])) { echo $_SESSION['emailResp']; } ?>"> <br/> O responsável receberá todos os certificados gerados em seu e-mail.
          </label>
        </div></td>
    </tr>	
	
	<tr>
      <td width="151" class="style9"><div align="right" class="style8">Nomes (um por linha)<font color="red">*</font>
      </div></td>
      <td colspan="3" class="style7"><label>
        <div align="left">
          <textarea name="participantes" id="participantes" rows="20" cols="57"><?php if(isset($_SESSION['participantes'])) { echo $_SESSION['participantes']; }?></textarea> <br/><br/>
		  Para enviar os certificados por e-mail aos participantes, insira o e-mail ao lado do nome separado por vírgula. <br/><br/>
		  <b>Exemplo:</b> Fulano da Silva, fulano@silva.com.br
          </label>
        </div></td>
    </tr>	

		
	<tr>
      <td colspan="4" bgcolor="#FFFFFF" align="center"><label>
        <br><span class="style9"><font color="red">* Campos Obrigat&oacute;rios</font></span>
      </label></td>
    </tr>

	
    <tr>
      <td colspan="4" bgcolor="#FFFFFF" align="center"><label>        
		<br><input type="submit" name="submit" value="Gerar">
		<input type="button" name="sair" value="Sair" onclick="window.location.href='logout.php';">
      </label></td>
    </tr>
    </form>
</table>
</p>


</body>


</html>


<?php
}
?>