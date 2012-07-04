<?php

$ERROR_CONTROLLER = 'error';
$DEFAULT_ACTION = 'index';

$DEFAULT_ROUTE = array(
	'controller' => $ERROR_CONTROLLER, 'action' => $DEFAULT_ACTION, 'id' => array(
		'cause' => 'NotFound'
		)
	);

$ROUTES = array(
    'default' => $DEFAULT_ROUTE,
    'keys' => array('controller', 'action', 'id'),
    'current' => array()
	);

$ERROR_MESSAGES = array(
	'NotFound' => 'Sorry, the requested page doesn\'t exists.',
	'default' => 'Errors happens, that\'s called Life.'
	);

?>
