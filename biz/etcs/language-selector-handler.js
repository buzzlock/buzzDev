/*
 * Uses the Language Selector 
 * to offer your Website in multiple languages
 */
$(document).ready(function(){
	$.translate(function(){ 
		function translateTo( EZGtoLang ){ 
			originalColor = '#262c33';
			var tlc = $.translate().toLanguageCode 
			if( tlc( EZGtoLang ) == "en" && tlc($.cookie("EZGtoLang")) == "en") {
			  $('#flags ul').find('a').removeClass('load');
			  return;
			}
			$('#article').translate( 'en', EZGtoLang, {  
				not: '#jq-translate-ui', 
				fromOriginal:true,   
				start: function(){ 
					$('#article').css('color', '#a0a8b2');
					$('#throbber').show() 
				},   
				complete: function(){ 
					$('#article').css('color', originalColor);
					$('#throbber').hide();
					$('#flags ul').find('a.load').removeClass('load').addClass('finish');
				},  
				error: function(){ 
					$('#article').css('color', originalColor);
					$('#throbber').hide() 
				} 
			}); 
		} 
		$('#flags ul').find('a').click(function(){
			$('#flags ul').find('a').removeClass('finish');
			$(this).addClass('load');
			var lang = $(this).attr('id');
			translateTo( lang );
			$.cookie('EZGtoLang', lang );
			return false;
		}); 
		var EZGtoLang = $.cookie('EZGtoLang'); 
		if( EZGtoLang ) translateTo( EZGtoLang ); 
	}); 
})