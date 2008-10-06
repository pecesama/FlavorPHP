<?php

class pgsql_db extends singleton implements data {

	private $connectionId;
	private $query_result;
	private $transaction = false;
	
	protected function __construct() {
		$connectStr = 'host='.DB_Server.(DB_Port?' port='.DB_Port:'').' dbname='.DB_name.' user='.DB_User.' password='.DB_Password;
		
		$this->connectionId = @pg_connect($connectStr);

		if (!$this->connectionId || DB_name=='') {
			throw new Exception($this->errorInfo());
		}
	}
	
	public static function getInstance() {
		return parent::getInstance(get_class());
	}
			
	public function query($sql) {
		if ($sql != '') {
			$this->query_result = @pg_query($this->connectionId, $sql);
			if ($this->query_result  === false) {
				throw new Exception($this->errorInfo($sql));
			}			
		} else {
			return false;
		}
		return ($this->query_result) ? $this->query_result : false;
	}
	
	public function buildArray($query, $assoc_ary = false) {
		if (!is_array($assoc_ary)) {
			throw new Exception('Is not a valid array');
		}

		$fields = array();
		$values = array();
		if ($query == 'INSERT') {
			foreach ($assoc_ary as $key => $var) {
				$fields[] = "`{$key}`";

				if (is_null($var)) {
					$values[] = 'NULL';
				} elseif (is_string($var)) {
					$values[] = "'" . $this->sql_escape($var) . "'";
				} else {
					$values[] = (is_bool($var)) ? intval($var) : $var;
				}
			}

			$query = ' (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $values) . ')';
		} else if ($query == 'UPDATE' || $query == 'SELECT') {
			$values = array();
			foreach ($assoc_ary as $key => $var) {
				if (is_null($var)) {					
					array_push($values, "`{$key}` = NULL");
				} elseif (is_string($var) and !is_numeric($var)) {
					array_push($values, "`{$key}` = '".$this->sql_escape($var)."'");
				} else {
					array_push($values, (is_bool($var)) ? "`{$key}` = ".intval($var) : "`".$key."` = $var");
				}
			}
			$query = implode(($query == 'UPDATE') ? ', ' : ' AND ', $values);
		}

		return $query;
	}
	
	public function fetchRow() {		
		return pg_fetch_assoc($this->query_result);
		/* TODO: Add Exception handler */		
	}
	
	public function rowSeek($rowNum) {
		$re = @pg_result_seek($this->query_result, $rowNum);
		if (!$re) {
			throw new Exception($this->errorInfo());
		}
		return $re;
	}

	//To get the value of a SERIAL field in an inserted row
	public function lastId() {
		$re = ($this->connectionId) ? @pg_last_oid($this->connectionId) : false;
		if (!$re) {
			throw new Exception($this->errorInfo());
		}
		return $re;
	}
	
	function nextId($table, $primary) {		
		$this->query("select max(".$primary.") as nextId from ".$table);
		$row = $this->getRow();
		return $row["nextId"] + 1;
	} 
	
	public function numRows() {
		$re = @pg_num_rows($this->query_result);
		if (!$re) {
			throw new Exception($this->errorInfo());
		}
		return $re;
	}
	
	function affectedRows() {
		$re = ($this->connectionId) ? @pg_affected_rows($this->connectionId) : false;
		if (!$re) {
			throw new Exception($this->errorInfo());
		}
		return $re;
	}
	
	public function sql_escape($msg) { return $msg;
		return pg_escape_string($this->connectionId, $msg);
	}
	
	public function errorInfo($sql = '') {
		return '<u>SQL ERROR</u> <br /><br />' . pg_last_error($this->connectionId) . '<br /><br />'.$sql;
	}
	
	public function close() {
		if (!$this->connectionId) {
			return false;
		}

		@pg_close($this->connectionId);
	}
	
	public function __destruct() {
		$this->close();
	}

}
?>