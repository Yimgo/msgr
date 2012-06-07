<?php render_partial("header", null); ?>

<?php
$tags=$params["Tags"];

if ($params["State"]==="ok"){
	$div_error_suppress="hide";
} else {
	$div_error_suppress="";
}
?>

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
				<li class="active"><a href="/pokemon/rss/tags"><i class="icon-tags icon-white"></i> Gérer les tags</a></li>
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
	<!-- Ajouter Abonnement + Recherche -->
	<div class="row">

		<div class="offset1 span10">

			<div class="alert alert-error <?php echo $div_error_suppress; ?>">
				<strong>Erreur</strong> lors de la suppression : le tag est utilisé pour tagger des articles !
			</div>

			<table class="table table-bordered table-striped span6">
				<thead>
					<tr>
						<th class="span5" style="text-align: center;">Nom du tag</th>
						<th class="span5" style="text-align: center;">Actions associées</th>
					</tr>
				</thead>

				<tbody>
					<!-- Liste des dossiers existants -->
					<?php foreach ($tags as $tag) { ?>
					<tr>
						<td>
							<span><?php echo $tag["nom"] ?></span>
							<form method="POST" action="rename_tag" class="form-inline" style="display:none" id="form-<?php echo $tag['id'] ; ?>">
								<input type="hidden" name="id" value="<?php echo $tag['id'] ; ?>" />
								<input type="text" id="nom" name="nom" value="<?php echo $tag['nom']; ?>"/>
								<button type="submit" class="btn"><i class="icon-check"></i> OK</button>
							</form>
						</td>
						<td>
							<form method="POST" action="delete_tag" class="form-inline">
	                            <a class="btn renommer" href="#" id="renommer-<?php echo $tag["id"];?>"><i class="icon-pencil"></i> Renommer</a>
	                            <input type="hidden" name="id" value="<?php echo $tag["id"] ; ?>" />
	                            <button type="submit" class="btn"><i class="icon-minus-sign"></i> Supprimer</button>
                        	</form>
						</td>
					</tr>
					<?php } ?>

					<!-- Ajout d'un dossier -->
					<tr>
						<form method="POST" action="add_tag" class="form-inline">
							<td>
								<input type="text" placeholder="Nom du nouveau tag" id="nom" name="nom" />
							</td>
							<td>
								<button type="submit" class="btn"><i class="icon-plus-sign"></i> Ajouter un tag</button>
							</td>
						</form>
					</tr>
				</tbody>
			</table>
		</div>

	</div>
</div>

<?php render_partial("includes_js", null); ?>
<script src="/pokemon/static/javascript/tags.js"></script>
<?php render_partial("footer", null); ?>