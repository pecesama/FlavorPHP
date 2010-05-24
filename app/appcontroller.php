<?php

abstract class appcontroller extends controller {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function beforeRender(){
	//	$this->debug->log('Pase por '.get_class($this).'<br>','Route',true);
	}
}

?>