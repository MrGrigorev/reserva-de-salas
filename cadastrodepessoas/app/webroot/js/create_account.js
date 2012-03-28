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
	
	$('input').blur(function() {
		var input = $(this);
		var val = input.val();
		var regex = /^data\[([A-Za-z]+)\]\[([A-Za-z]+)\]$/;
		var name = input.attr('name');
		var matches = regex.exec(name);
		if (matches.length != 3) {
			alert("Erro: nome do input incorreto.");
			return;
		}
		
		var modelName = matches[1];
		name = matches[2];
		//alert("VAL = " + val + "\n\nModel = " + modelName + "\n\nNAME = " + name);
		//$.post("", {value: input.val(), name: }, function(data) { // Do an AJAX call
		//});
	});
	
	$('input[type="file"]').change(function() {
		//alert("FILE BLUR");
	});
	
});