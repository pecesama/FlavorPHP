<?php

class utils{
	function pre($arr,$exit = true){
		echo "<pre>";
		print_r($arr);
		echo "</pre>";
		if($exit){
			exit;
		}
	}
}
