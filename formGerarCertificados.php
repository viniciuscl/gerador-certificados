
<script type="text/javascript" src="resources/js/gerarCertificados.js"></script>

<div id="div-formulario-gerar-certificados">			
		<form method="post" action="certificado.php">
			<fieldset id="formulario-gerar-certificados">
				<legend>Gerar Certificados</legend>
				
				<h3 style="color:red; text-align:center;">* Campos Obrigatórios</h3> <br/>
				
				<table>
					<tr>
						<td><label for="nomeEvento">Nome do Evento<label><span class="required">*</span></td>
						<td>					
						  <input type="text" size="75" maxlength="200" name="nomeEvento" id="nomeEvento" required/>
						</td>
					</tr>
					
					<tr>
						<td><label for="organizadorEvento">Organizador do Evento<label><span class="required">*</span></td>
						<td>					
						  <input type="text" size="75" maxlength="100" name="organizadorEvento" id="organizadorEvento" required/>
						</td>
					</tr>

					<tr>
						<td><label for="palestrante">Palestrante</label><span class="required">*</span></td>
						<td>					
							<input type="text"  size="50" maxlength="50" id="palestrante" name="palestrante"/> 
							<input type="checkbox" name="varios" value="varios" onclick="modificaCampo();"/> V&aacute;rios
						</td>
					</tr>
				   
				   
					<tr>
						<td><label for="dtaini">Data Inicial</label><span class="required">*</span></td>
						<td>
							<input type="date" maxlength="10" onkeypress="mascara(this, '##-##-####');" size="10"name="dtaini" id="dtaini" required/> 		 
							<font size="1"><?php if(buscaBrowser() == "Firefox" || buscaBrowser() == "IE") { ?>Ex: DD-MM-AAAA <?php } ?></span> 						
						</td>				  
					</tr>  
					
					<tr>
						<td><label for="dtafim">Data Final</label><span class="required">*</span></td>
						<td>
							<input type="date" name="dtafim" id="dtafim" maxlength="10" onkeypress="mascara(this, '##-##-####');" size="10" required/> 		 
							<font size="1"><?php if(buscaBrowser() == "Firefox" || buscaBrowser() == "IE") { ?>Ex: DD-MM-AAAA <?php } ?></span> 						
						</td>				  
					</tr>  	
					
					<tr>
						<td><label for="cargaHoraria">Carga Hor&aacute;ria</label><span class="required">*</span></td>
						<td>					
							<input type="text" size="1" maxlength="3" id="cargaHoraria" name="cargaHoraria" required /> Horas
						</td>
					</tr>	
					
					<tr>
						<td><label for="emailResp">E-mail do Responsável</label><span class="required">*</span></td>
						<td>					
							<input type="text" size="30" maxlength="100" id="emailResp" name="emailResp" required /> <br/> 
							O responsável receberá todos os certificados gerados em seu e-mail.
						</td>
					</tr>	
					
					<tr>
						<td><label for="participantes">Nomes (um por linha)</label><span class="required">*</span></td>
						<td>
							<textarea name="participantes" id="participantes" rows="20" cols="70" required></textarea> <br/><br/>
							Para enviar os certificados por e-mail aos participantes, insira o e-mail ao lado do nome separado por vírgula. <br/><br/>
							<strong>Exemplo:</strong> Fulano da Silva, fulano@silva.com.br				  
						</td>
					</tr>		
				</table>
				
				<br/>
				<center><button type="submit" id="botao-gerar-certificados">Gerar Certificados</button></center>
			</fieldset>			
		</form>
	
</div>