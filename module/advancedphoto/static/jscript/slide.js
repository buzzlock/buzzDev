$Behavior.ynadvphotoInitSlide = function() {
	Galleria.loadTheme(oParams.sJsHome + 'module/advancedphoto/static/jscript/galleria.classic.min.js');
	Galleria.run('#galleria', {
		autoplay: 5000
	});
}
$Behavior.ynadvphotoOverrideLoadInit = function() {
	$Core.loadInit = function() {
		debug('$Core.loadInit() Loaded');
		if($('#galleria').length != 0) {
			$('*:not(#galleria *)').unbind();	
		}
		else {
			$('*').unbind();
		}
		$.each($Behavior, function() {		
			this(this);
		});
	}
}
