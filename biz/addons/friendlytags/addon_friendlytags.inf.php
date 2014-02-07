<?php
/*----------------------------------------------/
/=== Default Add-On Setting - Must Available ===/
/----------------------------------------------*/

// Add-On Name (it's recommended to not more than 24 characters)
$addon_name = "Friendly Custom Tags";
// Add-On Version (number and dot only, ie. 2.1)
$addon_version = "1.7";
// Add-On Group [Admin | Client | All] default = (empty) = All
$addon_validfor = "";
// Add-On Description (Details of Add-on, HTML is allowed)
$addon_description = <<<DESC_CNT
Insert friendly tags in your pages!

Example:
<b>&#123;&#36;tagcfg1_site_name&#125;</b> for site name
<b>&#123;&#36;tagcfg2_payplan&#125;</b> for payplan name
...

<b>&#123;&#36;tagspr_fullname&#125;</b> for sponsor full name
<b>&#123;&#36;tagref_username&#125;</b> for referrer username
...
etc.

<strong>Using friendly tags in the User CP (member area) pages!</strong>
If you want to insert the member or sponsor or referrer tags inside member area, then you need to use "tagusr_" or "tagmyspr_" or "tagmyref_" tags.

Example:
<b>&#123;&#36;tagusr_fullname&#125;</b> for member full name
<b>&#123;&#36;tagusr_email&#125;</b> for member email address
...

<b>&#123;&#36;tagmyspr_fullname&#125;</b> for sponsor full name
<b>&#123;&#36;tagmyspr_email&#125;</b> for sponsor email address
...

<b>&#123;&#36;tagmyref_username&#125;</b> for referrer username
<b>&#123;&#36;tagmyref_country&#125;</b> for referrer country code
...
etc.
DESC_CNT;
?>