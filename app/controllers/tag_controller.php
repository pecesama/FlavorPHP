<?php

class tag_controller extends appcontroller {

	private $conf;
	
	public function __construct() {
		parent::__construct();
		$this->conf = new configuration();
		$this->themes->conf = $this->conf->find(1);
	}
	
	public function __call($method, $args){
		$page = (int) (isset($args[0])?$args[0]:1);
		if($page)
			$this->index($method, $page);
		else
			$this->redirect($this->conf['siteurl']);
	}
	
	public function index($id=NULL, $page=1){
		if(is_null($id) or is_numeric($id))
			$this->redirect($this->conf['siteurl']);
		
		$tag = $id;
		
		$post = new post();
		$link = new link();

		$this->html->setType($this->conf['current_theme']);

		$includes = $this->html->charsetTag("UTF-8");
		#$includes .= $this->html->includeRSS();
		#$includes .= $this->html->includeATOM();
		
		$this->themes->includes = $includes;
		
		$this->themes->links = $link->findAll();
		$this->themes->single = false;
		$total_rows = $post->countPosts(array('status'=>'publish','tag'=>$tag));

		$page = (int) (is_null($page)) ? 1 : $page;
		$limit = $this->conf['posts_per_page'];
		$offset = (($page-1) * $limit);
		$limitQuery = $offset.",".$limit;
		$targetpage = $this->path."tag/$tag/";

		$this->themes->pagination = $this->pagination->init($total_rows, $page, $limit, $targetpage);
	
		$posts = $post->getPostsByTag($tag,$limitQuery);

		foreach($posts as $k=>$p){
			$posts[$k]['title'] = htmlspecialchars($p['title']);
			$posts[$k]['tags'] = $post->getTags($p['ID']);
		}

		$this->themes->posts = $posts;

		$this->renderTheme($this->conf['current_theme'],'index.htm');
	}	

}

?>
