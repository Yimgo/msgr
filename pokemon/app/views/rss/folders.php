<?php
	render_partial('header', null);
	render_partial('menu', array('active' => 'folders'));
?>

<?php
$folders=$params["Folders"];

if ($params["State"]==="ok"){
	$div_confirm_suppress="hide";
} else {
	$div_confirm_suppress="";
	$explode_state=explode('/', $params["State"], 2);
	$id_folder_to_suppress=$explode_state[1];
}
?>

<div class="container">
    <!-- Ajouter Abonnement + Recherche -->
	<div class="row">

	<div class="offset1 span10">

		<div class="alert alert-error <?php echo $div_confirm_suppress; ?>">
			<strong>Erreur</strong> lors de la suppression : le dossier n'est pas vide !
			<br>
			Voulez-vous le supprimer quand même? (Attention, vous serez désabonné de tous les flux contenus dans ce dossier).
			<table>
				<il>
					<form method="POST" action="folders">
						<input type="hidden" name="delete_confirmed" value="<?php echo $id_folder_to_suppress ; ?>" />
						<button type="submit" class="btn">Oui</button>
					</form>
				</il>
				<il>
					<a class="btn" href="folders">Non</a>
				</il>
			</table>
		</div>

		<table class="table table-bordered table-striped span6">
			<thead>
				<tr>
					<th class="span5" style="text-align: center;">Titre du dossier</th>
					<th class="span5" style="text-align: center;">Actions associées</th>
				</tr>
			</thead>

			<tbody>
				<!-- Liste des dossiers existants -->
				<?php foreach ($folders as $folder) { ?>
				<tr>
					<td>
						<span><?php echo $folder["titre"] ?></span>
						<form method="POST" action="rename_folder" class="form-inline" style="display:none" id="form-<?php echo $folder['id'] ; ?>">
							<input type="hidden" name="id" value="<?php echo $folder["id"] ; ?>" />
							<input type="text" id="titre" name="titre" value="<?php echo $folder["titre"]; ?>"/>
							<button type="submit" class="btn"><i class="icon-check"></i> OK</button>
						</form>
					</td>
					<td>
                        <form method="POST" action="delete_folder" class="form-inline">
                            <a class="btn renommer" href="#" id="renommer-<?php echo $folder["id"];?>"><i class="icon-pencil"></i> Renommer</a>
                            <input type="hidden" name="id" value="<?php echo $folder["id"] ; ?>" />
                            <button type="submit" class="btn"><i class="icon-minus-sign"></i> Supprimer</button>
                        </form>
					</td>
				</tr>
				<?php } ?>

				<!-- Ajout d'un dossier -->
				<tr>
					<form method="POST" action="add_folder" class="form-inline">
						<td>
							<input type="text" placeholder="Nom du nouveau dossier" id="titre" name="titre" />
						</td>
						<td>
							<button type="submit" class="btn"><i class="icon-plus-sign"></i> Ajouter un dossier</button>
						</td>
					</form>
				</tr>
			</tbody>
		</table>
	</div>

</div>
</div>

<?php render_partial("includes_js", null); ?>
<script src="/pokemon/static/javascript/folders.js"></script>
<?php render_partial("footer", null); ?>
