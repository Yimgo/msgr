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
        <div class="span8">
            <?php echo $params['contenu']; ?>
        </div>

        <div class="span4">
            <h3>Tags concernant cet article</h3>
            <ul id="liste-tags-actifs" class="unstyled well">
            <?php foreach ($params["tags"] as $tag) { ?>
                <li id="tag_<?php echo $tag['id'] ;?>">
                    <i class="icon-tag"></i>
                    <a href="#"><?php echo $tag['nom']; ?></a>
                </i>
            <?php } ?>
            </ul>

            <hr />
            <div style="overflow-y: auto; height: 20em;">
                <h3>Vos autres tags</h3>
                <ul id="liste-tags-non-actifs" class="unstyled well">
                <?php foreach ($params["all_tags"] as $tag) {
                    if (! in_array($tag, $params["tags"])) { ?>
                    <li id="tag_<?php echo $tag['id'] ;?>">
                        <i class="icon-tag icon-white"></i>
                        <a href="#"><?php echo $tag['nom']; ?></a>
                    </i>
                <?php }} ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="row" id="commentaires">
        <hr />

    <div class="span8">
    <h2 id="comments">Liste des commentaires</h2>
<?php
    if (empty($params['comments'])) {
 ?>
        <div class="alert alert-info <?php echo $div_error_suppress; ?>">
                Soyez le premier à commenter !
        </div>
 <?php       
    }
    foreach($params['comments'] as $comment) {
?>
     <blockquote>
            <p><?php echo $comment['commentaire'];?></p>
            <small><strong><?php echo $comment['username']; ?></strong> - <?php echo $comment['date']; ?></small>
    </blockquote>

<?php
    }
?>
    </div>
    <div class="span4">
            <form class="well" method="POST" action="../add_commentaire">
            <fieldset>
                <legend>Participer à la discussion</legend>
                <input type="hidden" name="article_id" value="<?php echo $params['id'] ; ?>" />
                <textarea rows="3" name="commentaire" id="textarea" class="input-xlarge"></textarea>
                <button class="btn btn-primary" type="submit">Ajouter</button>
            </fieldset>
          </form>
    </div>  
</div>

<?php render_partial("footer", null); ?>
<script src="/pokemon/static/javascript/article.js"></script>
