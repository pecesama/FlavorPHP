<?php

class AppViews extends Views {

	public function __construct() {
		parent::__construct();
		
		$this->messages = Message::getInstance();
	}
	
}