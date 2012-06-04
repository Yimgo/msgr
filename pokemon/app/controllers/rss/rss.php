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




	/* index() routes vistor to login page if not logged, to listing page otherwise */
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

	/*
	 * login() displays login interface if user is not logged.
	 * POST parameters awaited:
	 *	user_login
	 *	user_password	 *
	 */
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

	/* logout() allows user to disconnect; session is cleared then and user is redirected to login page */
	public function logout($route) {
		$this->session_unset_var('user_id');
		$this->redirect_to('index');
	}

	/*
	 * signup() allows user to register and redirect to listing page
	 * POST parameters awaited:
	 *	user_login
	 *	user_password
	 *	user_email
	 */
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




	/* listing() displays main view: folders/feeds and associated articles */
	public function listing($route) {
		$this->render_view('listing', null);
	}

	/*
	 * move_flux_folder() allows user to move a feed to a specified folder.
	 * POST parameters awaited:
	 	flux_id
	 	dossier_id
 	 */
	public function move_flux_folder($route, $params) {
		$this->getConnectionWrapper()->changeFolder($this->session_get("user_id", null),$params['flux_id'], $params['dossier_id']);
	}

	/* search(): I don't know what is the purpose of this function :/ */
	public function search($route) {
		$search = $_GET["search"];
		$tags_id = explode(',', $_GET["tags_id"]);
	}

	/* get_tags() displays user's tags, JSON */
	public function get_tags() {
			echo json_encode($this->getConnectionWrapper()->getTags($this->session_get("user_id", null)));
	}

	/* get_flux_dossier() displays feeds, associated with their folder, current user, JSON */
	public function get_flux_dossiers() {
		$flux = $this->getConnectionWrapper()->getFluxByFolders($this->session_get("user_id", null));
		echo json_encode($flux);
	}

	/*
	 * get_articles() displays articles for a specified feed and current user.
	 * GET parameters awaited:
	 * 	0: feed id
	 *	1: offset of fetched articles (optional)
	 *	2: number of articles fetched (optional)
	 */
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

	/*
	 * get_latest_articles() latest unread articles for current user.
	 * GET parameters awaited:
	 *	0: offset of fetched articles (optional)
	 *	1: number of articles fetched (optional)
	 */
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

	/*
	 * getTagged(): I don't know what is the purpose of this function.
	 * This function certainly won't work because:
	 *	- user id is given to getTaggedArticles which is awaiting tag id
	 *	- getTaggedArticles() returns all articles for specified tag id, not only current user ones
	 * The behaviour of this function is unknown.
	 * WARNING: please fix this function or remove it if not used.
	 */
	public function getTagged() {
			echo json_encode($this->getConnectionWrapper()->getTaggedArticles($this->session_get("user_id", null)));
	}

	/*
	 * set_tag() allows current user to tag or untag the specified article with the specified tag.
	 * POST parameters awaited:
	 *	article_id
	 * 	tag_id
	 * 	tag [true/false => tag/untag]
	 */
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

	/*
	 * set_favori() allows current user to set specified article as starred.
	 * POST parameters awaited:
	 *	article_id
	 *	favori [true/false => star/unstar]
	 */
	public function set_favori($route, $params) {
		$user_id = $this->session_get('user_id', null);
		$article_id = $params['article_id'];
		$favori = filter_var($params['favori'], FILTER_VALIDATE_BOOLEAN);
		$this->getConnectionWrapper()->setFavori($user_id, $article_id, $favori);
	}

	/*
	 * set_lu() allows current user to set specified article as read.
	 * POST parameters awaited:
	 *	article_id
	 *	lu [true/false => read/unread]
	 */
	public function set_lu($route, $params) {
		$user_id = $this->session_get('user_id', null);
		$article_id = $params['article_id'];
		$lu = filter_var($params['lu'], FILTER_VALIDATE_BOOLEAN);
		$this->getConnectionWrapper()->setLu($user_id, $article_id, $lu);
	}

	/*
	 * add_commentaire() allows user to add a comment related to a specified feed.
	 * POST parameters awaited:
	 *	article_id
	 *	commentaire: content
	 */
	public function add_commentaire($route, $params) {
		$this->getConnectionWrapper()->addCommentaire($this->session_get("user_id", null),$params['article_id'], htmlspecialchars($params['commentaire']);
		$this->redirect_to('article/'.$params['article_id'].'#comments');
	}

	/*
	 * get_commentaires() returns all comments related to a specified feed.
	 * GET parameters awaited:
	 *	0: article id
	 */
	public function get_commentaires($route) {
		echo json_encode($this->getConnectionWrapper()->getCommentaires($route[0]));
	}

	/*
	 * parse_single_feed() allows user to add a new feed.
	 * The new feed is added to 'Non classé' folder by default.
	 * A new suscribtion is also added.
	 * POST parameter awaited:
	 *	url: feed's hyperlink
	 */
	public function parse_single_feed($flux)
	{
		$feed= new SimplePie();
		$feed->set_feed_url($_POST['url']);
		$feed->init();
		$feed->enable_cache(false);
		$feed->handle_content_type();

		$feed_title=strip_tags($feed->get_title());

		if (strlen($feed_title)>50) {
			$feed_title=substr($feed_title,0,47).'...';
		}
		$exist = $this->getConnectionWrapper()->addFlux($_POST['url'], $feed_title, $feed->get_description());
		$idFlux = $this->getConnectionWrapper()->getFluxId($feed_title);
		$this->NON_CLASSE = $this->getConnectionWrapper()->getFolderId($this->session_get("user_id", null), 'Non classé');

		$this->getConnectionWrapper()->addAbonnement($this->session_get("user_id", null), $this->NON_CLASSE, $idFlux);

		if (!$exist) {
			foreach ($feed->get_items() as $item):
				$item_title = strip_tags($item->get_title());
				if (strlen($item_title) > 50) {
					$item_title = substr($item_title, 0, 47) . '...';
				}
				$item_desc = $item->get_description();
				if (strlen($item_desc) == 0) {
					$item_desc = 'Aucune description disponible: ' . $item->get_permalink();
				}
				$item_content = $item->get_content();
				if (strlen($item_content) == 0) {
					$item_content = 'Aucun contenu supplémentaire disponible: ' . $item->get_permalink();
				}
				$this->getConnectionWrapper()->addArticle($idFlux, $item_title, $item->get_permalink(), $item_desc, $item_content, $item->get_date('Y-m-j G:i:s'));
			endforeach;
		}

		$this->redirect_to('listing');
	}




	/* folders() displays folders management interface, which allows user to add, rename or delete folders */
	public function folders($route, $params) {
		$folder=$this->getConnectionWrapper()->getFolders($this->session_get("user_id", null));
		if ($this->session_get("folder_suppress_error", null) === null) {
			$this->render_view("folders", array("Folders" => $folder, "State" => "ok"));
		} else {
			$this->session_unset_var('folder_suppress_error');
			$this->render_view("folders", array("Folders" => $folder, "State" => "error"));
		}
	}

	/*
	 * add_folder() allows user to add a folder.
	 * POST parameter awaited:
	 *	titre
	 */
	public function add_folder($route, $params) {
		$this->getConnectionWrapper()->addFolder($this->session_get("user_id", null),$params["titre"]);
		$this->redirect_to("folders");
	}

	/*
	 * delete_folder() allows user to delete a folder.
	 * WARNING: folder is able to be removed with a GET request; Id should be in a POST parameter.
	 * TODO: fix this.
	 * GET parameter awaited:
	 *	0: folder id
	 */
	public function delete_folder($route) {
		if ($this->getConnectionWrapper()->deleteFolder($this->session_get("user_id", null),$route[0]) === FALSE) {
			$this->session_set("folder_suppress_error", TRUE);
		}
		$this->redirect_to("folders");
	}

	/*
	 * rename_folder() allows user to rename a folder.
	 * POST parameters awaited:
	 *	id
	 *	titre
	 */
	public function rename_folder($route, $params) {
		$this->getConnectionWrapper()->renameFolder($this->session_get("user_id", null), $params['id'], $params['titre']);
		$this->redirect_to("folders");
	}




	/* tags() displays folders management interface, which allows user to add, rename or delete tags */
	public function tags($route) {
		$tags=$this->getConnectionWrapper()->getTags($this->session_get("user_id", null));
		if ($this->session_get("tag_suppress_error", null) === null) {
			$this->render_view("tags", array("Tags" => $tags, "State" => "ok"));
		} else {
			$this->session_unset_var('tag_suppress_error');
			$this->render_view("tags", array("Tags" => $tags, "State" => "error"));
		}
	}

	/*
	 * add_tag() allows user to add a tag.
	 * POST parameter awaited:
	 *	titre
	 */
	public function add_tag($route, $params) {
		$this->getConnectionWrapper()->addTag($this->session_get("user_id", null),$params["nom"]);
		$this->redirect_to("tags");
	}

	/*
	 * delete_tag() allows user to delete a tag.
	 * WARNING: tag is able to be removed with a GET request; Id should be in a POST parameter.
	 * TODO: fix this.
	 * GET parameter awaited:
	 *	0: tag id
	 */
	public function delete_tag($route) {
		if ($this->getConnectionWrapper()->deleteTag($this->session_get("user_id", null),$route[0]) === FALSE) {
			$this->session_set("tag_suppress_error", TRUE);
		}
		$this->redirect_to("tags");
	}

	/*
	 * rename_tag() allows user to rename a tag.
	 * POST parameters awaited:
	 *	id
	 *	nom
	 */
	public function rename_tag($route, $params) {
		$this->getConnectionWrapper()->renameTag($this->session_get("user_id", null), $params['id'], $params['nom']);
		$this->redirect_to("tags");
	}


	/*
	 * article() displays article interface, which allows user to manage tags and post comments.
	 * GET parameter awaited:
	 * 	0: article id
	*/
	public function article($route) {
		$params = $this->getConnectionWrapper()->getArticleById($this->session_get("user_id", null), $route[0]);
		$params["all_tags"] = $this->getConnectionWrapper()->getTags($this->session_get("user_id", null));
		$params['comments'] = $this->getConnectionWrapper()->getCommentaires($route[0]);
		$this->render_view('article', $params);
	}
}

?>
