<?php

require_once "lib/controller.php";
class TodolistController extends BaseController {
	
	public function index($route) {
		$liste = $this->session_get("todolist", null);
		$this->render_view("index", $liste);
	}

	public function add($route) {
		// 1. ajout a la session
		$liste = $this->session_get("todolist", null);
		
		if ($liste == null) $liste = array();

		array_push($liste, $route);

		$this->session_set("todolist", $liste);

		// render
		$this->render_view("index", $liste);
	}

	public function delete($route) {
		$liste = $this->session_get("todolist", null);
		if($liste != null){
			$newliste = array();
			for($i=0; $i<count($liste); $i++){
				if(strcmp($liste[$i],$route) != 0){
					array_push($newliste, $liste[$i]);
				}
			}
			$this->session_set("todolist", $newliste);
		}
		$this->render_view("index", $this->session_get("todolist", null));	
	}
	
	public function deleteall($route) {
		$liste = $this->session_get("todolist", null);
		if($liste != null){
			$newliste = array();
			for($i=0; $i<count($liste); $i++){
				if(strpos($liste[$i],$route) == FALSE){
					array_push($newliste, $liste[$i]);
				}
			}
			$this->session_set("todolist", $newliste);
		}
		$this->render_view("index", $this->session_get("todolist", null));	
	}
	

	public function reset($route) {
		$this->session_set("todolist", null);

		$this->render_view("index", $this->session_get("todolist", null));
	}
}
?>
