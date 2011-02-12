<?php 
 
class Index_controller extends AppController{

	public function __construct(){
		parent::__construct();
	}
	
	public function index($a=null,$b=null,$c=null,$d=null){		
		header("content-type: text/plain");
		echo "<<";
	}
}

