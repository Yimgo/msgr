/* --------------------------
   INITIALISATION DU DOCUMENT
   -------------------------- */

$(document).ready(function() {
    // Récupérér initialement la liste des flux + dossiers pour l'afficher
    get_liste_flux();
    // Bouton pour rafraichir la liste des flux
    $('#refresh_liste_flux').click(function() {
        get_liste_flux();
    });

    // Gestion des tags
    $.get('/pokemon/rss/get_tags', function(data) {
        raw_tags = data;
        fresh_dropdown_tags = make_dropdown_tags(eval(raw_tags));

        // AUTOCOMPLETION
        $("#search").attr('data-source', data); // Données utilisées pour l'auto-complétion

        $('#search').typeahead({
            source: function(typeahead, query) {
                // ne pas proposer à l'autocomplétion les tags déjà utilisés
                var all_tags = eval($(".typeahead").attr("data-source"));
                var used_tags = [];
                var showed_tags = [];

                $("#tag-list a").each(function() {
                    used_tags.push($(this).text());
                });

                $(all_tags).each(function(index) {
                    if ($.inArray(all_tags[index].titre + ' ', used_tags) == -1)
                        showed_tags.push(all_tags[index]);
                });

                return showed_tags;
            },
            property: "titre", // champ de 'data' qui sera utilisé pour les comparaisons
            onselect: function(obj) {
                // Ajouter le tag
                add_tag_to_dom(obj.titre, obj.id);
                // Réinitialiser la barre de recherche
                $("#search").val("");
            }
        });
    });

    // Gestion de la recherche : 
    //   - on ajoute manuellement les tags comme input caché à la validation
    $("#form-search").submit(function() {
        var tags_id = [];
        $("#tag-list a").each(function() {
            tags_id.push($(this).data('id'));
        });

        $('#form-search').append(
            $("<input>")
                .attr("type", "hidden")
                .attr("name", "tags_id")
                .val(tags_id)
        );
     });

    // Empecher d'ajouter un flux quand l'URL est vide
    $('#form_add_flux').submit(function() {
        if ($("#form_add_flux_URL").val() == "") return false;
        else return true;
    });
    
    $.getJSON('/pokemon/rss/get_latest_articles/0/10', function(data) {
        // Ajouter les articles dans la colonne de droite
        $.each(data, function(index, elem) {
            add_article_to_dom(elem.id, elem.titre, elem.contenu, elem.favori, elem.lu, elem.tags, elem.url);
        });
    })
});

/* --------------------------------
   GESTION DE LA LISTE DES ARTICLES
   -------------------------------- */

// Ajouter un article à la liste des articles
function add_article_to_dom(id, titre, contenu, favori, lu, liste_tags, url) {
    var new_dropdown_tags = $(fresh_dropdown_tags).clone(true, true);

    $('input', new_dropdown_tags)
        .click(function() { return false; })
        .typeahead({
            source: function(typeahead, query) {
                        var all_tags = eval(raw_tags);
                        var used_tags = [];
                        var showed_tags = [];
                        
                        $('li:visible', new_dropdown_tags).each(function() {
                            if ($(this).data('id') != undefined) {
                                used_tags.push($(this).text());
                            }
                        });

                        $(all_tags).each(function(index) {
                            if ($.inArray(' ' + all_tags[index].titre, used_tags) == -1)
                                showed_tags.push(all_tags[index]);
                        });

                        return showed_tags;
                    },
            property: "titre",
            onselect: function(obj) {
                $('li', new_dropdown_tags).each(function(index, li) {
                    if ($(li).data('id') == obj.id) {
                        $(li).show();
                        $.post('/pokemon/rss/set_tag', {'tag_id':obj.id, 'article_id':id, 'tag':true});
                        $('input', new_dropdown_tags).val("");
                    }
                });
            }
        });

    toto = $('<div>')
            .data('id', id)
            .append($('<p>')
                .append($('<a>', {html:titre, href:url}))
                .append($('<div>', {'class': 'article_properties btn-group'})
                    .append($('<button>', {'class':'btn dropdown-toggle', 'data-toggle':'dropdown'})
                        .append($('<i>', {'class': 'icon-tags'}))
                    )
                    .append(new_dropdown_tags)
                    .append($('<button>', {'class':'btn'})
                        .append($('<i>', {'class': lu ? 'icon-eye-open' : 'icon-eye-close'}))
                        .click(article_lu_nonlu)
                    )
                    .append($('<button>', {'class':'btn'})
                        .append($('<i>', {'class': favori ? 'icon-star' : 'icon-star-empty'}))
                        .click(article_favori)
                    )
                    .append($('<button>', {'class':'btn'})
                        .append($('<i>', {'class':'icon-chevron-down'}))
                        .click(function() {
                            if ($(this).children().hasClass('icon-chevron-down'))
                                $(this).children().removeClass('icon-chevron-down').addClass('icon-chevron-up');
                            else
                                $(this).children().removeClass('icon-chevron-up').addClass('icon-chevron-down');
                            $(this).parent().parent().siblings().toggle();
                        })
                    )
                )
            )
            .append($('<div>', {
                'html': contenu,
                'style': 'display: none'
            }));

    // Actuellement, tous les tags sont affichés dans une liste, tous masqués
    // On affiche uniquement les tags marqués pour cet article
    $.each(liste_tags, function(index, idtag) {
        $.each($('p div ul li', toto), function(index2, li) {
            if ($(li).data('id') == idtag)
                $(li).show();
        });
    });

    // Event click sur le tag : désactiver le tag
    $('p div ul li i.icon-minus-sign', toto)
    .click(function() {
        var li = $(this).parent().parent();
        id_tag =  $(li).data('id');
        id_article = $(li).parent().parent().parent().parent().data('id');
        $(li).hide();
        $.post('/pokemon/rss/set_tag', {'tag_id':id_tag, 'article_id':id_article, 'tag':false});
        return false;
    });

    $('#flux_container').append(toto);
}

