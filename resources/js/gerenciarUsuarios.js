		$(function() {
			$('#formulario-gerenciar-usuarios').puifieldset({
				toggleable: true,
				collapsed: true
			});			

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
		
		

		