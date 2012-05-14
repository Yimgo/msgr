<?php render_partial("header", null); ?>

<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="#">RSS</a>
            <div class="nav-collapse">
                <ul class="nav">
                    <li class="active"><a href="#">Home</a></li>
                </ul>
            </div>

        <ul class="nav pull-right">
            <li class="divider-vertical"></li>
            <li><a href="/pokemon/rss/logout">Déconnexion</a></li>
          </ul>

        </div>
    </div>
</div>

<div class="container">
    <!-- Ajouter Abonnement + Recherche -->
    <div class="row">
        <!-- Ajouter Abonnement --> 
        <div class="span4">
            <form>
                <div class="input-append">
                    <input name="url" type="text" class="span3" />
                    <button type="submit" class="btn"><i class="icon-plus-sign"></i></button>
                    <a id='refresh_liste_flux' href='#' class='btn pull-right'><i class='icon-refresh'></i></a>
                </div>
            </form>
        </div>
        <!-- Recherche -->
        <div class="span8">
            <form class="form-search" action="/pokemon/rss/search" id="form-search" >
                <div class="input-append input-prepend">
                    <span id="tag-list"></span>
                    <input type="text" id="search" name="search" class="search-query typeahead" data-items="4" />
                    <button type="submit" class="btn"><i class="icon-search"></i> Rechercher un article</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des RSS + Liste des articles-->
    <div class="row">
        <div class="span4">
            <h1>Liste des flux RSS</h1>
            
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
            <h1 id="titre_liste_articles"></h1>
            <div id="div_dropdown" class="dropdown pull-right" style="display:none"></div>
        
            <div id="liste_articles_chargement" class="alert alert-info fade in hide">
                <strong>Chargement</strong> des articles en cours…
            </div>

            <div id="liste_articles_erreur" class="alert alert-error fade in hide">
                <strong>Erreur</strong> lors du chargement des articles !
            </div>

            <div id="flux_container">
            </div>
       </div>
    </div>
</div>

<?php render_partial("footer", null); ?>
<!-- JS à la fin, après les includes de jQuery -->
<script src="/pokemon/static/javascript/listing.js"></script>
