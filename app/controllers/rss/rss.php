<?php

require_once "lib/controller.php";

class RssController extends BaseController {
    public function index($route) {
        $this->render_view("index", array());
    }

    public function search($route) {
        $search = $_GET["search"];
        $tags_id = explode(',', $_GET["tags_id"]);
    }

    public function get_tags() {
        // Renvoie les tags pour un utilisateur (TODO: login)
        $tags = array(
            array("titre" => "Intéressant", "id" => 0),
            array("titre" => "Nul", "id" => 1),
            array("titre" => "Linux", "id" => 2),
            array("titre" => "C/C++", "id" => 3),
            array("titre" => "A relire", "id" => 4),
            array("titre" => "Science", "id" => 5),
        );
        
        echo json_encode($tags);
    }

    public function get_flux_dossiers() {
        // Renvoie tous les flux et l'organisation en dossier (TODO: login)
        $flux = array(
            array(
                "titre" => "Non classé", // DOSSIER qui contient tous les flux… sans dossier ;-)
                "id" => -1,
                "liste_flux" => array(
                    array(
                        "titre" => "Le site le plus bête du monde",
                        "nb_nonlus" => 987,
                        "id" => 12
                    )
                )
            ),
            array(
                "titre" => "Informations Françaises",
                "id" => 1,
                "liste_flux" => array(
                    array(
                        "titre" => "Le Monde",
                        "nb_nonlus" => 14,
                        "id" => 0
                    ),
                    array(
                        "titre" => "Le Figaro",
                        "nb_nonlus" => 2,
                        "id" => 1
                    ),
                    array(
                        "titre" => "Le Progrès",
                        "nb_nonlus" => 0,
                        "id" => 2
                    ),
                    array(
                        "titre" => "Le Canard Enchainé",
                        "nb_nonlus" => 130,
                        "id" => 3
                    )
                )
            ),
            array(
                "titre" => "Informatique",
                "id" => 2,
                "liste_flux" => array(
                    array(
                        "titre" => "PCInpact",
                        "nb_nonlus" => 2,
                        "id" => 4
                    ),
                    array(
                        "titre" => "LinuxFR",
                        "nb_nonlus" => 20,
                        "id" => 5
                    )
                )
            )
        );
        // pour tester le rendu en cas d'erreur cote client
        if (rand(0, 10) == 0) echo "erreur json; df ;d;f d;";
        else echo json_encode($flux);
    }

    public function get_articles($id_flux) {
        // Renvoie tous les articles pour un flux donné (TODO: login)
        $lorem = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum";

        $articles = array(
            array("titre" => "Test 1",
                  "id" => 0,
                  "contenu" => $lorem),
            array("titre" => "Test numero bis",
                  "id" => 1,
                  "contenu" => $lorem),
            array("titre" => "Un troisieme article",
                  "id" => 2,
                  "contenu" => $lorem),
            array("titre" => "Last one",
                  "id" => 10,
                  "contenu" => $lorem . $lorem . $lorem . $lorem)
              );

        // pour tester le rendu en cas d'erreur cote client
        if (rand(0, 10) == 0) echo "erreur json; df ;d;f d;";
        else echo json_encode($articles);
    }
}

?>
