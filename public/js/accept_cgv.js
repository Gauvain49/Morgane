function acceptCgv(wrapper, checkbox) {
	var payments = document.getElementsByClassName(wrapper);
	var link = [];
	for (var i = 0; i < payments.length; i++) {
		var sendPayment = payments[i].getElementsByTagName('a')[0];
		link[i] = sendPayment.getAttribute('href');
		sendPayment.removeAttribute('href');
		sendPayment.setAttribute('onclick', 'showAlert()');
	}
	var a = document.getElementById(checkbox);
	//var b = a.checked;
	a.addEventListener('change', function(){
		if (this.checked == true) {
			for (var i = 0; i < payments.length; i++) {
				var sendPayment = payments[i].getElementsByTagName('a')[0];
				//link[i] = truc.getAttribute('href');
				sendPayment.removeAttribute('onclick');
				sendPayment.setAttribute('href', link[i]);
			}
		} else {
			for (var i = 0; i < payments.length; i++) {
				var sendPayment = payments[i].getElementsByTagName('a')[0];
				link[i] = sendPayment.getAttribute('href');
				sendPayment.removeAttribute('href');
				sendPayment.setAttribute('onclick', 'showAlert()');
			}
		}
	})
}

function showAlert()
{
  alert("Vous devez accepter les conditions générales de vente.");
}