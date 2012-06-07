<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a class="brand">RSS</a>
			<div class="nav-collapse">
				<ul class="nav">
					<li<?php if ($params['active'] == 'listing') echo ' class="active"'; ?>><a href="/pokemon/rss/listing"><i class="icon-home icon-white"></i> Accueil</a></li>
					<li<?php if ($params['active'] == 'folders') echo ' class="active"'; ?>><a href="/pokemon/rss/folders"><i class="icon-folder-open icon-white"></i> Gérer les dossiers</a></li>
					<li<?php if ($params['active'] == 'tags') echo ' class="active"'; ?>><a href="/pokemon/rss/tags"><i class="icon-tags icon-white"></i> Gérer les tags</a></li>
					</ul>
			</div>

			<ul class="nav pull-right">
		    <li<?php if ($params['active'] == 'report') echo ' class="active"'; ?>><a href="/pokemon/rss/report"><i class="icon-pencil icon-white"></i> Compte-rendu</a></li>
		      <li<?php if ($params['active'] == 'developers') echo ' class="active"'; ?>><a href="/pokemon/rss/developers"><i class="icon-road icon-white"></i> Développeurs</a></li>
				<li class="divider-vertical"></li>
				<li><a href="/pokemon/rss/logout"><i class="icon-user icon-white"></i> Déconnexion</a></li>
			</ul>
		</div>
	</div>
</div>
