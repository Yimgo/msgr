<?php render_partial("header", null); ?>

<script type="text/javascript">
var article_id = <?php echo $params["id"];?> ;
var article_est_lu = <?php echo $params["lu"] ? 'true' : 'false' ;?> ;
</script>

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
                    <li><a href="/pokemon/rss/listing"><i class="icon-home icon-white"></i> Accueil</a></li>
                    <li><a href="/pokemon/rss/folders"><i class="icon-folder-close icon-white"></i> Gérer les dossiers</a></li>
                    <li><a href="/pokemon/rss/tags"><i class="icon-tags icon-white"></i> Gérer les tags</a></li>
                    <li class="divider-vertical"></li>
                    <li class="active"><a href="#"><i class="icon-file icon-white"></i> Article</a></li>
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
        <div class="span8" id="article">
            <?php if (empty($params)) {?>
                <div class="alert alert-error fade in">
                    <strong>Erreur</strong> : l'article n'existe pas !
                </div>
            <?php } else { ?>
                <header class="jumbotron subhead">
                <h1><a href="<?php echo $params['url']; ?>"><?php echo $params['titre']; ?></a></h1>
                  <p class="lead pull-left"><em><?php echo $params['flux_nom']; ?></em></p>
                  <p class="lead pull-right"><?php echo $params['date'];?></p>
                </header>
            <?php } ?>
        </div>

        <div class="span4">
            <div id="actions" class="span2 offset1 btn-group">
                <button class="btn" id="bouton_lu">
                    <i class="icon-eye-open"></i>
                </button>
                <button class="btn" id="bouton_favori">
                    <i class="<?php echo ($params['favori']) ? 'icon-star' : 'icon-star-empty' ; ?>"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <hr />
        <!--
                'date' => $row['date'],
                'tags' => array()
        -->
        <div class="span8">
            <?php echo $params['contenu']; ?>
        </div>

        <div class="span4">
            <div>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            </div>
        </div>
    </div>

    <div class="row" id="commentaires">
        <hr />
        <h2>Liste des commentaires</h2>

        <!-- A répéter pour chaque commentaire -->
        <div class="row">
            <div class="span9">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            </div>

            <div class="span3">
                profil
            </div>
        </div>
</div>

<?php render_partial("footer", null); ?>
<script src="/pokemon/static/javascript/article.js"></script>
