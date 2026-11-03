/* # Validando alta usuarios
============================================*/
$(document).ready(function() {
	$('#altaUser').validate({
		errorElement: 'span',
		rules: {
			claveTxt: {
				minlength: 8,
				required: true,
			},
			nombreTxt: {
				minlength: 4,
				required: true,
			},
			apellidoTxt: {
				minlength: 4,
				required: true,
			},
			emailTxt: {
				required: true,
				email: true,
			},
			celularTxt: {
				minlength: 10,
				required: true,
			},
			telHogTxt: {
				minlength: 8,
				required: false,
			},
			telLabTxt: {
				minlength: 8,
				required: false,
			},
			dirHogTxt: {
				minlength: 8,
				required: false,
			},			
			dirlLabTxt: {
				minlength: 8,
				required: false,
			},
			horContTxt: {
				minlength: 8,
				required: false,
			},
		},
		highlight: function(element) {
			$(element)
				.closest('.control-group')
				.removeClass('success')
				.addClass('error');
		},
		success: function(element) {
			element
				.text('')
				.addClass('help-inline')
				.closest('.control-group')
				.removeClass('error')
				.addClass('success');
		},
	});
});

/* # Validando Nick si ya existe
============================================*/
//$(function(){
//	$('#enviar').on('click', function(e) {
//		e.preventDefault();
//
//		var nickTxt = $('#nickTxt').val();
//		var emailTxt = $('#emailTxt').val();	
//		var claveTxt = $('#claveTxt').val();	
//		var dirHogTxt = $('#dirHogTxt').val();	
//		var dirLabTxt = $('#dirLabTxt').val();	
//		var telHogTxt = $('#telHogTxt').val();	
//		var telLabTxt = $('#telLabTxt').val();	
//		var celularTxt = $('#celularTxt').val();	
//		var nombreTxt = $('#nombreTxt').val();	
//		var apellidoTxt = $('#apellidoTxt').val();	
//		var horContTxt = $('#horContTxt').val();	
//		
//
//		$.ajax({
//			type: "POST",
//			url: "checkNick.php",
//			data: ('nickTxt='+nickTxt+'emailTxt='+emailTxt+'dirHogTxt='+dirHogTxt+'dirLabTxt='+dirLabTxt+'telHogTxt='+telHogTxt+'telLabTxt='+telLabTxt+'celularTxt='+celularTxt+'nombreTxt='+nombreTxt+'apellidoTxt='+apellidoTxt+'horContTxt='+horContTxt+'claveTxt='+claveTxt),
//			beforeSend: function(){
//				$('#imagen').show();
//				$('mensajes').html('Chequeando disponibilidad....')
//			},
//			success: function(respuesta){
//				$('#imagen').hide();
//				if (respuesta==1){
//					$('#mensajes').html('Nic Disponible');
//				}
//				else{
//					$('#mensajes').html('Nic No Disponible');
//					$('#nickTxt').val('');
//				}
//				if (respuesta==2){
//					$('#mensajes').html('Email Disponible');
//				}
//				else{
//					$('#mensajes').html('Email No Disponible');
//					$('#emailTxt').val('');
//				}
//				
//			}		
//		});
//	});
//})			


///* # Validando Email si ya existe
//============================================*/
//$(function(){
//	$('#enviar').on('click', function(e) {
//		e.preventDefault();
////$(document).ready(function() {
//		var emailTxt = $('#emailTxt').val();
//
//		$.ajax({
//			type: "POST",
//			url: "checkEmail.php",
//			data: ('emailTxt='+emailTxt),
//			beforeSend: function(){
//				$('#imagen').show();
//				$('mensajes').html('Comprobando Email....')
//			},
//			success: function(respuesta){
//				$('#imagen').hide();
//				if (respuesta==1){
//					$('#mensajes').html('Email Correcto');
//				}
//				else{
//					$('#mensajes').html('Email ya esta usado');
//					$('#emailTxt').val('');
//				}
//			}		
//		});
//	});
//});