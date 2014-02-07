<?php
/*----------------------------------------------/
/=== Default Add-On Setting - Must Available ===/
/----------------------------------------------*/

// Add-On Name (it's recommended to not more than 24 characters)
$addon_name = "User CP Tags";
// Add-On Version (number and dot only, ie. 2.1)
$addon_version = "1.0";
// Add-On Description (Details of Add-on, HTML is allowed)
$addon_description = <<<DESC_CNT
Insert custom tags in your user cp pages!

Example:
<b>&#123;&#36;usercptags_myreferrals&#125;</b> to display members total direct referrals (based from the referrer id)
<b>&#123;&#36;usercptags_referrals&#125;</b> to display members total referrals
<b>&#123;&#36;usercptags_messages&#125;</b> to display total members messages
DESC_CNT;
?>