<?php 
 
class Index_controller extends AppController{

	public function __construct(){
		parent::__construct();
	}
	
	public function index($nombre = null){
		
	}

        public function testMiltiparams($param1, $param2, $param3, $param4 = "null"){
            echo $param1;
            echo $param2;
            echo $param3;
            echo $param4;
        }
}

