/*
 *
 * Author: Teamwurkz Technologies Inc. (http://www.teamwurkz.com)
 *
 */


	var ttic = 1;
	var ttid = 4;	
	var tticurrSlide;
	var ttiselSlide ;

	setTimeout(ttislidePlay);	
	
	function ttislidePlay()
		{
			if (!ttiselSlide) {			
			tticurrSlide = ttic;
			$("#ttislide"+ttic).fadeIn(5000);
			$("#ttislidedesc"+ttic).animate({top:"280px"},2000);
			setTimeout(ttitagSlide,7000);
			}
		}
	
	function ttitagSlide()
		{
			if (ttiselSlide!='') {
			$("#ttislidedesc"+ttic).animate({top:"350px"},1000);
			$("#ttislide"+ttic).fadeOut(2000);
			ttic=ttic+1;
			ttid = $("#slideTotal").val();
			if ( ttic > ttid ) { ttic = 1; }
			ttislidePlay();			
			}
		}

