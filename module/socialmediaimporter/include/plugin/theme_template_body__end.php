<?php

if(Phpfox::isModule('socialmediaimporter') && defined('PHPFOX_IS_PAGES_VIEW') && defined('PAGE_TIME_LINE') && Phpfox::getLib('request')->get('req3')=='photo')
{
?>
<script type="text/javascript">
    $Behavior.createImportPhotoButton = function()
    {
        $('.profile_header_timeline').find('#section_menu>ul:last').append('<li><a href="<?php echo Phpfox::getLib('url')->makeUrl('socialmediaimporter.connect'); ?>" class="ajax_link"><?php echo Phpfox::getPhrase('socialmediaimporter.import_photos'); ?></a></li>');
    };
</script>
<?php
}
