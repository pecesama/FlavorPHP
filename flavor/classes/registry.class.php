<?php

class registry extends singleton implements ArrayAccess {

	private $vars = array();

	public function __construct() { }
	
	public static function getInstance() {
		return parent::getInstance(get_class());
	}
	
	public function __set($key, $value){
		if (isset($this->vars[$key]) == true) {
			throw new Exception("Unable to set var '".$key."'. Already set.");
		}

		$this->vars[$key] = $value;
		return true;
	}
	
	public function __get($key){
		if (isset($this->vars[$key]) == false) {
			return null;
		}

		return $this->vars[$key];
	}

	public function remove($var) {
		unset($this->vars[$key]);
	}

	public function offsetExists($offset) {
		return isset($this->vars[$offset]);
	}

	public function offsetGet($offset) {
		if (isset($this->vars[$offset]) == false) {
			return null;
		}

		return $this->vars[$offset];
	}

	public function offsetSet($offset, $value) {
		if (isset($this->vars[$offset]) == true) {
			throw new Exception("Unable to set var '".$offset."'. Already set.");
		}

		$this->vars[$offset] = $value;
		return true;
	}

	public function offsetUnset($offset) {
		unset($this->vars[$offset]);
	}
	
}
?>