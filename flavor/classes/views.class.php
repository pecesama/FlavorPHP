<?php

class views {

	protected $vars = array();
	protected $layout = "default";
	protected $registry;
	protected $html;
	protected $ajax;
	protected $path;
	protected $session;
	protected $cookie;
	protected $l10n;

	public function __construct() {
		$this->registry = registry::getInstance();
		$this->path = $this->registry["path"];
		$this->html = html::getInstance();
		$this->session = $this->registry["session"];
		$this->cookie = $this->registry["cookie"];
		$this->ajax = new ajax();
		$this->l10n = l10n::getInstance();
	}
	
	public function __set($name, $value){
		if (isset($this->vars[$name]) == true) {
			throw new Exception("Unable to set view '".$name."'. Already set.");
			return false;
		}

		$this->vars[$name] = $value;
		return true;
	}

	public function remove($name) {
		unset($this->vars[$name]);
		return true;
	}
	
	public function setlayout($name) {
		$this->layout = $name;
	}
	
	public function renderElement($name) {
		echo $this->fetch($name, "element");
	}

	public function fetch($name, $type = NULL) {
		
		if ($type == "element") {
			$path = Absolute_Path.APPDIR.DIRSEP."views".DIRSEP."elements".DIRSEP.$name.".php";
			$errorMsg = "The <strong>element</strong> '<em>".$name."</em>' does not exist.";
		} elseif ($type == "layout") {
			$path = Absolute_Path.APPDIR.DIRSEP."views".DIRSEP."layouts".DIRSEP.$this->layout.".php";			
			$errorMsg = "The <strong>layout</strong> '<em>".$this->layout."</em>' does not exist.";
		} else {
			$route = explode(".", $name);
			$path = Absolute_Path.APPDIR.DIRSEP."views".DIRSEP.$route[0].DIRSEP.$route[1].".php";
			$errorMsg = "The <strong>view</strong> '<em>".$name."</em>' does not exist.";
		}

		if (file_exists($path) == false) {							
			throw new Exception("FlavorPHP error: ". $errorMsg);
			return false;
		}
		
		foreach ($this->vars as $key => $value) {
			$$key = $value;
		}

		ob_start();
		include ($path);
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}

	public function fixNewlinesForCleanHtml($fixthistext) {
		$fixthistext_array = explode("\n", $fixthistext);
		foreach ($fixthistext_array as $unfixedtextkey => $unfixedtextvalue) {
			if (!preg_match("/^(\s)*$/", $unfixedtextvalue)) {
				$fixedtextvalue = preg_replace("/>(\s|\t)*</U", ">\n<", $unfixedtextvalue);
				$fixedtext_array[$unfixedtextkey] = $fixedtextvalue;
			}
		}
		return implode("\n", $fixedtext_array);
	}
	
	public function cleanHtmlCode($uncleanhtml) {
		$indent = "    ";
		$fixed_uncleanhtml =  $this->fixNewlinesForCleanHtml($uncleanhtml);
		$uncleanhtml_array = explode("\n", $fixed_uncleanhtml);
		$indentlevel = 0;
		foreach ($uncleanhtml_array as $uncleanhtml_key => $currentuncleanhtml) {
			$currentuncleanhtml = preg_replace("/\t+/", "", $currentuncleanhtml);
			$currentuncleanhtml = preg_replace("/^\s+/", "", $currentuncleanhtml);			
			$replaceindent = "";
			for ($o = 0; $o < $indentlevel; $o++) {
				$replaceindent .= $indent;
			}
			if (preg_match("/<(.+)\/>/", $currentuncleanhtml)) { 
				$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
			} else if (preg_match("/<!(.*)>/", $currentuncleanhtml)) { 
				$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
			} else if (preg_match("/<[^\/](.*)>/", $currentuncleanhtml) && preg_match("/<\/(.*)>/", $currentuncleanhtml)) { 
				$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
			} else if (preg_match("/<\/(.*)>/", $currentuncleanhtml) || preg_match("/^(\s|\t)*\}{1}(\s|\t)*$/", $currentuncleanhtml)) {
				$indentlevel--;
				$replaceindent = "";
				for ($o = 0; $o < $indentlevel; $o++) {
					$replaceindent .= $indent;
				}				
				$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
			} else if ((preg_match("/<[^\/](.*)>/", $currentuncleanhtml) && !preg_match("/<(link|meta|base|br|img|hr)(.*)>/", $currentuncleanhtml)) || preg_match("/^(\s|\t)*\{{1}(\s|\t)*$/", $currentuncleanhtml)) {
				$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;				
				$indentlevel++;
				$replaceindent = "";
				for ($o = 0; $o < $indentlevel; $o++) {
					$replaceindent .= $indent;
				}
			} else {
				$cleanhtml_array[$uncleanhtml_key] = $replaceindent.$currentuncleanhtml;
			}
		}
		return implode("\n", $cleanhtml_array);	
	}

}