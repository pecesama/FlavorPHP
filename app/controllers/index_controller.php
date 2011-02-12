<?php 
 
class Index_controller extends AppController{

	public function __construct(){
		parent::__construct();
	}
	
	public function index($a=null,$b=null,$c=null,$d=null){		
		echo $a . "\n";
		echo $b . "\n";
		echo $c . "\n";
		echo $d . "\n";
		
		$this->render();
	}
	
	public function beforeDispatch(){
		echo "beforeDispatch\n";
	}
	
	public function beforeRender(){
		echo "beforeRender\n";
	}
	
	public function afterRender(){
		echo "afterRender\n";
	}
}

