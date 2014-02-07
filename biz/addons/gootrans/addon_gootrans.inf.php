<?php
/*-------------------------------------------------------/
/===    Default Add-On Setting - Must be Available    ===/
/ Special Characters: { = &#123; and } = &#125;          /
/-------------------------------------------------------*/

// Add-On Name (it's recommended to not more than 24 characters)
$addon_name = "Google Translator (deprecated)";
// Add-On Version (number and dot only, ie. 2.1)
$addon_version = "1.0";
// Add-On Description (Details of Add-on, HTML is allowed)
$addon_description = <<<DESC_CNT
Replace the default languages selector using the Google Translator API.

Please note that the translator result may different since the words translated by machine.

Important: Google Translate API v1 was officially deprecated on May 26, 2011; it was shut off completely on December 1, 2011. Please visit http://code.google.com/apis/language/translate/v1/getting_started.html for details.
	
Usage:
Insert <b>&#123;&#36;addon_gootrans_text&#125;</b> and <b>&#123;&#36;addon_gootrans_flags&#125;</b> tag in your template file.

DESC_CNT;
?>