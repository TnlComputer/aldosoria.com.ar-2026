// JavaScript Document
$(document).ready(function(){
	$('.up').hide();
	
	$(window).scroll(function(){
		if($(this).scrollTop() > 100	){
			$('.up').fadeIn('1000');
		}else{
			$('.up').fadeOut('1000');
		}		
	});
	
	$('.up').click(function(){
		$('body, html').animate({
			screenTop: 0
		}, 150);
	});
});