<?php
/**
 * Debug class
 * 
 * USO BASICO: en los controllers se puede usar como 
 * $this->debug->log('datos','titulo')
 * y en el render aparece una box arriba diciendo 'titulo:datos' mas otra informacion de debug, como $_POST y $_GET
 * 
 *
 * @package debug
 **/

class Debug extends Singleton{
	/**
	 * Va guardando la informacion que el desarrollador va enviando usando $debug->log(...);
	 *
	 * @var array
	 **/
	public $data = array();
	
	/**
	 * Define el separador que se va usar en las cadenas de peticion de debug
	 *
	 * @var string
	 **/
	public $splitter = ',';
	//TODO hacer esto configurable??
	
	function __construct(){
		
	}
	
	public static function getInstance() {
		return parent::getInstance(get_class());
	}
	
	public function makeDebugBox($content = ''){
		return '<div style="border:1px solid #000; min-height:20px; background:#efefef; padding: 0; width:90%;margin:0 auto 10px auto; font-size:11px; position:relative;">
			<h3 style="padding:0; margin:2px 0 4px 10px; border-bottom:1px solid #999">Debug Mode</h3>
			<div style="height:20px; width:20px;position:absolute; top:0; right:0;"><a href="#" onclick="var target= document.getElementById(\'flvr_debug_info\'); if(target.style.display!=\'block\'){ target.style.display=\'block\';} else { target.style.display=\'none\';} return false;">&nbsp;+&nbsp;</a></div>
			<div id="flvr_debug_info" style="display:none;overflow:scroll;height:100px; padding:10px ">'.$content.'</div>
		</div>';
	}
	
	public function isEnabled(){
		//TODO hacer esto configurable??
		return false;
	}
	
	public	function getDefaultData(){
		//TODO hacer esto configurable??
		return array('GET','POST','SESSION');
	}
	
	public function isRequested(){
		return isset($_GET['debug']);
	}
	
	public function getRequestedData(){
		if(isset($_GET['debug'])){
			return explode($this->splitter,$_GET['debug']);
		}else{
			return array();
		}
	}
	
	
	private function fetchData(){
		$vars = array();
		if($this->isRequested()){
			$vars = array_merge($vars,$this->getRequestedData());
		}
		if(count($this->getRequestedData()) == 0){
			$vars = array_merge($vars,$this->getDefaultData());
		}
		//Mejorar esto
		$get = $_GET;
		$post = $_POST;
		$session = $_SESSION;
		$cockie  = $_COOKIE;
		$server =  $_SERVER;
		if(isset($_SESSION['flavor_php_session']['debug_logs']) && count($_SESSION['flavor_php_session']['debug_logs']) > 0){
			foreach($_SESSION['flavor_php_session']['debug_logs'] as $key=>$var){
				$this->data[] = array('title'=>$var['title'],'value'=>$var['value']);
			}
		}
		foreach($vars as $key=>$var){
			$this->data[] = array('title'=>$var,'value'=>(print_r(${strtolower($var)},1)));
		}
	}
	
	public function clearLogs(){
		unset($_SESSION['flavor_php_session']['debug_logs']);
	}
	
	public function log($args, $title = '', $append = false){
		$new_data = array();
		$temp_data = '';
		if(!is_array($args)){
			$args = array(array($args,$title));
		}
		foreach($args as $key=>$data){
			if(!$append){
				$_SESSION['flavor_php_session']['debug_logs'][md5($title)] = array('title'=>$data[1],'value'=>$data[0]);
			}else{
				if(isset($_SESSION['flavor_php_session']['debug_logs'][md5($title)])){
					$temp_data = $_SESSION['flavor_php_session']['debug_logs'][md5($title)]['value'];
				}else{
					$temp_data = '';
				}
				$_SESSION['flavor_php_session']['debug_logs'][md5($title)] = array('title'=>$data[1],'value'=>$temp_data.$data[0]);
			}
			
		}
		
	}
	
	public function show(){
		$this->fetchData();
		$info = '<ul>';
		foreach($this->data as $key=> $data){
			$info .= '<li style="border-bottom:1px solid #999;"><h4>'.$data['title'].'</h4><pre>'.$data['value'].'</pre>';
		}
		$info .= '</ul>';
		return $this->makeDebugBox($info);
	}
}