<?php render_partial("header", null); ?>

<?php
$values = array(
    'tab_active_login' => '',
    'div_error_login' => 'hide',
    'input_login' => '',
    'tab_active_signup' => '',
    'div_error_signup' => 'hide',
    'input_user_login' => '',
    'input_user_password' => '',
    'input_user_email' => ''
);

if ($params['type'] == 'login') {
    $values['tab_active_login'] = 'active';
    if ($params['state'] == 'error') {
        $values['div_error_login'] = '';
        $values['input_login'] = 'error';
    }
}
else if ($params['type'] == 'signup') {
    $values['tab_active_signup'] = 'active';
    if ($params['state'] == 'error') {
        $values['div_error_signup'] = '';
        $values['input_user_login'] = ($params['error'] == 'user_login') ? 'error' : '';
        $values['input_user_password'] = ($params['error'] == 'user_password') ? 'error' : '';
        $values['input_user_email'] = ($params['error'] == 'user_email') ? 'error' : '';
    }
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

            <div class="tabbable tabs-left">
                <ul class="nav nav-tabs">
                    <li class="<?php echo $values['tab_active_login'] ; ?>"><a href="#tab_login" data-toggle="tab">Se connecter</a></li>
                    <li class="<?php echo $values['tab_active_signup'] ; ?>"><a href="#tab_inscription" data-toggle="tab">S'inscrire</a></li>
                </ul>
                
                <div class="tab-content">
                    <div class="tab-pane <?php echo $values['tab_active_login'] ; ?>" id="tab_login">

                        <div class="alert alert-error <?php echo $values['div_error_login']; ?>">
                            <strong>Erreur</strong> lors de la connexion !
                        </div>

                        <form class="form-horizontal well" method="POST" action="/pokemon/rss/login">
                            <fieldset>
                                <legend>Connectez-vous...</legend>

                                <div class="control-group <?php echo $values['input_login']; ?>" >
                                    <label class="control-label" for="user_login">Login :</label>
                                    <div class="controls">
                                        <input type="text" placeholder="Nom d'utilisateur..." name="user_login" id="user_login" />
                                    </div>
                                </div>
                                
                                <div class="control-group <?php echo $values['input_login']; ?>" >
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

                    <div class="tab-pane <?php echo $values['tab_active_signup'] ; ?>" id="tab_inscription">
                        <div class="alert alert-error <?php echo $values['div_error_signup']; ?>">
                            <strong>Erreur</strong> lors de l'inscription !
                        </div>

                        <form class="form-horizontal well" method="POST" action="/pokemon/rss/signup">
                            <fieldset>
                                <legend>Inscrivez-vous !</legend>

                                <div class="control-group <?php echo $values['input_user_login']; ?>" >
                                    <label class="control-label" for="user_login">Login :</label>
                                    <div class="controls">
                                        <input type="text" placeholder="Nom d'utilisateur..." name="user_login" id="user_login" />
                                    </div>
                                </div>
                                
                                <div class="control-group <?php echo $values['input_user_password']; ?>" >
                                    <label class="control-label" for="user_password">Mot de passe :</label>
                                    <div class="controls">
                                        <input type="password" placeholder="*******" name="user_password" id="user_password" />
                                    </div>
                                </div>

                                <div class="control-group <?php echo $values['input_user_email']; ?>" >
                                    <label class="control-label" for="user_email">Adresse email :</label>
                                    <div class="controls">
                                        <input type="text" placeholder="foo@bar.com" name="user_email" id="user_email" />
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Inscription</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php render_partial("footer", null); ?>
