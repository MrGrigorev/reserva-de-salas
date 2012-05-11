$(document).ready(function() {
	$(":input").keyup(function(e) {
		if(e.keyCode == 13) {
			$('.submittableForm').submit();
		}
	});
});