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
				Ce site propose un service d'aggrégation de flux RSS, permettant aux utilisateurs de commenter les nouvelles.
			</p>
			<p>
				Chaque utilisateur possède son propre compte sur le site et gère ses abonnements indépendamment des autres utilisateurs.
				Par défaut, une vue des dix derniers articles non lus est affichée, mais l'utilisateur peut classer ses flux dans des dossiers, qu'il gère comme il l'entend.
				Chaque article a un état <em>lu ou non lu</em> et la possibilité d'être marqué comme <em>favori</em>. De plus, chaque article peut être marqué d'un certain nombre de <em>tags</em>. Ces tags sont spécifiques à chaque utilisateur, ce qui lui permet d'effectuer son propre classement. L'utilisateur peut également effectuer une recherche en texte plein ou en spécifiant une liste de <em>tags</em> associés aux articles désirés.
			</p>

			<h1>Architecture</h1>
			<p>
				Nous avons choisi de nous baser sur le framework Pokemon que nous avons étudié en TD/TP, pour sa simplicité, sa modularité et son extensibilité.<br />
				Ce choix nous a permis de découper efficacement les traitements de l'application en isolant la manipulation des données, l'interface utilisateur et la communication des ces deux élements, selon les préceptes du patron de conception <acronym title="Modèle Vue Contrôleur">MVC</acronym>.<br />
				Cette architecture nous offre la possibilité de proposer des vues différentes selon l'utilisateur en considérant le même jeu de données, collant ainsi au plus près de ses besoins.<br />
				Aussi, une <a href="developers">API</a> claire et complète est disponible dans le but de permettre à tous de communiquer avec l'application comme bon lui semble; cela permet surtout la création d'applications multi-plateformes. Cette API REST repose sur les méthodes GET et POST pour inter-agir avec les jeux de données qui sont retournés dans le format JSON, compréhensible par une multitude de langages de programmation.<br />
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
				De plus, nous utilisons des déclencheurs - ou triggers - afin d'automatiser des insertions ou suppressions dans la base de données. Cela nous permet de clarifier le code PHP tout en nous permettant de respecter automatiquement les contraintes d'intégrité.
			</p>


			<h1>Gestion de projet</h1>
			<p>
				Nous avons dès le début du projet décidé d'utiliser des outils de travail collaboratif afin de pouvoir travailler le plus efficacement possible sur notre projet. Pour cela, nous avons créé un projet <strong>Google Code</strong> qui nous permet d'avoir plusieurs outils de gestion de projet informatique et de travail collaboratif : une partie wiki pour mettre de la documentation, un serveur svn pour héberger le code du projet, et une partie bugtracker permettant de déclarer des bugs et de les assigner à une personne du groupe en fonction de la nature du bug. En effet, chacun des membres de notre équipe s'est spécialisé dans un aspect ou une fonctionnalité de notre site web. Ainsi, la répartition du travail s'est fait comme suit :
				<ul>
					<li>Guillaume Burel : développement de la partie connection à la base de données (utilisation de PDO et développement d'un Wrapper SQL permettant d'éxécuter des requêtes SQL) ainsi que la création de la base SQL.</li>
					<li>Paul Mougel : développement de la partie interface graphique du site web (vues en PHP, HTML et Javascript).</li>
					<li>Idris Daikh : développement et implémentation d'un parser de flux RSS. L'implémentation s'étant faite à l'aide d'une bibliothèque qu'il a fallu débuguer et integrer au code de notre site Web.</li>
					<li>Antoine Allard et Ouassim Karrakchou : réalisation des fonctions du contrôleur et celles du Wrapper exécutant les requêtes SQL, ainsi que tests et déclarations de bugs.</li>
				</ul>
				La spécialisation de chacun des membres du projet dans un aspect du développement nous a permis de rapidement commencer le développement car chacun était conscient de la tâche qu'il avait à accomplir.
			</p>
			<p>
				Les heures de Projet WEB à l'emploi du temps nous ont servi à faire des réunions où l'on discutait de l'avancement du projet, vérifiait les tâches qu'il restait à accomplir et décidait de leur priorité.
			</p>
			<p>
				Cela nous a permis d'avoir à la fin des 20 heures de projet un site Web mature et fonctionnel.
			</p>
			<p>
				La répartition temporelle des tâches s'est faite comme suit :
				<ul>
					<li>Séance 1 : choix du framework, et définition d'une méthode d'installation facile du site Web dans un serveur Web. Définition de la structure de la base de données et des différentes tables. Développement d'un système de connexion.</li>
					<li>Séance 2 : développement de la charte graphique et de l'interface générale du site Web, début du développement du Wrapper SQL, début développement du controlleur et de la vue Listing.</li>
					<li>Séance 3 : intégration du parser RSS, développement d'un système de tags.</li>
					<li>Séance 4 : développement d'un système de dossiers.</li>
					<li>Séance 5 : tests et corrections</li>
				</ul>
			</p>
		</div>
	</div>
</div>

<?php render_partial("includes_js", null); ?>
<?php render_partial("footer", null); ?>
