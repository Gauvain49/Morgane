window.onload = function() {
	var isMSIE = '\v' == 'v';
	if (isMSIE) {
		var isResized = false;
		if (screen.systemXDPI === undefined) { //ie < 8
			var rect = document.body.getBoundingClientRect();
			isResized = (rect.right - rect.left) != document.body.clientWidth;
		} else { //ie > 7
			isResized = 96 != screen.deviceXDPI;
		}
		if (isResized) {
			var style_node = document.createElement("style");
			style_node.setAttribute("type", "text/css");
			style_node.setAttribute("media", "screen");

			if (document.styleSheets && document.styleSheets.length > 0) {
				var last_style_node = document.styleSheets[document.styleSheets.length - 1];
				if (typeof(last_style_node.addRule) == "object") {
					last_style_node.addRule('.background', 'filter: none !important;');
				}
			}
		}
	}
};

(function() {
	var prepareMobileView = function() {
		var htmlTagClasses = document.documentElement.className;
		//set opera mobile as mobile device
		if (htmlTagClasses.indexOf('opera') > -1 && htmlTagClasses.indexOf('linux') > -1) {
			htmlTagClasses += ' mobile';
		}

		if (window.outerHeight) {
			var resolution = Math.min(window.outerHeight, window.outerWidth),
				isSetAsMobile = (htmlTagClasses.indexOf('mobile') > -1),
				mobileResolution = 640; //iPhone

			// set large devices as desktop
			if (isSetAsMobile && resolution > mobileResolution) {
				htmlTagClasses = htmlTagClasses.replace('mobile', '');
			}
		}

		// add viewport meta tag for mobile browsers
		if (htmlTagClasses.indexOf('mobile') > -1) {
			var meta = document.createElement('meta');
			meta.name = 'viewport';
			meta.content = 'width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0';
			document.getElementsByTagName('head')[0].appendChild(meta);
		}
		document.documentElement.className = htmlTagClasses;
	};

	if (document.addEventListener) {
		document.addEventListener('DOMContentLoaded', prepareMobileView, false);
	}
})();