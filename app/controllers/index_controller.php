<?php 

class Index_controller extends AppController{

	public function __construct(){
		parent::__construct();
	}

	public function index($id = null){
		$this->title_for_layout("Hello World Page!");
		
echo		$this->router->getSegment(3);



		$this->render();
	}

	public function dos($id){
		echo $this->router->getSegment(10);
	}

}
