<?php

class router {
	
	private $registry;

	public function __construct() {
		$this->registry = registry::getInstance();
	}

	public function dispatch() {
		
		$this->getController($controller, $action, $params);
		$class = $controller."_controller";

		$controller = new $class();

		if (is_callable(array($controller, $action)) == false) {
			$params = $action;
			$action = 'index';
			#$this->notFound();
		}

		$controller->action = $action;
		$controller->params = $params;
		if($params)
			$controller->$action($params);
		else
			$controller->$action();
	}
	
	private function getController(&$controller, &$action, &$params) {
		$params = NULL;
		$route = (empty($_GET["url"])) ? "" : $_GET["url"];

		if (empty($route)) { $route = "index"; }

		$route = trim($route, "/\\");
		$parts = explode("/", $route);
		
		if (isset($parts[0])) {
			if (is_numeric($parts[0])) {
				$controller = "index";
				$action = "index";
				$params = $parts[0];	
			} else {
				if(!file_exists(Absolute_Path.'app'.DIRSEP.'controllers'.DIRSEP."{$parts[0]}_controller.php")){ 
					$controller = "index";
					$action = "index";
					$params = $parts[0];
				}else{
					$controller = $parts[0];

					if (isset($parts[1])) {
						$action = $parts[1];
					} else {
						$action = "index";
					}

					if (isset($parts[2])) {
						$params = $parts[2];
					}
				}
			}
		} else {
			$controller = "index";
		}
	}

	private function notFound($error="") {
		header('HTTP/1.0 404 Not Found');
		header('Content-Type: text/html; charset=utf-8');
		die("404 Controller action Not Found");
	}

}
?>
