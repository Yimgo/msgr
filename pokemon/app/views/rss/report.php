<?php 
	render_partial('header', null); 
	render_partial('menu', array('active' => 'report'));
?>

<div class="container">
	<div class="row">

		<div class="span8 offset2">
			<header class="jumbotron subhead" id="overview">
			  <h1>Présentation du projet</h1>
			  <p class="lead">Antoine ALLARD, Guillaume BUREL, Idris DAIKH, Ouassim KARRAKCHOU, Paul MOUGEL.</p>
			</header>

			<h1>Description</h1>
			<p>
				<em>Yet another RSS aggregator. Better than others.</em><br/>
				Monsignor, pl. monsignori, is the form of address for those members of the clergy of the Catholic Church holding certain ecclesiastical honorific titles.
			</p>
			<p>
				Ce site est un aggrégateur de flux RSS, permettant aux utilisateurs de commenter les nouvelles.
			</p>
			<p>
				Chaque utilisateur possède son propre compte sur le site et gère ses abonnements indépendamment des autres utilisateurs.
				Par défaut, une vue des dix derniers articles non lus est affichée, mais l'utilisateur peut classer ses flux dans des dossiers, qu'il gère comme il l'entend.
				Chaque article a un état <em>lu ou non lu</em> et la possibilité d'être marqué comme <em>favori</em>. De plus, chaque article peut être marqué d'un certain nombre de <em>tags</em>. Ces tags sont spécifiques à chaque utilisateur, ce qui lui permet d'effectuer son propre classement.
			</p>

			<h1>Architecture</h1>
			<p>
				Nous avons choisi de nous baser sur le framework Pokemon que nous avons étudié en TD/TP, pour sa simplicité, sa modularité et son extensibilité.<br />
				Ce choix nous a permis de découper efficacement les traitements de l'application en isolant la manipulation des données, l'interface utilisateur et la communication des ces deux élements, selon les préceptes du patron de conception <acronym title="Modèle Vue Contrôleur">MVC</acronym>.<br />
				Cette architecture nous offre la possibilité de proposer des vues différentes selon l'utilisateur en considérant le même jeu de données, collant ainsi au plus près de ses besoins.<br /> 
				Aussi, une <a href="developers">API</a> claire et complète est disponible dans le but de permettre à tous de communiquer avec l'application comme bon lui semble; cela permet surtout la création d'applications multi-plateformes.<br />
				Enfin, une attention particulière a été portée sur le modèle de l'application, c'est à dire la manipulation des données en base. Une architecture modulaire d'accès à ces dernières a donc été mise en place.
			</p>

			<h1>Modèle</h1>

			<p>
				Le schéma utilisé par notre application est le suivant (<em>en gras, la clé primaire de chaque table</em>) :
				<img src="/pokemon/static/images/bdd.png" />
			</p>

			<p>
				Nous utilisons la couche d'abstraction aux bases de données PDO, qui nous fournit une bibliothèque objet indépendante de la base de données sous-jacente : nous utilisons actuellement MySQL mais pourrions utiliser PostgreSQL sans effectuer de changements majeurs dans le code, nous offrant une flexibilité certaine.<br />
				Aussi, la manipulation des données est faite à travers d'appels SQL standards ce qui permet l'utilisation de tout SGBD répondant aux spécifications ACID.<br />
				Cependant, le contrôleur fait appel à un wrapper lui permettant d'interagir avec l'objet PDO, qui est obtenu en utilisant une fabrique assurant l'unicité de l'objet et la sélection de la base de données.<br />
				En effet, les bases de données disponibles sont spécifiées à l'aide de profils - consistant en de simples fichiers *.ini - dans lesquels sont indiqués le SGBD et les informations de connexion à la base, le profil par défaut étant défini dans un fichier de configuration.
			</p>

			<p>
				Dans la logique du MVC, tous nos accès la base de données sont effectués dans la couche Modèle de l'application : elle fournit une liste de fonctions aux types de retour bien définis et qui permettent d'effectuer des actions sur la base de données de manière transparente pour l'application.
			</p>
			<p>
				La structure de la base de données nous a tout particulièrement occupés, car nous nous appuyons au maximum sur ses fonctionnalités afin de décharger le code métier de certaines lourdeurs.
				Certaines opérations sont ainsi effectuées sans subir de vérification au préalable par le code PHP, les contraintes étant gérées par la base de données plus efficacement.
				De plus, nous utilisons des décelencheurs - ou triggers - afin d'automatiser des insertions ou suppressions dans la base de données. Cela nous permet de clarifier le code PHP tout en nous permettant de respecter automatiquement les contraintes d'intégrité.
			</p>

			
			<h1>Gestion de projet</h1>
			<p>
				Nous avons utilisé SVN pour synchroniser notre code et travailler à plusieurs.
			</p>
			<p>
				De plus, nous avons beaucoup utilisé le système de gestion de tickets disponible sur Google Code. Nous y avons noté les différents points à implémenter, les bugs rencontrés et les idées d'améliorations.
			</p>
		</div>
	</div>
</div>

<?php render_partial("includes_js", null); ?>
<?php render_partial("footer", null); ?>
