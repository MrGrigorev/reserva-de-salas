$(document).ready(function() {
	var userType = {
		Employee : 0,
		Student: 1,
		Professor: 2
	};
	
	$('.userTypeRadio').change(function() {
		$('.userType').each(function() {
			$(this).hide();
		});
		
		$('.userTypeRadio').each(function() {
			if ($(this).attr('checked')) {
				var selectedVal = $(this).val();
				if (selectedVal == userType.Employee) {
					$("#Employee").show();
				}
				else if (selectedVal == userType.Student) {
					$("#Student").show();
				}
				else {
					$("#Professor").show();
				}
				return;
			}
		});
		
	});
});