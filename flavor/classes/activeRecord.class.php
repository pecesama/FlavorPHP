<?php 

class ActiveRecord implements ArrayAccess {
	protected $record = array(); // Contiene los campos de la tabla
	private $auxRecord = array(); //contiene propiedades agregadas fuera de los campos de la tabla.
	private $keyField = "";
	private $table = NULL;
	private $isNew = true;
	protected $registry;
	public $db;	
	private $columns;
	
	public function __construct() {
		$this->registry = registry::getInstance();
		$this->db = $this->registry["db"];
		$this->table = $this->modelName();

		$rs = $this->db->query("SHOW COLUMNS FROM ".$this->table);

		while ($row = $this->db->fetchRow()) {
			$this->columns[$row["Field"]] = $row;
			$this->record[$row["Field"]] = "";
		    if( $row["Key"] === "PRI" ) {
				$this->keyField = $row["Field"];
		    }
		}		
		
		if(empty($this->keyField)) {
		    throw new Exception( "Primary Column not found for Table: '".$this->table."'");
		}
		

	}
	
	public function __set($key, $value){
		if(array_key_exists($key,$this->record)){
			$this->record[$key]=$value;
		}else{
			$this->auxRecord[$key] = $value;
		}
	}
	
	public function __get($key){
		if(array_key_exists($key,$this->record)){
			return $this->record[$key];
		}elseif(array_key_exists($key,$this->auxRecord)){
			return $this->auxRecord[$key];
		}
	}
	
	public function __call($method, $args){
		$field = substr($method,6);
		if(substr($method,0,-(strlen($field)))=="findBy"){ 
			return $this->findBy($field, $args[0]);	
		}else{
			throw new Exception("Method (".$method.") not declarated in the Active Record.");
		}
	}
	
	private function modelName(){
		$source = get_class($this);
		if(preg_match("/([a-z])([A-Z])/", $source, $reg)){
			$source = str_replace($reg[0], $reg[1]."_".strtolower($reg[2]), $source);
		}
		return strtolower(inflector::pluralize($source));
	}	
	
	public function prepareFromArray($array){
		foreach ($array as $key => $var) {
			$this->record[$key] = $var;
		}		
	}
	
	public function prepareFromJSON($jsonData){
		$this->prepareFromArray(json_decode($jsonData));
	}
	
	public function jsonCrud($jsonData){
		//echo "<br><br>".$jsonData."<br>";
		$arrayData = json_decode($jsonData);
		//print_r($arrayData);
		//die();
		$primary = $this->keyField;
		if(in_array($arrayData->action,array("add","update","delete"))){
			//echo "Que hace: $arrayData->action<br>";
			if(count($arrayData->data)>0){
				foreach($arrayData->data as $data){
					if(isset($data->$primary)){ // <-- Esto no esta del todo bien creo...
						if(is_numeric($data->$primary) && $data->$primary > 0){
							//echo "Registro: ".$data->$primary."<br>";
							$this->find($data->$primary);
							$dataArray = get_object_vars($data);
							//print_r($dataArray);
							if($arrayData->action == "add" || $arrayData->action == "update"){
								$this->prepareFromArray($dataArray);
								$this->save();
							}
							if($arrayData->action == "delete"){
								$this->delete();
							}
							//echo "<br>";
						} else {
							throw new Exception("Wrong Primary Key, action failed");
						}
					} else {
						throw new Exception("Primary Key Missing, action failed");
					}
				}
			} else {
				throw new Exception("No hay nada en el data!");   
			}
		} else {
			throw new Exception("Unknow Action, process failed");
		}
	}
	
	public function create($values) {		
				
		$sql = "INSERT INTO ".$this->table.$this->db->buildArray("INSERT", $values);
		
		$rs = $this->db->query($sql);
		if (!$rs) {
			throw new Exception("SQL Error, Insert Failed");
		}
		
		return $this->db->lastId();
	}
	
	
	public function save() {
			if( $this->isNew ) {
				$id = $this->create($this->record);
				$this->record[$this->keyField] = $id;
				$this->isNew = false;

				return $id;
			} else {
				return $this->update();
			}
	}	
	
	public function update() {
		if( !isset( $this->record[$this->keyField] ) || empty( $this->record[$this->keyField] ) ) {
			throw new Exception( "Primary Key Missing, update failed" );
		}
		
		$key = $this->record[$this->keyField];
		
		if(isset($this->columns["modified"])){
			$this->record["modified"] = date("Y-m-d H:i:s",strtotime("now"));
		}
		
		$sql = "UPDATE ".$this->table." SET ".$this->db->buildArray("UPDATE", $this->record)." WHERE ".$this->keyField.'='.intval($key);
		$rs = $this->db->query($sql);
		if (!$rs) {
			throw new Exception("SQL Error, Update Failed");
		}
		
		return $rs;
	}
	
