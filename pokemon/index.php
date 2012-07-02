<?php

require_once "lib/routing.php";

$GLOBALS['POKEMON_ROOT'] = dirname($_SERVER['SCRIPT_NAME']);

$route = route_for_request_path($_GET["REQUEST_PATH"]);
route_to($route);

?>