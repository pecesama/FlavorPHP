<?php

class views {

	protected $vars = array();
	protected $layout = "default";
	protected $registry;
	protected $html;
	protected $ajax;
	protected $path;
	protected $session;
	protected $l10n;

	public function __construct() {
		$this->registry = registry::getInstance();
		$this->path = $this->registry["path"];
		$this->html = html::getInstance();
		$this->ajax = new ajax();
		$this->session = session::getInstance();
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
			$path = Absolute_Path."app".DIRSEP."views".DIRSEP."elements".DIRSEP.$name.".php";
			$errorMsg = "The <strong>element</strong> '<em>".$name."</em>' does not exist.";
		} elseif ($type == "layout") {
			$path = Absolute_Path."app".DIRSEP."views".DIRSEP."layouts".DIRSEP.$this->layout.".php";			
			$errorMsg = "The <strong>layout</strong> '<em>".$this->layout."</em>' does not exist.";
		} else {
			$route = explode(".", $name);
			$path = Absolute_Path."app".DIRSEP."views".DIRSEP.$route[0].DIRSEP.$route[1].".php";
			$errorMsg = "The <strong>view</strong> '<em>".$name."</em>' does not exist.";
		}

		if (file_exists($path) == false) {							
			throw new Exception("Flavor.fwk error: ". $errorMsg);
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

}

?>