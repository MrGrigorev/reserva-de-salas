$(document).ready(function() {
	function showSelectedRadio() {
		$('.userTypeRadio').each(function() {
			if ($(this).attr('checked')) {
				var selectedVal = $(this).val();
				$("#" + selectedVal).show();
				return;
			}
		});
	}
	function hideAndClean() {
		$('.userType').each(function() {
			$(this).find('input').each(function() {
				$(this).attr('value', '');
			});
			$(this).hide();
		});
	}
	
	$('.radio').each(function() {
		$(this).removeClass('required');
	});
	
	$('.radio').each(function() {
		$(this).addClass('requiredLegend');
	});
	
	showSelectedRadio();
	
	$('.userTypeRadio').change(function() {
		hideAndClean();
		showSelectedRadio();
	});
	
});