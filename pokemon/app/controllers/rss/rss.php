<?php

require_once 'lib/controller.php';
require_once 'lib/DatabaseConnectionFactory.php';
require_once 'app/controllers/rss/ConnectionWrapper.php';

class RssController extends BaseController {
	private $connectionWrapper;
	
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
		$this->redirect_to('index'); 
		}
	}

	public function folders($route) {
		$params = array(
			array("titre" => "toto", "id" => 0),
			array("titre" => "tata", "id" => 1)
		);
		$this->render_view("folders", $params);
	}

	public function add_folder($route, $params) {
		// $params["titre"]
		$this->redirect_to("folders");
	}

	public function delete_folder($route) {
		// $route[0] = id à supprimer
		$this->redirect_to("folders");
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
		$flux = array(
				array(
					"titre" => "Non classé", // DOSSIER qui contient tous les flux… sans dossier ;-)
					"id" => -1,
					"liste_flux" => array(
								array(
									"titre" => "Le site le plus bête du monde",
									"nb_nonlus" => 987,
									"id" => 12
								)
							)
				),
				array(
					"titre" => "Informations Françaises",
					"id" => 1,
					"liste_flux" => array(
								array(
									"titre" => "Le Monde",
									"nb_nonlus" => 14,
									"id" => 0
								),
								array(
									"titre" => "Le Figaro",
									"nb_nonlus" => 2,
									"id" => 1
								),
								array(
									"titre" => "Le Progrès",
									"nb_nonlus" => 0,
									"id" => 2
								),
								array(
									"titre" => "Le Canard Enchainé",
									"nb_nonlus" => 130,
									"id" => 3
								)
							)
				),
				array(
					"titre" => "Informatique",
					"id" => 2,
					"liste_flux" => array(
								array(
									"titre" => "PCInpact",
									"nb_nonlus" => 2,
									"id" => 4
								),
								array(
									"titre" => "LinuxFR",
									"nb_nonlus" => 20,
									"id" => 5
								)
							)
				)
			);
		// pour tester le rendu en cas d'erreur cote client
		if (rand(0, 10) == 0) echo "erreur json; df ;d;f d;";
			else echo json_encode($flux);
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
		
		// pour tester le rendu en cas d'erreur cote client
		if (rand(0, 10) == 0) 
			echo "erreur json; df ;d;f d;";
		else 
			echo json_encode($articles);
	}
	
	public function getTagged() {
			// Renvoie les tags pour un utilisateur
			echo json_encode($this->getConnectionWrapper()->getTaggedArticles($this->session_get("user_id", null)));
	}
	
	
	public function set_tag($route, $params) {
		$article_id = $params['article_id'];
		$tag_id = $params['tag_id'];
		if(filter_var($params['tag'], FILTER_VALIDATE_BOOLEAN))
			$this->getConnectionWrapper()->tagArticle($article_id, $tag_id);
		else
			$this->getConnectionWrapper()->untagArticle($article_id, $tag_id);
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
}

?>
