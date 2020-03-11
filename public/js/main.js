$(document).ready(function(e) {
	$("#open-infobox").click(function() {
		$("#bgBlack").fadeIn("slow", function() {
			$("#infobox").animate({ "top":"150px" }, 500);
		});
		return false;
	});
	$("#closeInfobox").click(function() {
		$("#infobox").animate({"top":"-600px"}, 300, function() {
			$("#bgBlack").fadeOut("fast");
		});
	});
});
