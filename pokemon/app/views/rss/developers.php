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
				<li class="divider-vertical"></li>
				<li class=""><a href="/pokemon/rss/report"><i class="icon-book icon-white"></i> Compte-rendu</a></li>
				<li class="active"><a href="/pokemon/rss/developers"><i class="icon-book icon-white"></i> Développeurs</a></li>
				</ul>
			</div>

			<ul class="nav pull-right">
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
			  <h1>Développeur ?</h1>
			  <p class="lead">Voici notre API que vous pouvez utiliser.</p>
			</header>

			<h1>Description</h1>
			<p>
				Blabla.
			</p>
		</div>
	</div>
</div>

<?php render_partial("footer", null); ?>