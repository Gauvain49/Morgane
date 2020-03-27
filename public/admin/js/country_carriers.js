function countryCarriers(nomCible, listLang, langLength, index) {
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
			var content = contents.replace(/__country__/g, counter);
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

function basePrice(name) {
	//console.log(index);
	var base = document.getElementById(name);
	var input = base.getElementsByTagName('input');
	for (var b = 0; b<input.length; b++) {
		if (input[b].checked) {
			var valInput = input[b].value;
		}
	}
	if (valInput == 'price') {
		var valLabelStepMin = 'Pour un prix total &gt;= à';
		var valLabelStepMax = 'jusqu\'à un prix total &lt;= à';
	} else if (valInput == 'weight') {
		var valLabelStepMin = 'Pour un poids total &gt;= à';
		var valLabelStepMax = 'jusqu\'à un poids total &lt;= à';
	} else if (valInput == 'qty') {
		var valLabelStepMin = 'Pour une quantité total &gt;= à';
		var valLabelStepMax = 'jusqu\'à une quantité total &lt;= à';
	}
	var price = document.getElementById('carriers_config_billing_on_0');
	var weight = document.getElementById('carriers_config_billing_on_1');
	var qty = document.getElementById('carriers_config_billing_on_2');
	price.addEventListener('change', function(){
		valInput = price.value;
		var valLabelStepMin = 'Pour un prix total &gt;= à';
		var valLabelStepMax = 'jusqu\'à un prix total &lt;= à';
	
		var valLabel = [valLabelStepMin, valLabelStepMax];
		var index = $('#carriers_config_steps div.col-3').length;
		for (var i = 0; i < index; i++) {
			var a = document.getElementById('block_carriers_config_steps_' + i);
			var label_step_min = a.getElementsByClassName("step_min");
			for (var l=0; l<label_step_min.length; l++) {
				label_step_min[l].innerHTML = valLabel[0];
				//console.log(label_step_min[l].innerHTML);
			}
			var label_step_max = a.getElementsByClassName("step_max");
			for (var l=0; l<label_step_max.length; l++) {
				label_step_max[l].innerHTML = valLabel[1];
				//console.log(label_step_min[l].innerHTML);
			}
		}
		console.log(valInput);
	});
	weight.addEventListener('change', function(){
		valInput = weight.value;
		var valLabelStepMin = 'Pour un poids total &gt;= à';
		var valLabelStepMax = 'jusqu\'à un poids total &lt;= à';
	
		var valLabel = [valLabelStepMin, valLabelStepMax];
		var index = $('#carriers_config_steps div.col-3').length;
		for (var i = 0; i < index; i++) {
			var a = document.getElementById('block_carriers_config_steps_' + i);
			var label_step_min = a.getElementsByClassName("step_min");
			for (var l=0; l<label_step_min.length; l++) {
				label_step_min[l].innerHTML = valLabel[0];
				//console.log(label_step_min[l].innerHTML);
			}
			var label_step_max = a.getElementsByClassName("step_max");
			for (var l=0; l<label_step_max.length; l++) {
				label_step_max[l].innerHTML = valLabel[1];
				//console.log(label_step_min[l].innerHTML);
			}
		}
		console.log(valInput);
	});
	qty.addEventListener('change', function(){
		valInput = qty.value;
		var valLabelStepMin = 'Pour une quantité total &gt;= à';
		var valLabelStepMax = 'jusqu\'à une quantité total &lt;= à';
	
		var valLabel = [valLabelStepMin, valLabelStepMax];
		var index = $('#carriers_config_steps div.col-3').length;
		for (var i = 0; i < index; i++) {
			var a = document.getElementById('block_carriers_config_steps_' + i);
			var label_step_min = a.getElementsByClassName("step_min");
			for (var l=0; l<label_step_min.length; l++) {
				label_step_min[l].innerHTML = valLabel[0];
				//console.log(label_step_min[l].innerHTML);
			}
			var label_step_max = a.getElementsByClassName("step_max");
			for (var l=0; l<label_step_max.length; l++) {
				label_step_max[l].innerHTML = valLabel[1];
				//console.log(label_step_min[l].innerHTML);
			}
		}
	});
	
	var valLabel = [valLabelStepMin, valLabelStepMax];
	var index = $('#carriers_config_steps div.col-3').length;
	for (var i = 0; i < index; i++) {
		var a = document.getElementById('block_carriers_config_steps_' + i);
		var label_step_min = a.getElementsByClassName("step_min");
		for (var l=0; l<label_step_min.length; l++) {
			label_step_min[l].innerHTML = valLabel[0];
			//console.log(label_step_min[l].innerHTML);
		}
		var label_step_max = a.getElementsByClassName("step_max");
		for (var l=0; l<label_step_max.length; l++) {
			label_step_max[l].innerHTML = valLabel[1];
			//console.log(label_step_min[l].innerHTML);
		}
	}


	return valLabel;
}