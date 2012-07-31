function modifier () {
	//On replie tous les autres
	$("table.modifiable tr > td.deplie").each(function () {
		var ligne = $(this).parent();
		//On le replie	: suppression de l'image & du hidden et on remet le texte initial à la place des champs
		//Problème : donne l'impression que sauvegardé alors que non
		var titre = ligne.find("input#titre").val();
		var email = ligne.find("input#email").val();
		if (email != "") {
			titre = '<a href="mailto:' + email + '">' + titre + '</a>';
		}
		ligne.find("input#titre").parent().html(titre);

		ligne.find("input[type='text']").each(function () {
			$(this).parent().html($(this).val());
		});
		ligne.find("input[type='radio'][name='coopeman']").parent().text(function () {
			if ($(this).children("input[type='radio']:checked").val() == "") {
				if (!$(this).hasClass("coopeman")) {
					$(this).addClass("coopeman");
				}
				return 1;
			} else {
				$(this).removeClass("coopeman");
				return "";
			}
		});
		ligne.find("input[type='radio'][name='caution']").parent().text(function () {
			return $(this).children("label[for='" + $(this).children("input[type='radio']:checked").attr("id") + "']").text();
		});
		ligne.find("input").remove();
		ligne.find("select").parent().html($("select > option:selected").val());


		$(this).removeClass('deplie')
		.click(modifier);
	});

	//On déplie celui-ci
	$(this).addClass('deplie');
	$("input[name='id']").val($(this).parent().attr('title'));

	var temp = $(this);
	temp.html('<input type="image" alt="valider" title="valider" src="res/images/valider.png" />');

	temp = temp.next();
	var titre = temp.html();	//Soit prenom ["pseudo"] nom ; soit <a href="mailto:mail">prenom ["pseudo"] nom</a>
	var avecMail = /^<a(?: xmlns="http:\/\/www\.w3\.org\/1999\/xhtml")? href="mailto:([a-zA-Z0-9_.-]+@[a-zA-Z0-9_.-]+\.[a-z]{2,6})">(.+)<\/a>$/;
	temp.html('<label for="titre">Nom : </label><input type="text" name="titre" id="titre" value="" /><br/><label for="email">email : </label><input type="text" id="email" name="email" value="" />');
	if(titre.match(avecMail)) {
		var match = avecMail.exec(titre);
		temp.children("input#email").val(match[1]);
		temp.children("input#titre").val(match[2]);
	} else {
		temp.children("input#titre").val(titre);
	}

	temp = temp.next();
	temp.html('<input type="text" name="depot" value="' + temp.html() + '" />');

	temp = temp.next();
	var reponse = temp.html();
	temp.html('<input type="radio" value="1" id="caution_1" name="caution"/>' +
			'<label for="caution_1">Oui</label><br/>' +
			'<input type="radio" value="0" id="caution_0" name="caution" checked="checked"/>' +
	'<label for="caution_0">Non</label>')

	//Si déjà une caution (= à "oui"), on propose d'en faire une nouvelle
	if (reponse == "oui") {
		temp.html('<input type="radio" value="1" id="caution_2" name="caution"/>' +
				'<label for="caution_2">Nouveau chèque</label><br/>' + temp.html());
		temp.children("input#caution_1").attr("checked","checked");
	} else {
		temp.children("input#caution_0").attr("checked","checked");
	}

	temp = temp.next();
	var annee = temp.text();
	temp.text('');
	$("#annees").clone().attr('name','promo').removeAttr('id').removeAttr('multiple').appendTo(temp);
	if(temp.children().children("option[value='" + annee + "']").length == 0) {
		temp.children().append('<option value="' + (annee == 'exté' ? 0 : annee) + '">' + annee + '</option>').children().last().attr("selected","selected"); 
	} else {
		temp.children().children("option[value='" + annee + "']").attr('selected','selected');
	}

	temp = temp.next();
	var coopeman = temp.text();
	temp.html('<input type="radio" name="coopeman" id="c_1" value="1" /><label for="c_1"> Oui</label><input type="radio" name="coopeman" id="c_0" value="0" /><label for="c_0"> Non</label>');
	temp.children('input#c_' + (coopeman == 1 ? 1 : 0)).attr('checked','checked');

	$(this).unbind('click');
}

$("table.modifiable tr > td:first-child").click(modifier);

/**
 * Met à jour l'email + 3 champs cachées contenant "prenom", "pseudo" et "nom" d'après le contenu de input#titre 
 * 
 * @return void
 */
function emailAuto2() {
	var titre = $(this).val();
	var prenom, surnom, nom;
	var debutPseudo = titre.indexOf('"');
	if (debutPseudo == -1) {
		//Pas de surnom - limite : premier espace
		var limite = titre.indexOf(' ');
		prenom = titre.substring(0,limite);
		nom = titre.substring(limite+1,titre.length);
		surnom = '';
	} else {
		prenom = titre.substring(0,debutPseudo-1);
		var finPseudo = titre.indexOf('"',debutPseudo+1);
		surnom = titre.substring(debutPseudo+1,finPseudo);
		nom = titre.substring(finPseudo+2,titre.length);
	}

	//On met maintenant les champs et l'email à jour
	$("input#email").val(prenom.toLowerCase() + '.' + nom.toLowerCase() + '@supelec.fr');
}
$("input#titre").live("change", emailAuto2);
