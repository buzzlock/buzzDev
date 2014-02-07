$(document).ready(function() {

	// START --- Sidebar close and open (with cookies)

	function close_sidebar() {
		$("#open_sidebar").show();
		$("#close_sidebar, #dothe_leftsidebar").hide(30);
		//$("#close_sidebar").hide();
		//document.getElementById('dothe_leftsidebar').style.display = 'none';
	}

	function open_sidebar() {
		$("#open_sidebar").hide();
		$("#close_sidebar, #dothe_leftsidebar").show(30);
		//$("#close_sidebar").show();
		//document.getElementById('dothe_leftsidebar').style.display = 'block';
	}

	$('#close_sidebar').click(function(){
		close_sidebar();
		if($.browser.webkit) {
		    location.reload();
		}
		$.cookie('sidebar', 'closed' );
	});
	
	$('#open_sidebar').click(function(){
		open_sidebar();
		if($.browser.webkit) {
		    location.reload();
		}
		$.cookie('sidebar', 'open' );
	});
	
	var sidebar = $.cookie('sidebar');

		if (sidebar == 'closed') {
			close_sidebar();
	    };

		if (sidebar == 'open') {
			open_sidebar();
	    };
		
		if (!sidebar) {
			$('#open_sidebar').hide();
		};

	// END --- Sidebar close and open (with cookies)

});

function isHideShow(isform, isval, boole) {
	if (boole == true) {
		if (isform.value == isval) {
			document.getElementById('id_HideShow').style.display = 'block';
		} else {
			document.getElementById('id_HideShow').style.display = 'none';
		}
	} else {
		if (isform.value == isval) {
			document.getElementById('id_HideShow').style.display = 'none';
		} else {
			document.getElementById('id_HideShow').style.display = 'block';
		}
	}
}

function isHideShow2(isform, isval, boole, elemId) {
	var isval = isval.replace(' ', '');
	var isval_arr = isval.split("|"); 

	function inArray(needle, haystack) {
		var length = haystack.length;
		for(var i = 0; i < length; i++) {
			if(haystack[i] == needle) return true;
		}
		return false;
	}
	
	if (boole == true) {
		if (inArray(isform.value, isval_arr)) {
			document.getElementById(elemId).style.display = 'block';
		} else {
			document.getElementById(elemId).style.display = 'none';
		}
	} else {
		if (inArray(isform.value, isval_arr)) {
			document.getElementById(elemId).style.display = 'none';
		} else {
			document.getElementById(elemId).style.display = 'block';
		}
	}

}
