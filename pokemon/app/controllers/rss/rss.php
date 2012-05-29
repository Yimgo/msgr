<?php

require_once 'lib/controller.php';
require_once 'lib/DatabaseConnectionFactory.php';
require_once 'app/controllers/rss/ConnectionWrapper.php';
//require_once ($_SERVER["DOCUMENT_ROOT"].'/pokemon/'.'app/controllers/rss/rssparser.inc.php');
require_once 'app/controllers/rss/rssparser.inc.php';

class RssController extends BaseController {
	private $connectionWrapper;
	const NON_CLASSE = 1;
	
	private function getConnectionWrapper() {
		if(!isset($this->connectionWrapper)) {
			$this->connectionWrapper = new ConnectionWrapper();
		}
		
		return $this->connectionWrapper;
	}
	
	public function index($route) {
		if (is_null($this->session_get("user_id", null))) {
			$is_connected = false;
		}
		else {
			$is_connected = true;
		}
		
		if ($is_connected)
			$this->redirect_to('listing');
		else
			$this->redirect_to('login');
	}
	
	public function listing($route) {
		$this->render_view('listing', null);
	}
	
	public function login($route, $params) {
		if (isset($params["user_login"])) {
			if (($user_id = $this->getConnectionWrapper()->signIn($params["user_login"], $params["user_password"])) === FALSE) {
				$this->render_view('login', array('type' => 'login', 'state' => 'error', 'error' => 'credentials'));
			}
			else {
				$this->session_set("user_id", $user_id);
				$this->redirect_to('index'); 
			}
		}
		else {
			$this->render_view('login', array('type' => 'login', 'state' => 'new_conn', 'error' => ''));
		}
	}
	
	public function logout($route) {
		$this->session_unset_var('user_id');
		$this->redirect_to('index');
	}
	
	public function signup($route, $params) {
		$user_login = $params['user_login'];
		$user_password = $params['user_password'];
		$user_email = $params['user_email'];
		
		/* Si un des champs n'est pas rempli */
		if (empty($user_login)) {
			$this->render_view('login', array('type' => 'signup', 'state' => 'error', 'error' => 'user_login'));
		return;
		}
		else if (empty($user_password)) {
			$this->render_view('login', array('type' => 'signup', 'state' => 'error', 'error' => 'user_password'));
		return;
		}
		else if (empty($user_email)) {
			$this->render_view('login', array('type' => 'signup', 'state' => 'error', 'error' => 'user_email'));
		return;
		}
		
		/* Inscription dans la base de données */
		if (($user_id = $this->getConnectionWrapper()->signUp($user_login, $user_password, $user_email)) === FALSE) {
			$this->render_view('login', array("type" => "signup", 'state' => 'error', 'error' => 'db'));
		}
		else {
			$this->session_set("user_id", $user_id);
			$this->getConnectionWrapper()->addFolder($this->session_get("user_id", null),'Non classé');
			$this->redirect_to('index'); 
		}
	}
	
	public function folders($route) {
		$folder=$this->getConnectionWrapper()->getFolders($this->session_get("user_id", null));
		$this->render_view("folders", $folder);
	}

	public function add_folder($route, $params) {
		$this->getConnectionWrapper()->addFolder($this->session_get("user_id", null),$params["titre"]);
		$this->redirect_to("folders");
	}

	public function delete_folder($route) {
		$this->getConnectionWrapper()->deleteFolder($this->session_get("user_id", null),$route[0]);
		$this->redirect_to("folders");
	}

	public function rename_folder($route, $params) {
		$this->getConnectionWrapper()->renameFolder($this->session_get("user_id", null), $params['id'], $params['titre']);
		$this->redirect_to("folders");
	}

	public function move_flux_folder($route, $params) {
		// $params["flux_id"] : flux concerné
		// $params["dossier_id"] : nouveau dossier
		$this->getConnectionWrapper()->changeFolder($this->session_get("user_id", null),$params['flux_id'], $params['dossier_id']);	
	}

	public function search($route) {
		$search = $_GET["search"];
		$tags_id = explode(',', $_GET["tags_id"]);
	}
	
	public function get_tags() {
			// Renvoie les tags pour un utilisateur
			echo json_encode($this->getConnectionWrapper()->getTags($this->session_get("user_id", null)));
	}
	
