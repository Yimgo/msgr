<?php

require_once "lib/routing.php";

$route = route_for_request_path($_GET["request_path"]);
if ($route=="error"){
	require_once "static/html/404.php";
}else{
	route_to($route);
}

?>
