<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

?>


	<form method="post" action="{url link='current'}"  class="ynfr_add_edit_form"  id="ynfr_edit_campaign_sponsor_levels_form" onsubmit="" enctype="multipart/form-data">

		
		<div id="js_fundraising_block_sponsor_levels"class="js_fundraising_block page_section_menu_holder" style="display:none;">
			<div class="extra_info" >{phrase var='fundraising.sponsor_form_notice'} </div>

        <div id="ynfr_sponsor_holder">
            <div id="ynfr_sholder" class="ynfr_sample_holder" style="display: none">
                <div class="table">
                    <div class="table_left">
                        <table>
                            <tr>
                                <td>
                                    {phrase var='fundraising.amount'}:
                                </td>
                                <td>
                                    <input type="text" class="ynfr required number ynfr_sponsor_level_amount" name="val[sponsor_level][][amount]" value="" id="amount" size="30" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {phrase var='fundraising.description'}:
                                </td>
                                <td>
                                    <input type="text" class="ynfr required=" val[sponsor_level][][level_name]" value="" id="level_name" size="30" />
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="table_right">
                        <a href="#" onclick="ynfr_removeLevels(this);">{phrase var='fundraising.remove_upper'}</a>
                    </div>
                </div>
            </div>
            {if !$bIsEdit}
            <div id="ynfr_sholder">
                <div class="table">
                    <div class="table_left">
                        <table>
                            <tr>
                                <td>
                                    {phrase var='fundraising.amount'}:
                                </td>
                                <td>
                                    <input type="text" class="ynfr required number ynfr_sponsor_level_amount" name="val[sponsor_level][1][amount]" value="" id="amount" size="30" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {phrase var='fundraising.description'}:
                                </td>
                                <td>
                                    <input type="text" class="ynfr required" name="val[sponsor_level][1][level_name]" value="" id="level_name" size="30" />
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="table_right">
                        <a href="#" onclick="removeLevels(this);">{phrase var='fundraising.remove_upper'}</a>
                    </div>
                </div>
            </div>
            {else}
            {foreach from=$aForms.sponsor_level key=iKey item=aSponsor}
            {if isset($aSponsor.amount) && !empty($aSponsor.amount) && isset($aSponsor.level_name) && !empty($aSponsor.level_name) }
            <div id="ynfr_sholder">
                <div class="table">
                    <div class="table_left">
                        <table>
                            <tr>
                                <td>
                                    {phrase var='fundraising.amount'}:
                                </td>
                                <td>
                                    <input type="text" class="ynfr required number ynfr_sponsor_level_amount" name="val[sponsor_level][{$iKey}][amount]" value="{$aSponsor.amount}" id="amount" size="30" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {phrase var='fundraising.description'}:
                                </td>
                                <td>
                                    <input type="text" class="ynfr required" name="val[sponsor_level][{$iKey}][level_name]" value="{$aSponsor.level_name}" id="title" size="30" />
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="table_right">
                        <a href="#" onclick="ynfr_removeLevels(this);">{phrase var='fundraising.remove_upper'}</a>
						
                    </div>
                </div>
            </div>
            {/if}
            {/foreach}
            {/if}
        </div>

        <div class="table_clear">
            <input id="add_level" type="button" class="button" value="{phrase var='fundraising.add_level'}" onclick="ynfr_addMoreLevels();"/>
        </div>

        <div class="table_clear">
			<input type="submit" name="val[submit_sponsor_levels]" value="{phrase var='fundraising.save'}" class="button" onclick="$('.ynfr_sample_holder').remove();" />
            {if $bIsEdit && $aForms.is_draft == 1}
                <input type="submit" name="val[publish_sponsor_levels]" value="{phrase var='fundraising.publish'}" class="button"/>
            {/if}
		</div>
			</div>
	 </form>

<script type="text/javascript">
		
</script>

