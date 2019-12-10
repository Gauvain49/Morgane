function multilanguageProperties(nomCible, listLang, langLength, langDefault) {
	//On récupère le nom 'global' des div avec différents languages.
	//var nomCible = 'categories_contents';
	var noeud = document.getElementById(nomCible);
	if(noeud != null) {
		var clitoris = noeud.children;
	console.log(clitoris);
		var suce = clitoris[0].innerHTML;
	console.log(suce);
		var counter = 0;//On implémente le compteur
		for(var i in listLang) {
			clitoris[0].setAttribute("id", noeud + '_' + i);
			noeud.innerHTML += clitoris;
			console.log(clitoris);
		}
		console.log(clitoris);console.log(suce);
	}
	
	//On récupère le contenu des divs à créer
	/*var contents = noeud.getAttribute('data-prototype');
	//console.log(counter);
	//twig : on boucle les langues existantes et implèmente leur ID dans les div
	for(var i in listLang) {
		//On ne créée le nouvel élément que s'il n'existe pas
		var ifExist = document.getElementById(nomCible + '_' + counter);
	console.log(ifExist);
		if (ifExist === null) {
			var content = contents.replace(/__name__/g, counter);
			noeud.insertAdjacentHTML('beforebegin', content);
		}
		// On précise ensuite le nom de la langue dans le label
		// s'il y a plus de 2 langues
		if(langLength > 1) {
			var truc = document.getElementById(nomCible + '_' + counter).children;
			for(var a = 0; a<truc.length;a++) {
				var machin = truc[a].getElementsByTagName("label");
				for(var b = 0; b<machin.length;b++) {
					var chose = machin[b].innerHTML = machin[b].innerHTML + ' (' + listLang[i] + ')';
				}
			}
		}
		
		//On met la div dans une variable...
		var toto = nomCible + '_' + counter;
		//... pour lui ajouter la class spécifiant que c'est une div recueillant plusieurs language
		document.getElementById(toto).classList.add('chooseLang');
		if (i != langDefault) {

			//twig : on cache les div ne correspondant pas à la langue demandée par défaut
			var tata = document.getElementById(toto).classList.add('d-none');
		}
		var hidden = nomCible + '_' + counter + '_lang';
		var language = document.getElementById(hidden);
		language.value = i;
		counter++;
	}

	//On sélectionne le select du langage à renseigner
	var element = document.getElementsByClassName("choice_language_multiple");
	for(var i = 0;i<element.length;i++) {
		var select = element[i];
		var option = select.selectedIndex;
		var valeur = select.options[option].value;
		var data = select.options[option].dataset.role;
		//On mets dans une variable toutes les div destinées à pouvoir subir un changement
		var cibles = document.getElementsByClassName('chooseLang');
		var cible = document.getElementById(nomCible + '_' + valeur);
		cible.classList.remove('d-none');

		select.addEventListener('change', function(){
			//Dès le changement, on cache toutes les divs
		 	for(var j = 0;j<cibles.length;j++) {//On retire la class aux balises p...
		 		var div = cibles[j];
		 		div.classList.add('d-none');
		 	}
			var newOption = this.selectedIndex;
			valeur = this.options[newOption].value;
		 	cible = document.getElementById(nomCible + '_' + valeur);//On redéfinie la cible avec la nouvelle valeur
		 		cible.classList.remove('d-none');
		 	data = select.options[newOption].dataset.role;
			var button = document.getElementsByClassName('choiceLanguage');
			for(var i=0;i<button.length;i++) {
				button[i].firstElementChild.innerHTML = data;
			}
		});
	}
	var button = document.getElementsByClassName('choiceLanguage');
	for(var i=0;i<button.length;i++) {
		button[i].firstElementChild.innerHTML = data;
	}*/
}