<?php

class user extends models{
	public function validateLogin($data) {
		$valid = $this->findBySql("SELECT id_user, login, password FROM users WHERE login='".$this->sql_escape($data['login'])."' AND password='".md5($data["password"])."'");
		if(empty($valid) == false) {
			return $valid['id_user'];
		}
		return false;
	}	
}

?>
