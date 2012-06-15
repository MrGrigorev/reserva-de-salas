$(document).ready(function() {
	var error = $("#error");
	
	$("#UserLoginForm").submit(function(e) {
		error.hide();
		e.preventDefault();
		var nusp = $('#UserNusp').val();
		var password = $('#UserPassword').val();
		var loginUrl = $("#loginUrl").val();
		$.ajax({
	           type: 'POST',
	           dataType: 'json',
	           url: loginUrl,
	           data: {nusp: nusp, password: password},
	           success: function(data) {
	        	   if (data.id > 0) {
	        		   var url = '/reservadesalas/Users/authorize/' + data.id + '/' + data.name + '/' + data.user_type;
	        		   window.redirect(url, 0);
	        	   }
	        	   else {
	        		   error.html(data.error);
	        		   error.show();
	        	   }
	           },
	           error: function(jqXHR, textStatus, errorThrown){
	        	   error.html(textStatus);
	        	   error.show();
	           }
	        });
	});
});