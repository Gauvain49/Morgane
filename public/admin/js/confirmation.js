(function(e) {
	//On créé un tableau avec les liens comprenant la class 'confirmSuppression'
	var lien = document.getElementsByClassName('confirmSuppression');
	for(var i = 0; i < lien.length; i++) {
		lien[i].addEventListener('click', function(){//Si le lien contenant la class est cliqué
			var texte = this.getAttribute('title'); //On récupère la valeur de l'attribut 'title' contenant le message de la boîte confirm
			var lien = this.getAttribute('href'); //On récupère le lien de redirection
			this.removeAttribute('href'); //On retire l'attribut 'href' pour éviter la redirection au clic
			var verif = confirm(texte); //On affiche la boîte de dialogue avec le texte du lien 'title'
			if(verif) { //Si on clique sur Oui, on redirige vers la page de suppresion
				window.location = lien;
			} else {
				// Sinon, on ne fait rien, et on redonne l'attribut 'href' au lien.
				// (si on ne redonne pas l'attribut 'href', l'utilisateur ne pourra plus cliquer sur le lien sans recharger la page)
				this.addEventListener('mouseup', function(){
					this.setAttribute('href', lien);
				});
			}
		});
	}
})();