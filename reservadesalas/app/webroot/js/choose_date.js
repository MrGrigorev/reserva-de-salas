$(document).ready(function() {
	$("#DateDate").datepicker();
	
	$("#DateBeginTime").timepicker({});
	
	$("#DateEndTime").timepicker({});
	
	$("#loadAvailableRooms").click(function(){
		/* TODO: make controller */
		var date = $("#DateDate").val();
		var begin_time = $("#DateBeginTime").val();
		var end_time = $("#DateEndTime").val();
		$.ajax({
           type: 'POST',
           dataType: 'json',
           url: '/Reservations/loadAvailableRooms',
           data: {date: date, begin_time: begin_time, end_time: end_time},
           success: function(data) {
               $('#availableRooms').html('');
               options = '';
               for (i in data) {
                   room = data[i];
                   options += '<p>'+ room +'</p>';
               }
               $('#availableRooms').html(options);
           }
        });
		return false;
	})
});