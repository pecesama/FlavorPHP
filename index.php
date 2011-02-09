<?php
/**
  * FlavorPHP is a framework based on MVC pattern, constructed with the help of several patterns.
  *
  * @version SVN
  * @author Pedro Santana <pecesama_at_gmail_dot_com>
  * @author Victor Bracco <vbracco_at_gmail_dot_com>
  * @author Victor de la Rocha <vyk2rr_at_gmail_dot_com>
  */

error_reporting (E_ALL);

if(!version_compare(PHP_VERSION, '5.2.0', '>=' ) ) {
	die("FlavorPHP needs PHP 5.2.x or higher to run. You are currently running PHP ".PHP_VERSION.".");
}

define('DIRSEP', DIRECTORY_SEPARATOR);
define('Absolute_Path', dirname(__FILE__).DIRSEP);
define('APPDIR','app');

$configFile = Absolute_Path.'config.php';

if (!file_exists($configFile)) {
	die('Installation required');
} else {
    require_once($configFile);
	if(!defined('Absolute2Flavor')){
		define('Absolute2Flavor',Absolute_Path);
	}
}

function __autoload($className) {
	$className = strtolower($className);
	$directories = array(
		Absolute2Flavor.'flavor'.DIRSEP.'classes'.DIRSEP.$className.'.class.php', // Flavor classes
		Absolute2Flavor.'flavor'.DIRSEP.'interfaces'.DIRSEP.$className.'.interface.php', // maybe we want an interface
		Absolute2Flavor.'flavor'.DIRSEP.'helpers'.DIRSEP.$className.'.helper.php', // maybe we want a helper
		Absolute_Path.APPDIR.DIRSEP.$className.'.php', // maybe we want appcontroller or appviews
		Absolute_Path.APPDIR.DIRSEP."controllers".DIRSEP.$className.'.php', // maybe we want a controller
		Absolute_Path.APPDIR.DIRSEP.'models'.DIRSEP.$className.'.php', // maybe we want a model
		Absolute_Path.APPDIR.DIRSEP.'libs'.DIRSEP.$className.'.class.php' // maybe we want a third party class
		// If you need more directories just add them here
	);

	$success = false;
	foreach($directories as $file){
		if(!$success){
			if(file_exists($file)){
				require_once($file);
				$success = true;
			}
		}else break;
	}
	if(!$success) {
		die("Could not include class file '".$className."' ");
	}
}

// 'Globals' to be used throughout the application
// usign the _Registry Pattern_

$registry = Registry::getInstance();

try {

	ob_start();

	$path = (substr(Path, strlen(Path) - strlen("/")) == "/") ? Path : Path."/" ;
	$registry->path = $path;

	if(!defined('requiresBD')){
		$db = new DbFactory(strtolower(DB_Engine));
	} else {
		if(requiresBD){
			$db = new DbFactory(strtolower(DB_Engine));
		} else {
			$db = null;
		}
	}
	$registry->db = $db;

	$views = new Appviews();
	$registry->views = $views;

	$themes = new Themes();
	$registry->themes = $themes;

	$session = Session::getInstance();
	$registry->session = $session;

	$cookie = Cookie::getInstance();
	$registry->cookie = $cookie;

	$router = new Router();
	$registry->router = $router;
	
	$debug = Debug::getInstance();
	$registry->debug = $debug;
	
	$registry->validateErrors = array();

	$router->dispatch(); // Here starts the party

} catch(Exception $e) {
	echo $e->getMessage();
	exit();
}
?>
