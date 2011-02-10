<?php 

class Index_controller extends AppController{

	public function __construct(){
		parent::__construct();
	}

	public function index($a=null,$b=null,$c=null,$d=null){
		$this->title_for_layout("Hello World Page!");
		
		echo $a;
		echo $b;
		echo $c;
		
		exit;
		echo $a;
		
		echo $id . "<br />";
		echo $this->router->getSegment(0)."<<br />";
		echo $this->router->getSegment(1)."<<br />";
		echo $this->router->getSegment(2)."<<br />";
		echo $this->router->getSegment(3)."<<br />";
		echo $this->router->getSegment(4)."<<br />";
		
		$this->render();
	}

	public function dos($id){
		echo $this->router->getSegment(10);
	}

	public function beforeDispatch(){
		if($this->session->check("logged")){
			echo "Hola";		
		}else{
			echo "Adios";
		}
	}	
}

