$(document).ready(function() {
	
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		m++;
		var y = date.getFullYear();
		
		var room_id = $('#room_id').val();
		
		function getEventData (month, year) {
			var events = [];
			/* get reservation via ajax */
			$.ajax({
				type: 'POST',
				dataType: 'json',
				async: false,
				url: '/reservadesalas/Rooms/getRoomReservations',
				data: {room_id: room_id, month: month, year: year},
				success: function(data) {
					for (i = 0; i < data.length; i++) {
						start_date = new Date(data[i]['start_time']);
						end_date = new Date(data[i]['end_time']);
						events[i] = {
							title: data[i]['info'], 
							start: start_date, 
							end: end_date, 
							allDay: false
						};
					}
				},
				error: function(jqXHR, textStatus, errorThrown){
					alert ('Erro. Tente novamente: ' + textStatus);
				}
			});
			return events;
		}
		
		var events = getEventData(m, y);
		var calendar = $('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month'
			},
			editable: false,
			events: events
		});
		
		
		$('.fc-button-next').click(function() {
			var date = calendar.fullCalendar('getDate');
			var year = date.getFullYear();
			var month = date.getMonth();
			month++;
		    var events = getEventData(month, year);
		    calendar.fullCalendar('removeEvents');
		    calendar.fullCalendar('addEventSource', events);
		    
		});
		$('.fc-button-prev').click(function() {
			var date = calendar.fullCalendar('getDate');
			var year = date.getFullYear();
			var month = date.getMonth();
			month++;
		    var events = getEventData(month, year);
		    calendar.fullCalendar('removeEvents');
		    calendar.fullCalendar('addEventSource', events);
		});
		
		$('.fc-button-today').click(function() {
		    var events = getEventData(m, y);
		    calendar.fullCalendar('removeEvents');
		    calendar.fullCalendar('addEventSource', events);
		});
		
	});