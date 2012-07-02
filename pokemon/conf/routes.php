<?php

$DEFAULT_ROUTE = array(
	'controller' => 'error', 'action' => 'index', 'id' => array(
		'cause' => 'NotFound'
		)
	);

$ROUTES = array(
    'default' => $DEFAULT_ROUTE,
    'keys' => array('controller', 'action', 'id'),
    'current' => array()
	);

?>
