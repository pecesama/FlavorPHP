<?php

class index_controller extends appcontroller {

	private $conf;
	
	public function __construct() {
		parent::__construct();
		$this->conf = new configuration();
		$this->themes->conf = $this->conf->find(1);
	}
	
	public function __call($method, $args){
		$page = (int)$args[0];
		if ($method == 'page' and isset($args[0])) {
			if ($page == 1) {
				$this->redirect("");
			}
			$this->index(NULL, $page);
		}
	}
	
	public function index($id=NULL, $page=1){
		$post = new post();
		$link = new link();
		$comment = new comment();
				
		$this->html->setType($this->conf['current_theme']);
		
		$includes = $this->html->charsetTag("UTF-8");
		#$includes .= $this->html->includeRSS();
		#$includes .= $this->html->includeATOM();
		
		$this->themes->includes = $includes;

		$this->themes->links = $link->findAll();
		$this->themes->single = ($id) ? true : false;
		if($id){
			if($post_content = $post->findAll('*',null,1,"WHERE urlfriendly='".rawurlencode($post->sql_escape($id))."' AND status='publish'")){
				$post_content = $post_content[0];

				$post_content['title'] = htmlspecialchars($post_content['title']);
				$post_content['content'] = utils::hight($post_content['content']);
				$post_content['tags'] = $post->getTags($post_content['ID']);
				$this->themes->post = $post_content;
				$this->themes->comments_count = $comment->countCommentsByPost($post_content['ID']);
				
				$comments = $comment->findAll('comments.*, md5(comments.email) as md5_email','created',NULL,'WHERE ID_post='.$post_content['ID'].' AND status=1');
				foreach($comments as $k=>$comment){
					$comment['content'] = utils::hight($comment['content']);
					$comments[$k] = $comment;
				}				
								
				$this->themes->authorErr = $this->html->validateError('author');
				$this->themes->emailErr = $this->html->validateError('email');
				$this->themes->urlErr = $this->html->validateError('url');
				$this->themes->contentErr = $this->html->validateError('content');
				
				$this->themes->cookie = array(
					'author' => $this->cookie->check('author')?$this->cookie->author:'',
					'email' => $this->cookie->check('email')?$this->cookie->email:'',
					'url' => $this->cookie->check('url')?$this->cookie->url:'',
				);
				
				$this->themes->comments = $comments;
				$this->themes->id = $post_content['ID'];
			} else {
				//buscar
				$this->themes->title = "B&uacute;squedas";
				$this->themes->busqueda = strip_tags($id);
				if($this->themes->searches = $post->findAll("urlfriendly,title,match(title, content, urlfriendly) against('".$post->sql_escape($id)."') as score","score DESC",20,"WHERE status='publish' AND match(title, content, urlfriendly) against('".$post->sql_escape($id)."')")) {
					$this->renderTheme($this->conf['current_theme'],'search.htm');
				} else {
					$this->redirect(Path,false);
				}
			}
		} else {
			$total_rows = $post->countPosts();
			$page = (int) (is_null($page)) ? 1 : $page ;
			$limit = $this->conf['posts_per_page'];
			$offset = (($page-1) * $limit);
			$limitQuery = $offset.",".$limit;
			$targetpage = $this->path.'index/page/';
			
			$this->themes->pagination = $this->pagination->init($total_rows, $page, $limit, $targetpage);
			
			$posts = $post->findAll("ID,urlfriendly,title,IF(POSITION('<!--more-->' IN content)>0,MID(content,1,POSITION('<!--more-->' IN content)-1),content) as content",'ID DESC',$limitQuery,"WHERE status='publish'");
			foreach($posts as $k=>$p){
				$posts[$k]['title'] = htmlspecialchars($p['title']);
				$posts[$k]['tags'] = $post->getTags($posts[$k]['ID']);
				$posts[$k]['comments_count'] = $comment->countCommentsByPost($posts[$k]['ID']);
			}
			
			//$this->themes->comments_count = $comment->countCommentsByPost($comments['ID']); //aaaaaaa
			
			$this->themes->posts = $posts;
		}

		$this->renderTheme($this->conf['current_theme'],'index.htm');
	}
	
	public function addComment($id=null){
		if($_SERVER["REQUEST_METHOD"]=="POST"){
			if(is_null($id))
				$this->redirect(Path, false); //aqui se deberia usar conf 
		
			$id = (int) $id;			
			
			$_POST['suscribe'] = 0;
			if($this->cookie->check('id_user')){
				$_POST['user_id'] = $this->cookie->id_user;
				$_POST['status'] = 1;
			}else{
				$_POST['user_id'] = 0;
				$_POST['status'] = 0;
			}

			$_POST['type'] = '';//'pingback', 'trackback', ''

			$_POST['IP'] = utils::getIP();
			$_POST['ID_post'] = $id;

			$this->cookie->author = $_POST['author'];
			$this->cookie->email = $_POST['email'];
			$this->cookie->url = $_POST['url'];

			$comment = new comment();			
			$comment->prepareFromArray($_POST);			
			$comment->save();
			
			$post = new post();			
			$p = $post->findBy('ID',$id);
			
			$this->redirect($p['urlfriendly']);
		}
	}
}
?>	
