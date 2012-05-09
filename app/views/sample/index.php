<?php link_to_css("style.css"); ?>
<?php render_partial("plop", $params) ?>

<p>Hello world!</p>
<p>
    Current directory: 
    <?php echo $params["current_dir"] ?>
</p>
