<?php

require_once 'lib/controller.php';

class ErrorController extends BaseController {

	public function index($route) {
		self::render_error(404, 'Sorry, the requested page doesn\'t exists.', $route);
	}
}

?>
