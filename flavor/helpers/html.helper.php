<?php

class html extends singleton {
	
	protected $registry;
	protected $path;
	protected $type = "views";
	
	public function __construct() {
		$this->registry = registry::getInstance();
		$this->path = $this->registry["path"];
	}
	
	public function setType($type) {
		$this->type = $type;
		if($this->type!='views'){
			$this->type='themes/'.$this->type;
		}
	}
	
	public static function getInstance() {
		return parent::getInstance(get_class());
	}	
	
	public function charsetTag($charSet) {		
		$charSet = "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$charSet."\"/>\n";
		return $charSet;
	}
	
	public function includeCss($css) {
		$css = "<link rel=\"stylesheet\" href=\"".$this->path."app/".$this->type."/css/".$css.".css\" type=\"text/css\" />\n";
		return $css;
	}
	
	public function includeJs($js) {		
		$js = "<script type=\"text/javascript\" src=\"".$this->path."app/libs/js/".$js.".js\"></script>\n";
		return $js;
	}
	
	public function includePluginFacebox() {
		$js = $this->includeCss("facebox");
		$js .= "\t<script type=\"text/javascript\">\n";
		$js .= "\t	var path = '".$this->path."';\n";
	  	$js .= "\t</script>\n";
		$js .= $this->includeJs("facebox");
		$js .= "\t<script type=\"text/javascript\">\n";
		$js .= "\t	jQuery(document).ready(function($) {\n";
		$js .= "\t	  $('a[rel*=facebox]').facebox() \n";
		$js .= "\t	})\n";
	  	$js .= "\t</script>\n";
		return $js;
	}
	
	public function includeFavicon($icon="favicon.ico") {		
		$favicon = "<link rel=\"shortcut icon\" href=\"".$this->path.'app/'.$this->type."/images/".$icon."\" />\n";
		return $favicon;
	}
	
	public function includeRSS($rssUrl="feed/rss/") {		
		$rss = "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"RSS 2.0\" href=\"".$this->path.$rssUrl."\" />\n";
		return $rss;
	}
	
	public function includeATOM($atomUrl="feed/atom/") {		
		$atom = "<link rel=\"alternate\" type=\"application/atom+xml\" title=\"Atom 1.0\" href=\"".$this->path.$atomUrl."\" />\n";
		return $atom;
	}	
		
	public function formPost($url){
		return "<form action=\"".$this->path.$url."\" method=\"post\">";
	}
	
	public function linkTo($text, $url="", $html_attributes="") {		
		$html = "<a href=\"".$this->path.$url;
		$html .= "\"";		
		$html .= " $html_attributes ";		
		$html .= ">".$text."</a>";		
		return $html;
	}

	public function linkToConfirm($text, $url=""){
		$html = $this->linkTo($text, $url, "onclick=\"return confirm('Are you sure?');\"");
		return $html;
	}
	
	public function image($name, $alt=""){
		return "<img src=\"".$this->path.'app/'.$this->type."/images/".$name."\" alt=\"".$alt."\" title=\"".$alt."\" />";
	}		
	
	public function checkBox($name, $html_attributes=""){
		$html = "<input type=\"checkbox\" name=\"".$name."\"";
		$html .= $html_attributes;				
		$html .= " />\n";			
		return $html;
	}
		
	public function radioButton($name, $value, $html_attributes=""){
		$html = "<input type=\"radio\" value=\"".$value."\" name=\"".$name."\" ";		
		$html .= $html_attributes;
		$html .= " />";			
		return $html;
	}
	
	public function textField($name, $html_attributes=""){
		$html = "<input type=\"text\" name=\"".$name."\" id=\"".$name."\" ";
		$html .= $html_attributes;
		$html .= " />";		
		return $html;
	}
	
	public function textArea($name, $value="", $html_attributes=""){
		$html = "<textarea name=\"".$name."\" ";
		$html .= $html_attributes;
		$html .= ">";
		$html .= $value;
		$html .= "</textarea>";		
		return $html;
	}
	
	public function hiddenField($name, $html_attributes=""){
		$html = "<input type=\"hidden\" name=\"".$name."\"";
		$html .= $html_attributes;
		$html .= " />";		
		return $html;
	}
	
	public function passwordField($name, $html_attributes=""){
		$html = "<input type=\"password\" name=\"".$name."\" ";
		$html .= $html_attributes;
		$html .= " />";		
		return $html;
	}
	
	public function select($name, $values, $selected=""){
		//$values[""]="";		
		$html = "<select name=\"".$name."\">\n";		
		foreach ($values as $key=>$value){
			$html .= "\t<option ";
			if (is_numeric($key)){
				$key = $value;
			}
			$html .= " value=\"$key\"";
			if($selected==$key){
				$html .= " selected=\"selected\"";
			}
			$html .= ">$value</option>\n";
		}		
		$html .= "</select>\n";		
		return $html;
	}
}
?>