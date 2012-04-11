$(document).ready(function() {
	function selectAll() {
		$('.selectableBySelectAll').each(function() {
			$(this).attr('checked', true);
		});
	}
	function unselectAll() {
		$('.selectableBySelectAll').each(function() {
			$(this).attr('checked', false);
		});
	}
	
	$('.selectAll').change(function() {
		var checked = $(this).attr('checked');
		if (checked) {
			selectAll();
		}
		else {
			unselectAll();
		}
	});
});