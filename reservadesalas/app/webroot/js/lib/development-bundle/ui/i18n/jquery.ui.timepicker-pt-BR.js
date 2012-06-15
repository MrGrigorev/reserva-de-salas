/* Brazilian initialisation for the jQuery UI date picker plugin. */
jQuery(function($){
	$.timepicker.regional['pt-BR'] = {
		currentText: 'Agora',
		closeText: 'Confirmar',
		ampm: false,
		amNames: ['AM', 'A'],
		pmNames: ['PM', 'P'],
		timeFormat: 'hh:mm tt',
		timeSuffix: '',
		timeOnlyTitle: 'Escolha um horário',
		timeText: 'Horário',
		hourText: 'Hora',
		minuteText: 'Minuto',
		secondText: 'Segundo',
		millisecText: 'Milisegundo',
		timezoneText: 'Fuso horário',
	};
	$.timepicker.setDefaults($.timepicker.regional['pt-BR']);
});