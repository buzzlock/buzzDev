<?php
/*----------------------------------------------/
/=== Add-On Setting                          ===/
/----------------------------------------------*/

// How many new signup will displayed
$addon_latestsignup_show = 10;

// Display member status
$addon_latestsignup_show_status = 1;

// Scroller speed
$addon_latestsignup_speed = 500;

// Delay time between scrolling
$addon_latestsignup_pause = 1500;

// Animation for sliding
$addon_latestsignup_anim  = "fade";

// Stop scrolling on mouve over, true or false
$addon_latestsignup_mousepause = true;

// Set the container height, defai=ult 0 = disbale or off
$addon_latestsignup_height = 0;

// Scrolling direction, up (default) or down
$addon_latestsignup_direction = "down";

// Scrolling latest mebers style
$addon_latestsignup_style = <<<CSSADD_CNT
	<style type="text/css" media="all">
	#latestsignup-container
	{
		width: 250px; 
		margin: auto;
		margin-top: 30px;
		/*border: 2px solid #999999;*/
		padding: 5px;
	}
	 
	#latestsignup-container ul li div
	{
		border: 1px solid #aaaaaa;
		background: #ffffff;
	}
	</style>
CSSADD_CNT;
?>