		$(function() {
			$('#formulario-gerar-certificados').puifieldset({
				toggleable: true,
				collapsed: false
			});			
			$('#modelo').puidropdown(); 
			$('#nomeEvento').puiinputtext(); 
			$('#palestrante').puiinputtext(); 
			$('#dtaini').puiinputtext(); 
			$('#dtafim').puiinputtext(); 
			$('#cargaHoraria').puiinputtext(); 
			$('#emailResp').puiinputtext(); 
			$('#organizadorEvento').puiinputtext(); 
			$('#participantes').puiinputtextarea(); 
			$('#botao-sair').puibutton();
			$('#botao-gerar-certificados').puibutton();
		});

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

		