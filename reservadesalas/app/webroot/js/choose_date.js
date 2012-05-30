$(document).ready(function() {
	$("#DateDate").datepicker();
	
	$("#DateBeginTime").timepicker({});
	
	$("#DateEndTime").timepicker({});
	
	
	$("#loadAvailableRooms").click(function(){
		/* TODO: make controller */
		var date = $("#DateDate").val();
		var begin_time = $("#DateBeginTime").val();
		var end_time = $("#DateEndTime").val();
		
		if (date == '') {
			$('#DateDate').parent().addClass('error');
			$('#DateDate').parent().append('<div class=\'error-message\'>Não deve ser vazio.</div>');
			return false;
		}
		
		$.ajax({
           type: 'POST',
           dataType: 'json',
           url: '/reservadesalas/Reservations/loadAvailableRooms',
           data: {},
           success: function(data) {
               $('#availableRooms').html('');
               options = '<br />\
            	   			<table id=\'roomsTable\'>\
            	   		      <tr><td class=\'header\'>Nome</td><td class=\'header\'>Número</td><td class=\'header\'>Bloco</td></tr>';
               for (i in data) {
            	   name = (data[i]['Room']['name'] == null) ? '': data[i]['Room']['name'];
                   number = data[i]['Room']['number'];
                   building = data[i]['Building']['name'];
                   options += '<tr><td>'+ name + '</td><td>' + number + '</td><td>' + building + '</td><tr>';
               }
               options += '</table>';
               $('#availableRooms').html(options);
           }
        });
		return false;
	})
});