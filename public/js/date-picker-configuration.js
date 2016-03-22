var currentDate = new Date();

			$.datepicker.regional['es'] = {
				closeText: 'Cerrar',
				prevText: 'Anterior',
				nextText: 'Siguiente',
				currentText: 'Hoy',
				monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
				monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
				dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
				dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
				dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
				weekHeader: 'Sm',
				dateFormat: 'yy-mm-d', 
				yearSuffix: '',
				setDate: currentDate,
			};
			$.datepicker.setDefaults($.datepicker.regional['es']);
			$('.datepicker').prop('readonly', true).css('background-color', 'white').datepicker({dateFormat: 'yy-mm-dd'});