$(document).ready(function() {	
	var dateIndex = 0;
	
	var reservationDate = $("#reservationDate").html();
	
	function addDateAndTimePickers() {
		$("#Date" + dateIndex + "Date").datepicker();
		$("#Date" + dateIndex + "BeginTime").timepicker({});
		$("#Date" + dateIndex + "EndTime").timepicker({});
	}
	
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
	
	function getRepetitionValue() {
		var repetitionValue;
		
		$('.reservationRadio').each(function() {
			if ($(this).attr('checked')) {
				repetitionValue = $(this).val();
				return;
			}
		});
		
		return repetitionValue;
	}
	
	function showUntilDateInput(repetitionValue) {
		if (repetitionValue == 'none')
			$("#reservationUntil").hide();
		else
			$("#reservationUntil").show();
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
			
			if (rdate.valueOf() < today.valueOf()) {
				$('#Date' + i + 'Date').parent().addClass('error');
				$('#Date' + i + 'Date').parent().append('<div class=\'error-message\'>Data/horário inválidos.</div>');
				return false;
			}
			
			if (begin_time > end_time) {
				$('#Date' + i + 'EndTime').parent().addClass('error');
				$('#Date' + i + 'EndTime').parent().append('<div class=\'error-message\'>Horário final menor que inicial.</div>');
				return false;
			}
			
			return true;
	}
	
	addDateAndTimePickers();
	
	$("#DateUntil").datepicker();
	$("#DateCapacity").numeric();
	
	showUntilDateInput(getRepetitionValue());
	
	$('.reservationRadio').change(function() {
		showUntilDateInput(this.value);
	})
	
	$("#addDatetime").click(function() {
		dateIndex++;
		var newReservationDate = reservationDate.replace(/\[0\]/g, '[' + dateIndex + ']');
		newReservationDate = newReservationDate.replace(/Date0/g, 'Date' + dateIndex);
	
		$("#reservationDates").append('<span id="newDate' + dateIndex + '">' + newReservationDate + '</span>');
		addDateAndTimePickers();
		
		if (dateIndex == 1)
			$("#removeDatetime").show();
	
		return false;
	})
	
	$("#removeDatetime").click(function() {
		if (dateIndex > 0) {
			$('#newDate' + dateIndex).remove();
			dateIndex--;

			if (dateIndex == 0)
				$("#removeDatetime").hide();
		}
		
		
		return false;
	})
	
	$("#loadAvailableRooms").click(function(){
		var date = [];
		var begin_time = [];
		var end_time = [];
		var repetition = getRepetitionValue();
		var end_date = $('#DateUntil').val();
		
		var invalidData = true;
		for (var i = 0; i <= dateIndex; i++) {
			var dateId = "#Date" + i + "Date";
			var beginTimeId = "#Date" + i + "BeginTime";
			var endTimeId = "#Date" + i + "EndTime";
			
			date[i] = $(dateId).val();
			begin_time[i] = $(beginTimeId).val();
			end_time[i] = $(endTimeId).val();
		
			$('#Date' + i + 'Date').parent().removeClass('error');
			$('#Date' + i + 'Date').next('.error-message').remove();
			
			if (validateInputItem(dateId, date[i]) == false |
				validateInputItem(beginTimeId, begin_time[i]) == false |
				validateInputItem(endTimeId, end_time[i]) == false) {
				invalidData = false;
				continue;
			}
			
			if (validateReservationDateHour(date[i], begin_time[i], end_time[i], i) ==  false) {
				invalidData = false;
				continue;
			}

			if (repetition != 'none' && $.datepicker.parseDate('dd/mm/yy', date[i]) > $.datepicker.parseDate('dd/mm/yy', end_date)) {
				$('#Date' + i + 'Date').parent().addClass('error');
				$('#Date' + i + 'Date').parent().append('<div class=\'error-message\'>Deve ser antes da data limite.</div>');
				invalidData = false;
			}
		}
		
		$('#DateUntil').parent().removeClass('error');
		$('#DateUntil').next('.error-message').remove();
		if(repetition != 'none' && end_date == "") {
			$('#DateUntil').parent().addClass('error');
			$('#DateUntil').parent().append('<div class=\'error-message\'>Não deve ser vazio.</div>');
			invalidData = false;
		}
		
		if (invalidData == false)
			return false;
		
		capacity = $("#DateCapacity").val();	
		
		var json = $.toJSON({'date': date, 'begin_time': begin_time, 'end_time': end_time, 'capacity': capacity, 'repetition': repetition, 'until_date': end_date});
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
                   var link = 'link aqui';//'<a href=\'/reservadesalas/Reservations/createReservation/'+ idRoom +'/'+ date.replace(/\//g, '-') +'/'+ begin_time.replace(':', '-') +'/'+ end_time.replace(':', '-') + '\' >' + name + ' - ' + number + '</a>';
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