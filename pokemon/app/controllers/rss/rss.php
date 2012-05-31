<?php

require_once 'lib/controller.php';
require_once 'lib/DatabaseConnectionFactory.php';
require_once 'app/controllers/rss/ConnectionWrapper.php';
require_once 'app/controllers/rss/rssparser.inc.php';

class RssController extends BaseController {
	private $connectionWrapper;
	private $NON_CLASSE;

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

	public function article($route) {
		$params = $this->getConnectionWrapper()->getArticleById($this->session_get("user_id", null), $route[0]);
		$this->render_view('article', $params);
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

	public function get_articles($params) {
	  if (!isset($params[0]))
	  	return array();

	  if (!isset($params[1])) {
	  	$params[1] = "";
	 		$params[2] = "";
	 	}

	 	else if (!isset($params[2]))
	 		$params[2] = "";

	  $begin = filter_var($params[1], FILTER_VALIDATE_INT, array('options' => array('default' => 0,
                                                                                        'min_range' => 0)));
    $count = filter_var($params[2], FILTER_VALIDATE_INT, array('options' => array('default' => 10,
                                                                                        'min_range' => 0)));

		$articles = $this->getConnectionWrapper()->getArticles($this->session_get('user_id', null), $params[0], $begin, $count);

		echo json_encode($articles);
	}

	public function get_latest_articles($params) {
	  if (!isset($params[0])) {
	  	$params[0] = "";
	 		$params[1] = "";
	 	}

	 	else if (!isset($params[1]))
	 		$params[1] = "";

	  $begin = filter_var($params[0], FILTER_VALIDATE_INT, array('options' => array('default' => 0,
                                                                                        'min_range' => 0)));
    $count = filter_var($params[1], FILTER_VALIDATE_INT, array('options' => array('default' => 10,
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
		$feed->enable_cache(false);
		$feed->handle_content_type();

		$feed_title=strip_tags($feed->get_title());

		if(strlen($feed_title)>50) {
			$feed_title=substr($feed_title,0,47).'...';
		}
		$exist=$this->getConnectionWrapper()->addFlux($_POST['url'],$feed_title,$feed->get_description());
		$idFlux=$this->getConnectionWrapper()->getFluxId($feed_title);
		$this->NON_CLASSE=$this->getConnectionWrapper()->getFolderId($this->session_get("user_id", null),'Non classé');

		$this->getConnectionWrapper()->addAbonnement($this->session_get("user_id", null),$this->NON_CLASSE,$idFlux);

		if(!$exist) {
			foreach ($feed->get_items() as $item):
				$item_title=strip_tags($item->get_title());
				if(strlen($item_title)>50) {
					$item_title=substr($item_title,0,47).'...';
				}
				$item_desc=$item->get_description();
				if(strlen($item_desc)==0) {
					$item_desc='Aucune description disponible: '.$item->get_permalink();
				}
				$item_content=$item->get_content();
				if(strlen($item_content)==0) {
					$item_content='Aucun contenu supplémentaire disponible: '.$item->get_permalink();
				}
				$this->getConnectionWrapper()->addArticle($idFlux,$item_title,$item->get_permalink(),$item_desc,$item_content,$item->get_date('Y-m-j G:i:s'));
			endforeach;
		}

		$this->redirect_to('listing');
	}
}

?>
