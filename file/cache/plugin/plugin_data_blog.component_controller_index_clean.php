<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = '?>

<script type="text/javascript">
<?php if (!Phpfox::getUserParam(\'blog.can_import_blog\')):?>
$("#section_menu").find(\'ul li a[href$="blog/import"]\').eq(0).css(\'display\', \'none\');
$("#section_menu").find(\'ul li a[href$="blog/import/"]\').eq(0).css(\'display\', \'none\');
<?php endif;?>
<?php if (!Phpfox::getUserParam(\'blog.can_export_blog\')):?>
$("#section_menu").find(\'ul li a[href$="blog/export"]\').eq(0).css(\'display\', \'none\');
$("#section_menu").find(\'ul li a[href$="blog/export/"]\').eq(0).css(\'display\', \'none\');
<?php endif;?>
</script> '; ?>