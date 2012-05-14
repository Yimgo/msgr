<?php
 
require_once "lib/controller.php";

class TodolistController extends BaseController
{
	public function index($route)
	{
		$this->render_view("index", array(
				"nb_tache" => $this->session_get("nb_tache", null),
				"tache_table" => $_SESSION
				));
	}
    
	public function save($route)
	{
		$task=$route;
		if ($task!=""){
			$nb_tache=$this->session_get("nb_tache", null);
			if ($nb_tache==null){
				$nb_tache=0;
			}
			if ($this->session_get(hash("sha256", $task), null)==null){
				$this->render_view("save", array(
						"state" => "OK",
						"tache" => $task
						));
				$this->session_set(hash("sha256", $task),$task);
				$nb_tache++;
				$this->session_set("nb_tache", $nb_tache);
			}else{
				$this->render_view("save", array(
						"state" => "EXISTE",
						"tache" => $task
						));
			}
		}else{
			$this->render_view("save", array(
					"state" => "VIDE",
					"tache" => null
					));
		}
	}
	
	public function done($route)
	{
		$hash=hash("sha256", $route);
		$this->render_view("done", array(
				"tache" => $this->session_get($hash, null)
				));
		$this->session_unset_var($hash);
		$nb_tache=$this->session_get("nb_tache", null);
		if (($nb_tache!=null)&&($nb_tache!=1)){
			$nb_tache--;
		}else if($nb_tache==1){
			$nb_tache=null;
		}
		$this->session_set("nb_tache", $nb_tache);
	}
	
	public function reset($route)
	{
		session_unset();
		$this->render_view("reset",null);
	}
}

?>
