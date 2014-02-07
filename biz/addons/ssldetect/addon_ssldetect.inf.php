<?php
/*-------------------------------------------------/
/=== Default Add-On Setting - Must be Available ===/
/ Special Characters: { = &#123; and } = &#125;    /
/-------------------------------------------------*/

// Add-On Name (it's recommended to not more than 24 characters)
$addon_name = "Detect SSL Connection Add-On";
// Add-On Version (number and dot only, ie. 2.1)
$addon_version = "1.0";
// Add-On Description (Details of Add-on, HTML is allowed)
$addon_description = <<<DESC_CNT
This addon will detect current SSL connection status.


If you want to using SSL connection, then you need to change your site url (in the <a href='index.php?a=admin&amp;b=settings'>General Settings</a> page) from <b>http://</b> to <b>http<font color=red>s</font>://</b>


Usage:
Insert <b>&#123;&#36;addon_ssldetect&#125;</b> tag in your template file or custom page content.
DESC_CNT;
?>