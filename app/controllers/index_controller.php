<?php 
 
class Index_controller extends AppController{

	public function __construct(){
		parent::__construct();
	}
	
	public function index($nombre = null){
		
	}

	public function testMiltiparams($param1 = null, $param2 = null, $param3 = null, $param4 = null){
		echo $param1;
		echo $param2;
		echo $param3;
		echo $param4;
	}
	
	public function testAR(){
		
		echo "1. new modelo(); <br />";
		$A = new modelo();
		
		echo "2. ->find(1) <br />";
		$A->find(1);
		$A["uno"] = "B";
		
		echo "3. ->save().";
		$A->save();
		
		echo "4. ... <br />";
	}
}

