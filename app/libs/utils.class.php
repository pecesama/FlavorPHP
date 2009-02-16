<?php
class utils{
	//Debug 
	public function pre($arr,$exit=true){
		echo "<pre>";print_r($arr);echo"</pre>";
		if($exit)exit;
	}

	public function convert2HTML($content){
		$content = htmlspecialchars($content);
		return str_replace("$","&#36;",$content);
	}

	public function hight($code,$lang='php'){
		$geshi = new GeSHi;		
		$function_code = '
		 $geshi = new GeSHi;
		 $geshi->set_source($matches[1]);
		 $geshi->set_language("php-brief");
		 return "<div class=\"code\">".$geshi->parse_code()."</div>";
		';
		$code = preg_replace_callback('/\[php\](.*?)\[\/php\]/ims',create_function('$matches',$function_code),$code);
		$code = preg_replace_callback('/\[php num=.*?\](.*?)\[\/php\]/ims',create_function('$matches',$function_code),$code);
		
		$code = preg_replace_callback('/<\?php(.*?)\?>/ims',create_function('$matches','ob_start();eval($matches[1]); return ob_get_clean();'),$code);
		return $code;
	}
	
	public function getIP(){
		if($ip = getenv('HTTP_X_FORWARDED_FOR'))
			return $ip;
		elseif($ip = getenv('REMOTE_ADDR'))
			return $ip;		
		return false;
	}
}
?>
