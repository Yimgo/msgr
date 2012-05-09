<?php

require_once "lib/controller.php";

class SampleController extends BaseController
{
    public function index($route)
    {
	// TRAITEMEMENTS
	// ....

	// Appel affichage
        $this->render_view("index", array(
            "current_dir" => getcwd(),
            "name" => "John John"
        ));

	/*
	$http_status = 404;
	$message = "Franklin n'a pas de pyjama";
	$context_map = array(
	    "jour" => "vendredi",
            "bar" => "foo",
            "barbaba" => "foo",
	);
	$this->render_error($http_status, $message, $context_map);
	*/

    }
}

?>
