<?php 

session_start(); 

include_once("banco.php");

if(!isset($_SESSION['user'])) 
{ //nao encontrou usuario, exclui variáveis de sessão e redireciona
	echo "<script>window.location='http://cpd.cirp.usp.br';</script>";
	unset($_SESSION['user']);
} 

else 
{		
		require_once('fpdf/fpdf.php');
		require_once("fpdf/makefont/makefont.php"); 
		define('FPDF_FONTPATH','fpdf/font'); 		
		
		$modelo = $_POST['modelo'];
		$nomes = $_POST['participantes'];
		$DataInicial = $_POST['dtaini'];
		$DataFim = $_POST['dtafim'];		
		$CargaHoraria = $_POST['carga'];
		$emailResp = $_POST['emailResp'];
		
		if(isset($_POST['varios'])) {
			$orador = "";
		} else {
			$orador = $_POST['palestrante'];
		}
		
		$Evento = trim($_POST['nome']);
		
		$_SESSION['palestrante'] = $orador;
		$_SESSION['participantes'] = $nomes;
		$_SESSION['carga'] = $CargaHoraria;
		$_SESSION['dtaini'] = $DataInicial;
		$_SESSION['dtafim'] = $DataFim;
		$_SESSION['modelo'] = $modelo;
		$_SESSION['nome'] = $Evento;
		$_SESSION['emailResp'] = $emailResp;
		
		$aux = explode("\n",$nomes);		
		$qtd_nomes = sizeof($aux);
		
		$NomeAluno = array();
		for($i=0;$i<$qtd_nomes;$i++) {
			$aux2 = explode(",",trim($aux[$i]));
			$NomeAluno[$i] = $aux2[0];
			$EmailAluno[$i] = $aux2[1];
		}	
		
		$EventoSemAcent = remove_acentuacao($Evento,true);
		
		if(!is_numeric($CargaHoraria)) {
			echo "<script> alert('Carga Horária Inválida!');
				   window.history.back()
		    </script>";	
			break;
		}		
		
		/************* ORGANIZA DATA DO EVENTO ******************/		
		if($DataInicial > $DataFim) {
			echo "<script type=\"text/javascript\"> alert(\"A data final não pode ser menor que a data inicial!\"); </script>";
			echo "<script type=\"text/javascript\"> window.history.back() </script>";
			break;
		}
		
		$meses = array("janeiro","fevereiro","março","abril","maio","junho","julho","agosto","setembro","outubro","novembro","dezembro");
		$aux = explode("-",$DataInicial);
		
		if($aux[2]>2000) {
			$DiaInicial = $aux[0];
			$MesInicial = $meses[$aux[1]-1];
			$AnoInicial = $aux[2];				
		} else {		
			$DiaInicial = $aux[2];
			$MesInicial = $meses[$aux[1]-1];
			$AnoInicial = $aux[0];
		}
		
		$aux = explode("-",$DataFim);
		
		if($aux[2]>2000) {
			$DiaFim = $aux[0];
			$MesFim = $meses[$aux[1]-1];
			$AnoFim = $aux[2];
			$Data_Aux = $aux[0]."_".$aux[1]."_".$aux[2];				
		} else {		
			$DiaFim = $aux[2];
			$MesFim = $meses[$aux[1]-1];
			$AnoFim = $aux[0];
			$Data_Aux = $aux[2]."_".$aux[1]."_".$aux[0];
		}		
		
		if($DiaInicial == $DiaFim) {
			$Data = "no dia ".$DiaInicial." de ".$MesInicial." de ".$AnoInicial;		
		}
		
		if($DiaInicial != $DiaFim && $MesInicial == $MesFim) {
			$Data = "nos dias ".$DiaInicial." a ".$DiaFim." de ".$MesInicial." de ".$AnoInicial;
		} else if($DiaInicial != $DiaFim && $MesInicial != $MesFim) {
			$Data = "nos dias ".$DiaInicial." de ".$MesInicial." a ".$DiaFim." de ".$MesFim." de ".$AnoFim;
		}
		
		if($AnoInicial != $AnoFim) {
			$Data = "nos dias ".$DiaInicial." de ".$MesInicial." de ".$AnoInicial." a ".$DiaFim." de ".$MesFim." de ".$AnoFim;
		}
		
		/**********************************************************************************/
		
		if(!is_dir("/var/www/certificados/certificados/$EventoSemAcent")) {
			mkdir("/var/www/certificados/certificados/$EventoSemAcent");
		}

		//CRIA E ABRE ARQUIVO ZIP
		$zip = new ZipArchive();
		$criou = $zip->open("/var/www/certificados/certificados/$EventoSemAcent/certificados.zip", ZipArchive::CREATE);

		//CABEÇALHO DO E-MAIL
		$headers = "MIME-Version: 1.1\r\n";
		$headers .= "Content-type: text/plain; charset=utf-8\r\n";
		$headers .= "From: CPD - DTI-RP <cpd@cirp.usp.br>\r\n"; // remetente
		$headers .= "Return-Path: cpd@cirp.usp.br\r\n"; // return-path		
		
		
		//GERA CERTIFICADO PARA CADA NOME FORNECIDO E INSERE NO ARQUIVO ZIP E ENVIA POR E-MAIL
		for($i=0;$i<$qtd_nomes;$i++) {			
			geraCertificado($modelo,$Data,$NomeAluno[$i],$Evento,$orador,$CargaHoraria);
			$NomeAlunoSemAcent = remove_acentuacao($NomeAluno[$i],true);
			$zip->addfile("/var/www/certificados/certificados/$EventoSemAcent/".$NomeAlunoSemAcent.".pdf", $NomeAlunoSemAcent.".pdf");

			$hash = md5($NomeAlunoSemAcent.$EmailAluno[$i].$Evento);
			$data = date("y-m-d H:i:s");
			
			//ENVIA EMAIL, SE INFORMADO
			if($EmailAluno[$i] != "") {
				$msg = "Olá $NomeAluno[$i],
				\n\nSeu certificado está disponível no link abaixo.
				\n\nDownload do Certificado: http://cpd.cirp.usp.br/certificados/certificados/$EventoSemAcent/$NomeAlunoSemAcent.pdf
				\n\nPara verificar a validade dos certificados, acesse o link: http://cpd.cirp.usp.br/certificados/validar
				\n\nObrigado pela sua participação.
				\n\nCentro de Produção Digital - DTI-RP";										
				mail("$EmailAluno[$i]", "Certificado - $Evento", $msg, $headers);				
			} 
			
			$sql = "INSERT INTO certificado (modelo,evento,nome_participante,email_participante,hash_validacao,data) VALUES ('$modelo','".utf8_decode($Evento)."','".utf8_decode($NomeAluno[$i])."','$EmailAluno[$i]','$hash','$data')";				
			
			mysql_query($sql) || die(mysql_error());
		}

		$zip->close();	
		
		//CONEXAO FTP
		$dados = array(
			"host" => "ftp.sistemas.cirp.usp.br",
			"usuario" => "vinicius@cirp.usp.br",
			"senha" => "124578"
		);
				
		$fconn = ftp_connect($dados["host"]);
		ftp_login($fconn, $dados["usuario"], $dados["senha"]);		
		ftp_put($fconn, "/CPD/Certificados/certificados_".$EventoSemAcent.".zip", "/var/www/certificados/certificados/$EventoSemAcent/certificados.zip", FTP_BINARY);
		ftp_close($fconn);
		
		//Envia e-mail com o link para download de todos os certificados (.zip)
		$msg = "Prezado Responsável,
		\n\nClique no link abaixo para realizar o download de todos os certificados.
		\n\nDownload dos Certificados: http://cpd.cirp.usp.br/certificados/certificados/$EventoSemAcent/certificados.zip
		\n\nPara verificar a validade dos certificados, acesse o link: http://cpd.cirp.usp.br/certificados/validar
		\n\nCentro de Produção Digital - DTI-RP";										
		mail("$emailResp", "Certificados - $Evento", $msg, $headers);	
		
		//del_pasta("/var/www/certificados/certificados/$EventoSemAcent/");

		unset($_SESSION['palestrante']);
		unset($_SESSION['participantes']);
		unset($_SESSION['carga']);
		unset($_SESSION['dtaini']);
		unset($_SESSION['dtafim']);
		unset($_SESSION['modelo']);
		unset($_SESSION['nome']);	
		unset($_SESSION['emailResp']);	
		
		echo "<script> alert('Certificados Gerados com Sucesso!');
				   window.parent.location.href='http://cpd.cirp.usp.br/certificados/solicitaDados.php'
		    </script>";		  		
						
}

	function geraCertificado($modelo,$Data,$NomeAluno,$Evento,$orador,$CargaHoraria) {
		
		if($modelo == "iflalac") {
			//Cria o arquivo PDF
			$pdf=new FPDF();
			$pdf->SetTitle('Certificado de '. utf8_decode($NomeAluno)); 
			// Abre o arquivo PDF para edição
			$pdf->Open();
			// Adiciona uma página ao arquivo
			$pdf->AddPage('L','A4');
			$pdf->AddFont('Century','','Century.php');
			//$pdf->AddFont('CENTURY');
			// Adiciona imagem, neste caso cabeçalho e rodap´é
			$pdf->Image('images/header.png',5,5,225,45);
			$pdf->Image('images/lateral.png',247,0,50,220);

			$pdf->SetY(67);
			$pdf->SetX(20);
			
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFont('Century', '', 18);
			
			if($modelo == "iflalac") {
				if($orador != "") {
					$pdf->MultiCell(220, 10, utf8_decode("Certifico para os devidos fins que $NomeAluno participou, como ouvinte, do evento \"$Evento\" apresentado pelo(a) \"$orador\" organizado pelo Instituto de Estudos Avançados da Universidade de São Paulo, Polo Ribeirão Preto, $Data com carga horária de $CargaHoraria horas."),0,"J",false);
				} else {
					$pdf->MultiCell(220, 10, utf8_decode("Certifico para os devidos fins que $NomeAluno participou, como ouvinte, do evento \"$Evento\" organizado pelo Instituto de Estudos Avançados da Universidade de São Paulo, Polo Ribeirão Preto, $Data com carga horária de $CargaHoraria horas."),0,"J",false);
				}		
			} 
			
			$pdf->Image('images/assinaturaProfRudinei.PNG',120,130,43,16);
			$pdf->SetFont('Century', '', 12);
			$pdf->SetY(157);
			$pdf->SetX(73);
			$pdf->Cell(145,0,'Prof. Dr. Rudinei Toneto Junior',0,0,'C');
			$pdf->ln(7);
			$pdf->SetX(73);
			$pdf->Cell(145,0,utf8_decode("Coordenador do IEA - Polo Ribeirão Preto"),0,0,'C');
			$pdf->SetY(175);
			$pdf->ln(4);
			$pdf->SetX(73);
			$pdf->Cell(145,0,utf8_decode("Avenida Bandeirantes, 3.900, 14040-900, Ribeirão Preto/SP"),0,0,'C');
			$pdf->ln(7);
			$pdf->SetX(73);
			$pdf->Cell(145,0,'(16) 3602-0368 - www.iea.rp.usp.br',0,0,'C');


			// Gera o arquivo PDF	
			$NomeAlunoSemAcent = remove_acentuacao($NomeAluno,true);					
			$EventoSemAcent = remove_acentuacao($Evento,true);
			$pdf->Output("/var/www/certificados/certificados/$EventoSemAcent/".$NomeAlunoSemAcent.".pdf","F");
			
		} 
	}
	
	function del_pasta($del_pasta) {
	

		chmod("$del_pasta", 0777);
		if (is_dir($del_pasta)) {

		// abre o diretório
			$abrirPasta = opendir($del_pasta);

		// le o conteudo do diretório e joga em um array
			while ($nome_itens = readdir($abrirPasta)) {
				$itens[] = $nome_itens;
			}
		// pega a quantidade de arquivos que estão dentro do diretório
			$count = count($itens);

		// caso a contagem de arquivos seja maior que 2 executa o foreach apagando todos os arquivos,
		// maior que dois, por causa dos '.' & '..' que tambem são contados como arquivo.
			if ($count > 2) {
				foreach ($itens as $key => $arquivo) {
					if ($arquivo != '.' && $arquivo != '..') {
						unlink($del_pasta . $arquivo);
					}
				}

				//closedir($del_pasta);
				rmdir($del_pasta);
			} else {
				echo "A pasta não existe ou não pode ser deletada.";
			}	
		}
	
	}
	
	function remove_acentuacao($sub,$underline){
		$acentos = array(
			'À','Á','Ã','Â', 'à','á','ã','â',
			'Ê', 'É',
			'Í', 'í', 
			'Ó','Õ','Ô', 'ó', 'õ', 'ô',
			'Ú','Ü',
			'Ç', 'ç',
			'é','ê', 
			'ú','ü',
			' ',
			);
		
		if($underline) {
			$remove_acentos = array(
				'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
				'e', 'e',
				'i', 'i',
				'o', 'o','o', 'o', 'o','o',
				'u', 'u',
				'c', 'c',
				'e', 'e',
				'u', 'u',
				'_',
				);
		} else {
			$remove_acentos = array(
				'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
				'e', 'e',
				'i', 'i',
				'o', 'o','o', 'o', 'o','o',
				'u', 'u',
				'c', 'c',
				'e', 'e',
				'u', 'u',
				' ',
				);
		}
		
		return str_replace($acentos, $remove_acentos, urldecode($sub));
	}		

?>

  
