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
  
  public function tagArticle($tag_id, $article_id) {
    $insertStatement = $this->connection->prepare('INSERT INTO map_tag_article(article_id, tag_id) VALUES (:article_id, :tag_id);');
    $insertStatement->bindParam(':article_id', $article_id);
    $insertStatement->bindParam(':tag_id', $tag_id);
    
    if ($insertStatement->execute() === FALSE) {
      return FALSE;
    }
  }
  
  function untagArticle($tag_id, $article_id) {
    $insertStatement = $this->connection->prepare('DELETE FROM map_tag_article WHERE article_id = :article_id AND tag_id = :tag_id;');
    $insertStatement->bindParam(':article_id', $article_id);
    $insertStatement->bindParam(':tag_id', $tag_id);
    
    if($insertStatement->execute() === FALSE) {
      return FALSE;
    }
	}

	public function getArticles($user_id, $flux_id) {
		$selectArticleLecture = $this->connection->prepare('SELECT article.id id, article.contenu contenu, article.titre titre, article.url url, lecture.lu lu, lecture.favori favori FROM article INNER JOIN lecture ON article.id = lecture.article_id WHERE article.flux_id = :flux_id AND lecture.user_id = :user_id;');
		$selectArticleLecture->bindParam(':flux_id', $flux_id);
		$selectArticleLecture->bindParam(':user_id', $user_id);
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
				'titre' => $row['titre'],
				'url'  => $row['url'],
				'lu' => filter_var($row['lu'], FILTER_VALIDATE_BOOLEAN),
				'tags' => $tags,
				'favori' => filter_var($row['favori'], FILTER_VALIDATE_BOOLEAN)
				));
		}

		return $articles;
	}


}
?>
