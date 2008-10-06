<?php

class session extends singleton implements ArrayAccess {
	
	public function __construct() {
		if (!isset($_SESSION)) {
			session_start();
		}
	}
	
	public static function getInstance() {
		return parent::getInstance(get_class());
	}

	public function __set($key, $value){
		$_SESSION["flavor_fwk_session"][$key] = $value;
	}
	
	public function __get($key){
		return $_SESSION["flavor_fwk_session"][$key];
	}
	
	public function destroy($key){
		unset($_SESSION["flavor_fwk_session"][$key]);
	}
	
	public function check($key){
		return isset($_SESSION["flavor_fwk_session"][$key]);
	}
	
	function flash($value){
		$_SESSION["flavor_fwk_session"]["flash"] = $value;
	}
	
	function issetFlash(){
		if (!isset($_SESSION["flavor_fwk_session"]["flash"]) or $_SESSION["flavor_fwk_session"]["flash"] == ""){
			return false;
		}
		return true;
	}	
	
	function getFlash(){
		if (!@is_null($_SESSION["flavor_fwk_session"]["flash"])){
			$flash = $_SESSION["flavor_fwk_session"]["flash"];
			$_SESSION["flavor_fwk_session"]["flash"] = "";
			return $flash;
		}
		return "";
	}

	public function offsetExists($offset) {
		return isset($_SESSION["flavor_fwk_session"][$offset]);
	}	

	public function offsetSet($offset, $value) {
		$_SESSION["flavor_fwk_session"][$offset] = $value;
	}
	
	public function offsetGet($offset) {
		return $_SESSION["flavor_fwk_session"][$offset];
	}

	public function offsetUnset($offset) {
		unset($_SESSION["flavor_fwk_session"][$offset]);
	}
	
}
?>