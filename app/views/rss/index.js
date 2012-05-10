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
    $.get('../get_tags', function(data) {
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
});

/* --------------------------------
   GESTION DE LA LISTE DES ARTICLES
   -------------------------------- */

// Ajouter un article à la liste des articles
function add_article_to_dom(id, titre, contenu) {
    $('#flux_container').append(
        $('<div>')
            .data('id', id)
            .append($('<p>', { html: titre }))
            .append($('<div>', {
                html: contenu,
                style: 'display: none'
            }))
    );
}

// Appelée quand on clique sur un flux
// - colore la ligne pour signifier qu'on a cliqué dessus
// - Récupère les articles correspondants et les insère
function click_flux() {

    // Mettre le titre du flux dans la colonne de droite
    $('#titre_liste_articles').html($(this).data('titre'));

    // Coloriage de la ligne courante
    $("#liste_flux tr").removeClass("ligne_flux_selectionne");
    $(this).addClass("ligne_flux_selectionne");

    // Affichage de la barre de chargement
    $("#liste_articles_chargement").slideDown('slow');
    $("#liste_articles_erreur").slideUp('fast');
    
    // --- Recuperation des articles ---
    $("#flux_container").html("");
    $.getJSON('../get_articles/' + $(this).data('id'), function(data) {
        // Ajouter les articles dans la colonne de droite
        $.each(data, function(index, elem) {
            add_article_to_dom(elem.id, elem.titre, elem.contenu);
        });
        click_list_articles();
    })
    .success(function() {
        $("#liste_articles_chargement").slideUp('slow');
        $('#div_dropdown').show();
    })
    .error(function() {
        $("#liste_articles_chargement").slideUp('slow');
        $("#liste_articles_erreur").slideDown('slow');
        $("#liste_flux tr").removeClass("ligne_flux_selectionne");
        $('#div_dropdown').hide();
    });
}

// Pour chaque article, permettre de le cacher/le déplier en cliquant dessus
function click_list_articles() {
    $("#flux_container div p")
        .unbind('click')
        .click(function() {
            $(this).siblings().each(function() {
                if ($(this).is(':hidden'))
                    $(this).slideDown('slow');
                else
                    $(this).slideUp('slow');
            });
        });
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
    // $(this).data('id')
    // TODO: ENVOYER LES DONNEES POUR SIGNIFIER A PHP LE CHANGEMENT DE DOSSIER
    get_liste_flux();
}

// Créer le bouton "Déplacer le flux vers un autre dossier"
function creer_bouton_liste_dossiers(data) {
    $('#div_dropdown').empty();

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
    $('#div_dropdown')
        .append($('<a>', {'class' : 'btn dropdown-toggle', 'data-toggle' : 'dropdown', 'data-target':'#', 'href':'#', 'html': ' Déplacer dans un autre dossier '})
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
    $("#liste_flux_chargement").slideDown('slow');
    $("#liste_flux_erreur").slideUp('fast');
    // RAZ de la liste
    $('#liste_flux').html('');

    $.getJSON('../get_flux_dossiers')
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
        $("#liste_flux_chargement").slideUp('slow');
    })
    .error( function() {
        $("#liste_flux_chargement").slideUp('slow');
        $("#liste_flux_erreur").slideDown('slow');
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
        .slideDown('fast');

    // Evénement
    $("#tag-list a").click(function() {
        $(this).hide('fast', function() { $(this).remove(); });
    });
}