// Appelée quand on clique sur un flux
// - colore la ligne pour signifier qu'on a cliqué dessus
// - Récupère les articles correspondants et les insère
function click_flux() {

    // Mettre le titre du flux dans la colonne de droite
    $('#titre_liste_articles').html($(this).data('titre'));
    $('#titre_liste_articles').data('id', $(this).data('id'));

    // Coloriage de la ligne courante
    $("#liste_flux tr").removeClass("ligne_flux_selectionne");
    $(this).addClass("ligne_flux_selectionne");

    // Affichage de la barre de chargement
    $("#liste_articles_chargement").show();
    $("#liste_articles_erreur").hide();
    
    // --- Recuperation des articles ---
    $("#flux_container").html("");
    $.getJSON('/pokemon/rss/get_articles/' + $(this).data('id'), function(data) {
        // Ajouter les articles dans la colonne de droite
        $.each(data, function(index, elem) {
            add_article_to_dom(elem.id, elem.titre, elem.contenu, elem.favori, elem.lu, elem.tags, elem.url);
        });
    })
    .success(function() {
        $("#liste_articles_chargement").hide();
        $('#div_dropdown_move_folder').show();
    })
    .error(function() {
        $("#liste_articles_chargement").hide();
        $("#liste_articles_erreur").show();
        $("#liste_flux tr").removeClass("ligne_flux_selectionne");
        $('#div_dropdown_move_folder').hide();
    });
}

// Met en favori/non favori un article
function article_favori() {
    id = $(this).parent().parent().parent().data('id');

    if ($(this).children().hasClass('icon-star')) {
        $.post('/pokemon/rss/set_favori', {'article_id' : id, 'favori': 'false'});
        $(this).children().removeClass('icon-star').addClass('icon-star-empty');
    } else {
        $.post('/pokemon/rss/set_favori', {'article_id' : id, 'favori' : 'true'});
        $(this).children().removeClass('icon-star-empty').addClass('icon-star');
    }
}

function article_lu_nonlu() {
    id = $(this).parent().parent().parent().data('id');

    if ($(this).children().hasClass('icon-eye-open')) {
        $.post('/pokemon/rss/set_lu', {'article_id' : id, 'lu': 'false'});
        $(this).children().removeClass('icon-eye-open').addClass('icon-eye-close');
    } else {
        $.post('/pokemon/rss/set_lu', {'article_id' : id, 'lu' : 'true'});
        $(this).children().removeClass('icon-eye-close').addClass('icon-eye-open');
    }
}

/* ---------------------------------------
   GESTION DE LA LISTE DES FLUX + DOSSIERS
   --------------------------------------- */

// Quand on clique sur un dossier : afficher/cacher ses enfants
function click_dossier() {
    var elem = $(this).next();
    while (! $(elem).hasClass('dossier')) {
        $(elem).toggle();
        elem = $(elem).next();
    }
    return;
}

// Déplacer un flux de dossier
function changer_flux_de_dossier() {
    var dossier_id = $(this).data('id');
    var flux_id =  $('#titre_liste_articles').data('id');
    $.post('/pokemon/rss/move_flux_folder',{'flux_id' : flux_id, 'dossier_id': dossier_id})
     .success(get_liste_flux)
    ;
}

