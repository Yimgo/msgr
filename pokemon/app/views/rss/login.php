<?php render_partial("header", null); ?>

<?php
if ($params['state'] == 'ERROR_CONN')
    $class_input = "error";
else
    $class_input = "";
?>

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
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="offset3 span6">

            <?php if ($params['state'] == 'ERROR_CONN') { ?>
                <div id="liste_flux_chargement" class="alert alert-error fade in">
                    <strong>Erreur</strong> lors de la connexion !
                </div>
            <?php } ?>

            <form class="form-horizontal well" method="POST" action="/pokemon/rss/login">
                <fieldset>
                    <legend>Connectez-vous...</legend>

                    <div class="control-group <?php echo $class_input; ?>" >
                        <label class="control-label" for="user_login">Login :</label>
                        <div class="controls">
                            <input type="text" placeholder="Nom d'utilisateur..." name="user_login" id="user_login" />
                        </div>
                    </div>
                    
                    <div class="control-group <?php echo $class_input; ?>" >
                        <label class="control-label" for="user_password">Mot de passe :</label>
                        <div class="controls">
                            <input type="password" placeholder="*******" name="user_password" id="user_password" />
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Connexion</button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>

<?php render_partial("footer", null); ?>
