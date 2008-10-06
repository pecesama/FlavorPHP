<?php

abstract class controller {
	
	protected $registry;
	protected $session;
	protected $pagination;	
	protected $l10n;
	protected $themes;
	protected $view;
	protected $path;
	protected $tfl= "";
	public $action;
	public $params;

	public function __construct() {
		$this->registry = registry::getInstance();
		$this->session = $this->registry["session"];
		$this->view = $this->registry["views"];
		$this->themes = $this->registry["themes"];
		$this->path = $this->registry["path"];
		$this->l10n = l10n::getInstance();
		$this->pagination = pagination::getInstance();
	}

	abstract public function index($id=NULL);
		
	public function beforeRender() {}
	public function afterRender() {}
	
	public function redirect($url, $intern = true) {
		if ($intern) {
			$url = (!$this->endsWith($url, "/")) ? $url."/" : $url ;
			$url = $this->path.$url;
		} else {
			$url = $url;
		}
		header("Location: ".$url);
		exit();
	}
	
	public function render($view=NULL) {
		if (is_null($view)) {
			$view = $this->action;
		}
		$this->beforeRender();
		$this->view->content_for_layout = $this->view->fetch($this->controllerName().".".$view);
		$this->view->title_for_layout = $this->tfl;
		echo $this->view->fetch("", "layout");
		$this->afterRender();
		exit();
	}
	
	public function renderTheme($theme,$file='index'){
		$path = Absolute_Path."app".DIRSEP."themes".DIRSEP.$theme.DIRSEP."$file.htm";			
		echo $this->themes->fetch($path);
	}

	public function fetchTheme($theme,$file='index'){
		$path = Absolute_Path."app".DIRSEP."themes".DIRSEP.$theme.DIRSEP."$file.htm";			
		return $this->themes->fetch($path);
	}
	
	protected function title_for_layout($str){
		$this->tfl = $str;
	}
	
	protected function controllerName(){
		$source = get_class($this);
		if(ereg("([a-z])([A-Z])", $source, $reg)){
			$source = str_replace($reg[0], $reg[1]."_".strtolower($reg[2]), $source);
		}	
		
		$controller = explode("_", $source);
		
		return strtolower($controller[0]);
	}
	
	private function endsWith($str, $sub) {
		return (substr($str, strlen($str) - strlen($sub)) == $sub);
	}
	
}
?>