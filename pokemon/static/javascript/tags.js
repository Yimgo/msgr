$(document).ready(function() {
	/* Quand on clique sur "Editer", transformer le champ texte en formulaire */
	$("a.renommer").click(function () {
		var form = $("#form-" + $(this).attr('id').split('-')[1]);
		form.toggle();
		$($(form).siblings()[0]).toggle();
	});
});