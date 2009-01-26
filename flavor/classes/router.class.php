<?php
class router{
	private $registry;

	public function __construct() {
		$this->registry = registry::getInstance();
	}

	public function dispatch() {
		
		$this->getController($controller, $action, $params, $extra);
		$class = $controller."_controller";

		$controller = new $class();

/*DEBUG
echo "controller: $class<br>";
echo "action: $action<br>";
echo "params: $params<br>";
echo "<hr>";
*/

		if(!is_callable(array($controller, $action)) or $action=='index')
		if(is_callable(array($controller, $params))){
			if(!is_numeric($params)){
				$action = $params;
				$params = $extra?$extra:null;
			}
		}

/*DEBUG
echo "controller: $class<br>";
echo "action: $action<br>";
echo "params: $params<br>";
echo "<hr>";
*/
		$controller->action = $action;
		$controller->params = $params;
		if($params)
			$controller->$action($params);
		else
			$controller->$action();
	}
	
	private function getController(&$controller, &$action, &$params, &$extra) {
		$extra = null;
		$params = null;
		$route = (empty($_GET["url"])) ? "" : $_GET["url"];

		if(empty($route))$route = "index";

		$route = trim($route, "/\\");
		$parts = explode("/", $route);

		if (isset($parts[0])){
			if (is_numeric($parts[0])){
				$controller = "index";
				$action = "index";
				$params = $parts[0];
			}else{
				if(file_exists(Absolute_Path.'app'.DIRSEP.'controllers'.DIRSEP."{$parts[0]}_controller.php")){
					$controller = $parts[0];

					if(isset($parts[1])){
						if(is_numeric($parts[1])){
							$action = 'index';
							$params = $parts[1];
						}else{
							$action = $parts[1];
							$params = isset($parts[2])?$parts[2]:null;
						}	
					}else
						$action = 'index';
				}else{
					$controller = "index";
					/*
					 * action:index por defecto.
					 * Al tener una instancia, se debe verificar si existe el metodo/action dentro
					 * para entonces convertir $action=$parts[0] y enviar como parametros los datos
					 * contenidos en $parts[1]
					 */
					$action = "index";
					$params = $parts[0];
					/*
					 * extra debe ser enviado, porque cuando se deduce que controller es index y action index,
					 * el primer valor de parts[0] se envia params, y parts[1] se pierde. El cual es necesario
					 * para enviar como params en caso de que parts[0] sea un action cuando creemos una
					 * instancia de la clase $controller.
					 */
					$extra = isset($parts[1])?$parts[1]:null;
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