	public function get_flux_dossiers() {		
		// Renvoie tous les flux et l'organisation en dossier (TODO: login)
		$flux = $this->getConnectionWrapper()->getFluxByFolders($this->session_get("user_id", null));
		// pour tester le rendu en cas d'erreur cote client
		echo json_encode($flux);
	}
	
	public function get_articles($id_flux) {
		// Renvoie tous les articles pour un flux donné (TODO: login)
		/*$lorem = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum";
		 * 
		 *   $articles = array(
		 *       array("titre" => "Test 1",
		 *       "id" => 0,
		 *       "contenu" => $lorem,
		 *       "favori" => true,
		 *       "lu" => true,
		 *       "tags" => array(1,2)),
		 *       array("titre" => "Test numero bis",
		 *       "id" => 1,
		 *       "contenu" => $lorem,
		 *       "favori" => false,
		 *       "lu" => false,
		 *       "tags" => array()),
		 *       array("titre" => "Un troisieme article",
		 *       "id" => 2,
		 *       "contenu" => $lorem,
		 *       "favori" => true,
		 *       "lu" => true,
		 *       "tags" => array(4)),
		 *       array("titre" => "Last one",
		 *       "id" => 10,
		 *       "contenu" => $lorem . $lorem . $lorem . $lorem,
		 *       "favori" => false,
		 *       "lu" => true,
		 *       "tags" => array(6,7))
		);*/
		
		$articles = $this->getConnectionWrapper()->getArticles($this->session_get('user_id', null), $id_flux);
		
		echo json_encode($articles);
	}
	
	public function get_latest_articles($limits) {
	  $limits_array  = explode('/', $limits);
	  $begin = filter_var($limits_array[0], FILTER_VALIDATE_INT, array('options' => array('default' => 0,
                                                                                        'min_range' => 0)));
    $count = filter_var($limits_array[1], FILTER_VALIDATE_INT, array('options' => array('default' => 10,
                                                                                        'min_range' => 0)));
    echo json_encode($this->getConnectionWrapper()->getLatestArticles($this->session_get('user_id', null), $begin, $count));
	}
	
	public function getTagged() {
			// Renvoie les tags pour un utilisateur
			echo json_encode($this->getConnectionWrapper()->getTaggedArticles($this->session_get("user_id", null)));
	}
	
	
	public function set_tag($route, $params) {
		if (isset($params['article_id']) && isset($params['tag_id'])&&isset($params['tag'])) {
		  $article_id = $params['article_id'];
		  $tag_id = $params['tag_id'];
		 
			if(filter_var($params['tag'], FILTER_VALIDATE_BOOLEAN))
				$this->getConnectionWrapper()->tagArticle($article_id, $tag_id);
			else
				$this->getConnectionWrapper()->untagArticle($article_id, $tag_id);
    }
	}
	
	public function set_favori($route, $params) {
		$user_id = $this->session_get('user_id', null);
		$article_id = $params['article_id'];
		$favori = filter_var($params['favori'], FILTER_VALIDATE_BOOLEAN);
		$this->getConnectionWrapper()->setFavori($user_id, $article_id, $favori);
	}
	
	public function set_lu($route, $params) {
		$user_id = $this->session_get('user_id', null);
		$article_id = $params['article_id'];
		$lu = filter_var($params['lu'], FILTER_VALIDATE_BOOLEAN);
		$this->getConnectionWrapper()->setLu($user_id, $article_id, $lu);
	}
	
	public function parse_single_feed($flux)
	{
		$feed= new SimplePie();
		$feed->set_feed_url($_POST['url']);
		$feed->init();
		$feed->handle_content_type();
		$exist=$this->getConnectionWrapper()->addFlux($feed->get_permalink(),$feed->get_title(),$feed->get_description());
		$idFlux=$this->getConnectionWrapper()->getFluxId($feed->get_title());
		
		if(!$exist) {
			foreach ($feed->get_items() as $item): 
				$this->getConnectionWrapper()->addArticle($idFlux,$item->get_title(),$item->get_permalink(),$item->get_description(),$item->get_date('Y-m-j G:i:s'));
			endforeach;	
		}

		$this->getConnectionWrapper()->addAbonnement($this->session_get("user_id", null),self::NON_CLASSE,$idFlux);
		
		$this->redirect_to('listing');
	}
}

?>
