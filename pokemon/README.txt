--------------------------------------------------------------------------------
  TP WEB

  Partie 2 :
  Développement d'un framework Web, page dynamique côté serveur : POKEMON en PHP

  Partie 3 : 
  Ajout aux framework des pages dynamique côté client : Javascript

--------------------------------------------------------------------------------
  J. Ponge, F. Le Mouël - INSA Lyon, Département TC - 2010
--------------------------------------------------------------------------------

POKEMON : PHP On a Killer Environment Mainly Oversimple for Noob
(un framework simple pour jeune et gentil étudiant en TC)

Structure du squelette :
.
|-- README.txt			: ce fichier
|-- app				: les applications
|   |-- controllers		: décomposées en contrôleurs
|   |   `-- sample
|   |       `-- sample.php
|   `-- views			: et vues
|       |-- partials
|       |   `-- plop.php
|       `-- sample
|           `-- index.php
|-- conf			: fichier de configuration du routage d'URI
|   `-- routes.php
|-- index.php			: point d'entré
|-- lib				: les fichiers communs (routage et contôleur
|   |-- controller.php		: de base)
|   `-- routing.php
`-- static			: ressources statiques
    |-- css
    |   `-- style.css
    |-- html
    |   |-- 404.php
    |   `-- error.php
    |-- images
    `-- javascript

