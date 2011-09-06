<?php

class Singleton {
	private static $instances = array();
	
	public static function getInstance($class, $params = array()) {
		if (!isset(self::$instances[$class])) {
                    $reflector = new ReflectionClass($class);
                    
//                    $parameters = $reflector->getMethod('__construct')->getParameters();
//                    $parameters = $reflector->getMethod($class)->getParameters();
//                    if(count($parameters) > 0){
//                        self::$instances[$class] = $reflector->newInstanceArgs();
//                    } else {
//                        self::$instances[$class] = $reflector->newInstanceArgs($params);
//                    }
                    
                    self::$instances[$class] = $reflector->newInstanceArgs($params);
		}
		
		return self::$instances[$class];
	}
	
	private final function __clone() {}
}