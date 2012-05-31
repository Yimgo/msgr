<?php
require_once 'lib/Config.php';
require_once 'lib/DatabaseConnectionFactory.php';


class ConnectionWrapper {
	private $connection;
	const sel = 'qsdfhsdherh';

	public function __construct() {
		$this->connection = DatabaseConnectionFactory::get(Config::getConfigParam('DatabaseConnection', 'default_profile'));
	}

	public function signIn($login, $pwd) {
		$statement = $this->connection->prepare('SELECT id FROM user WHERE login = :login AND password = :pwd;');
		$statement->bindParam(':login', $login);
		$saltedPwd=hash('sha256', $pwd.self::sel);
		$statement->bindParam(':pwd', $saltedPwd);
		if ($statement->execute() === FALSE) {
			return FALSE;
		}

		if ($user = $statement->fetch()){
			return $user["id"];
		}
		return FALSE;
	}

	public function signUp($login, $pwd, $email) {
		$insertStatement = $this->connection->prepare('INSERT INTO user(login, password, email) VALUES(:login, :pwd, :email);');
		$insertStatement->bindParam(':login', $login);
		$saltedPwd = hash('sha256', $pwd.self::sel);
		$insertStatement->bindParam(':pwd', $saltedPwd);
		$insertStatement->bindParam(':email', $email);

		if($insertStatement->execute() === FALSE) {
			return FALSE;
		}
		return $this->signIn($login, $pwd);
	}

	public function getTags($user_id) {
		$statement = $this->connection->prepare('SELECT id, nom FROM tag WHERE user_id = :user_id;');
		$statement->bindParam(':user_id', $user_id);
		if ($statement->execute() === FALSE) {
			return array();
		}

		$tags = array();
		while ($row = $statement->fetch()) {
			array_push($tags, array("titre" => $row["nom"], "id" => $row["id"]));
		}
		return $tags;
	}


