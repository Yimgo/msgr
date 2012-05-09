<?php

require_once "lib/routing.php";

$route = route_for_request_path($_GET["request_path"]);
route_to($route);

?>
