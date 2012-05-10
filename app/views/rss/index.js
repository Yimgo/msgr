// Ajouter un article à la liste 
function add_article(id, titre, contenu) {
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

// Toggle onclick sur les articles
function toggle_list_articles() {
    $("#flux_container div p")
        .unbind('click')
        .click(function() {
            $(this).siblings().each(function() {
                $(this).toggle();
            });
        });
}

// Regle le comportement au click sur un flux/dossier
function click_flux_dossier() {
    $('#liste_flux tr').click(function() {

        // Quand on clique sur un dossier : afficher/cacher ses enfants
        if ($(this).hasClass('dossier')) {
            var elem = $(this).next();
            while (! $(elem).hasClass('dossier')) {
                $(elem).toggle();
                elem = $(elem).next();
            }
            return;
        }

        // Sinon, on a cliqué sur un flux : il faut chercher la liste des articles correspondant
        $("#liste_flux tr").removeClass("ligne_flux_selectionne");
        $(this).addClass("ligne_flux_selectionne");

        // Affichage de la barre de chargement
        var flux_id = $(this).data('id');
        var flux_titre= $('td:first', $(this)).text();

        $("#liste_articles_chargement").slideDown('slow');
        $("#liste_articles_erreur").slideUp('fast');
        
        // Recuperation des articles
        $("#flux_container").html("");
        $.getJSON('../get_articles/' + flux_id, function(data) {
            $.each(data, function(index, elem) {
                add_article(elem.id, elem.titre, elem.contenu);
            });
            toggle_list_articles();
        })

        .success(function() {
            $("#liste_articles_chargement").slideUp('slow');
        })
        .error(function() {
            $("#liste_articles_chargement").slideUp('slow');
            $("#liste_articles_erreur").slideDown('slow');
            $("#liste_flux tr").removeClass("ligne_flux_selectionne");
        });
    });
}

$(document).ready(function() {

    /* GET LISTE TAGS + BARRE DE RECHERCHE */
    $.get('../get_tags', function(data) {
        $("#search").attr('data-source', data); // Données utilisées pour l'auto-complétion

        $('#search').typeahead({
            source: function(typeahead, query) {
                var all_tags = eval($(".typeahead").attr("data-source"));
                var used_tags = [];
                var showed_tags = [];

                // Enlever les tags déjà utilisés
                $("#tag-list a").each(function() {
                    used_tags.push($(this).text());
                });

                $(all_tags).each(function(index) {
                    if ($.inArray(all_tags[index].titre + ' ', used_tags) == -1)
                        showed_tags.push(all_tags[index]);
                });

                return showed_tags;
            },
            property: "titre",
            onselect: function(obj) {
                // Ajouter le tag
                $('<a>', {
                    class: 'btn btn-primary hide',
                    href: '#',
                    html: obj.titre
                })
                .append($('<i>', {
                    class: 'icon-remove icon-white'}))
                .data('id', obj.id)
                .appendTo("#tag-list")
                .slideDown('fast');

                // Ajouter l'événement
                $("#tag-list a").click(function() {
                    $(this).hide('fast', function() { $(this).remove(); });
                });

                $("#search").val("");
            }
        });
    });

    /* GET LISTE DOSSIER + LISTE FLUX */
    $.getJSON('../get_flux_dossiers', function(data) {
        
        $.each(data, function(index, dossier) {
            // DOSSIER
            $('<tr>', { class: 'dossier' })
                .append($('<td>', {colspan: 3, style: 'text-align: center; background-color: #eee;'})
                            .append($('<i>', {class: 'icon-folder-open pull-left'}))
                            .append($('<b>', {html : dossier.titre}))
                )
            .appendTo('#liste_flux');
            
            $.each(dossier.liste_flux, function(index2, flux) {
                // FLUX
                if (flux.nb_nonlus == 0) type_badge = "";
                else if (flux.nb_nonlus > 0 && flux.nb_nonlus < 10) type_badge = "badge-success";
                else if (flux.nb_nonlus >= 10 && flux.nb_nonlus <= 50) type_badge = "badge-warning";
                else type_badge = "badge-important";

                $('<tr>')
                    .data('id', flux.id)
                    .append($('<td>', {html : flux.titre }))
                    .append($('<td>').append(
                                $('<span>', {class: 'badge ' + type_badge, html: flux.nb_nonlus})
                            ))
                    .append($('<td>')
                                .append($('<a>', {href: '#'}))
                                .append($('<i>', {class: 'icon-circle-arrow-right'}))
                            )
                .appendTo('#liste_flux')
            });
        });

        // "Dossier" final invisible, pour que le javascript s'arrete de boucler
        $('<tr>', {class: 'dossier'}).appendTo('#liste_flux');

        // onclick
        click_flux_dossier();
    })
    .error( function() {
        $("#liste_flux_erreur").slideDown('slow');
    });

     
    /* GESTION RECHECHE : on ajoute manuellement les tags comme input caché à la validation */
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
        
        return true;
     });
});
