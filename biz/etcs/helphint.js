/*
* Created: Nov 25th, 2009. This notice must stay intact for usage 
* Author: Dynamic Drive at http://www.dynamicdrive.com/
* Visit http://www.dynamicdrive.com/ for full source code
* Modified: Dec 2010 for necessary requirements
*/


var helphintku={
	helphintoffsets: [20, -30], //additional x and y offset from mouse cursor for helphints
	fadeinspeed: 150, //duration of fade effect in milliseconds
	rightclickstick: true, //sticky helphint when user right clicks over the triggering element (apart from pressing "s" key) ?
	stickybordercolors: ["#E2931B", "#555555"], //border color of helphint depending on sticky state
	//stickynotice1: ["Press \"s\"", "or right click", "to sticky box"], //customize helphint status message
	stickynotice1: ["Right click to sticky this HintBox"], //customize helphint status message
	stickynotice2: "Click outside this HintBox to hide it", //customize helphint status message

	//***** NO NEED TO EDIT BEYOND HERE

	isdocked: false,

	/*
	positionhelphint:function($, $helphint, e){
		var x=e.pageX+this.helphintoffsets[0], y=e.pageY+this.helphintoffsets[1]
		var tipw=$helphint.outerWidth(), tiph=$helphint.outerHeight(), 
		x=(x+tipw>$(document).scrollLeft()+$(window).width())? x-tipw-(helphintku.helphintoffsets[0]*2) : x
		y=(y+tiph>$(document).scrollTop()+$(window).height())? $(document).scrollTop()+$(window).height()-tiph-10 : y
		//y=(y+tiph>$(document).scrollTop()+$(window).height())? $(document).scrollTop()+10 : y
		$helphint.css({left:x, top:y})
	},
	*/
	positionhelphint:function($, $helphint, e){
		var x=e.pageX+this.helphintoffsets[0], y=e.pageY+this.helphintoffsets[1]
		var tipw=$helphint.outerWidth(), tiph=$helphint.outerHeight(), 
		x=(x+tipw>$(document).scrollLeft()+$(window).width())? x-tipw-(helphintku.helphintoffsets[0]*2) : x
		
		if (y+tiph > $(document).scrollTop()+$(window).height()) {
			if (tiph < $(window).height()) {
				y = $(document).scrollTop()+$(window).height()-tiph-10;
			} else {
				y = 10;
			}
		}
		
		$helphint.css({left:x, top:y})
	},
	
	showbox:function($, $helphint, e){
		$helphint.fadeIn(this.fadeinspeed)
		this.positionhelphint($, $helphint, e)
	},

	hidebox:function($, $helphint){
		if (!this.isdocked){
			$helphint.stop(false, true).hide()
			$helphint.css({borderColor:'#E2931B'}).find('.stickystatus:eq(0)').css({background:this.stickybordercolors[0]}).html(this.stickynotice1)
		}
	},

	dockhelphint:function($, $helphint, e){
		this.isdocked=true
		$helphint.css({borderColor:'#555555'}).find('.stickystatus:eq(0)').css({background:this.stickybordercolors[1]}).html(this.stickynotice2)
	},


	init:function(targetselector, tipid){
		jQuery(document).ready(function($){
			var $targets=$(targetselector)
			var $helphint=$('#'+tipid).appendTo(document.body)
			if ($targets.length==0)
				return
			var $alltips=$helphint.find('div.hintbox')
			if (!helphintku.rightclickstick)
				helphintku.stickynotice1[1]=''
			helphintku.stickynotice1=helphintku.stickynotice1.join(' ')
			helphintku.hidebox($, $helphint)
			$targets.bind('mouseenter', function(e){
				$alltips.hide().filter('#'+$(this).attr('data-helphint')).show()
				helphintku.showbox($, $helphint, e)
			})
			$targets.bind('mouseleave', function(e){
				helphintku.hidebox($, $helphint)
			})
			$targets.bind('mousemove', function(e){
				if (!helphintku.isdocked){
					helphintku.positionhelphint($, $helphint, e)
				}
			})
			$helphint.bind("mouseenter", function(){
				helphintku.hidebox($, $helphint)
			})
			$helphint.bind("click", function(e){
				e.stopPropagation()
			})
			$(this).bind("click", function(e){
				if (e.button==0){
					helphintku.isdocked=false
					helphintku.hidebox($, $helphint)
				}
			})
			$(this).bind("contextmenu", function(e){
				if (helphintku.rightclickstick && $(e.target).parents().andSelf().filter(targetselector).length==1){ //if oncontextmenu over a target element
					helphintku.dockhelphint($, $helphint, e)
					return false
				}
			})
			$(this).bind('keypress', function(e){
				var keyunicode=e.charCode || e.keyCode
				if (keyunicode==122){ //if "z" key was pressed
					helphintku.dockhelphint($, $helphint, e)
				}
			})
		}) //end dom ready
	}
}

//helphintku.init("targetElementSelector", "helphintcontainer")
helphintku.init("*[data-helphint]", "divhelphintku")