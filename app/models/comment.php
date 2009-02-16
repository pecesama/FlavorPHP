<?php

class comment extends models {

	private $spam;
	private $security;
	
	protected $filter = array(
			'content' => array(				
				'filters' => array(
					array('htmlLineBreaks'),
					array('xssClean', 'true')
				)
			)
		);
	
	
	protected $validate = array(	
			'author' => array(
				'required' => true,
				'rules' => array(
					array(
						'rule' => VALID_NOT_EMPTY,
						'message' => 'Por favor introduce tu Nombre.',
					)
				)
			),
			'url' => array(
				'rules' => array(
					array(
						'rule' => VALID_URL,
						'message' => 'No es una URL valida.'
					)
				)
			),
			'email' => array(
				'required' => true,
				'rules' => array(
					array(
						'rule' => VALID_EMAIL,
						'message' => 'El e-mail no es valido.'
					)
				)
			),
			'content' => array(
				'required' => true,
				'rules' => array(
					array(
						'rule' => VALID_NOT_EMPTY,
						'message' => 'Debes introducir un comentario.',
					),
					array(
						'rule' => array('isSpam'),
						'message' => 'No se aceptan comentarios en blanco o con spam.',
					)
				)
			)			
		);

	public function __construct() {
		parent::__construct();
		$this->spam = antispam::getInstance();
		$this->security = security::getInstance();		
	}
	
	public function htmlLineBreaks($value) {
		return nl2br($value);
	}
	
	public function xssClean($value, $params) {
		$type = (isset($params[0]))? $params[0] : 'false';
		return $this->security->clean($value, $type);
	}
	
	public function isSpam($value) {
		$valid = false;     
		if (empty($value)) {
			$valid = false;
		} else {
			if ($this->spam->isSpam($value) == false) {
				$valid = true;
			} else {
				$valid = false;
			}
		}		
		return $valid;
	}

	public function countComments($extra=NULL) { // Este no se para que fue pensado
		$sql = 'SELECT count(*) as total FROM `comments`';
		if(is_null($extra) == false) $sql.= ' '.$extra;
		$valid = $this->findBySql($sql);
        if(empty($valid) == false)
            return $valid['total'];
        return 0;
	}
	
	public function countCommentsByPost($idPost) {		
		if (isset($idPost)) {
			$sql = 'SELECT count(*) as total FROM `comments` WHERE id_post='.$idPost;
			$valid = $this->findBySql($sql);
			if(empty($valid) == false) {
				return $valid['total'];
			}
			return 0;			
		} else {
			return 0;
		}
	}
}

?>