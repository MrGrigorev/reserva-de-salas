$(document).ready(function() {
	
	$('.userTypeRadio').change(function() {
		$('.userType').each(function() {
			$(this).hide();
		});
		
		$('.userTypeRadio').each(function() {
			if ($(this).attr('checked')) {
				var selectedVal = $(this).val();
				$("#" + selectedVal).show();
				return;
			}
		});
		
	});
});