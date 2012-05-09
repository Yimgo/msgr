// Ajouter un article à la liste 
function add_article(id, titre, contenu) {
    ($('<div>').html('<p>' + titre + '</p>' + '<div style="display: none">' + contenu + '</div>')
               .data('id', id)
    ).appendTo($("#flux_container"));
}

// Toggle onclick sur les articles
function toggle_list_articles() {
    $("#flux_container div p").unbind('click');
    $("#flux_container div p").click(function() {
        $(this).siblings().each(function() {
            $(this).toggle();
        });
    });
}

// Regle le comportement au click sur un flux/dossier
function click_flux_dossier() {
    $('#liste_flux tr').click(function() {
        if ($(this).hasClass('dossier')) {
            var elem = $(this).next();
            while (! $(elem).hasClass('dossier')) {
                $(elem).toggle();
                elem = $(elem).next();
            }
            return;
        }

        $("#liste_flux tr").removeClass("ligne_flux_selectionne");

        $(this).addClass("ligne_flux_selectionne");

        // Affichage de la barre de chargement
        var flux_id = this.id.split('_')[1]; // "flux_ID" : on veut récupérer l'ID uniquement
        var flux_titre= $('td:first', $(this)).text();

        $("#liste_articles_chargement").slideDown('slow');
        $("#liste_articles_erreur").slideUp('fast');
        
        $("#flux_container").html("");
        // Recuperation des articles
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
        $("#search").attr('data-source', data);

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
                $("<a class='btn btn-primary hide' href='#'>" + obj.titre + " <i class='icon-remove icon-white'></i></a>")
                    .data('id', obj.id)
                    .appendTo("#tag-list")
                    .slideDown('fast');

                // Ajouter les événements
                $("#tag-list a").click(function() {
                    $(this).hide('fast', function() { $(this).remove(); });
                });

                $("#search").val("");
            }
        });
    });

    /* GET LISTE DOSSIER + LISTE FLUX */
    $.getJSON('../get_flux_dossiers', function(data) {
        html_liste_flux = '';
        $.each(data, function(index, dossier) {
            // DOSSIER
            html_liste_flux += "<tr class='dossier'>";
            html_liste_flux += "<td colspan='3' style='text-align: center; background-color:#eee' >";
            html_liste_flux += "<i class='icon-folder-open pull-left'></i> ";
            html_liste_flux += "<b>" + dossier.titre + "</b>";
            html_liste_flux += "</td></tr>";

            $.each(dossier.liste_flux, function(index2, flux) {
                // FLUX
                html_liste_flux += "<tr id='flux_" + flux.id + "'>";
                html_liste_flux += "<td>" + flux.titre + "</td>";

                if (flux.nb_nonlus == 0) type_badge = "";
                else if (flux.nb_nonlus > 0 && flux.nb_nonlus < 10) type_badge = "badge-success";
                else if (flux.nb_nonlus >= 10 && flux.nb_nonlus <= 50) type_badge = "badge-warning";
                else type_badge = "badge-important";

                html_liste_flux += '<td><span class="badge ' + type_badge + '">' + flux.nb_nonlus + '</span></td>';

                html_liste_flux += "<td><a href='#'><i class='icon-circle-arrow-right'></i></a></td>";

                html_liste_flux += "</tr>";
            });
        });
        
        // "Dossier" final invisible, pour que le javascript s'arrete de boucler
        html_liste_flux += "<tr class='dossier'></tr>";

        // MAJ du DOM
        $("#liste_flux").html(html_liste_flux);

        // onclick
        click_flux_dossier();
    })
    .error( function() {
        $("#liste_flux_erreur").slideDown('slow');
    });

     
    /* GESTION TAGS AUTOCOMPLETE */
    $("#form-search").submit(function() {
        var tags_id = [];
        $("#tag-list a").each(function() {
            tags_id.push($(this).data('id'));
        });

        var tag = $("<input>")
            .attr("type", "hidden")
            .attr("name", "tags_id")
            .val(tags_id);
        $("#form-search").append($(tag));
        
        return true;
     });
});
