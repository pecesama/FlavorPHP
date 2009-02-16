<?php

class feed_controller extends controller {

	private $conf;
	
	public function __construct() {
		parent::__construct();
		$this->conf = new configuration();
		$this->conf->find(1);
		$this->l10n->setLanguage($this->conf["language"]);
	}
	
	public function index($id=NULL) {
		$this->rss($id);
	}
	
	public function rss($id=NULL) {		
		$link = new bookmark();
        $this->view->conf = $this->conf;
		$this->view->setLayout("feed");
        $this->view->links = $link->findAll(NULL, "id_link DESC", NULL, NULL);
		$this->render("rss");
	}	
	
	public function atom() {		
		$link = new bookmark();
        $this->view->conf = $this->conf;
		$this->view->setLayout("feed");
        $this->view->links = $link->findAll(NULL, "id_link DESC", NULL, NULL);
		$this->render();
	}
}

?>