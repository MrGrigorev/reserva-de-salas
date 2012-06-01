$(document).ready(function() {
	$("#DateDate").datepicker();
	
	$("#DateBeginTime").timepicker({});
	
	$("#DateEndTime").timepicker({});
	
	function validateInputItem(inputID, value) {
		$(inputID).parent().removeClass('error');
		$(inputID).next('.error-message').remove();
		
		if (value == '') {
			$(inputID).parent().addClass('error');
			$(inputID).parent().append('<div class=\'error-message\'>Não deve ser vazio.</div>');
			return false;
		}
		
		return true;
	}
	
	$("#loadAvailableRooms").click(function(){
		/* TODO: make controller */
		var date = $("#DateDate").val();
		var begin_time = $("#DateBeginTime").val();
		var end_time = $("#DateEndTime").val();
		
		if (validateInputItem('#DateDate', date) == false |
			validateInputItem('#DateBeginTime', begin_time) == false |
			validateInputItem('#DateEndTime', end_time) == false)
			return false;
		
		var json = $.toJSON({'date': date, 'begin_time': begin_time, 'end_time': end_time});
		$.ajax({
           type: 'POST',
           dataType: 'json',
           url: '/reservadesalas/Reservations/loadAvailableRooms',
           data: {data: json},
           success: function(data) {
        	   alert(data);
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
           },
           error: function(jqXHR, textStatus, errorThrown){
        	   alert ('Something very bad went wrong: ' + textStatus);
           }
        });
		return false;
	})
});