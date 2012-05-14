<?php

require_once "lib/controller.php";

class SampleController extends BaseController
{
    public function index($route)
    {
        $this->render_view("index", array(
            "current_dir" => getcwd(),
            "name" => "Liste des éléments de la Todo-List"
        ));
    }
}

?>
