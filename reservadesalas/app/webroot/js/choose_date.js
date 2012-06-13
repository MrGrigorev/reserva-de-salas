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
	
	function validateReservationDateHour(date, hour) {
		
			var pieces_date = date.split('/');
			var pieces_hour = hour.split(':');
			rdate = new Date(pieces_date[2], parseInt(pieces_date[1]) - 1, pieces_date[0], pieces_hour[0], pieces_hour[1]);
			
			tday = new Date().getDate();
			tmonth = new Date().getMonth();
			tyear = new Date().getFullYear();
			thour = new Date().getHours();
			tminute = new Date().getMinutes();
			today = new Date(tyear, tmonth, tday, thour, tminute);
			
			$('#DateDate').parent().removeClass('error');
			$('#DateDate').next('.error-message').remove();
			if (rdate.valueOf() < today.valueOf()) {
				$('#DateDate').parent().addClass('error');
				$('#DateDate').parent().append('<div class=\'error-message\'>Data/horário inválidos.</div>');
				return false;
			}
			return true;
	}
	
	$("#loadAvailableRooms").click(function(){
		var date = $("#DateDate").val();
		var begin_time = $("#DateBeginTime").val();
		var end_time = $("#DateEndTime").val();
		
		if (validateInputItem('#DateDate', date) == false |
			validateInputItem('#DateBeginTime', begin_time) == false |
			validateInputItem('#DateEndTime', end_time) == false)
			return false;
		
		if (validateReservationDateHour(date, begin_time) ==  false) {
			return false;
		}
		
		var json = $.toJSON({'date': date, 'begin_time': begin_time, 'end_time': end_time});
		$.ajax({
           type: 'POST',
           dataType: 'json',
           url: '/reservadesalas/Reservations/loadAvailableRooms',
           data: {data: json},
           success: function(data) {
               $('#availableRooms').html('');
               options = '<br />\
            	   			<table id=\'roomsTable\'>\
            	   		      <tr><td class=\'header\'>Nome/Número</td></td><td class=\'header\'>Bloco</td></tr>';
               for (i in data) {
            	   idRoom = data[i]['Room']['id'];
            	   name = (data[i]['Room']['name'] == null) ? '': data[i]['Room']['name'];
                   number = (data[i]['Room']['number'] == null) ? '' : data[i]['Room']['number'];
                   var link = '<a href=\'/reservadesalas/Reservations/createReservation/'+ idRoom +'/'+ date.replace(/\//g, '-') +'/'+ begin_time.replace(':', '-') +'/'+ end_time.replace(':', '-') + '\' >' + name + ' - ' + number + '</a>';
                   building = data[i]['Building']['name'];
                   options += '<tr><td>'+ link + '</td><td>' + building + '</td><tr>';
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