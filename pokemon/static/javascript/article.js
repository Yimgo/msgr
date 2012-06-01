$(document).ready(function() {

    // Marquer l'article comme lu si besoin
    if (!article_est_lu)
        $.post('/pokemon/rss/set_lu', {'article_id' : article_id, 'lu': 'true'});

    // Actions sur les boutons "favori" et "lu"
    $("#bouton_favori").click(article_favori);
    $("#bouton_lu").click(article_lu_nonlu);

    // Actions sur chaque tag
    $("#liste-tags-actifs a").click(untag);
    $("#liste-tags-non-actifs a").click(tag);
});

// Met en favori/non favori un article
function article_favori() {
    if ($(this).children().hasClass('icon-star')) {
        $.post('/pokemon/rss/set_favori', {'article_id' : article_id, 'favori': 'false'});
        $(this).children().removeClass('icon-star').addClass('icon-star-empty');
    } else {
        $.post('/pokemon/rss/set_favori', {'article_id' : article_id, 'favori' : 'true'});
        $(this).children().removeClass('icon-star-empty').addClass('icon-star');
    }
}

// Marquer un article comme lu/non lu
function article_lu_nonlu() {
    if ($(this).children().hasClass('icon-eye-open')) {
        $.post('/pokemon/rss/set_lu', {'article_id' : article_id, 'lu': 'false'});
        $(this).children().removeClass('icon-eye-open').addClass('icon-eye-close');
    } else {
        $.post('/pokemon/rss/set_lu', {'article_id' : article_id, 'lu' : 'true'});
        $(this).children().removeClass('icon-eye-close').addClass('icon-eye-open');
    }
}

// Gestion des tags
function tag() {
    // Appeler en AJAX la page PHP pour sauvegarder en base
    var tag_id = $(this).parent().attr('id'); // tag_xxx
    tag_id = tag_id.split('_')[1];
    $.post('/pokemon/rss/set_tag', {'article_id' : article_id, 'tag_id' : tag_id, 'tag' : 'true'});

    // Enlever l'action sur clic et la remplacer par la nouvelle
    $(this).unbind('click');
    $(this).click(untag);

    // Déplacer l'élément vers la liste des tags actifs
    var element = $(this).parent().detach();
    $('i', element).removeClass("icon-white");
    $("#liste-tags-actifs").append(element);
}

function untag() {
    // Appeler en AJAX la page PHP pour sauvegarder en base
    var tag_id = $(this).parent().attr('id'); // tag_xxx
    tag_id = tag_id.split('_')[1];
    $.post('/pokemon/rss/set_tag', {'article_id' : article_id, 'tag_id' : tag_id, 'tag' : 'false'});

    // Enlever l'action sur clic et la remplacer par la nouvelle
    $(this).unbind('click');
    $(this).click(tag);

    // Déplacer l'élément vers la liste des tags non actifs
    var element = $(this).parent().detach();
    $('i', element).addClass("icon-white");
    $("#liste-tags-non-actifs").append(element);
}