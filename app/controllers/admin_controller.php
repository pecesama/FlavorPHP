<?php

class admin_controller extends appcontroller {

	private $conf;

	public function __construct() {
		parent::__construct();
		$this->conf = new configuration();
		$this->conf->find(1);
	}

	public function index($id = NULL) {
		$post = new post();
        $this->view->conf = $this->conf;
		$total_rows = $post->countPosts();
		$page = $id;
		$page = (is_null($page)) ? 1 : $page ;
		$limit = 10;
		$offset = (($page-1) * $limit);
		$limitQuery = $offset.",".$limit;
        $targetpage = $this->path.'admin/index/';
        $pagination = $this->pagination->init($total_rows, $page, $limit, $targetpage);
        $this->view->pagination = $pagination;
		$this->view->posts = $post->findAll(NULL, "ID DESC", $limitQuery, NULL);
		$this->view->setLayout("admin");
		$this->title_for_layout($this->l10n->__("Administraci&oacute;n - Codice CMS"));
		$this->render();
	}
	
	public function login($msg = "") {
		if ($msg == "nosession") {
			$this->cookie->flash = "La URL solicitada necesita autentificacion.";
		} elseif ($msg == "fail") {
			$this->cookie->flash = "Lo siento, la informacion ingresada es incorrecta.";
		} elseif ($msg == "logout") {
			$this->cookie->flash = "Haz terminado la sesion correctamente.";
		}
		$user = new user();
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if(($valid = $user->validateLogin($_POST)) == true) {
                $this->cookie->id_user = $valid['id_user'];
                $this->cookie->logged = $valid ? true : false ;
                $this->redirect("admin/");
            } else {
				$this->redirect("admin/login/fail/");
            }
		} else {
			$this->view->setLayout("admin");
			$this->title_for_layout("Login - Codice CMS");
			$this->render();
		}
	}

	public function add() {
		$post = new post();
		if ($_SERVER["REQUEST_METHOD"]=="POST") {
			if(isset($_POST['cancelar'])) {
				$this->redirect("admin/");
			}
			if (isset($_POST['borrador'])) {
				$_POST['status'] = 'draft';
				unset($_POST['borrador']);
			} elseif (isset($_POST['publicar'])) {
				$_POST['status'] = 'publish';
				unset($_POST['publicar']);
			} else {
				$this->redirect("admin/");
			}
			$_POST['urlfriendly'] = $post->buildUrl($_POST['title']);

			$tags = $_POST['tags'];
			unset($_POST['tags']);

			$post->prepareFromArray($_POST);
			$post->save();

			$post_id = $post->db->lastId();
			$post->updateTags($post_id,$tags);

			$this->redirect("admin/");
		} else {
			$this->view->setLayout("admin");
			$this->title_for_layout($this->l10n->__("Agregar post - Codice CMS"));
			$this->render();
		}
	}

	public function edit($id=null) {
		$id = (int) $id;
		if(!$id)$this->redirect('admin');

		$this->view->conf = $this->conf;
		$post = new post();
		$post->find($id);
		$post['title'] = utils::convert2HTML($post['title']);
		$post['content'] = utils::convert2HTML($post['content']);
		$post['tags'] = $post->getTags($id,'string');

		$this->view->post = $post;
		$this->view->id = $id;
		$statuses = array("publish", "draft");
		$this->view->statuses = $statuses;
		if ($_SERVER["REQUEST_METHOD"]=="POST") {
			if(isset($_POST['cancelar'])){
				$this->redirect("admin/");
			} else {
				###########
				# Las siguientes dos lineas no deberian estar pero algo anda mal con el ActiveRecord que no deja las variables
				# de las consultas que se realizan directamente desde dentro de algun metodo en el model con $this->db->query e interfiere
				# con el actualizar por que podria haber campos que no se requieren en la actualizacion.
				###########
				$post = new post();#######
				$post->find($id);####### 
				$_POST['urlfriendly'] = $post->buildUrl($_POST['urlfriendly'], $id);

	 			$post->updateTags($id,$_POST['tags']);
				unset($_POST['tags']);

				$post->prepareFromArray($_POST);

				$post->save();
				$this->redirect("admin/");
			}
		} else {
			$this->view->setLayout("admin");
			$this->title_for_layout($this->l10n->__("Editar post - Codice CMS"));
			$this->render();
		}
	}

	public function remove($id){
		$post = new post();
		$post->find($id);
		$post->delete();
		$this->redirect("admin/");
	}
	
	public function configuration($id){
		$this->view->conf = $this->conf;
		if ($_SERVER["REQUEST_METHOD"]=="POST"){
			if($_POST['admin_pass1']!=$_POST['admin_pass2']) {
				$this->cookie->flash = $this->l10n->__("Passwords must match");
				$this->render();
			} else {
				if($_POST['admin_pass1']!=""){
					$_POST['admin_pass']=md5($_POST['admin_pass1']);
				} 
				unset($_POST['admin_pass1']);
				unset($_POST['admin_pass2']);
				$this->conf->prepareFromArray($_POST);
				$this->conf->save();
				$this->redirect("admin/");
			}
		} else {
			$this->view->languages = $this->l10n->getLocalization();
			$this->title_for_layout($this->l10n->__("Configure"));
			$this->render();
		}
	}	

	function logout() {
		$this->cookie->destroy("id_user");
		$this->cookie->destroy("logged");
        $this->redirect("admin/login/logout/");
    }

	public function beforeRender() {
		if($this->action != "login" && $this->action != "logout") {
            if($this->cookie->check("logged") == false) {
                $this->redirect("admin/login/nosession/");
            }
        }
	}
}
?>
