<?php

class Message extends singleton implements ArrayAccess{
    
    const INFORMATION = 'info';
    const ERROR = 'error';
    const WARNING = 'warning';
    const SUCCESS = 'success';

    public function  __construct() {
        if(!isset($_SESSION['messages'])){
            $_SESSION['messages'] = array();
        }
    }

    public static function getInstance() {
        return parent::getInstance(get_class());
    }

    public function addMessage($type, $message){
        $_SESSION['messages'][] = array(
            "type" => $type,
            "message" => $message
        );
    }

    public function getMessages(){
        $messages = $_SESSION['messages'];
        $_SESSION['messages'] = array();
        return $messages;
    }

    public function issetMessages(){
        $isset = 0;
        if(isset($_SESSION['messages']) == true && count($_SESSION['messages']) > 0){
            $isset = 1;
        }
        return $isset;
    }

    /*
     * ArrayAccess Methods
     */
    public function offsetExists($offset) {
        
    }

    public function  offsetGet($offset) {
        
    }

    public function  offsetSet($offset, $value) {
        
    }

    public function  offsetUnset($offset) {
        
    }
    
}
