<?php

class ajax extends pquery {
	
	protected $registry;
	protected $path;
	public $jquery;
	
	public function __construct() {
		$this->registry = registry::getInstance();
		$this->path = $this->registry["path"];
		$this->jquery = new pquery();		
	}
	
	public function linkToBox($text, $url="", $html_attributes="") {		
		$html = "<a href=\"".$this->path.$url;
		$html .= "\" rel=\"facebox\"";		
		$html .= " $html_attributes ";		
		$html .= ">".$text."</a>";		
		return $html;
	}	
	
}
?>