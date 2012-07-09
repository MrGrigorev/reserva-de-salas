$(document).ready(function() {
	$("#addResource").click(function() {
		var addResourceButton = $(this);
		var dates = $("#ReservationDates").val();
		var beginTimes = $('#ReservationBeginTimes').val();
		var endTimes = $('#ReservationEndTimes').val();
		var repetitions = $('#ReservationRepetitions').val();
		var untilDate = $('#ReservationUntil').val();
		
		var json = $.toJSON({'dates': dates, 'beginTimes': beginTimes, 'endTimes': endTimes, 'repetitions': repetitions, 'untilDate': untilDate});
		$.ajax({
           type: 'POST',
           dataType: 'json',
           url: '/reservadesalas/Resources/getAvailableResources',
           data: {data: json},
           success: function(data) {
               $('#availableResources').html("");
               var resources = $("#resources").find('table');
               var table = $("<table id='roomsTable'></table>");
               var header = $("<tr><td class='header'>Nome</td><td class='header'>Número de Patrimônio</td><td class='header'></td></tr>");
               table.append(header);

               $.each(data, function(i, value) {
            	   var name = value['Resource']['name'];
            	   var serial_number = value['Resource']['serial_number'];
            	   var resourceId = value['Resource']['id'];
            	   var link = "<a href='/reservadesalas/Resources/viewResource/" + resourceId + "' target='_blank'>" + name + "</a>";
            	   var row = $("<tr><td><input type='hidden' value='" + value['Resource']['id'] + "' />" + link + "</td><td>" + serial_number + "</td><td class='center'><button type='button' class='select'>Adicionar</button></td></tr>");
            	   row.find(".select").click(function() {
            		   var addButton = $(this);
            		   var line = $("<tr></tr>");
            		   var input = $("<input type='hidden' name='data[Resources][]' value='" + resourceId + "' />");
            		   line.append(input);
            		   var closeButton = $("<button>Remover</button>");
            		   closeButton.click(function() {
            			  line.remove();
            			  addButton.show();
            		   });
            		   line.append("<td>" + link + "</td><td>" + serial_number + "</td>");
            		   var cell = $("<td class='center'></td>");
            		   cell.append(closeButton);
            		   line.append(cell);
            		   resources.append(line);
            		   addButton.hide();
            	   });
            	   table.append(row);
               });
               addResourceButton.remove();
               $("#availableResources").append("<h2>Recursos disponíveis</h2>")
               $('#availableResources').append(table);
           },
           error: function(jqXHR, textStatus, errorThrown){
        	   alert ('Erro. Tente novamente: ' + textStatus);
           }
        });
		return false;
	})
});