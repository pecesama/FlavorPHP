	<?php echo $this->renderElement("admin_header"); ?>

    <div id="page-navigation" class="clearfix">
			
			<ul>				
				<li class="current">
					<?php echo $this->html->linkTo("Administrar posts", "admin/", " title=\"Administrar los posts\""); ?>
				</li>
				<li>
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
			
			<h2>Panel de administraci&oacute;n<span> (editar y eliminar)</span></h2>
			<div class="inner-box clearfix">
			
				<div id="sidebar">
				
					<ul>
						<li class="head">Herramientas</li>
						<li class="current">
							<?php echo $this->html->linkTo("Posts", "admin/", " title=\"Administrar los posts\""); ?>
						</li>
						<li>
							<?php echo $this->html->linkTo("Comentarios", "admin/comments/", " title=\"Administrar los comentarios\""); ?>
						</li>
						<li>
							<?php echo $this->html->linkTo("Enlaces", "admin/comments/", " title=\"Administrar los enlaces\""); ?>
						</li>
					</ul>				
				
				</div>
			
				<div id="table-block">

					<table cellspacing="0" cellpadding="0">
					<tbody>						
						<?php
						if ($posts == null) {
						?>						
							<div class="error">
								No hay posts
			                </div>
						<?php
						} else {
						?>
							<tr class="header">
								<td>T&iacute;tulo</td>
								<td>Estado</td>							
								<td>Acciones</td>
							</tr>
						<?php
							$odd = false;
							foreach ($posts as $post) { 
								$alt = ($odd) ? "class=\"alternate\"" : "" ;
								$status = ($post["status"] == "publish") ? "<td class=\"true\">published</td>" : "<td class=\"false\">draft</td>" ;
						?>
							<tr <?php echo $alt; ?>>
								<td><?php echo $post["title"]; ?></td>
								<?php echo $status; ?>
								<td class="actions">
									<?php echo $this->html->linkTo($this->l10n->__("Edit"), "admin/edit/".$post["ID"]."/", " title=\"Edit post\""); ?>
									| 
									<?php echo $this->html->linkToConfirm($this->l10n->__("Remove"), "admin/remove/".$post["ID"]."/"); ?>
								</td>								
							</tr>
						<?php
								$odd = !$odd;
							} 
						} 
						?>
					</tbody>
				</table>
				<?php echo $pagination; ?>
				</div>
			
			</div>
				
		</div>
	
	
	<?php echo $this->renderElement("admin_footer"); ?>