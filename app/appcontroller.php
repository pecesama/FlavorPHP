<?php

abstract class appcontroller extends controller {

	protected $html;
	
	public function __construct() {
		parent::__construct();
		$this->html = html::getInstance();
	}
}

?>