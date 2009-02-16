	<?php echo $this->renderElement("admin_header"); ?>

    <div id="page-navigation" class="clearfix">
			
			<ul>				
				<li>
					<?php echo $this->html->linkTo("Administrar posts", "admin/", " title=\"Administrar los posts\""); ?>
				</li>
				<li class="current">
					<?php echo $this->html->linkTo("Agregar post", "admin/add/", " title=\"Agregar un nuevo post\""); ?>
				</li>
				<li>
					<?php echo $this->html->linkTo("Configuraci&oacute;n", "admin/config/", " title=\"Configurar blog\""); ?>
				</li>
				<li>
					<?php echo $this->html->linkTo("Cerrar sesi&oacute;n", "admin/logout/", " title=\"Terminar la sesi&oacute;n\""); ?>
				</li>
				<li>
					<?php echo $this->html->linkTo("Ir al blog", "", " title=\"Regresar al blog\""); ?>
				</li>
			</ul>
				
		</div>
		
		<div id="page-content" class="clearfix"> 
			
			<h1>Codice CMS Dashboard</h1> 
			
			<h2>Agregar post</h2> 
			<div class="inner-box clearfix">
			
				<div id="form-block"> 
									
					<?php echo $this->html->formPost("admin/add/"); ?>
							
						<label for="title">T&iacute;tulo</label>
						<?php echo $this->html->textField("title"); ?>						

						<label for="content">Contenido</label>
						<?php echo $this->html->textArea("content"); ?>
						<em>Contenido del post</em>
						
						<label for="tags">Etiquetas</label>
						<?php echo $this->html->textField("tags", " value=\"\" class=\"medium\" "); ?>
						<em>Separa cada etiqueta con un espacio: moblog urbano teléfono. O bien, para unir 2 palabras en una sola etiqueta, utiliza comillas dobles: "transporte diario". </em>
						
						<input class="submit reset" id="cancelar" name="cancelar" type="submit" value="Cancelar">
						<input class="submit draft" id="borrador" name="borrador" type="submit" value="Borrador" /> 
						<input class="submit" id="publicar" name="publicar" type="submit" value="Agregar post" /> 
				 				
							
						</form>
				
				</div> 
			
			</div> 
		</div>
	
	
	<?php echo $this->renderElement("admin_footer"); ?>
