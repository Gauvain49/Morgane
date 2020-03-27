function regionCarriers(nomCible, listLang, langLength, index) {
	//On récupère le nom 'global' des div avec différents pays.
	var noeud = document.getElementById(nomCible);
	//console.log(nomCible);
	//On récupère le contenu des divs à créer
	var contents = noeud.getAttribute('data-prototype');
	var counter = index;//On implémente le compteur
	//twig : on boucle les langues existantes et implèmente leur ID dans les div
	for(var i in listLang) {
		//On ne créée le nouvel élément que s'il n'existe pas
		var ifExist = document.getElementById(nomCible + '_' + counter);
		if (ifExist === null) {
			var content = contents.replace(/__region__/g, counter);
			noeud.insertAdjacentHTML('beforebegin', content);
		}
		document.getElementById(nomCible + '_' + counter).lastChild.value = i;
		// On précise ensuite le nom du pays dans le label
		addCountry(nomCible, counter, listLang, i);
		/*var enfant = document.getElementById(nomCible + '_' + counter).children;
		for(var a = 0; a<enfant.length;a++) {
			var label = enfant[a].getElementsByTagName("label");
			for(var b = 0; b<label.length;b++) {
				var chose = label[b].innerHTML = listLang[i];
			}
		}*/
		counter++;
	}
}

function addCountry(nomCible, counter, listLang, i) {
	//console.log(nomCible + '_' + counter);
	if (nomCible + '_' + counter) {
		//console.log(nomCible + '_' + counter);
		var enfant = document.getElementById(nomCible + '_' + counter).children;
		for(var a = 0; a<enfant.length;a++) {
			var label = enfant[a].getElementsByTagName("label");
			//console.log(label);
			for(var b = 0; b<label.length;b++) {
				//console.log(label[b]);
				var chose = label[b].innerHTML = listLang[i];
			}
		}		
	}
}