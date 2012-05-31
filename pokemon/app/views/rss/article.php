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
 	                <li class=""><a href="/pokemon/rss/listing"><i class="icon-home icon-white"></i> Accueil</a></li>
 	                <li class=""><a href="/pokemon/rss/folders"><i class="icon-folder-close icon-white"></i> Gérer les dossiers</a></li>
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
                <h1><a href="<?php echo $params['url']; ?>"><?php echo $params['titre']; ?></a></h1>

            <?php } ?>
        </div>

        <div class="span4">
            <div id="actions" class="span2 offset1 btn-group">
                <button class="btn">
                    <i class="<?php echo ($params['lu']) ? 'icon-eye-open' : 'icon-eye-close' ; ?>"></i>
                </button>
                <button class="btn">
                    <i class="<?php echo ($params['favori']) ? 'icon-start' : 'icon-star-empty' ; ?>"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <hr />
        <!-- $params[id] = id du flux
                'lu' => filter_var($row['lu'], FILTER_VALIDATE_BOOLEAN),
                'favori' => filter_var($row['favori'], FILTER_VALIDATE_BOOLEAN),
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
