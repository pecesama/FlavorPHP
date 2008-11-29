<?php
/*
	Class name: Themes
	Class autor: Victor De la Rocha http//mis-algoritmos.com/themes-class
	Email: vyk2rr [at] gmail [dot] com
*/
class themes{
	protected $registry;
	protected $path;
	protected $l10n;

	protected $output;
	protected $vars=array(); //variable para apilar las variables que se asignan a la plantilla
	
	public function __construct(){
		$this->l10n = l10n::getInstance();
	}
	
	public function __set($name, $value){
		$this->vars[$name] = $value;
		return true;
	}

	public function remove($name) {
		unset($this->vars[$name]);
		return true;
	}
	
	//obtiene el contenido del tema ya con todos los valores sutituidos en las variables.
	public function fetch($file){
		$this->exec($file);
		return $this->output;
	}
	
	//muestra el contenido del tema ya con todos los valroes sustituidos en las variables.
	public function display($file){
		$this->exec($file);
		echo $this->output;
	}
	
	//corre el proceso de sustitucion de valores en las variables del theme y retorna todo en la variable $this->output para ser devuelto por: fetch o display
	public function exec($file){
		$this->file = $file;
		$output = file_get_contents($file);
		$this->output = $output;
		$this->registrar_vars();
		$this->__();
		$this->eval_control_structures();

		//evaluate All as PHP code
		ob_start();eval($this->output);
		$this->output = ob_get_clean();
	}
	
	protected function eval_control_structures(){ 
		//finding {header *}
		preg_match_all("/{header ([^}]+?)}/s",$this->output,$out);
		$headers = '';
		foreach ($out[1] as $o)
			$headers .= "header('$o');\n";

		$this->output = preg_replace("/{header [^}]+?}/s","",$this->output);

		//fake scape
		$this->output = $this->quotemeta($this->output);
		$this->output = str_replace('\"','\\"',trim($this->output));

		$this->output = "echo \"".str_replace('"','\"',trim($this->output))."\";";

		#$this->output = preg_replace("/\n+/s","\n",$this->output);
		$this->output = $headers.trim($this->output);

		//finding IFs sentences and converting to php code
		#$this->output = preg_replace_callback("/{if ([^}]+)}/",create_function('$arr','return "\";if(".stripslashes($arr[1])."){echo\"";'),$this->output);
		$this->output = preg_replace_callback("/{if ([^}]+)}/",create_function('$arr','return "\";if(".stripslashes(preg_replace(array("/\\\\$([a-zA-Z0-9]+)/s","/\\\\\\$this->vars\[\\\'([a-zA-Z0-9]+)\\\'\]\.([a-zA-Z0-9]+)/s"),array("\$this->vars[\'\$1\']","\\\\\\$this->vars[\\\'$1\\\'][\\\'$2\\\']"),$arr[1]))."){echo\"";'),$this->output);
		$this->output = preg_replace("/{else}/","\";}else{echo\"",$this->output);
		$this->output = preg_replace_callback("/{elseif ([^}]+)}/",create_function('$arr','return "\";}elseif(".stripslashes(preg_replace(array("/\\\\$([a-zA-Z0-9]+)/s","/\\\\\\$this->vars\[\\\'([a-zA-Z0-9]+)\\\'\]\.([a-zA-Z0-9]+)/s"),array("\$this->vars[\'\$1\']","\\\\\\$this->vars[\\\'$1\\\'][\\\'$2\\\']"),$arr[1]))."){echo\"";'),$this->output);
		$this->output = preg_replace("/{\/if}/","\";} echo \"",$this->output);

		//finding FOREACHs or BLOCKs sentences and converting to php code
		$this->output = preg_replace_callback("/{block ([^}]+) as ([^}]+)=>[\$]([^}]+)}/",create_function('$arr','return "\";foreach(".stripslashes(preg_replace(array("/\\\\$([a-zA-Z0-9]+)/s","/\\\\\\$this->vars\[\\\'([a-zA-Z0-9]+)\\\'\]\.([a-zA-Z0-9]+)/s"),array("\$this->vars[\'\$1\']","\\\\\\$this->vars[\\\'$1\\\'][\\\'$2\\\']"),$arr[1]))." as ".stripslashes(preg_replace(array("/\\\\$([a-zA-Z0-9]+)/s","/\\\\\\$this->vars\[\\\'([a-zA-Z0-9]+)\\\'\]\.([a-zA-Z0-9]+)/s"),array("\$this->vars[\'\$1\']","\\\\\\$this->vars[\\\'$1\\\'][\\\'$2\\\']"),$arr[2]))."=>\$this->vars[\'".stripslashes(preg_replace(array("/\\\\$([a-zA-Z0-9]+)/s","/\\\\\\$this->vars\[\\\'([a-zA-Z0-9]+)\\\'\]\.([a-zA-Z0-9]+)/s"),array("\$this->vars[\'\$1\']","\\\\\\$this->vars[\\\'$1\\\'][\\\'$2\\\']"),$arr[3]))."\']){echo\"";'),$this->output);
		$this->output = preg_replace_callback("/{block ([^}]+) as ([^}]+)}/",create_function('$arr','return "\";foreach(".stripslashes(preg_replace(array("/\\\\$([a-zA-Z0-9]+)/s","/\\\\\\$this->vars\[\\\'([a-zA-Z0-9]+)\\\'\]\.([a-zA-Z0-9]+)/s"),array("\$this->vars[\'\$1\']","\\\\\\$this->vars[\\\'$1\\\'][\\\'$2\\\']"),$arr[1]))." as ".stripslashes(preg_replace(array("/\\\\$([a-zA-Z0-9]+)/s","/\\\\\\$this->vars\[\\\'([a-zA-Z0-9]+)\\\'\]\.([a-zA-Z0-9]+)/s"),array("\$this->vars[\'\$1\']","\\\\\\$this->vars[\\\'$1\\\'][\\\'$2\\\']"),$arr[2]))."){echo\"";'),$this->output);
		$this->output = preg_replace("/{\/block}/","\";} echo \"",$this->output);

		//Converting the $this->vars[\'variable\'] format to {$this->vars['variable']}
		//$this->output = preg_replace("/[\$]this->vars\[\\\'([^ \.\\\]+)\\\'\]\[\\\'([^ \.\\\]+)\\\'\]/","{\$this->vars['$1']['$2']}",$this->output);
		$this->output = preg_replace("/[\$]this->vars\[\\\'([^ \.\\\]+)\\\'\]/","{\$this->vars['$1']}",$this->output);
		
		//Convertin the {__(\'word\')} format to {__('word')}
		#$this->output = preg_replace("/[\$]this->vars\[\\\'([^ \.\\\]+)\\\'\]/","{\$this->vars['$1']}",$this->output);
	}
	
