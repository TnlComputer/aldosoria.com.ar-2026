
/* # Validando Nick si ya existe
============================================*/
$(document).on('click', function(e) {
	e.preventDefault();
	
	var nickTxt = $('#nickTxt').val();
	
	$.ajax({
		type: "POST",
		url: "checkNick.php",
		data: ('nickTxt='+nickTxt),
		beforeSend: function(){
			$('#imagen').show();
			$('mensajes').html('Chequeando disponibilidad....')
		},
		success: function(respuesta){
			$('#imagen').hide();
			if (respuesta==1){
				$('#mensajes').html('Nic Disponible');
			}
			else{
				$('#mensajes').html('Nic No Disponible');
				$('#nickTxt').val('');
			}
		}		
	});
});
