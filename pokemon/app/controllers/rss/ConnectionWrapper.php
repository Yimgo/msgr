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
    $statement = $this->connection->prepare('SELECT user_id FROM user WHERE user_login = :login AND user_password = :pwd;');
    $statement->bindParam(':login', $login);
    $saltedPwd=hash('sha256', $pwd.self::sel);
    $statement->bindParam(':pwd', $saltedPwd);
    if ($statement->execute() === FALSE) {
      return FALSE;
    }
    
    if ($user = $statement->fetch()){
      return $user["user_id"];
    }
    return FALSE;
  }
  
  public function getTags($id_user) {
    $statement = $this->connection->prepare('SELECT * FROM tag WHERE tag_id_user = :id_user;');
    $statement->bindParam(':id_user', $id_user);
    if ($statement->execute() === FALSE) {
      return array();
    }
    
    $tags = array();
    while ($row = $statement->fetch()) {
        array_push($tags, array("titre" => $row["tag_nom"], "id" => $row["tag_id"]));
    }
    return $tags;
  }
  
  public function setTags($tag_article_id_article, $tag_article_id_tag) {
        $insertStatement = $this->connection->prepare('SELECT COUNT(*) FROM tag_article WHERE tag_article_id_article=:id_article');
        $insertStatement->bindParam(':id_article', $tag_article_id_article);
		$res = $insertStatement->execute();
		$result = $insertStatement->fetch(PDO::FETCH_ASSOC);
		if($result['COUNT(*)'] == 0){
			return FALSE; //id_article pas dans bdd
		}
        $insertStatement = $this->connection->prepare('SELECT COUNT(*) FROM tag_article WHERE tag_article_id_article=:id_article AND tag_article_id_tag=:id_tag');
        $insertStatement->bindParam(':id_article', $tag_article_id_article);
		$insertStatement->bindParam(':id_tag', $tag_article_id_tag);
		$res = $insertStatement->execute();
		$result = $insertStatement->fetch(PDO::FETCH_ASSOC);
		print_r($result);
		if($result['COUNT(*)'] == 0){ //id_tag pas présent pour cet id_article
			$insertStatement = $this->connection->prepare('INSERT INTO tag_article(tag_article_id_article, tag_article_id_tag) VALUES(:id_article, :id_tag);');
			$insertStatement->bindParam(':id_article', $tag_article_id_article);
			$insertStatement->bindParam(':id_tag', $tag_article_id_tag);
			if($insertStatement->execute() === FALSE) {
			  return FALSE;
			}
		}
	}
	
  public function setFavori($id_user,$id_article, $bool_favori) {
		$insertStatement = $this->connection->prepare('SELECT COUNT(*) FROM lecture WHERE lecture_id_user=:id_user AND lecture_id_article=:id_article');
        $insertStatement->bindParam(':id_user', $id_user);
        $insertStatement->bindParam(':id_article', $id_article);
		$res = $insertStatement->execute();
		$result = $insertStatement->fetch(PDO::FETCH_ASSOC);
		if($result['COUNT(*)'] == 0){
			return FALSE; //couple id_user / id_article pas dans la table lecture
		}
        $insertStatement = $this->connection->prepare('SELECT * FROM lecture WHERE lecture_id_user=:id_user AND lecture_id_article=:id_article');
        $insertStatement->bindParam(':id_user', $id_user);
        $insertStatement->bindParam(':id_article', $id_article);
		$res = $insertStatement->execute();
		$result = $insertStatement->fetch(PDO::FETCH_ASSOC);
		if($result['lecture_sauvegarde'] != $bool_favori){
			$insertStatement = $this->connection->prepare('UPDATE lecture SET lecture_sauvegarde=:bool WHERE lecture_id_user=:id_user AND lecture_id_article=:id_article');
			$insertStatement->bindParam(':bool', $bool_favori);
			$insertStatement->bindParam(':id_user', $id_user);
			$insertStatement->bindParam(':id_article', $id_article);
			if($insertStatement->execute() === FALSE) {
			  return FALSE;
			}
		}
	}
	
  public function setLu($id_user,$id_article, $bool_lu) {
		$insertStatement = $this->connection->prepare('SELECT COUNT(*) FROM lecture WHERE lecture_id_user=:id_user AND lecture_id_article=:id_article');
        $insertStatement->bindParam(':id_user', $id_user);
        $insertStatement->bindParam(':id_article', $id_article);
		$res = $insertStatement->execute();
		$result = $insertStatement->fetch(PDO::FETCH_ASSOC);
		if($result['COUNT(*)'] == 0){
			return FALSE; //couple id_user / id_article pas dans la table lecture
		}
        $insertStatement = $this->connection->prepare('SELECT * FROM lecture WHERE lecture_id_user=:id_user AND lecture_id_article=:id_article');
        $insertStatement->bindParam(':id_user', $id_user);
        $insertStatement->bindParam(':id_article', $id_article);
		$res = $insertStatement->execute();
		$result = $insertStatement->fetch(PDO::FETCH_ASSOC);
		if($result['lecture_lu_nonlu'] != $bool_lu){
			$insertStatement = $this->connection->prepare('UPDATE lecture SET lecture_lu_nonlu=:bool WHERE lecture_id_user=:id_user AND lecture_id_article=:id_article');
			$insertStatement->bindParam(':bool', $bool_lu);
			$insertStatement->bindParam(':id_user', $id_user);
			$insertStatement->bindParam(':id_article', $id_article);
			if($insertStatement->execute() === FALSE) {
			  return FALSE;
			}
		}
	}
  
  public function signUp($login, $pwd, $email) {
    $insertStatement = $this->connection->prepare('INSERT INTO user(user_login, user_password, user_email) VALUES(:login, :pwd, :email);');
    $insertStatement->bindParam(':login', $login);
    $saltedPwd=hash('sha256', $pwd.self::sel);
    $insertStatement->bindParam(':pwd', $saltedPwd);
    $insertStatement->bindParam(':email', $email);
    
    if($insertStatement->execute() === FALSE) {
      return FALSE;
    }
    return $this->signIn($login, $pwd);
  }
  
  public function setTag($id_tag, $id_article, $tag) {
    if($tag){
      $insertStatement = $this->connection->prepare('INSERT INTO tag_article(tag_article_id_article, tag_article_id_tag) VALUES ( :id_article, :id_tag);');
      $insertStatement->bindParam(':id_article', $id_article);
      $insertStatement->bindParam(':id_tag', $id_tag);
      
      if($insertStatement->execute() === FALSE) {
        return FALSE;
      }
    }else{
      $insertStatement = $this->connection->prepare('DELETE FROM tag_article WHERE tag_article_id_article = :id_article AND tag_article_id_tag = :id_tag;');
      $insertStatement->bindParam(':id_article', $id_article);
      $insertStatement->bindParam(':id_tag', $id_tag);
      
      if($insertStatement->execute() === FALSE) {
        return FALSE;
      }
    }
	}

	public function getArticles($id_user, $id_flux) {
		$selectArticleLecture = $this->connection->prepare('SELECT article_id, article_contenu, article_titre, article_url, lecture_lu_nonlu, lecture_sauvegarde FROM article INNER JOIN lecture ON article_id = lecture_id_article WHERE article_id_flux = :id_flux AND lecture_id_user = :id_user;');
		$selectArticleLecture->bindParam(':id_flux', $id_flux);
		$selectArticleLecture->bindParam(':id_user', $id_user);
		if ($selectArticleLecture->execute() === FALSE) {
			return array();
		}
		
		$selectTag_Article = $this->connection->prepare('SELECT tag_article_id_tag FROM tag_article WHERE tag_article_id_article = :id_article;');
		
		$articles = array();
		while ($row = $selectArticleLecture->fetch()) {
			$tags = array();
			$selectTag_Article->bindParam('id_article', $row['article_id']);
			if($selectTag_Article->execute() !== FALSE) {
				$tags = $selectTag_Article->fetchAll(PDO::FETCH_COLUMN, 0);
			}

			$tags = array_map('intval', $tags);

			$selectTag_Article->closeCursor();

			array_push($articles, array(
				'id' => intval($row['article_id']),
				'contenu' => $row['article_contenu'],
				'titre' => $row['article_titre'],
				'url'  => $row['article_url'],
				'lu' => filter_var($row['lecture_lu_nonlu'], FILTER_VALIDATE_BOOLEAN),
				'tags' => $tags,
				'favori' => filter_var($row['lecture_sauvegarde'], FILTER_VALIDATE_BOOLEAN)
				));
		}

		return $articles;
	}


}
?>
