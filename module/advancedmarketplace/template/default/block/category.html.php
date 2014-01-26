<?php

defined('PHPFOX') or exit('NO DICE!');

?>
{*template file='core.block.category'*}
{if !$bIsProfile}
	<div class="sub_section_menu rwmenu">
		<ul>
		{foreach from=$aCategories key=iKey item=aCategory}
			<li class="submenu category {if $aCategory.category_id == $iCurrentCategoryId}active{/if}" style="position:relative;">
				<?php ?>
				<a title="{$aCategory.name|parse|clean}" href="{$aCategory.url}{*if Phpfox::getLib('request')->get('view') != ''}view_{request var='view'}/{/if*}">
					{$aCategory.name}
				</a>
				{if count($aCategory.children) > 0 && $aCategory.category_id == $iTopParentId}
					{*template file='advancedmarketplace.block.subcategory' aCategories=$aCategory.children*}
					<?php
						
						// if($this->_aVars["aCategory"]["category_id"] == $this->_aVars["iCurrentCategoryId"])
							// $this->_aVars["iCurrentLevel"] += 1;
							
						// $iLevel = $this->_aVars["iCurrentLevel"] + (($this->_aVars["iCurrentLevel"] == 0)?0:1);
						// echo $iLevel . " - " . $this->_aVars["aCategory"]["level"];
						PHPFOX::getService("advancedmarketplace")->buildSubCategory(
							$this->_aVars["aCategory"]["children"],
							$this->_aVars["iCurrentLevel"],
							$this->_aVars["iCurrentCategoryId"],
							$this->_aVars["iCurrentLevel"] + 1
						);
					?>
				{/if}
			</li>
		{/foreach}
		</ul>
	</div>
	{literal}
	<style type="text/css">
		.rwmenu .submenu {
			border-top: 1px solid #D7D7D7;
		}
	</style>
	<script language="javascript" type="text/javascript">
    $Behavior.initFlistCss = function() {
		$(".rwmenu").parent().css({
			"padding-top": "0px"
		});
    }
	</script>
	{/literal}
{/if}