	public function getTaggedArticles($tag_id) {
		$statement = $this->connection->prepare('SELECT article_id FROM map_tag_article WHERE tag_id = :tag_id;');
		$statement->bindParam(':tag_id', $tag_id);
		if ($statement->execute() === FALSE) {
		  return array();
		}
		$articles = array();
		while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
			$statement_article = $this->connection->prepare('SELECT titre,id,contenu,lu,favori FROM article,lecture WHERE article.id = lecture.article_id = :article_id;');
			$statement_tags = $this->connection->prepare('SELECT tag_id FROM map_tag_article WHERE article_id = :article_id;');
			$statement_article->bindParam(':article_id', $row["article_id"]);
			$statement_tags->bindParam(':article_id', $row["article_id"]);
			if ($statement_article->execute() === FALSE) {
			  return array();
			}
			$fetch = $statement_article->fetch(PDO::FETCH_ASSOC);
			if($fetch != ""){
				$array = array("titre" => $fetch["titre"], "id" => $fetch["id"], "contenu" => $fetch["contenu"], "favori" => $fetch["favori"], "lu" => $fetch["lu"], "tags" => array());
				if ($statement_tags->execute() === FALSE) {
					  return array();
				}
				$tags_array = array();
				while ($row_tag = $statement_tags->fetch(PDO::FETCH_ASSOC)) {
					array_push($array["tags"], $row_tag["tag_id"]);
				}
				array_push($articles, $array);
			}
		}
		return $articles;
	}


	public function setTags($article_id, $tag_id) {
		$insertStatement = $this->connection->prepare('INSERT INTO map_tag_article(article_id, tag_id) VALUES(:article_id, :tag_id);');
		$insertStatement->bindParam(':article_id', $article_id);
		$insertStatement->bindParam(':tag_id', $tag_id);
		if($insertStatement->execute() === FALSE) {
			return FALSE;
		}
	}

	public function setFavori($user_id, $article_id, $favori) {
		$insertStatement = $this->connection->prepare('UPDATE lecture SET favori=:favori WHERE user_id=:user_id AND article_id=:article_id');
		$insertStatement->bindParam(':favori', $favori);
		$insertStatement->bindParam(':user_id', $user_id);
		$insertStatement->bindParam(':article_id', $article_id);
		if($insertStatement->execute() === FALSE) {
			return FALSE;
		}
	}

	public function setLu($user_id, $article_id, $lu) {
		$insertStatement = $this->connection->prepare('UPDATE lecture SET lu=:lu WHERE user_id=:user_id AND article_id=:article_id');
		$insertStatement->bindParam(':lu', $lu);
		$insertStatement->bindParam(':user_id', $user_id);
		$insertStatement->bindParam(':article_id', $article_id);
		if($insertStatement->execute() === FALSE) {
			return FALSE;
		}
	}

	public function tagArticle($article_id, $tag_id) {
		$insertStatement = $this->connection->prepare('INSERT INTO map_tag_article(article_id, tag_id) VALUES (:article_id, :tag_id);');
		$insertStatement->bindParam(':article_id', $article_id);
		$insertStatement->bindParam(':tag_id', $tag_id);

		if ($insertStatement->execute() === FALSE) {
			return FALSE;
		}
	}

	public function untagArticle($article_id, $tag_id) {
		$insertStatement = $this->connection->prepare('DELETE FROM map_tag_article WHERE article_id = :article_id AND tag_id = :tag_id;');
		$insertStatement->bindParam(':article_id', $article_id, PDO::PARAM_INT);
		$insertStatement->bindParam(':tag_id', $tag_id, PDO::PARAM_INT);

		if($insertStatement->execute() === FALSE) {
			return FALSE;
		}
	}

	public function getArticleById($user_id, $article_id) {
		$selectArticleLecture = $this->connection->prepare('SELECT article.id id, article.contenu contenu, article.titre titre, article.url url, lecture.lu lu, lecture.favori favori, article.date date, flux.nom flux_nom FROM article, lecture, flux WHERE flux.id = article.flux_id AND article.id = lecture.article_id AND article.id = :article_id AND lecture.user_id = :user_id');
		$selectArticleLecture->bindParam(':article_id', $article_id);
		$selectArticleLecture->bindParam(':user_id', $user_id);
		if ($selectArticleLecture->execute() === FALSE) {
			return array();
		}
		else {
			$row = $selectArticleLecture->fetch();
			if ($row === FALSE) return array();

			$result = array(
				'id' => intval($row['id']),
				'flux_nom' => $row['flux_nom'],
				'contenu' => $row['contenu'],
				'titre' => $row['titre'],
				'url'  => $row['url'],
				'lu' => filter_var($row['lu'], FILTER_VALIDATE_BOOLEAN),
				'favori' => filter_var($row['favori'], FILTER_VALIDATE_BOOLEAN),
				'date' => $row['date'],
				'tags' => array()
			);

			$selectTag_Article = $this->connection->prepare('SELECT tag.id id, tag.nom nom FROM map_tag_article, tag WHERE article_id = :article_id AND tag.user_id = :user_id AND map_tag_article.tag_id = tag.id ;');
			$selectTag_Article->bindParam(':user_id', $user_id);
			$selectTag_Article->bindParam(':article_id', $article_id);

			if ($selectTag_Article->execute() !== FALSE) {
				while ($row2 = $selectTag_Article->fetch()) {
					array_push($result['tags'], array('id' => $row2['id'], 'nom' => $row2['nom']));
				}
			}

			return $result;
		}
	}

	public function getArticles($user_id, $flux_id, $begin, $count) {
		$selectArticleLecture = $this->connection->prepare('SELECT article.id id, article.contenu contenu, article.description description, article.titre titre, article.url url, lecture.lu lu, lecture.favori favori, article.date date FROM article INNER JOIN lecture ON article.id = lecture.article_id WHERE article.flux_id = :flux_id AND lecture.user_id = :user_id ORDER BY article.date DESC LIMIT :begin, :count;');
		$selectArticleLecture->bindParam(':flux_id', $flux_id);
		$selectArticleLecture->bindParam(':user_id', $user_id);
		$selectArticleLecture->bindParam(':begin', $begin, PDO::PARAM_INT);
		$selectArticleLecture->bindParam(':count', $count, PDO::PARAM_INT);
		if ($selectArticleLecture->execute() === FALSE) {
			return array();
		}

		$selectTag_Article = $this->connection->prepare('SELECT tag_id FROM map_tag_article WHERE article_id = :article_id;');

		$articles = array();
		while ($row = $selectArticleLecture->fetch()) {
			$tags = array();
			$selectTag_Article->bindParam('article_id', $row['id']);
			if($selectTag_Article->execute() !== FALSE) {
				$tags = $selectTag_Article->fetchAll(PDO::FETCH_COLUMN, 0);
			}

			$tags = array_map('intval', $tags);

			$selectTag_Article->closeCursor();

			array_push($articles, array(
				'id' => intval($row['id']),
				'description' => $row['description'],
				'titre' => $row['titre'],
				'url'  => $row['url'],
				'lu' => filter_var($row['lu'], FILTER_VALIDATE_BOOLEAN),
				'tags' => $tags,
				'favori' => filter_var($row['favori'], FILTER_VALIDATE_BOOLEAN),
				'date' => $row['date']
				));
		}

		return $articles;
	}

	public function addArticle($flux_id,$titre,$url,$description, $contenu,$date) {
		$insertArticle = $this->connection->prepare('INSERT INTO article(flux_id, titre, url, description, contenu,date) VALUES (:flux_id,:titre,:url,:description, :contenu,:date);');
		$insertArticle->bindParam(':flux_id', $flux_id);
		$insertArticle->bindParam(':titre', $titre);
		$insertArticle->bindParam(':url', $url);
		$insertArticle->bindParam(':description', $description);
		$insertArticle->bindParam(':contenu', $contenu);
		$insertArticle->bindParam(':date', $date);

		if ($insertArticle->execute() === FALSE) {
			return False;
		}
	}

	public function getFluxId($nom) {
		$statement = $this->connection->prepare('SELECT id FROM flux WHERE nom = :nom;');
		$statement->bindParam(':nom', $nom);
		if ($statement->execute() === FALSE) {
			return False;
		}
		$donnees = $statement->fetch();
		$statement->closeCursor();
		return $donnees['id'];
	}

	public function addFlux($url,$nom,$description) {
		$statement = $this->connection->prepare('SELECT * FROM flux WHERE nom = :nom;');
		$statement->bindParam(':nom', $nom);
		if ($statement->execute() === FALSE) {
			return FALSE;
		}

		if (count($statement->fetchAll())==0) {
			$exist=FALSE;
			$insertFlux = $this->connection->prepare('INSERT INTO flux(url, nom, description) VALUES (:url,:nom,:description);');
			$insertFlux->bindParam(':url', $url);
			$insertFlux->bindParam(':nom', $nom);
			$insertFlux->bindParam(':description', $description);
			if ($insertFlux->execute() === FALSE) {
				return FALSE;
			}
		}
		else{
			$exist=TRUE;
		}
		$statement->closeCursor();
		return $exist;
	}

	public function addAbonnement($user_id,$dossier_id,$flux_id) {
		$insertAbonnement = $this->connection->prepare('INSERT INTO abonnement(user_id, dossier_id, flux_id) VALUES (:user_id, :dossier_id, :flux_id);');
		$insertAbonnement->bindParam(':user_id', $user_id);
		$insertAbonnement->bindParam(':dossier_id', $dossier_id);
		$insertAbonnement->bindParam(':flux_id', $flux_id);
		if ($insertAbonnement->execute() === FALSE) {
			return False;
		}
	}

	public function addFolder($user_id,$nom) {
		$insertFolder = $this->connection->prepare('INSERT INTO dossier(nom, user_id) VALUES (:nom, :user_id);');
		$insertFolder->bindParam(':nom', $nom);
		$insertFolder->bindParam(':user_id', $user_id);
		if ($insertFolder->execute() === FALSE) {
			return False;
		}
	}

	public function getFolderId($user_id,$nom) {
		$statement = $this->connection->prepare('SELECT id FROM dossier WHERE nom = :nom AND user_id=:user_id;');
		$statement->bindParam(':user_id', $user_id);
		$statement->bindParam(':nom', $nom);
		if ($statement->execute() === FALSE) {
			return False;
		}
		$donnees = $statement->fetch();
		$statement->closeCursor();
		return $donnees['id'];
	}

	public function getNbTotalArticles($flux_id) {
		$statement = $this->connection->prepare('SELECT * FROM article WHERE flux_id = :flux_id;');
		$statement->bindParam(':flux_id', $flux_id);
		if ($statement->execute() === FALSE) {
			return FALSE;
		}
		$result=count($statement->fetchAll());
		$statement->closeCursor();
		return $result;
	}

	public function getNbArticlesNotRead($flux_id,$user_id) {
		$statement = $this->connection->prepare('SELECT * FROM article a INNER JOIN lecture l ON a.id = l.article_id
				WHERE a.flux_id =:flux_id AND l.lu=0 AND l.user_id=:user_id;');
		$statement->bindParam(':flux_id', $flux_id);
		$statement->bindParam(':user_id', $user_id);
		if ($statement->execute() === FALSE) {
			return FALSE;
		}
		$result=count($statement->fetchAll());
		$statement->closeCursor();
		return $result;
	}

	public function getFolders($user_id) {
		$donnees=array();
		$statement = $this->connection->prepare('SELECT * FROM dossier WHERE user_id = :user_id;');
		$statement->bindParam(':user_id', $user_id);
		if ($statement->execute() === FALSE) {
			return FALSE;
		}

		while ($result = $statement->fetch())
		{
			array_push($donnees,array("titre" =>$result['nom'],"id" =>$result['id']));
		}

		$statement->closeCursor();
		return $donnees;
	}

	public function deleteFolder($user_id,$folder_id) {
		$statement = $this->connection->prepare('DELETE FROM dossier WHERE user_id = :user_id AND id = :folder_id;');
		$statement->bindParam(':folder_id', $folder_id);
		$statement->bindParam(':user_id', $user_id);
		if ($statement->execute() === FALSE) {
			return FALSE;
		}
	}

	public function renameFolder($user_id, $folder_id, $nom) {
		$statement = $this->connection->prepare('UPDATE dossier SET nom=:nom WHERE user_id = :user_id AND id = :folder_id;');
		$statement->bindParam(':nom', $nom);
		$statement->bindParam(':user_id', $user_id);
		$statement->bindParam(':folder_id', $folder_id);

		if ($statement->execute() === FALSE) {
			return FALSE;
		}
	}

	public function getFluxByFolders($user_id) {
		$tab2=array();
		$donnees=array();
		$folder=$this->getFolders($user_id);

		for($i=0;$i<sizeof($folder);$i++)
		{
			$statement = $this->connection->prepare('SELECT flux.nom,abonnement.flux_id FROM flux,abonnement WHERE abonnement.dossier_id=:dossier_id AND abonnement.flux_id=flux.id AND user_id=:user_id;');
			$statement->bindParam(':dossier_id', $folder[$i]['id']);
			$statement->bindParam(':user_id', $user_id);
			if ($statement->execute() === FALSE) {
				return FALSE;
			}

			while ($result = $statement->fetch())
			{
				array_push($donnees,array("titre" =>$result['nom'],"nb_nonlus" =>$this->getNbArticlesNotRead($result['flux_id'],$user_id),"id" =>$result['flux_id']));
			}

			$statement->closeCursor();
			array_push($tab2,array("titre" =>$folder[$i]['titre'],"id"=>$folder[$i]['id'],"liste_flux"=>$donnees));
			$donnees=array();
		}
		return $tab2;
	}

	public function changeFolder($user_id,$flux_id,$folder_id) {
		$statement = $this->connection->prepare('UPDATE abonnement SET dossier_id=:dossier_id WHERE flux_id = :flux_id AND user_id=:user_id;');
		$statement->bindParam(':dossier_id', $folder_id);
		$statement->bindParam(':flux_id', $flux_id);
		$statement->bindParam(':user_id', $user_id);

		if ($statement->execute() === FALSE) {
			return FALSE;
		}
	}

	public function getLatestArticles($user_id, $begin, $count) {
	  $selectArticleLecture = $this->connection->prepare(<<<EOD
SELECT article.id id, article.description description, article.contenu contenu, article.titre titre, article.url url, lecture.lu lu, lecture.favori favori, article.date date 
FROM article 
INNER JOIN lecture ON article.id = lecture.article_id 
WHERE lecture.user_id = :user_id 
AND article.flux_id IN (
	SELECT flux_id
	FROM abonnement
	WHERE user_id = :user_id)
AND lecture.lu = 0
ORDER BY article.date DESC 
LIMIT :begin,:count;
EOD
);

		$selectArticleLecture->bindParam(':user_id', $user_id);
		$selectArticleLecture->bindParam(':begin', $begin, PDO::PARAM_INT);
		$selectArticleLecture->bindParam(':count', $count, PDO::PARAM_INT);
		if ($selectArticleLecture->execute() === FALSE) {
			return array();
		}

		$selectTag_Article = $this->connection->prepare('SELECT tag_id FROM map_tag_article WHERE article_id = :article_id;');

		$articles = array();
		while ($row = $selectArticleLecture->fetch()) {
			$tags = array();
			$selectTag_Article->bindParam('article_id', $row['id']);
			if($selectTag_Article->execute() !== FALSE) {
				$tags = $selectTag_Article->fetchAll(PDO::FETCH_COLUMN, 0);
			}

			$tags = array_map('intval', $tags);

			$selectTag_Article->closeCursor();

			array_push($articles, array(
				'id' => intval($row['id']),
				'contenu' => $row['contenu'],
				'description' => $row['description'],
				'titre' => $row['titre'],
				'url'  => $row['url'],
				'lu' => filter_var($row['lu'], FILTER_VALIDATE_BOOLEAN),
				'tags' => $tags,
				'favori' => filter_var($row['favori'], FILTER_VALIDATE_BOOLEAN),
				'date' => $row['date']
				));
		}

		return $articles;
  }
}
?>
