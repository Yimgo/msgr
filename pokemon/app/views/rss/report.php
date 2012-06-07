<?php render_partial("header", null); ?>

<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<div class="brand">RSS</div>
			<div class="nav-collapse">
				<ul class="nav">
				<li><a href="/pokemon/rss/listing"><i class="icon-home icon-white"></i> Accueil</a></li>
				<li><a href="/pokemon/rss/folders"><i class="icon-folder-open icon-white"></i> Gérer les dossiers</a></li>
				<li class=""><a href="/pokemon/rss/tags"><i class="icon-tags icon-white"></i> Gérer les tags</a></li>
				</ul>
			</div>

			<ul class="nav pull-right">
			<li class="divider-vertical"></li>
            <li class=""><a href="/pokemon/rss/report"><i class="icon-book icon-white"></i> Compte-rendu</a></li>
			<li class=""><a href="/pokemon/rss/developers"><i class="icon-book icon-white"></i> Développeurs</a></li>
			<li class="divider-vertical"></li>
			<li><a href="/pokemon/rss/logout"><i class="icon-user icon-white"></i> Déconnexion</a></li>
			</ul>
		</div>
	</div>
</div>


<div class="container">
	<div class="row">

		<div class="span8 offset2">
			<header class="jumbotron subhead" id="overview">
			  <h1>Présentation du projet</h1>
			  <p class="lead">Antoine ALLARD, Guillaume BUREL, Idris DAIKH, Ouassim KARRAKCHOU, Paul MOUGEL.</p>
			</header>

			<h1>Description</h1>
			<p>
				Ce site est un aggrégateur de flux RSS, permettant aux utilisateurs de commenter les nouvelles.
			</p>
			<p>
				Chaque utilisateur possède son propre compte sur le site et gère ses abonnements indépendamment des autres utilisateurs.
				Par défaut, une vue des dix derniers articles non lus est affichée, mais l'utilisateur peut classer ses flux dans des dossiers, qu'il gère comme il l'entend.
				Chaque article a un état <em>lu ou non lu</em> et la possibilité d'être marqué comme <em>favori</em>. De plus, chaque article peut être marqué d'un certain nombre de <em>tags</em>. Ces tags sont spécifiques à chaque utilisateur, ce qui lui permet d'effectuer son propre classement.
			</p>

			<h1>Description technique</h1>

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
