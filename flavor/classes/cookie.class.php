<?php

class cookie extends singleton implements ArrayAccess {
	
	private $expire;
	
	public function __construct() {
		$this->expire = time () + 7 * 24 * 3600; // One week by default
	}
	
	public function setExpire($expireTime){
		if (!empty ($expireTime)) {
			$this->expire = time () + $expireTime;
		}
	}
	
	public static function getInstance() {
		return parent::getInstance(get_class());
	}

	public function __set($key, $value){		
		setcookie($key, $value, $this->expire, "/");
	}
	
	public function __get($key){
		return $_COOKIE[$key];
	}
	
	public function destroy($key){
		setcookie($key, null, time() - 7 * 24 * 3600);
	}
	
	public function set($key, $value,$my_expire=NULL,$my_target=NULL){	
		$expire = ($my_expire)? time()+$my_expire : $this->expire;
		$target = ($my_target)? $my_target : "/";
		setcookie($key, $value, $expire, $target);
	}
	
	public function check($key){
		return array_key_exists($key, $_COOKIE);
	}		

	public function offsetSet($offset, $value) {		
		setcookie($offset, $value, $this->expire, "/");
	}
	
	public function offsetGet($offset) {		
		return $_COOKIE[$offset];
	}

	public function offsetUnset($offset) {
		setcookie($key, null, time() - 7 * 24 * 3600);
	}
	
	public function offsetExists($offset) {
		return array_key_exists($key, $_COOKIE);
	}
	
}
?>