// Créer le bouton "Déplacer le flux vers un autre dossier"
function creer_bouton_liste_dossiers(data) {
    $('#div_dropdown_move_folder').empty();

    // Liste de tous les dossiers dans un <ul>…</ul>
    var html_li_dossiers = $('<ul>', {'class' : 'dropdown-menu'});
    $.each(data, function(index, dossier) {
        $('<li>').append(
            $('<a>', {'href' : '#', 'html':' ' + dossier.titre})
                .prepend($('<i>', {'class':'icon-hand-right'}))
                .data('id', dossier.id)
                .click(changer_flux_de_dossier)
        )
        .appendTo($(html_li_dossiers));
    }); 

    // Création du bouton "dropdown", auquel on ajoute la liste des dossiers
    $('#div_dropdown_move_folder')
        .append($('<a>', {'class' : 'btn dropdown-toggle', 'data-toggle' : 'dropdown', 'data-target':'#', 'href':'#', 'html': ' Déplacer '})
            .prepend($('<i>', {'class':'icon-list-alt'}))
            .append($('<span>', {class:'caret'}))
        )
        .append(html_li_dossiers)
    ;
}

// Rafraichit la liste des dossiers + flux
// - Récupère la liste depuis PHP
// - Insère les éléments dans le DOM
function get_liste_flux() {
    $("#liste_flux_chargement").show();
    $("#liste_flux_erreur").hide();
    // RAZ de la liste
    $('#liste_flux').html('');

    $.getJSON('/pokemon/rss/get_flux_dossiers')
        .success(function(data) {
            // Stocker la liste des dossiers (pour faire le dropdown)
            creer_bouton_liste_dossiers(data);

            // Insérer les données dans le DOM
            $.each(data, function(index, dossier) {
                // Insérer le dossier dans le tableau
                add_dossier_to_dom(dossier.titre);
               
                // Ajouter chaque flux du dossier dans le tableau
                $.each(dossier.liste_flux, function(index2, flux) {
                    add_flux_to_dom(flux.titre, flux.nb_nonlus, flux.id);
                });
            });

            // "Dossier" final invisible, pour que le javascript s'arrete de boucler
            $('<tr>', {class: 'dossier'}).appendTo('#liste_flux');
        })
    .success(function() {
        $("#liste_flux_chargement").hide();
    })
    .error( function() {
        $("#liste_flux_chargement").hide();
        $("#liste_flux_erreur").show();
    });
}

// Ajouter un dossier au tableau contenant la liste des dossiers + flux
function add_dossier_to_dom(nom) {
    $('<tr>', { class: 'dossier' })
        .append($('<td>', {colspan: 3, style: 'text-align: center; background-color: #eee;'})
                .append($('<i>', {class: 'icon-folder-open pull-left'}))
                .append($('<b>', {html : nom}))
               )
        .click(click_dossier)
        .appendTo('#liste_flux');
}

// Ajouter un flux au tableau contenant la liste des dossiers + flux
function add_flux_to_dom(titre, nb_nonlus, id) {
    if (nb_nonlus == 0) type_badge = "";
    else if (nb_nonlus > 0 && nb_nonlus < 10) type_badge = "badge-success";
    else if (nb_nonlus >= 10 && nb_nonlus <= 50) type_badge = "badge-warning";
    else type_badge = "badge-important";

    $('<tr>')
        .data('id', id)
        .data('titre', titre)
        .click(click_flux)
        .append($('<td>', {'html' : titre }))
        .append($('<td>')
                .append($('<span>', {'class': 'badge ' + type_badge, 'html':nb_nonlus}))
               )
        .append($('<td>')
                .append($('<i>', {'class': 'icon-circle-arrow-right'}))
               )
        .appendTo('#liste_flux')
    ;
}


// Ajouter le tag au DOM
function add_tag_to_dom(titre, id) {
    // DOM
    $('<a>', {
        class: 'btn btn-primary hide',
        href: '#',
        html: titre + ' '
    })
    .append($('<i>', {
        class: 'icon-remove icon-white'}))
        .data('id', id)
        .appendTo("#tag-list")
        .show();

    // Evénement
    $("#tag-list a").click(function() {
        $(this).hide(function() { $(this).remove(); });
    });
}

function make_dropdown_tags(tags) {
    dropdown_tags = $('<ul>', {'class' : 'dropdown-menu'});
    $.each(tags, function(index, elem) {
        dropdown_tags.append(
            $('<li>', {'style':'display:none'})
                .data('id',elem.id)
                .append($('<a>', {'html': ' ' +elem.titre, 'href':'/pokemon/rss/tag/' + elem.id})
                    .prepend($('<i>', {'class' : 'icon-tag'}))
                    .append($('<i>', {'class' : 'icon-minus-sign pull-right'}))
                )
        );
    });

    dropdown_tags
    .append($('<hr>'))
    .append($('<li>')
        .append($('<form>', {'style' : 'text-align:center'})
            .append($('<input>', {'type':'text', 'class': 'input-small typeahead', 'data-items':4}))
        )
    );

    return dropdown_tags;
}
