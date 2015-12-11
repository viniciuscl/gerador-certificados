<?php 

session_start(); 

include_once("banco.php");

if(!isset($_SESSION['user']))  { //nao encontrou usuario, exclui variáveis de sessão e redireciona
	echo "<script>window.location.href='index.php';</script>";
	unset($_SESSION['user']);
} else  {		
		
		/**
			LIBS FPDF
		**/
		require_once('resources/libs/fpdf/fpdf.php');
		require_once("resources/libs/fpdf/makefont/makefont.php"); 
		define('FPDF_FONTPATH','resources/libs/fpdf/font'); 		
		
		/**
			BUSCA NO BANCO DE DADOS O ENDEREÇO DO SEU SITE E O CAMINHO NO SERVIDOR PARA ARMAZENAR OS CERTIFICADOS GERADOS
		**/		
		$sql = "SELECT * FROM config;";
		$query = $conn->query($sql);
		$result = $query->fetch_array();
		
		$downloadURL = $result["url_download_certificados"];
		$caminhoServidor = $result["caminho_armazenar_certificados"];
				
		/** RECEBE E TRATA OS DADOS ENVIADOS PELO FORMULÁRIO **/
		$nomes = $_POST['participantes'];
		$DataInicial = $_POST['dtaini'];
		$DataFim = $_POST['dtafim'];		
		$CargaHoraria = $_POST['cargaHoraria'];
		$emailResp = $_POST['emailResp'];
		$organizadorEvento = $_POST['organizadorEvento'];
		
		if(isset($_POST['varios'])) {
			$orador = "";
		} else {
			$orador = $_POST['palestrante'];
		}
		
		$Evento = trim($_POST['nomeEvento']);
			
		$participantes = explode("\n",$nomes);		
		$qtd_nomes = sizeof($participantes);
		
		$NomeAluno = array();
		for($i=0;$i<$qtd_nomes;$i++) {
			$aux = explode(",",trim($participantes[$i]));
			if(sizeof($aux) == 2) {
				$NomeAluno[$i] = $aux[0];
				$EmailAluno[$i] = $aux[1];
			} else {
				$NomeAluno[$i] = trim($participantes[$i]);
			}
		}	
		
		$EventoSemAcent = remove_acentuacao($Evento,true);
					
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
		
		//VERIFICA SE O CAMINHO NO SERVIDOR PARA ARMAZENAR OS CERTIFICADOS EXISTE. CRIA SE NÃO EXISTIR.
		if(!is_dir($caminhoServidor."/$EventoSemAcent")) {
			mkdir($caminhoServidor."/$EventoSemAcent");
		}

		//CRIA E ABRE ARQUIVO ZIP QUE SERÁ ENVIADO POR E-MAIL PARA O RESPONSÁVEL (OU ENVIADO PARA FTP DE FORMA OPCIONAL - VIDE CÓDIGO COMENTADO MAIS ABAIXO)
		$zip = new ZipArchive();
		$criou = $zip->open($caminhoServidor."/$EventoSemAcent/certificados.zip", ZipArchive::CREATE);

		//CABEÇALHO DO E-MAIL (ALTERAR CAMPOS FROM E RETURN-PATH COM OS SEUS VALORES)
		$headers = "MIME-Version: 1.1\r\n";
		$headers .= "Content-type: text/plain; charset=utf-8\r\n";
		$headers .= "From: SEU-EMAIL <seu@email.br>\r\n"; // remetente
		$headers .= "Return-Path: seu@email.br\r\n"; // return-path		
		
		
		//GERA CERTIFICADO PARA CADA NOME FORNECIDO, INSERE NO ARQUIVO ZIP, ENVIA POR E-MAIL PARA O ALUNO E INSERE NO BANCO DE DADOS
		for($i=0;$i<$qtd_nomes;$i++) {			
		
			geraCertificado($Data,$NomeAluno[$i],$Evento,$orador,$CargaHoraria,$organizadorEvento,$caminhoServidor);
			
			$NomeAlunoSemAcent = remove_acentuacao($NomeAluno[$i],true);
			$zip->addfile($caminhoServidor."/$EventoSemAcent/".$NomeAlunoSemAcent.".pdf", $NomeAlunoSemAcent.".pdf");

			$hash = md5($NomeAlunoSemAcent.$Evento);
			$data = date("y-m-d H:i:s");
			
			//ENVIA EMAIL, SE INFORMADO
			if(isset($EmailAluno) && $EmailAluno[$i] != "") {
				$msg = "Olá $NomeAluno[$i],
				\n\nSeu certificado está disponível no link abaixo.
				\n\nDownload do Certificado: $downloadURL/$EventoSemAcent/$NomeAlunoSemAcent.pdf
				\n\nPara verificar a veracidade dos certificados, acesse o link: $downloadURL
				\n\nObrigado pela sua participação!";										
				
				mail($EmailAluno[$i], "Certificado - $Evento", $msg, $headers);				
				$emailAluno = $EmailAluno[$i];
			} else {
				$emailAluno = "";
			}
			
			$sql = "INSERT INTO certificado (evento,organizador_evento,nome_participante,email_participante,hash_validacao,data) 
						VALUES ('".utf8_decode($Evento)."','".utf8_decode($organizadorEvento)."','".utf8_decode($NomeAluno[$i])."','".$emailAluno."','$hash','$data')";				
			$conn->query($sql);
			
		}

		$zip->close();	
		
		
		/**
			UTILIZAR ESTE CÓDIGO CASO DESEJE ENVIAR O .ZIP PARA UM SERVIDOR FTP
		**/
		/*
		//CONEXAO FTP
		$dados = array(
			"host" => "",
			"usuario" => "",
			"senha" => ""
		);
		
		//ORIGEM E DESTINO (ftp) DO ARQUIVO. CUIDADO COM NOMES DE ARQUIVOS ACENTUADOS
		$caminhoFTP = "";
		$caminhoOrigemArquivo = "";
				
		$fconn = ftp_connect($dados["host"]);
		ftp_login($fconn, $dados["usuario"], $dados["senha"]);		
		ftp_put($fconn, $caminhoFTP, $caminhoOrigemArquivo, FTP_BINARY);
		ftp_close($fconn);
		*/
		
		//PREPARA A MENSAGEM DO E-MAIL E ENVIA PARA O RESPONSAVEL
		$msg = "Prezado Responsável,
				\n\nClique no link abaixo para realizar o download de todos os certificados.
				\n\nDownload dos Certificados: $downloadURL/$EventoSemAcent/certificados.zip
				\n\nPara verificar a validade dos certificados, acesse o link: $downloadURL
				\n\nObrigado";										
		
		mail("$emailResp", "Certificados - $Evento", $msg, $headers);	
				

		/*echo "<script> alert('Certificados Gerados com Sucesso!');
					   window.parent.location.href='principal.php'
		     </script>";				*/
						
}

	function geraCertificado($Data,$NomeAluno,$Evento,$orador,$CargaHoraria,$organizadorEvento,$caminhoServidor) {
		
			//CRIA O PDF
			$pdf=new FPDF();
			$pdf->SetTitle('Certificado de '. utf8_decode($NomeAluno)); 
			
			// ABRE O PDF PARA EDIÇÃO
			$pdf->Open();
			
			// ADICIONA UMA PÁGINA AO ARQUIVO
			$pdf->AddPage('L','A4');
			$pdf->AddFont('Century','','Century.php');
						
			// ADICIONA IMAGEM (CABEÇALHO)
			$pdf->Image('resources/images/header.png',0,5);
			
			$pdf->SetY(67);
			$pdf->SetX(20);
			
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFont('Century', '', 18);
			
			
			if($orador != "") {
				$pdf->MultiCell(220, 10, utf8_decode("Certifico para os devidos fins que $NomeAluno participou, como ouvinte, do evento \"$Evento\" apresentado por \"$orador\" organizado pelo(a) $organizadorEvento, $Data com carga horária de $CargaHoraria horas."),0,"J",false);
			} else {
				$pdf->MultiCell(220, 10, utf8_decode("Certifico para os devidos fins que $NomeAluno participou, como ouvinte, do evento \"$Evento\" organizado pelo(a) $organizadorEvento, $Data com carga horária de $CargaHoraria horas."),0,"J",false);
			}		
			 
			//ADICIONA A ASSINATURA
			$pdf->Image('resources/images/assinaturaFulano.gif',125,130,43,16);
			
			//NOME DO ASSINANTE
			$pdf->SetFont('Century', '', 12);
			$pdf->SetY(157);
			$pdf->SetX(73);
			$pdf->Cell(145,0,'Sr. Fulano da Silva',0,0,'C');
			
			//TITULO OU FUNCAO DO ASSINANTE
			$pdf->ln(7);
			$pdf->SetX(73);
			$pdf->Cell(145,0,utf8_decode("Título/Função de Fulano da Silva"),0,0,'C');
			
			//ENDEREÇO DO LOCAL A DESEJAR
			$pdf->SetY(175);
			$pdf->ln(4);
			$pdf->SetX(73);
			$pdf->Cell(145,0,utf8_decode("Avenida Meu Endereço, São Paulo - SP"),0,0,'C');
			
			//RODAPE COM DEMAIS INFORMACOES
			$pdf->ln(7);
			$pdf->SetX(73);
			$pdf->Cell(145,0,'(11) 5555-5555 - www.seusite.com.br',0,0,'C');

			//GERA O ARQUIVO PDF COM O NOME DO ALUNO E SALVA NO SERVIDOR EM PASTA ESPECIFICA DO EVENTO
			$NomeAlunoSemAcent = remove_acentuacao($NomeAluno,true);					
			$EventoSemAcent = remove_acentuacao($Evento,true);			
			$pdf->Output($caminhoServidor."/$EventoSemAcent/".$NomeAlunoSemAcent.".pdf","F");
			
		
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

  