	public function delete(){
		if($this->isNew) return 0;
		if(count($this->record) == 0) return 0;
		
		if($this->multipleRecords and count($this->record)>0){
			$keys = "";
			foreach($this->record as $row){
				$keys .= "$this->keyField = ".intval($row["$this->keyField"])." or ";
			}
			$keys = substr($keys,0,-3);
			
			$sql = "DELETE FROM ".$this->table." WHERE $keys";
			$rs = $this->db->query($sql);
		}else{
			$key = $this->record[$this->keyField];

			$sql = "DELETE FROM ".$this->table." WHERE ".$this->keyField.'='.intval($key);
			$rs = $this->db->query($sql);
		}
		
		if (!$rs) {
			throw new Exception("SQL Error, Remove Failed");
		}
		
		return $rs;
	}
	
	public function isNew(){
		return $this->isNew;
	}
	
	public function find($id) { 
		
		$sql = "SELECT * FROM ".$this->table." WHERE ".$this->keyField."=".intval($id);
		$rs = $this->db->query($sql);
		$row = $this->db->fetchRow();
		
		if($row != null){
			$this->record = $row;
			$this->isNew = false;
		}
		
		return $this->record;
	}	
	
	public function findBy($field, $value) { 
		if(is_array($field)){
			$where = "";
			foreach($field as $k=>$v){
				$where .= $field[$k]."='".$this->db->sql_escape($value[$k])."' AND ";
			}
			$where = "(".substr($where,0,-5).")";
			
			$sql = "SELECT * FROM ".$this->table." WHERE ".$where." LIMIT 1";
		}else{
			$sql = "SELECT * FROM ".$this->table." WHERE ".$field."='".$this->db->sql_escape($value)."' LIMIT 1";
		}
		return $this->findBySql($sql);
	}
	
	public function findBySql($sql) { 		
		$rs = $this->db->query($sql);
        if($this->db->numRows() != 0){
            $this->record = $this->db->fetchRow();
            $this->isNew = false;
			$this->multipleRecords = false;
        }
		return $this->record;
	}
	
	public function findAll($fields=NULL, $order=NULL ,$limit=NULL, $extra=NULL) {
		$fields = $fields ? $fields : '*';
		$sql = "SELECT ".$fields." FROM ". $this->table." ";
		if($extra) $sql.= $extra." ";
		if($order) $sql.= "ORDER BY ".$order.' ';
		if($limit) $sql.= "LIMIT ".$limit;
		return $this->findAllBySql($sql);
	}
	
	public function findAllBy($field, $value){
		if(is_array($field)){
			$where = "";
			foreach($field as $k=>$v){
				$where .= $field[$k]."='".$this->db->sql_escape($value[$k])."' AND ";
			}
			$where = "(".substr($where,0,-5).")";
			
			$sql = "SELECT * FROM ".$this->table." WHERE ".$where."";
		}else{
			$sql = "SELECT * FROM ".$this->table." WHERE ".$field."='".$this->db->sql_escape($value)."'";
		}
		return $this->findAllBySql($sql);
	}
	
	public function findAllBySql($sql) { 		
		$rs = $this->db->query($sql);
		$rows = array();
		if($this->db->numRows() != 0){
			while($row = $this->db->fetchRow()) $rows[] = $row;
			$this->isNew = false;
			$this->multipleRecords = true;
			$this->record = $rows;
		}
		return $rows;
	}
	
	public function getPrimaryKey() {
		return $this->keyField;
	}
    
	public function offsetExists( $offset ) {
		return (isset( $this->record[$offset] ) || isset ($this->auxRecord[$offset]) );
	}
	
	public function offsetGet( $offset ) {
		if(array_key_exists($offset,$this->record)){
			return $this->record[$offset];
		}elseif(array_key_exists($offset,$this->auxRecord)){
			return $this->auxRecord[$offset];
		}
	}

	public function offsetSet( $offset, $value ) {
		if( $offset == $this->keyField ) {
			throw new Exception("Primary Key can't be set or changed");
		}
		if(array_key_exists($offset,$this->record)){
			$this->record[$offset] = $value;
		}else{
			$this->auxRecord[$offset] = $value;
		}
	}

	public function offsetUnset( $offset ) {
		if( isset( $this->record[$offset] ) ) {
			unset( $this->record[$offset] );
		}elseif( isset( $this->auxRecord[$offset]) ){
			unset( $this->auxRecord[$offset] );
		}
	}
	
	public function sql_escape($msg){
		return $this->db->sql_escape($msg);
	}
}