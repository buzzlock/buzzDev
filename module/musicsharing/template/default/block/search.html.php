<?php
defined('PHPFOX') or exit('NO DICE!');

?>
<form method="post" accept-charset="utf-8"  action="{$actionForm}" >
<div class="p_bottom_15">

{phrase var='musicsharing.keywords'} :
<div class="p_4">
    {$aFilters.title}
</div>
{if isset($aFilters.type)}
<div class="p_top_4">
   {$type_title}
    <div class="p_4">
        {$aFilters.type}
    </div>
</div>
{/if}
{if isset($aFilters.sort)}
<div class="p_top_4">
   {$sort_title}
    <div class="p_4">
        {$aFilters.sort}
    </div>
</div>
{/if}
<div class="p_top_8">
    <input type="hidden" value="search_" name="se"/>
    <input type="submit" name="search[submit]" value="{phrase var='core.submit'}" class="button" />
    <input type="submit" name="search[reset]" value="{phrase var='core.reset'}" class="button" />
</div>

</div>
</form>
