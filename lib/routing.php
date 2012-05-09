<?php

require_once "conf/routes.php";

function route_for_request_path($path)
{
	return preg_split('/\//', $path);
}

function route_to($route)
{
   require_once "app/controllers/" . $route[0] . "/" . $route[0] . ".php";
   $classname = ucfirst($route[0])."Controller";
   $t = new $classname();
   $t->$route[1]($route[2]);
}

?>
