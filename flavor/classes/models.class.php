<?php

class Models extends ActiveRecord {
	protected $registry;
	public $validateErrors;
	public $filterErrors;
	protected $validate;
	protected $isValid = true;
	protected $filter;
	protected $messages;

	public function __construct() {
		parent::__construct();
		
		$this->registry = registry::getInstance();
		$this->validateErrors = $this->registry->validateErrors;
		$this->filterErrors = $this->registry->filterErrors;

		$this->validate = NULL;
		$this->filter = NULL;
		
		if (!defined('VALID_NOT_EMPTY')) { 
			define('VALID_NOT_EMPTY', '/.+/'); 
		}
		if (!defined('VALID_NUMBER')) { 
			define('VALID_NUMBER', '/^[0-9]+$/'); 
		}
		if (!defined('VALID_EMAIL')) { 
			define('VALID_EMAIL', '/^[a-zA-Z0-9]{1}([\._a-zA-Z0-9-]+)(\.[_a-zA-Z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+){1,3}$/'); 
		}
		if (!defined('VALID_URL')) { 
			define('VALID_URL', '/^(http|https):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}((:[0-9]{1,5})?\/.*)?$/i'); 
		}

		$this->messages = Message::getInstance();
	}
	
	public function doFilter($datos) {
		if(!$this->filter){ return $datos; }
			foreach ($datos as $campo => $valor) {			
				if(array_key_exists($campo, $this->filter)) {
					if(array_key_exists('filters', $this->filter[$campo])) {
						foreach($this->filter[$campo]['filters'] as $filter) {
							if(is_array($filter)) {
								$function = array_shift($filter);
								if(method_exists($this, $function)) {
									$datos[$campo] = $this->$function($valor, $filter);
									$valor = $datos[$campo];
								}else{
									die("<strong>Fatal Error:</strong> No existe la funcion {$function} que usa  el campo '{$campo}'");
								}
							}
						}
					} else {
						die("<strong>Fatal Error:</strong> No hay 'filters' declarados para el campo '{$campo}'");
				}
			}
		}

		return $datos;
	}

        public function invalidate(){
            $this->isValid = false;
	}

        public function validate(){
            $this->record = $this->doFilter($this->record);
            return $this->validates($this->record);
        }
	
	public function validates($datos) {
            if(!$this->validate){ return true; }
//          $this->registry->datos = $datos;
            foreach ($datos as $campo => $valor) {
                if(array_key_exists($campo, $this->validate)) {
                    if((!isset($this->validate[$campo]['required']) || $this->validate[$campo]['required']==false) /*&& empty($valor)*/ ){
                        continue;
                    };
                    if(array_key_exists('rules',$this->validate[$campo])) {
                        foreach($this->validate[$campo]['rules'] as $rule) {
                            if(array_key_exists('rule',$rule)) {
                                if(is_array($rule['rule'])) {
                                    $function = array_shift($rule['rule']);
                                    if(method_exists($this, $function)) {
                                        if($this->$function($valor,$rule['rule'])) {
                                            $this->validateErrors[] = array($campo => array ('message' => $rule['message']));
                                            $this->messages->addMessage(Message::ERROR, ucfirst($campo)." : {$rule['message']}");
                                        }
                                    } else {
                                        die("Fatal Error: No existe la funcion {$function} que usa  el campo '{$campo}'");
                                    }
                                } else {
                                    if(!preg_match($rule['rule'], $valor)) {
                                        $this->validateErrors[] = array($campo => array ('message' => $rule['message']));
                                        $this->messages->addMessage(Message::ERROR, ucfirst($campo)." : {$rule['message']}");
                                    }
                                }
                            } else {
                                die("Fatal Error: Se esperaba un 'rule' para el campo '{$campo}'");
                            }
                        }
                    } else {
                        die("Fatal Error: No hay 'rules' declaradas para el campo '{$campo}'");
                    }
                }
            }

            if (empty($this->validateErrors)) {
                return true;
            } else {
                $this->registry->modify("validateErrors", $this->validateErrors);
                return false;
            }
        }

        public function save(){
            $this->record = $this->doFilter($this->record);
            $this->isValid = $this->validates($this->record);

            if($this->isValid){
                return parent::save();
            }
        }
}