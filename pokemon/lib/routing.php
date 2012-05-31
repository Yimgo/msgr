<?php

require_once "conf/routes.php";

function route_for_request_path($path)
{
	$array=explode("/",$path,3);
	if ((array_key_exists(0,$array)==false)||(array_key_exists(1,$array)==false)){
		return "error";
	}elseif (array_key_exists(2,$array)==false){
		return	array(
				"controller"=>$array[0],
				"action"=>$array[1]
			);
	}else{
		return	array(
				"controller"=>$array[0],
				"action"=>$array[1],
				"id"=>explode("/", $array[2])
			);
	}
}

function route_to($route)
{
	$controller_path="app/controllers/".$route["controller"]."/".$route["controller"].".php";
	if (file_exists($controller_path)){
		require_once $controller_path;
		$class_name=ucwords($route["controller"]."Controller");
		$controlleur=new $class_name;
		if (method_exists($controlleur,$route["action"])){
			if (isset($route["id"])){
				if(isset($_POST) && !empty($_POST)) {
				  $controlleur->$route["action"]($route["id"], $_POST);
				} else {
				  $controlleur->$route["action"]($route["id"], array());
				}
			}else{
				if(isset($_POST) && !empty($_POST)) {
					$controlleur->$route["action"](null, $_POST);
				} else {
					$controlleur->$route["action"](null, array());
				}
			}
		} else {
			require_once "static/html/404.php";
		}
	}else{
		require_once "static/html/404.php";
	}
}

?>
