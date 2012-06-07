<?php 
	render_partial('header', null); 
	render_partial('menu', array('active' => 'tags'));
?>

<?php
$tags=$params["Tags"];

if ($params["State"]==="ok"){
	$div_error_suppress="hide";
} else {
	$div_error_suppress="";
}
?>

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
