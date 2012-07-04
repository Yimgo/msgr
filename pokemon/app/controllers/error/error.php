<?php

require_once 'lib/controller.php';

class ErrorController extends BaseController {

	public function index($params) {
		global $ERROR_MESSAGES;
		global $ROUTES;

		$message = isset($ERROR_MESSAGES[$params['cause']]) ? $ERROR_MESSAGES[$params['cause']] : $ERROR_MESSAGES['default'];
		
		$trying = $ROUTES['current']['controller'] . '/' . $ROUTES['current']['action'];
		if (!empty($ROUTES['current']['id']))
			$trying .= '/' . implode('/', $ROUTES['current']['id']);

		self::render_error(404, $message, array('Trying' => $trying));
	}
}

?>
