$(document).ready(function() {
    if (!article_est_lu)
        $.post('/pokemon/rss/set_lu', {'article_id' : article_id, 'lu': 'true'});

    $("#bouton_favori").click(article_favori);
    $("#bouton_lu").click(article_lu_nonlu);
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

function article_lu_nonlu() {
    if ($(this).children().hasClass('icon-eye-open')) {
        $.post('/pokemon/rss/set_lu', {'article_id' : article_id, 'lu': 'false'});
        $(this).children().removeClass('icon-eye-open').addClass('icon-eye-close');
    } else {
        $.post('/pokemon/rss/set_lu', {'article_id' : article_id, 'lu' : 'true'});
        $(this).children().removeClass('icon-eye-close').addClass('icon-eye-open');
    }
}