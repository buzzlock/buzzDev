<?php ?>
<script type="text/javascript">
    oCore['core.disable_hash_bang_support'] = 1;
</script>
<?php
if (Phpfox::isModule('musicsharing'))
{
    Phpfox::getLib('setting')->setParam('musicsharing.url_image', Phpfox::getParam('core.url_pic') . 'musicsharing' . PHPFOX_DS);
}
?>

