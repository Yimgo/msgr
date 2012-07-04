<?php

require_once "conf/routes.php";

function route_for_request_path($path)
{
	global $ROUTES;
	global $DEFAULT_ACTION;

	$array = explode('/', $path, 3);
	if ($array === FALSE || empty($array) || !array_key_exists(0, $array) || empty($array[0]))
		return $ROUTES['default'];

	$ROUTES['current']['controller'] = $array[0];

	if (!array_key_exists (1, $array) || empty($array[1]))
		$ROUTES['current']['action'] = $DEFAULT_ACTION;
	else
		$ROUTES['current']['action'] = $array[1];

	if (array_key_exists(2,$array) && !empty($array[2]))
		$ROUTES['current']['id'] = explode('/', $array[2]);
	else
		$ROUTES['current']['id'] = array();

	
	return $ROUTES['current'];
}

function route_to($route)
{
	global $ROUTES;
  
	if (!file_exists('app/controllers/' . $route['controller'] . '/' . $route['controller'] . '.php'))
	{
		$route = $ROUTES['default'];
	}

	require_once('app/controllers/'. $route['controller'] . '/' . $route['controller'] . '.php');

	/* Determining class name. eg. sample => SampleController */
	$className = ucfirst($route['controller']) . 'Controller';

	$controller = new $className();

	if (method_exists($controller, $route['action'])) {
		$post_params = (isset($_POST) && !empty($_POST)) ? $_POST : array();

		$controller->$route['action']($route['id'], $post_params);
	}
}

?>