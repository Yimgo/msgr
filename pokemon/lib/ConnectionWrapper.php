<?php
require_once 'lib/DatabaseConnectionFactory.php';

class ConnectionWrapper {
  private $connection;
  const sel = 'qsdfhsdherh';
  
  public function __construct($type) {
    $this->connection = DatabaseConnectionFactory::get($type);
  }
  
  public function signIn($login, $pwd) {
    $statement = $this->connection->prepare('SELECT user_id FROM user WHERE user_login = :login AND user_password = :pwd;');
    $statement->bindParam(':login', $login);
    $statement->bindParam(':pwd', hash('sha256', $pwd.self::sel));
    if ($statement->execute() === FALSE) {
      return FALSE;
    }
    
    if ($user = $statement->fetch()){
		  return $user["user_id"];
		}
		return FALSE;
  }
  
  public function getTags() { 
    if (($result = $this->connection->query('SELECT * FROM tag')) === FALSE) {
      return array();
    }
    
    $tags = array();
    while ($row = $result->fetch()) {
        array_push($tags, array("titre" => $row["tag_nom"], "id" => $row["tag_id"]));
    }
    return $tags;
  }
  
  public function signUp($login, $pwd, $email) {
    $insert_statement = $this->connection->prepare('INSERT INTO user(user_login, user_password, user_email) VALUES(:login, :pwd, :email);');
    $insserert_statement->bindParam(':login', $login);
    $insert_statement->bindParam(':pwd', hash('sha256', $pwd.self::sel));
    $insert_statement->bindParam(':email', $email);
    
    if($statement->execute() === FALSE) {
      return FALSE;
    }
    return $this->signIn($login, $pwd);
  }    
}
?>
