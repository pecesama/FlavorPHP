<?php

class activeRecord implements ArrayAccess {
	private $record = array();
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
		$this->record[$key]=$value;
	}
	
	public function __get($key){
		return $this->record[$key];
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
		if(ereg("([a-z])([A-Z])", $source, $reg)){
			$source = str_replace($reg[0], $reg[1]."_".strtolower($reg[2]), $source);
		}
		return strtolower(inflector::pluralize($source));
	}	
	
	public function prepareFromArray($array){
		foreach ($array as $key => $var) {
			$this->record[$key] = $var;
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
			if(isset($this->columns["created"])){
				$this->record["created"] = date("Y-m-d H:i:s",strtotime("now"));
			}
			if(isset($this->columns["modified"])){
				$this->record["modified"] = date("Y-m-d H:i:s",strtotime("now"));
			}			
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
		$key = $this->record[$this->keyField];
		
		$sql = "DELETE FROM ".$this->table." WHERE ".$this->keyField.'='.intval($key);
		$rs = $this->db->query($sql);
		
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
		
		$this->record = $row;
		$this->isNew = false;
		
		return $this->record;
	}	
	
	public function findBy($field, $value) { 
		
		$sql = "SELECT * FROM ".$this->table." WHERE ".$field."='".$value."'";
		$rs = $this->db->query($sql);
		$row = $this->db->fetchRow();
		
		$this->record = $row;
		$this->isNew = false;
		
		return $this->record;
	}
	
	public function findBySql($sql) { 
				
		$rs = $this->db->query($sql);
		$row = $this->db->fetchRow();
		
		$this->record = $row;
		$this->isNew = false;
		
		return $this->record;
	}
	
	public function findAllBySql($sql) { 
				
		$rs = $this->db->query($sql);
		$rows = array();
		while($row = $this->db->fetchRow()) $rows[] = $row;
		return $rows;
	}
	
	function findAll($fields=NULL, $order=NULL ,$limit=NULL, $extra=NULL) {
		$fields = $fields ? $fields : '*';
		$sql = "SELECT ".$fields." FROM ". $this->table." ";
		if($extra) $sql.= $extra." ";
		if($order) $sql.= "ORDER BY ".$order.' ';
		if($limit) $sql.= "LIMIT ".$limit;
		$rs = $this->db->query($sql);
		$rows = array();
		while($row = $this->db->fetchRow()) $rows[] = $row;
		return $rows;
	}
	
	public function getPrimaryKey() {
		return $this->keyField;
	}
    
	public function offsetExists( $offset ) {
		return (isset( $this->record[$offset] ) );
	}
	
	public function offsetGet( $offset ) {
		return $this->record[$offset];
	}

	public function offsetSet( $offset, $value ) {
		if( $offset == $this->keyField ) {
			throw new Exception("Primary Key can't be set or changed");
		}
		$this->record[$offset] = $value;
	}

	public function offsetUnset( $offset ) {
		if( isset( $this->record[$offset] ) ) {
			unset( $this->record[$offset] );
		}
	}
	
	public function sql_escape($msg){
		return $this->db->sql_escape($msg);
	}
}
?>