	//sustituye las variables en el output del template
	protected function registrar_vars(){
		foreach($this->vars as $k=>$v){
			//pre($this->vars);
			if(is_array($v)){
				//Si es un arreglo, se intenta procesar un nivel mas adentro para sustituir en el theme por lo que tenga: nombredearreglo.dato
				foreach($v as $_k=>$_v)
					if(!is_array($_v))
						$this->output = str_replace('{'.$k.'.'.$_k.'}',$_v,$this->output);
			}else{
				// sustituimos directamente las variables {$variable}
				$this->output = str_replace('{'.$k.'}',$v,$this->output);
			}
		}
		
		//replacing {$key} format by $this->vars['key']
		//replacing  {$array.key} format by $this->vars['array']['key']  
		$patrones = array(
			'/{\$([^ \.}]+)}/s',
			'/{\$([^ \.}]+)\.([^ \.}]+)}/s'
		);
		$reemplazos = array(
			"{\$this->vars['$1']}",
			"{\$this->vars['$1']['$2']}"
		);
		$this->output = preg_replace($patrones, $reemplazos, $this->output);
	}
	
	//Utiliza gettext
	protected function __(){
		$patron = "/{__\((?:'|\")([^\)]+?)(?:'|\")\)}/s"; 
		preg_match_all($patron,$this->output,$out);
		
		foreach($out[1] as $k=>$v){
				$this->output = str_replace("{__('$v')}",$this->l10n->__($v),$this->output);
			}
	}

	protected function quotemeta($str){
		$chars = array(/*'.',*/ "\\", /*'+',*/ /*'*',*/ /*'?',*/ /*'[',*/ /*'^',*/ /*']',*/ /*'(',*/ /* '$' Por el momento no validar este*/);
		foreach($chars as $char)
			$this->output = str_replace($char,"\\$char",$this->output);
		return $this->output;
	}

	protected function unquotemeta($str){
		return $str;
	}

}
?>
