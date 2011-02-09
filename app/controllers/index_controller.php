<?php 

class Index_controller extends AppController{

	public function __construct(){
		parent::__construct();
	}

	public function index($id = null){
		echo "Esto es una prueba.";
	}

}
