$(document).ready(function(){
	$('a.back').click(function(){
		parent.history.back();
		return false;
	});
	
	window.redirect = function(url, seconds) {
		var miliseconds = parseInt(seconds) * 1000;
		if (miliseconds <= 0) {
			window.location.href = url;
		}
		else {
			setTimeout(function() {
				window.location.href = url;
			}, miliseconds);
		}
	}
});