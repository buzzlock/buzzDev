$Behavior.advphotoEditReady = function() {
	var trace = function() {
		args = arguments;
		if(console) {
			// console.clear();
			for(index in args) {
				console.log(args[index]);
			}
		}
	};
	$('#slider').nivoSlider({
		"beforeChange": function() {
			// trace(this);
		},
		effect : $('#slider').attr("eff"),
		slices : 10,
		boxCols : 12,
		boxRows : 8,
		animSpeed : 1000,
		pauseTime : 2000,
		directionNav: true,
		controlNav: true,
		controlNavThumbs: true,
		pauseOnHover: true,
		preloadImageURL: "buff",
		manualAdvance: false
	});
	
	$("div.effects").children().click(function(evt){
		evt.preventDefault();
		trace ($(this).attr("ref"));
		$("div.effects .active").removeClass("active");
		$(this).addClass("active");
		$('#slider').data('nivoslider').setEffect($(this).attr("ref"));
		$("input[name=\"val[yn_slide_type]\"]").val($(this).attr("ref"));
		
		return false;
	});
};