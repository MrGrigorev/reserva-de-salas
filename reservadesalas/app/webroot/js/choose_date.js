$(document).ready(function() {
	var dateIndex = 0;
	
	var reservationDate = $("#reservationDate").html();
	
	function addDateAndTimePickers() {
		$("#Date" + dateIndex + "Date").datepicker();
		$("#Date" + dateIndex + "BeginTime").timepicker({});
		$("#Date" + dateIndex + "EndTime").timepicker({});
	}
	
	addDateAndTimePickers();
	
	$("#DateUntil").datepicker();
	$("#DateCapacity").numeric();
	
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
	
	function validateReservationDateHour(date, begin_time, end_time, i) {
		
			var pieces_date = date.split('/');
			var pieces_hour_begin = begin_time.split(':');
			rdate = new Date(pieces_date[2], parseInt(pieces_date[1]) - 1, pieces_date[0], pieces_hour_begin[0], pieces_hour_begin[1]);
			
			tday = new Date().getDate();
			tmonth = new Date().getMonth();
			tyear = new Date().getFullYear();
			thour = new Date().getHours();
			tminute = new Date().getMinutes();
			today = new Date(tyear, tmonth, tday, thour, tminute);
			
			$('#Date' + i + 'Date').parent().removeClass('error');
			$('#Date' + i + 'Date').next('.error-message').remove();
			if (rdate.valueOf() < today.valueOf()) {
				$('#Date' + i + 'Date').parent().addClass('error');
				$('#Date' + i + 'Date').parent().append('<div class=\'error-message\'>Data/horário inválidos.</div>');
				return false;
			}
			
			$('#Date' + i + 'EndTime').parent().removeClass('error');
			$('#Date' + i + 'EndTime').next('.error-message').remove();
			if (begin_time > end_time) {
				$('#Date' + i + 'EndTime').parent().addClass('error');
				$('#Date' + i + 'EndTime').parent().append('<div class=\'error-message\'>Horário final menor que inicial.</div>');
				return false;
			}
			
			return true;
	}
	
	$('.reservationRadio').change(function() {
		if (this.value == 'none')
			$("#reservationUntil").hide();
		else
			$("#reservationUntil").show();
	})
	
	$("#addDatetime").click(function() {
		dateIndex++;
		var newReservationDate = reservationDate.replace(/\[0\]/g, '[' + dateIndex + ']');
		newReservationDate = newReservationDate.replace(/Date0/g, 'Date' + dateIndex);
	
		$("#reservationDates").append(newReservationDate);
		addDateAndTimePickers();
	
		return false;
	})
	
	$("#loadAvailableRooms").click(function(){
		var date = [];
		var begin_time = [];
		var end_time = [];
		
		var invalidData = true;
		for (var i = 0; i <= dateIndex; i++) {
			var dateId = "#Date" + i + "Date";
			var beginTimeId = "#Date" + i + "BeginTime";
			var endTimeId = "#Date" + i + "EndTime";
			
			date[i] = $(dateId).val();
			begin_time[i] = $(beginTimeId).val();
			end_time[i] = $(endTimeId).val();
		
			if (validateInputItem(dateId, date[i]) == false |
				validateInputItem(beginTimeId, begin_time[i]) == false |
				validateInputItem(endTimeId, end_time[i]) == false) {
				invalidData = false;
				continue;
			}
			
			if (validateReservationDateHour(date[i], begin_time[i], end_time[i], i) ==  false)
				invalidData = false;
		}
		
		if (invalidData == false)
			return false;
		
		capacity = $("#DateCapacity").val();	
		
		var json = $.toJSON({'date': date, 'begin_time': begin_time, 'end_time': end_time, 'capacity': capacity});
		$.ajax({
           type: 'POST',
           dataType: 'json',
           url: '/reservadesalas/Reservations/loadAvailableRooms',
           data: {data: json},
           success: function(data) {
               $('#availableRooms').html('');
               options = '<br />\
            	   			<table id=\'roomsTable\'>\
            	   		      <tr><td class=\'header\'>Nome/Número</td></td><td class=\'header\'>Bloco</td><td class=\'header\'>Capacidade</td></tr>';
               for (i in data) {
            	   idRoom = data[i]['Room']['id'];
            	   name = (data[i]['Room']['name'] == null) ? '': data[i]['Room']['name'];
                   number = (data[i]['Room']['number'] == null) ? '' : data[i]['Room']['number'];
                   var link = '<a href=\'/reservadesalas/Reservations/createReservation/'+ idRoom +'/'+ date.replace(/\//g, '-') +'/'+ begin_time.replace(':', '-') +'/'+ end_time.replace(':', '-') + '\' >' + name + ' - ' + number + '</a>';
                   building = data[i]['Building']['name'];
                   capacity = data[i]['Room']['capacity'];
                   options += '<tr><td>'+ link + '</td><td>' + building + '</td><td>' + capacity + '</td></tr>';
               }
               options += '</table>';
               $('#availableRooms').html(options);
           },
           error: function(jqXHR, textStatus, errorThrown){
        	   alert ('Erro. Tente novamente: ' + textStatus);
           }
        });
		return false;
	})
});