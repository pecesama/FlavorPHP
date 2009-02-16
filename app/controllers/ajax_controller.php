<?php

class ajax_controller extends controller {
	
	private $conf;
	
	public function __construct() {
		parent::__construct();
		$this->conf = new configuration();
		$this->conf->find(1);
		$this->l10n->setLanguage($this->conf["language"]);
	}
	
	public function index($id="") { }
	
	public function edit($id) {
		$this->view->conf = $this->conf;	
		$link = new bookmark();		
		$this->view->link = $link->find($id);
		$this->view->id = $id;
		if ($_SERVER["REQUEST_METHOD"]=="POST"){
			$link->prepareFromArray($_POST);
			$link->save();
			$this->redirect("index");
		} else {			
			$this->title_for_layout($this->l10n->__("Edit a bookmark"));
			$this->render();
		}		
	}
}

?>