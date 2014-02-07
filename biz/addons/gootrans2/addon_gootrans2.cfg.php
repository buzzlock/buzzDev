<?php
/*----------------------------------------------/
/=== Add-On Setting                          ===/
/----------------------------------------------*/

// Google translator code snippet
$addon_gootrans2_snippet = <<<gTransCode

<div id="google_translate_element"></div><script>
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: 'auto',
    includedLanguages: 'ar,bg,da,nl,en,tl,fr,de,el,hi,hu,id,it,ja,pt,ru,es,sv,tr,uk',
    autoDisplay: false,
    layout: google.translate.TranslateElement.InlineLayout.SIMPLE
  }, 'google_translate_element');
}
</script>
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

gTransCode;

// Reset default languages selector
$TMPL['current_language'] = '';
$TMPL['language_selector'] = '';
?>