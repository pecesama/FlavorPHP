	<?php echo $this->renderElement("admin_header"); ?>

    <div id="page-navigation" class="clearfix"> 
        <ul class="aright">
	        <li><?php echo $this->html->linkTo("Regresar", "", " title=\"Regresar al inicio\""); ?></li>
        </ul>
    </div>
    
    <div id="page-content" class="clearfix">    
        <h1>Ingresar</h1> 
        <div class="inner-box clearfix">
        	<?php if ($this->cookie->check('flash')) { ?>
            <div id="sidebar">
            	<div class="error">
		            <?php echo $this->cookie->flash; ?>
                </div>
            </div>
            <?php } ?>
            <?php echo $this->html->formPost("admin/login/"); ?>			
            <div id="form-block">    
                <label for="login">Usuario</label>                
                <?php echo $this->html->textField("login", " class=\"short\" "); ?>
                <label for="password">Contrase&ntilde;a</label> 
                <?php echo $this->html->passwordField("password", " class=\"short\" "); ?>                
                <input type="submit" class="submit" value="Ingresar" />    
            </div>
            </form>
        </div>    
    </div>
	
	<?php echo $this->renderElement("admin_footer"); ?>
