<?php

// Select the DB strategy
class dbFactory {

	private $strategy = NULL;
	
	public function __construct($strategy) {
		$dbm = array('mysqli','pgsql');
		if (!in_array($strategy,$dbm)) {
			throw new Exception('Invalid parameter for Data Base Strategy');
		}
		try {
			switch ($strategy) {
				case 'mysqli' :
					$this->strategy = mysqli_db::getInstance();
					break;
				case 'pgsql' :
					$this->strategy = pgsql_db::getInstance();
					break;
			}
		} catch(Exception $e) {
			echo $e->getMessage();
			exit();
		}
	}
	
	public function query($sql) {
		try {
			return $this->strategy->query($sql);
		} catch(Exception $e) {
			echo $e->getMessage();
			exit();
		}
	}
	
	public function buildArray($query, $assoc_ary = false) {
		try {
			return $this->strategy->buildArray($query, $assoc_ary);
		} catch(Exception $e) {
			echo $e->getMessage();
			exit();
		}
	}
	
	public function fetchRow() {
		try {
			return $this->strategy->fetchRow();
		} catch(Exception $e) {
			echo $e->getMessage();
			exit();
		}
	}	
	
	public function rowSeek($rowNum) {
		try {
			return $this->strategy->rowSeek($rowNum);
		} catch(Exception $e) {
			echo $e->getMessage();
			exit();
		}
	}
	
	public function lastId() {
		try {
			return $this->strategy->lastId();
		} catch(Exception $e) {
			echo $e->getMessage();
			exit();
		}
	}
	
	public function nextId($table, $primary) {
		try {
			return $this->strategy->nextId($table, $primary);
		} catch(Exception $e) {
			echo $e->getMessage();
			exit();
		}
	}
	
	public function numRows() {
		try {
			return $this->strategy->numRows();
		} catch(Exception $e) {
			echo $e->getMessage();
			exit();
		}
	}
	
	public function affectedRows() {
		try {
			return $this->strategy->affectedRows();
		} catch(Exception $e) {
			echo $e->getMessage();
			exit();
		}
	}
	
	public function sql_escape($msg) {
		try {
			return $this->strategy->sql_escape($msg);
		} catch(Exception $e) {
			echo $e->getMessage();
			exit();
		}
	}
	
	public function close() {
		try {
			return $this->strategy->close();
		} catch(Exception $e) {
			echo $e->getMessage();
			exit();
		}
	}	
}
?>