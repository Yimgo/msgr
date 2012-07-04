<?php
	render_partial('header', null);
	render_partial('menu', array('active' => 'listing'));
?>

<div class="container">
    <!-- Ajouter Abonnement + Recherche -->
    <div class="row">
        <!-- Ajouter Abonnement -->
        <div class="span4">
            <form method="POST" action="<?php echo $GLOBALS['POKEMON_ROOT']; ?>/rss/parse_single_feed" id="form_add_flux">
                <div class="input-append">
                    <input id="form_add_flux_URL" name="url" type="text" placeholder="Saisir une URL" class="span3" autocomplete="off" />
                    <button type="submit" class="btn" ><i class="icon-plus-sign"></i></button>
                </div>
            </form>
        </div>
        <!-- Recherche -->
        <div class="span8">
            <form class="form-search" action="<?php echo $GLOBALS['POKEMON_ROOT']; ?>/rss/search" id="form-search" >
                <div class="input-append input-prepend">
                    <span id="tag-list"></span>
                    <input type="text" id="search" name="search" class="search-query typeahead" data-items="4" autocomplete="off" />
                    <button type="submit" class="btn"><i class="icon-search"></i> Rechercher un article</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des RSS + Liste des articles-->
    <div class="row">
        <div class="span4">
            <h1>Liste des flux RSS</h1>

            <table class="table">
                <tr>
                    <td id="liste_articles_non_lus_command"><i class="icon-eye-close pull-left"></i> <b>Liste des articles non lus</b><span class="badge pull-right" id="total_unread_count">0</span></td>
                </tr>
                <tr>
                    <td id="liste_articles_favoris_command"><i class="icon-star pull-left"></i> <b>Liste des articles favoris</b></td>
                </tr>
            </table>

            <div id="liste_flux_chargement" class="alert alert-info fade in hide">
                <strong>Chargement</strong> de la liste des flux en cours…
            </div>

            <div id="liste_flux_erreur" class="alert alert-error fade in hide">
                <strong>Erreur</strong> lors du chargement de la liste des flux !
            </div>

            <table class="table" id="liste_flux">
            </table>

        </div>

        <div class="span8">
            <h1 id="titre_liste_articles">Liste des articles non lus</h1>
            <div id="div_dropdown_move_folder" class="dropdown" style="display:none"></div>

            <div id="liste_articles_chargement" class="alert alert-info fade in hide">
                <strong>Chargement</strong> des articles en cours…
            </div>

            <div id="liste_articles_erreur" class="alert alert-error fade in hide">
                <strong>Erreur</strong> lors du chargement des articles !
            </div>

            <div id="liste_flux_fin" class="alert alert-error fade in hide">
                <strong>Erreur</strong> : aucun article à charger !
            </div>

            <div id="flux_container">
            </div>

            <div style="margin-bottom: 1em;">
                <a class="btn" id="pagin-left" style="display:none"><i class="icon-arrow-left"></i> </a>
                <a class="btn" id="pagin-right"><i class="icon-arrow-right"></i> </a>
            </div>
       </div>
    </div>
</div>

<?php render_partial("includes_js", null); ?>
<script src="<?php echo $GLOBALS['POKEMON_ROOT']; ?>/static/javascript/listing.js"></script>
<?php render_partial("footer", null); ?>
