<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<title><?php echo $title_for_layout; ?></title>
		<meta name="generator" content="flavorPHP" />
        <?php echo $this->html->charsetTag("UTF-8"); ?>
        <?php echo $this->html->includeCss("default"); ?>
		<?php echo $this->html->includeJs("jquery"); ?>
		<?php echo $this->html->includeJs("markitup/jquery.markitup.pack"); ?>
		<?php echo $this->html->includeJs("markitup/sets/html/set"); ?>
		<?php echo $this->html->includeCssAbsolute("js/markitup/skins/simple/style"); ?>
		<?php echo $this->html->includeCssAbsolute("js/markitup/sets/html/style"); ?>		
		<script type="text/javascript" >
		   $(document).ready(function() {
			  $("#content").markItUp(my_html);
		   });
		</script>		
	</head> 
<body> 
	<div id="page-container"> 
    	<?php echo $content_for_layout ?>
	</div>
</body>
</html> 
