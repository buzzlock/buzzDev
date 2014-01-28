var initMenuLeftCounter = 0;

$Behavior.initMenuLeft = function(){
	if(initMenuLeftCounter > 0){
		return true;
	}
	
   initMenuLeftCounter = 1;
	var addEvent = function addEvent(element, eventName, func) {
		if (element.addEventListener) {
	    	return element.addEventListener(eventName, func, false);
	    } else if (element.attachEvent) {
	        return element.attachEvent("on" + eventName, func);
	    }
	};
	
	var body = $('body');
	
	addEvent(document.getElementById('mobile_header_home'), 'click', function(){
		if ( body.hasClass('snapjs-left') ) {
			body.removeClass('snapjs-left');
		} else {
			body.addClass('snapjs-left');
		}
	});

	if($('#ym-open-right').length){
		addEvent(document.getElementById('ym-open-right'), 'click', function(){
			if ( body.hasClass('snapjs-right') ) {
				body.removeClass('snapjs-right');
			} else {
				body.addClass('snapjs-right');
			}
		});
	}
	if($('#ym-open-menu-right').length){
		addEvent(document.getElementById('ym-open-menu-right'), 'click', function(){
			if ( body.hasClass('snapjs-right') ) {
				body.removeClass('snapjs-right');
			} else {
				body.addClass('snapjs-right');
			}
		});
	}
	if($('#ym-open-edit-right').length){
		addEvent(document.getElementById('ym-open-edit-right'), 'click', function(){
			if ( body.hasClass('snapjs-right') ) {
				body.removeClass('snapjs-right');
			} else {
				body.addClass('snapjs-right');
			}
		});
	}
	
	/* Prevent Safari opening links when viewing as a Mobile App */
	(function (a, b, c) {
	    if(c in b && b[c]) {
	        var d, e = a.location,
	            f = /^(a|html)$/i;
	        a.addEventListener("click", function (a) {
	            d = a.target;
	            while(!f.test(d.nodeName)) d = d.parentNode;
	            "href" in d && (d.href.indexOf("http") || ~d.href.indexOf(e.host)) && (a.preventDefault(), e.href = d.href)
	        }, !1)
	    }
	})(document, window.navigator, "standalone");
}