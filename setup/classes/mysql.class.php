<?php
class mysql{
	var $link=false;
	var $row;
	function mysql(){
			global $host,$user,$pass,$db;
			$this->link = @mysql_connect($host,$user,$pass) or die(mysql_error());
			mysql_select_db($db,$this->link);
		}

	function query($sqlStr){
			if(!$this->link)exit("<strong>No hay conexi&oacute;n a la base de datos</strong>.");
			$this->SQLquery = mysql_query($sqlStr,$this->link) or die($this->error($sqlStr));
			return $this->SQLquery;
		}
	
	function fetch(){
			$this->row = mysql_fetch_assoc($this->SQLquery);
			return $this->row;
		}

	function sql_quote($value){
			if(get_magic_quotes_gpc())
				$value = stripslashes($value);
			//check if this function exists 
			if(function_exists('mysql_real_escape_string'))
				$value = mysql_real_escape_string($value,$this->link);
			//for PHP version < 4.3.0 use addslashes 
			else
				$value = addslashes($value);
			return $value;
		}
	
	function error($sql){
		$error = "<div style='background-color:#f0f0f0;font-family:courier new;font-size:11px;'>";
			$error .= "<p>".mysql_error()."</p>";
			$error .= "SQL: ".$sql;
		$error .= "</div>";
		return $error;
	}
}
?>
