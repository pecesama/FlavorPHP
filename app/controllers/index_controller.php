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
		echo "<h3>1. new modelo(); </h3>";
		
			$A = new modelo();
			
		echo "<h4>Nuevo prepareFromArray(); ->save(); </h4>";
			$A->prepareFromArray(array("A" => "uno"));
			echo $A->save();
			
		echo "<h4>Nuevo prepareFromArray(); ->save(); </h4>";
			$A->prepareFromArray(array("A" => "dos"));
			echo $A->save();
			
		echo "<h4>Nuevo prepareFromArray(); ->save(); </h4>";
			$A->prepareFromArray(array("A" => "tres"));
			echo $A->save();
			
		echo "<h4>Nuevo prepareFromArray(); ->save(); </h4>";
			$A->prepareFromArray(array("A" => "cuatro"));
			echo $A->save();
			
		echo "<h3>2. ->find(1); </h3>";
		
			echo $a = $A->find(1);
			$A["A"] = "B";
		
			utils::pre($a,0);
		
		echo "<h3>3. ->save(); </h3>";
		
			echo $A->save();
		
		echo "<h3>4. ->findAll();</h3>";
		
			echo $a = $A->findAll();
			
			utils::pre($a);
			
			
	}
}

