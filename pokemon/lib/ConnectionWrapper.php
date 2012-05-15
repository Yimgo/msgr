<?php
require_once 'lib/DatabaseConnectionFactory.php';

class ConnectionWrapper {
  private $connection;
  const sel = 'qsdfhsdherh';
  
  public function __construct($type) {
    $this->connection=DatabaseConnectionFactory::get($type);
  }
  
  public function authUser($login, $pwd) {
    $statement = $this->connection->prepare('SELECT * FROM user WHERE user_login = :login AND user_password = :pwd;');
    $statement->bindParam(':login', $login);
    $statement->bindParam(':pwd', hash('sha256',$pwd.self::sel));
    $statement->execute();
    
    if ($user = $statement->fetch()){
		  return $user["user_id"];
		}
		return FALSE;
  }
  
  public function getTags() {
    $result = $this->connection->query('SELECT * FROM tag');
    $tags = array(); 
    while ($row = $result->fetch()) {
        array_push($tags, array("titre" => $row["tag_nom"], "id" => $row["tag_id"]));
    }
    return $tags;
  }
}
