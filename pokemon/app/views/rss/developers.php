<?php
	render_partial('header', null);
	render_partial('menu', array('active' => 'developers'));
?>

<div class="container">
	<div class="row">

		<div class="span8 offset2">
			<header class="jumbotron subhead" id="overview">
			  <h1>Développeur ?</h1>
			  <p class="lead">
					Monsignor aka <acronym title="Monsignor is not a Simple Google Readers">msgr</acronym> est un aggrégateur de flux RSS.
				</p>
				<p>
					Une API flexible et puissante basée sur JSON est proposée pour le développement d'applications natives ou l'automatisation de tâches.<br />
					N'hésitez pas également à contribuer, par la remontée de bugs ou d'améliorations, ou par la contribution directe au code source, en vous rendant sur la page du projet hébergée par <a href="http://code.google.com/p/msgr">Google Code</a>.<br />
			  	Une liste exhaustive des ordres gérés se situe ci-dessous.
			  </p>
			</header>

			<h2><acronym title="Application Programming Interface">API</acronym></h2>

			<h3>Inscription</h3>
			<p>
				<dl class="dl-horizontal">
					<dt>Ordre</dt>
					<dd>signup</dd>
					<dt>Description<dt>
					<dd>Inscription d'un nouvel utilisateur</dd>
					<dt>Paramètres POST</dt>
					<dd><em>user_login</em></dd>
					<dd><em>user_password</em></dd>
					<dd><em>user_email</em></dd>
				</dl>
			</p>
			<h3>Connexion</h3>
			<p>
				<dl class="dl-horizontal">
					<dt>Ordre</dt>
					<dd>login</dd>
					<dt>Description<dt>
					<dd>Connexion d'un utilisateur</dd>
					<dt>Paramètres POST</dt>
					<dd><em>user_login</em></dd>
					<dd><em>user_password</em></dd>
				</dl>
			</p>
			<h3>Déconnexion</h3>
			<p>
				<dl class="dl-horizontal">
					<dt>Ordre</dt>
					<dd>logout</dd>
					<dt>Description<dt>
					<dd>Déconnexion utilisateur</dd>
					<dt>Paramètres</dt>
					<dd>Aucun</dd>
				</dl>
			</p>
			<h3>Déplacement d'un flux vers un dossier</h3>
			<p>
				<dl class="dl-horizontal">
					<dt>Ordre</dt>
					<dd>move_flux_folder</dd>
					<dt>Description<dt>
					<dd>Déplacement d'un flux vers un dossier</dd>
					<dt>Paramètres POST</dt>
					<dd><em>flux_id</em></dd>
					<dd><em>dossier_id</em></dd>
				</dl>
			</p>
			<h3>Recherche</h3>
			<p>
				<dl class="dl-horizontal">
					<dt>Ordre</dt>
					<dd>search</dd>
					<dt>Description<dt>
					<dd>Permet d'effectuer une recherche dans les articles par selection de tags et/ou par entrée utilisateur</dd>
					<dt>Paramètres GET</dt>
					<dd><em>0</em>: Recherche texte</dd>
					<dd><em>1</em>: Liste des tags, séparés par des virgules (optionnel)</dd>
					<dt>Paramètres Route</dt>
					<dd><em>0</em>: Offset (optionnel)</dd>
					<dd><em>1</em>: Nombre d'articles à récupérer (optionnel)</dd>
				</dl>
			</p>
			<h3>Tags</h3>
			<p>
				<dl class="dl-horizontal">
					<dt>Ordre</dt>
					<dd>signup</dd>
					<dt>Description<dt>
					<dd>Obtention de la liste des tags de l'utilisateur</dd>
					<dt>Paramètres </dt>
					<dd>Aucun</dd>
				</dl>
			</p>
			<h3>Flux par dossier</h3>
			<p>
				<dl class="dl-horizontal">
					<dt>Ordre</dt>
					<dd>get_flux_dossiers()</dd>
					<dt>Description<dt>
					<dd>Récupération de la liste des flux associés avec leurs dossiers</dd>
					<dt>Paramètres</dt>
					<dd>Aucun</dd>
				</dl>
			</p>
			<h3>Articles pour un flux</h3>
			<p>
				<dl class="dl-horizontal">
					<dt>Ordre</dt>
					<dd>get_articles</dd>
					<dt>Description<dt>
					<dd>Récupération de la liste des articles pour un flux spécifié, avec pagination</dd>
					<dt>Paramètres Route</dt>
					<dd><em>0</em>: feed id</dd>
					<dd><em>1</em>: offset (optionnel)</dd>
					<dd><em>2</em>: nombre d'articles à afficher (optionnel)</dd>
				</dl>
			</p>
			<h3>Derniers articles non lus</h3>
			<p>
				<dl class="dl-horizontal">
					<dt>Ordre</dt>
					<dd>get_latest_articles</dd>
					<dt>Description<dt>
					<dd>Récupération de la liste des derniers articles non lus</dd>
					<dt>Paramètres Route</dt>
					<dd><em>0</em>: offset (optionnel)</dd>
          <dd><em>1</em>: nombre d'articles à afficher (optionnel)</dd>
				</dl>
			</p>
			<h3>Tag article</h3>
			<p>
				<dl class="dl-horizontal">
					<dt>Ordre</dt>
					<dd>set_tag</dd>
					<dt>Description<dt>
					<dd>Tag d'un article</dd>
					<dt>Paramètres GET</dt>
					<dd><em>tag_id</em></dd>
					<dd><em>article_id</em></dd>
					<dd><em>tag</em>: [true/false =&gt; tag/untag]</dd>
				</dl>
			</p>
			<h3>Star article</h3>
			<p>
				<dl class="dl-horizontal">
					<dt>Ordre</dt>
					<dd>set_favori</dd>
					<dt>Description<dt>
					<dd>Marquer l'article comme favori</dd>
					<dt>Paramètres POST</dt>
					<dd><em>article_id</em></dd>
          <dd><em>favori</em>: [true/false =&gt; star/unstar]<dd>
				</dl>
			</p>
			<h3>Lire article</h3>
			<p>
				<dl class="dl-horizontal">
					<dt>Ordre</dt>
					<dd>set_lu</dd>
					<dt>Description<dt>
					<dd>Marquer l'article comme lu</dd>
					<dt>Paramètres POST</dt>
					<dd><em>article_id</em></dd>
          <dd><em>lu</em>: [true/false =&gt; lu/nonlu]</dd>
				</dl>
			</p>
			<h3>Articles favoris</h3>
			<p>
				<dl class="dl-horizontal">
					<dt>Ordre</dt>
					<dd>get_favorite_articles</dd>
					<dt>Description<dt>
					<dd>Récupération de la liste des articles favoris</dd>
					<dt>Paramètres Route</dt>
					<dd><em>0</em>: offset (optionnel)</dd>
          <dd><em>1</em>: nombre d'articles à afficher (optionnel)</dd>
				</dl>
			</p>
			<h3>Ajout commentaire</h3>
			<p>
				<dl class="dl-horizontal">
					<dt>Ordre</dt>
					<dd>add_commentaire</dd>
					<dt>Description<dt>
					<dd>Ajout d'un commentaire à un article</dd>
					<dt>Paramètres POST</dt>
					<dd><em>article_id</em></dd>
          <dd><em>commentaire</em>: message du commentaire</dd>
				</dl>
			</p>
			<h3>Commentaires</h3>
			<p>
				<dl class="dl-horizontal">
					<dt>Ordre</dt>
					<dd>get_commentaires</dd>
					<dt>Description<dt>
					<dd>Récupération de la liste des commentaires d'un article</dd>
					<dt>Paramètre GET</dt>
					<dd><em>0</em>: article_id</dd>
				</dl>
			</p>
			<h3>Ajout flux</h3>
			<p>
				<dl class="dl-horizontal">
					<dt>Ordre</dt>
					<dd>parse_single_feed</dd>
					<dt>Description<dt>
					<dd>Ajout d'un flux</dd>
					<dt>Paramètre POST</dt>
					<dd><em>url</em></dd>
				</dl>
			</p>
			<h3>Ajout dossier</h3>
			<p>
				<dl class="dl-horizontal">
					<dt>Ordre</dt>
					<dd>add_folder</dd>
					<dt>Description<dt>
					<dd>Création d'un nouveau dossier</dd>
					<dt>Paramètre POST</dt>
					<dd><em>titre</em></dd>
				</dl>
			</p>
			<h3>Suppression dossier</h3>
			<p>
				<dl class="dl-horizontal">
					<dt>Ordre</dt>
					<dd>delete_folder</dd>
					<dt>Description<dt>
					<dd>Suppression d'un dossier</dd>
					<dt>Paramètre POST</dt>
					<dd><em>id</em></dd>
				</dl>
			</p>
			<h3>Renommage dossier</h3>
			<p>
				<dl class="dl-horizontal">
					<dt>Ordre</dt>
					<dd>rename_folder</dd>
					<dt>Description<dt>
					<dd>Renommage d'un dossier</dd>
					<dt>Paramètre POST</dt>
					<dd><em>id</em>: id du dossier à renommer</dd>
					<dd><em>titre</em>: nouveau nom du dossier</dd>
				</dl>
			</p>
			<h3>Ajout tag</h3>
			<p>
				<dl class="dl-horizontal">
					<dt>Ordre</dt>
					<dd>add_tag</dd>
					<dt>Description<dt>
					<dd>Création d'un nouveau tag</dd>
					<dt>Paramètre POST</dt>
					<dd><em>titre</em></dd>
				</dl>
			</p>
			<h3>Suppression tag</h3>
			<p>
				<dl class="dl-horizontal">
					<dt>Ordre</dt>
					<dd>delete_tag</dd>
					<dt>Description<dt>
					<dd>Suppression d'un tag</dd>
					<dt>Paramètre POST</dt>
					<dd><em>id</em></dd>
				</dl>
			</p>
			<h3>Renommage tag</h3>
			<p>
				<dl class="dl-horizontal">
					<dt>Ordre</dt>
					<dd>rename_tag</dd>
					<dt>Description<dt>
					<dd>Renommage d'un tag</dd>
					<dt>Paramètre POST</dt>
					<dd><em>id</em>: id du tag à renommer</dd>
					<dd><em>titre</em>: nouveau nom du tag</dd>
				</dl>
			</p>
			<h3>MAJ flux</h3>
			<p>
				<dl class="dl-horizontal">
					<dt>Ordre</dt>
					<dd>update_flux</dd>
					<dt>Description<dt>
					<dd>Mise à jour de tous les flux afin d'insérer les nouveau articles en base</dd>
					<dt>Paramètre</dt>
					<dd>Aucun</dd>
				</dl>
			</p>
			<h3>Suppression abonnement</h3>
			<p>
				<dl class="dl-horizontal">
					<dt>Ordre</dt>
					<dd>delete_abonnement</dd>
					<dt>Description<dt>
					<dd>Suppression d'un abonnement</dd>
					<dt>Paramètre POST</dt>
					<dd><em>flux_id</em></dd>
				</dl>
			</p>

			<h3>Export OPML</h3>
			<p>
				<dl class="dl-horizontal">
					<dt>Ordre</dt>
					<dd>opml</dd>
					<dt>Description<dt>
					<dd>Export au format OPML des abonnements de l'utilisateur courant. L'organisation en dossiers est conservée.</dd>
					<dt>Paramètre</dt>
					<dd>Aucun</dd>
				</dl>
			</p>
		</div>
	</div>
</div>

<?php render_partial("includes_js", null); ?>
<?php render_partial("footer", null); ?>
