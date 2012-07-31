$(function () {

	//Affichage des menus
	$("#sidebar>ul>li>a").click(function () {
		var menucourant = $("#champ>div");
		menucourant.children().appendTo($("#" + menucourant.attr("class")));	//On enlève le précédent menu
		menucourant.removeClass();				//On enlève l'info sur le menu affiché
		$("#champ").css('background-color', $(this).parent().css('background-color'));	//La couleuuuur
		$(this).parent().children("div").appendTo(menucourant);		//On met le menu demandé
		menucourant.addClass($(this).parent().attr("id"));	//On enregistre quel est le menu actuellement affiché
                if ($(this).parent().attr("id") != "#menu") {
                   //$("#champ>span").style.display = "block";
                   document.getElementById('bouffe').style.display = 'none';
                   document.getElementById('cocktails').style.display = 'none';
                }
      
		return false;
	});

	//Recherche du compte
	function callback (row) {
		return row[0].substring(0,row[0].indexOf(';'));
	}

	$("#main_nom").autocomplete("compte.php", {
		max: 0,
		minChars: 3,
		formatItem: callback,
		formatResult: callback,
		width: "184px"})
	.result(function (event, data, formatted) {
		$(this).next().text(formatted.substring(formatted.indexOf(';')+1, formatted.indexOf(',')-1) + " €");
		$(this).next().next().attr("src",formatted.substring(formatted.indexOf(',')+1, formatted.indexOf('*')));
	});


	//Ajouter un sorter pour les dates
	$.tablesorter.addParser({
		id: "date",
		is: function(s) {
			return /^\d{2}h\d{2} le \d{2}\/\d{2}$/.test(s);
		},
		format: function(s) {
			return $.tablesorter.formatFloat(s.replace(/^(\d{2})h(\d{2}) le (\d{2})\/(\d{2})$/,"$4$3$1$2"));
		},
		type: "numeric"
	});

	//Tri des tableaux
	$("table").tablesorter();

	//Email et nom automatiques, suppression accents pour nouveau compte
	function champsAuto() {
		var prenom = no_accent($("#rc_prenom").val());
		var nom = no_accent($("#rc_nom").val());
		
		$("#rc_prenom").val(prenom);
		$("#rc_nom").val(nom);
		$("#rc_email").val(replaceAll(prenom.toLowerCase()," ","") + '.' + replaceAll(nom.toLowerCase()," ","") + '@supelec.fr');
		$("#main_nom").val(prenom + " " + nom);
	}
	$("#rc_prenom").change(champsAuto);
	$("#rc_nom").change(champsAuto);
	
	//Création de compte : Gérer les deux champs nom
	$("#tache3>a").click(function () {
		$("#main_nom").attr("disabled","disabled");	//On désactive le champ de recherche

		//On prépare sa réactivation
		$("#sidebar>ul>li").not("#tache3").children("a").click(function (event) {
			$("#main_nom").removeAttr("disabled");
			$(this).unbind(event);
		});
	});


	// Remplace toutes les occurences d'une chaine
	function replaceAll(str, search, repl) {
		while (str.indexOf(search) != -1)
			str = str.replace(search, repl);
		return str;
	}
 
	function no_accent(str) {
		var norm = new Array('À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë',
				'Ì','Í','Î','Ï', 'Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý',
				'Þ','ß', 'à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î',
				'ï','ð','ñ', 'ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ý','þ','ÿ','\'');
		var spec = new Array('A','A','A','A','A','A','A','C','E','E','E','E',
				'I','I','I','I', 'D','N','O','O','O','0','O','O','U','U','U','U','Y',
				'b','s', 'a','a','a','a','a','a','a','c','e','e','e','e','i','i','i',
				'i','d','n', 'o','o','o','o','o','o','u','u','u','u','y','y','b','y','');
		for (var i = 0; i < spec.length; i++)
			str = replaceAll(str, norm[i], spec[i]);
		return str;
	}


//	Charge dynamiquement des js au premier clic sur le lien
	function chargement_js (type, nom, element) {
		var adresse = 'res/js/modif_' + type + '_' + nom + '.js';
		if($('head>script[src="' + adresse + '"]').length == 0) {
			$(element).unbind('click');
			$("head").append('<script type="text/javascript" src="' + adresse + '" />');
			$(element).click();
		}
		return false;
	}

//	Modification d'une ligne d'un tableau
	$("table.modifiable tr>td:first-child").click(function () {
		return chargement_js('tableau', $("table.modifiable").attr('id'), this); 
	});

//	Modification d'un compte
	$("#charger_compte").click( function () {
		//On charge les infos du compte
		$.getJSON("donnees.php", { c: $(this).prev().val() },
				function(data) {
			//On remplit avec les données récupérées

			//Parse nom pour prénom/surnom/nom
			var prenom, surnom, nom;
			var debutPseudo = data.nom.indexOf('"');
			if (debutPseudo == -1) {
				//Pas de surnom - limite : premier espace
				var limite = data.nom.indexOf(' ');
				prenom = data.nom.substring(0,limite);
				nom = data.nom.substring(limite+1,data.nom.length);
				surnom = '';
			} else {
				prenom = data.nom.substring(0,debutPseudo-1);
				var finPseudo = data.nom.indexOf('"',debutPseudo+1);
				surnom = data.nom.substring(debutPseudo+1,finPseudo);
				nom = data.nom.substring(finPseudo+2,data.nom.length);
			}
			$("#rc_prenom").val(prenom);
			$("#rc_surnom").val(surnom);
			$("#rc_nom").val(nom);

			$("#rc_id").val(data.id);
			$("#rc_depot").val(data.solde);
			$("#rc_email").val(data.email);

			//Si un trop vieux (=pas de bouton dispo pour sa promo), on le rajoute. Et on enlève ceux rajoutés précédemment
			$(".ajoute").remove();
			if($("#rc_p" + data.promo).length == 0) {
				$("#rc_email").parent().next().append('<input class="ajoute" type="radio" name="promo" value="' + data.promo + '" id="rc_p' + data.promo + '" /> <label class="ajoute" for="rc_p' + data.promo + '">' + data.promo + '</label>')
					.children().last().prev().attr("checked","checked");
			} else {
				$("#rc_p" + data.promo).attr("checked","checked");
			}

			//On rajoute un bouton si une caution a déjà été donnée : en mettre une nouvelle ?
			//Si pas déjà de caution : 0 -> non ; 1 -> oui (càd nouvelle)
			//Si déjà une caution : 0 -> non (on l'enlève) ; 1 -> oui (on garde) ; 2 -> nouvelle
			if (data.caution == "0" || data.caution == null) {
				$("#rc_caution_0").attr("checked","checked")
				$("#rc_caution_2").remove();
				$("label[for='rc_caution_2']").remove();
			} else {
				$("#rc_caution_1").attr("checked","checked")
				if ($("#rc_caution_2").length == 0) {
					$("#rc_caution_1").before('<input type="radio" value="2" id="rc_caution_2" name="caution" /><label for="rc_caution_2">Nouveau chèque</label>');
				}
			}
			$("#rc_coopeman_" + data.coopeman).attr("checked","checked");
			$("#rc_ouvert_" + data.ouvert).attr("checked","checked");
		});

		return false;
	});
});
