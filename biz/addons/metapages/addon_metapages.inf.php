<?php
/*-------------------------------------------------------/
/===    Default Add-On Setting - Must be Available    ===/
/ Special Characters: { = &#123; and } = &#125;          /
/-------------------------------------------------------*/

// Add-On Name (it's recommended to not more than 24 characters)
$addon_name = "Custom Meta Pages";
// Add-On Version (number and dot only, ie. 2.1)
$addon_version = "1.0";
// Add-On Description (Details of Add-on, HTML is allowed)
$addon_description = <<<DESC_CNT
Customize the meta description and keywords contents for the <b>public</b> pages.

Usage:
Update the addon configuration and insert the custom description and keywords tags using followng syntax:

- <b>&#36;addon_metapages_description[</b>PAGE_NAME<b>]</b> = "DESCRIPTION CONTENT";

- <b>&#36;addon_metapages_keywords[</b>PAGE_NAME<b>]</b> = "KEYWORD LIST";

Note: Empty the content to use the default value.

DESC_CNT;
?>