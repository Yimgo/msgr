<?php 
	render_partial('header', null); 
	render_partial('menu', array('active' => 'tags'));
?>

<?php
$tags=$params["Tags"];

if ($params["State"]==="ok"){
	$div_confirm_suppress="hide";
} else {
	$div_confirm_suppress="";
	$explode_state=explode('/', $params["State"], 2);
	$id_tag_to_suppress=$explode_state[1];
}
?>

<div class="container">
	<!-- Ajouter Abonnement + Recherche -->
	<div class="row">

		<div class="offset1 span10">

			<div class="alert alert-error <?php echo $div_confirm_suppress; ?>">
				<strong>Erreur</strong> lors de la suppression : le tag est utilisé pour tagger des articles!
				<br>
				Voulez-vous le supprimer quand même? (Attention, ces articles ne seront plus taggés avec ce tag).
				<table>
					<il>
						<form method="POST" action="tags">
							<input type="hidden" name="delete_confirmed" value="<?php echo $id_tag_to_suppress ; ?>" />
							<button type="submit" class="btn">Oui</button>
						</form>
					</il>
					<il>
						<a class="btn" href="tags">Non</a>
					</il>
				</table>
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
