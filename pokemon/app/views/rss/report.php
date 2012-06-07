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

			<h1>Description technique</h1>
			<p>
				Notre application est développée sur le framework Pokemon basé sur une architecture de modèle vue-contrôleur (MVC). <br />
				L'accès à la base de donnée est géré par PDO, toutes les demandes de connexion et les requêtes en base de l'application sont traitées par un wrapper. <br />
				Parseur php... <br />
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
