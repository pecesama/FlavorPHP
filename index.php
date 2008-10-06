<?php
/**
  * Flavor.fwk is a framework based on MVC pattern, constructed with the help of several patterns.
  *
  * @version SVN
  * @author Pedro Santana <pecesama_at_gmail_dot_com>
  */

error_reporting (E_ALL);

if(!version_compare(PHP_VERSION, '5.1.0', '>=' ) ) {
	die("Flavor.fwk needs PHP 5.1.x or higher to run. You are currently running PHP ".PHP_VERSION.".");
}

define('DIRSEP', DIRECTORY_SEPARATOR);
define('Absolute_Path', dirname(__FILE__).DIRSEP);

$configFile = Absolute_Path.'config.php';

if (!file_exists($configFile)) {
	die('Installation required');
} else {
    require_once($configFile);
}

function __autoload($className) {
	$success = false;
	$classFile = Absolute_Path.'flavor'.DIRSEP.'classes'.DIRSEP.$className.'.class.php';

	if (file_exists($classFile)) {
		require_once($classFile);
		$success= true;
	}
	
	// maybe we want an interface
	if (!$success) {
		$interfaceFile = Absolute_Path.'flavor'.DIRSEP.'interfaces'.DIRSEP.$className.'.interface.php';
		if (file_exists($interfaceFile)) { 
			require_once($interfaceFile);
			$success= true;
		}	
	}
	
	// maybe we want a helper
	if (!$success) {
		$helperFile = Absolute_Path.'flavor'.DIRSEP.'helpers'.DIRSEP.$className.'.helper.php';
		if (file_exists($helperFile)) { 
			require_once($helperFile);
			$success= true;
		}	
	}
	
	// maybe we want a controller
	if (!$success) {
		$controllerFile = Absolute_Path.'app'.DIRSEP.'controllers'.DIRSEP.$className.'.php';
		if (file_exists($controllerFile)) { 
			require_once($controllerFile);
			$success= true;
		}	
	}
	
	// maybe we want a model
	if (!$success) {
		$modelFile = Absolute_Path.'app'.DIRSEP.'models'.DIRSEP.$className.'.php';
		if (file_exists($modelFile)) { 
			require_once($modelFile);
			$success= true;
		}	
	}
	
	// maybe we want a third party class
	if (!$success) {
		$modelFile = Absolute_Path.'app'.DIRSEP.'libs'.DIRSEP.$className.'.class.php';
		if (file_exists($modelFile)) { 
			require_once($modelFile);
			$success= true;
		}	
	}
	
	if (!$success) {
		die("Could not include class file '".$className."' ");		
	}
}

// 'Globals' to be used throughout the application
// usign the _Registry Pattern_

$registry = registry::getInstance();

try {
	
	ob_start();

	$path = (substr(Path, strlen(Path) - strlen("/")) == "/") ? Path : Path."/" ;
	$registry->path = $path;
	
	$db = new dbFactory(strtolower(DB_Engine));
	$registry->db = $db;
	
	$views = new views();
	$registry->views = $views;

	$themes = new themes();
	$registry->themes = $themes;
	
	$session = session::getInstance();
	$registry->session = $session;
	
	$router = new router();
	$registry->router = $router;
	
	$router->dispatch(); // Here starts the party
	
} catch(Exception $e) {
	echo $e->getMessage();
	exit();
}
?>