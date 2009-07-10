<?php
class router{
	private $registry;
	private $class = array(
		'controller'=>'index',
		'action'=>'index',
		'params'=>''
	);
	private $route,$uri,$originalUri;
	private $routes = array();
	private $parts;
	
	public function __construct() {
		$this->registry = registry::getInstance();
		$this->getRoutes();
	}
	
	public function dispatch() {
		$this->getController();

		$controller = $this->class['controller'];
		$action = $this->class['action'];
		$params = $this->class['params'];
		
		$class = $controller."_controller";
		$controller = new $class();

		if(!is_callable(array($controller,$action))) {
			$this->notFound();
		}

		$controller->action = $action;
		$controller->params = $params;

		if($params)
			$controller->$action($params);
		else
			$controller->$action();
	}
	
	private function getController(){
		$this->route = (empty($_GET['url']))?'': $_GET['url'];
		
		if(empty($this->route))
			$this->route = "index";

		$this->originalUri = $this->cleanRoute($this->route);
		$this->cleanRoute();
		$this->getParams();
		
		$params = null;
		if (isset($this->parts[0])){
			if (is_numeric($this->parts[0])){
				$controller = "index";
				$action = "index";
				$params = $this->parts[0];
				unset($this->parts[0]);
			}else{
				if($this->controllerExists($this->parts[0])){
					$controller = $this->parts[0];
					unset($this->parts[0]);
					
					if(isset($this->parts[1]) and is_numeric($this->parts[1]) and $this->parts[1]){
						$action = 'index';
						$params = $this->parts[1];						
						unset($this->parts[1]);
					}elseif(isset($this->parts[1])){
						$action = $this->parts[1];

						if(isset($this->parts[2])){
							$params = $this->parts[2];
							unset($this->parts[2]);							
						}else{
							$params = null;
						}
						unset($this->parts[1]);
					}else{
						$action = 'index';
						$params = null;
					}
				} else {					
					if ($this->parts[0] == "index") {						
						ob_start();
						$path = Absolute_Path."app".DIRSEP."views/start/index.php";
						include ($path);
						$contents = ob_get_contents();
						ob_end_clean();
						echo $contents;
						die();
					} else {
						$this->notFound();
					}
				}
			}
		}else{
			$controller = "index";
		}

		$this->class['controller'] = $controller;
		$this->class['action'] = $action;
		$this->class['params'] = $params;
	}

	private function notFound($error="") {
		header('HTTP/1.0 404 Not Found');
		header('Content-Type: text/html; charset=utf-8');
		die("404 Controller action Not Found");
	}

	/*
	 * - Si no se envia el parametro $route, deja en $this->uri la url formateada y 
	 *   en $this->parts deja todas las partes listas para procesar.
	 * - Si se define $route, únicamente retorna la url formateada correctamente.
	 *
	 * ejemplo de salida => 
	 *  uri: index/saludo/1
 	 *  parts: array([0]=>index,[1]=>saludo,[2]=>1)
 	 *  -------------------------------
	 *  uri: dos/nombre/victor/a/b/c
 	 *  parts: array([0]=>dos,[1]=>nombre,[2]=>victor,[3]=>a,[4]=>b,[5]=>c)
	 */
	private function cleanRoute($route=null){
		$parts = array();
		$uri = null;
		foreach(explode("/", preg_replace("{/*(.+?)/*$}", "\\1", ($route?$route:$this->route))) as $v){
			$v = trim($v);
			if($v!=''){
				if(!$route)
					$parts[] = $v;
				$uri .= $v.'/';
			}
		}
		if($route){
			return $uri;
		}else{
			$this->uri = $uri;
			$this->parts = $parts;
		}
	}
	
	/*
	 * Extrae el parámetro que se enviará y busca las rutas definidas en $this->routes para procesarlas.
	 */
	private function getParams(){
		foreach($this->routes as $target=>$route){
			if(preg_match("|^$route/$|",($this->uri!='index/')?$this->uri:'',$out)){
				if(isset($out)){
					unset($out[0]);
					foreach($out as $k=>$v)
						$target = str_replace("\$$k",$v,$target);
					if(!($this->controllerExists($this->parts[0]) and ($route=='(.*)' or $route=='(.+)'))){
						$this->route = $this->cleanRoute($target);
						$this->cleanRoute();
						break;
					}
				}
			}
		}		
	}

	private function controllerExists($controller){
		return file_exists(Absolute_Path.'app'.DIRSEP.'controllers'.DIRSEP."{$controller}_controller.php");
	}
	/*
	 * Obtiene las rutas desde el archivo app/routes.php
	 */
	private function getRoutes(){
		require(Absolute_Path.'app'.DIRSEP.'routes.php');
	}
	
	/*
	 * Agrega una ruta manual
	 */
	public function add($GET,$target){
		if(is_array($target)){
			$controller = isset($route['controller'])?$route['controller']:'index';
			$action = isset($route['action'])?$route['action']:'index';
			$params = isset($route['params'])?$route['params']:null;
			$target = "$controller/$action/$params";
		}
		$this->routes[$target] = $GET;
	}
}
?>
