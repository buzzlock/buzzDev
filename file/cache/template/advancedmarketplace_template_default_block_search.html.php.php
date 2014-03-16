<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 16, 2014, 7:30 pm */ ?>
<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author			Raymond Benc
 * @package 		Phpfox
 * @version 		$Id: block.html.php 6820 2013-10-22 13:05:35Z Raymond_Benc $
 */
 
 

 if (( isset ( $this->_aVars['sHeader'] ) && ( ! PHPFOX_IS_AJAX || isset ( $this->_aVars['bPassOverAjaxCall'] ) || isset ( $this->_aVars['bIsAjaxLoader'] ) ) ) || ( defined ( "PHPFOX_IN_DESIGN_MODE" ) && PHPFOX_IN_DESIGN_MODE ) || ( Phpfox ::getService('theme')->isInDnDMode())): ?>

<div class="block<?php if (( defined ( 'PHPFOX_IN_DESIGN_MODE' ) || Phpfox ::getService('theme')->isInDnDMode()) && ( ! isset ( $this->_aVars['bCanMove'] ) || ( isset ( $this->_aVars['bCanMove'] ) && $this->_aVars['bCanMove'] == true ) )): ?> js_sortable<?php endif;  if (isset ( $this->_aVars['sCustomClassName'] )): ?> <?php echo $this->_aVars['sCustomClassName'];  endif; ?>"<?php if (isset ( $this->_aVars['sBlockBorderJsId'] )): ?> id="js_block_border_<?php echo $this->_aVars['sBlockBorderJsId']; ?>"<?php endif;  if (defined ( 'PHPFOX_IN_DESIGN_MODE' ) && Phpfox ::getLib('module')->blockIsHidden('js_block_border_' . $this->_aVars['sBlockBorderJsId'] . '' )): ?> style="display:none;"<?php endif; ?>>
<?php if (! empty ( $this->_aVars['sHeader'] ) || ( defined ( "PHPFOX_IN_DESIGN_MODE" ) && PHPFOX_IN_DESIGN_MODE ) || ( Phpfox ::getService('theme')->isInDnDMode())): ?>
		<div class="title <?php if (defined ( 'PHPFOX_IN_DESIGN_MODE' ) || Phpfox ::getService('theme')->isInDnDMode()): ?>js_sortable_header<?php endif; ?>">		
<?php if (isset ( $this->_aVars['sBlockTitleBar'] )): ?>
<?php echo $this->_aVars['sBlockTitleBar']; ?>
<?php endif; ?>
<?php if (( isset ( $this->_aVars['aEditBar'] ) && Phpfox ::isUser())): ?>
			<div class="js_edit_header_bar">
				<a href="#" title="<?php echo Phpfox::getPhrase('core.edit_this_block'); ?>" onclick="$.ajaxCall('<?php echo $this->_aVars['aEditBar']['ajax_call']; ?>', 'block_id=<?php echo $this->_aVars['sBlockBorderJsId'];  if (isset ( $this->_aVars['aEditBar']['params'] )):  echo $this->_aVars['aEditBar']['params'];  endif; ?>'); return false;"><?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'misc/application_edit.png','alt' => '','class' => 'v_middle')); ?></a>				
			</div>
<?php endif; ?>
<?php if (true || isset ( $this->_aVars['sDeleteBlock'] )): ?>
			<div class="js_edit_header_bar js_edit_header_hover" style="display:none;">
<?php if (Phpfox ::getService('theme')->isInDnDMode() && ( ( isset ( $this->_aVars['bCanMove'] ) && $this->_aVars['bCanMove'] ) || ! isset ( $this->_aVars['bCanMove'] ) )): ?>
					<a href="#" onclick="if (confirm('<?php echo Phpfox::getPhrase('core.are_you_sure', array('phpfox_squote' => true)); ?>')){
					$(this).parents('.block:first').remove(); $.ajaxCall('core.removeBlockDnD', 'sController=' + oParams['sController'] 
					+ '&amp;block_id=<?php if (isset ( $this->_aVars['sDeleteBlock'] )):  echo $this->_aVars['sDeleteBlock'];  else: ?> <?php echo $this->_aVars['sBlockBorderJsId'];  endif; ?>');} return false;"title="<?php echo Phpfox::getPhrase('core.remove_this_block'); ?>">
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'misc/application_delete.png','alt' => '','class' => 'v_middle')); ?>
					</a>
<?php else: ?>
<?php if (( ( isset ( $this->_aVars['bCanMove'] ) && $this->_aVars['bCanMove'] ) || ! isset ( $this->_aVars['bCanMove'] ) )): ?>
						<a href="#" onclick="if (confirm('<?php echo Phpfox::getPhrase('core.are_you_sure', array('phpfox_squote' => true)); ?>')) { $(this).parents('.block:first').remove();
						$.ajaxCall('core.hideBlock', '<?php if (isset ( $this->_aVars['sCustomDesignId'] )): ?>custom_item_id=<?php echo $this->_aVars['sCustomDesignId']; ?>&amp;<?php endif; ?>sController=' + oParams['sController'] + '&amp;type_id=<?php if (isset ( $this->_aVars['sDeleteBlock'] )):  echo $this->_aVars['sDeleteBlock'];  else: ?> <?php echo $this->_aVars['sBlockBorderJsId'];  endif; ?>&amp;block_id=' + $(this).parents('.block:first').attr('id')); } return false;" title="<?php echo Phpfox::getPhrase('core.remove_this_block'); ?>">
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'misc/application_delete.png','alt' => '','class' => 'v_middle')); ?>
						</a>				
<?php endif; ?>
<?php endif; ?>
			</div>
			
<?php endif; ?>
<?php if (empty ( $this->_aVars['sHeader'] )): ?>
<?php echo $this->_aVars['sBlockShowName']; ?>
<?php else: ?>
<?php echo $this->_aVars['sHeader']; ?>
<?php endif; ?>
		</div>
<?php endif; ?>
<?php if (isset ( $this->_aVars['aEditBar'] )): ?>
	<div id="js_edit_block_<?php echo $this->_aVars['sBlockBorderJsId']; ?>" class="edit_bar" style="display:none;"></div>
<?php endif; ?>
<?php if (isset ( $this->_aVars['aMenu'] ) && count ( $this->_aVars['aMenu'] )): ?>
	<div class="menu">
	<ul>
<?php if (count((array)$this->_aVars['aMenu'])):  $this->_aPhpfoxVars['iteration']['content'] = 0;  foreach ((array) $this->_aVars['aMenu'] as $this->_aVars['sPhrase'] => $this->_aVars['sLink']):  $this->_aPhpfoxVars['iteration']['content']++; ?>
 
		<li class="<?php if (count ( $this->_aVars['aMenu'] ) == $this->_aPhpfoxVars['iteration']['content']): ?> last<?php endif;  if ($this->_aPhpfoxVars['iteration']['content'] == 1): ?> first active<?php endif; ?>"><a href="<?php echo $this->_aVars['sLink']; ?>"><?php echo $this->_aVars['sPhrase']; ?></a></li>
<?php endforeach; endif; ?>
	</ul>
	<div class="clear"></div>
	</div>
<?php unset($this->_aVars['aMenu']); ?>
<?php endif; ?>
	<div class="content"<?php if (isset ( $this->_aVars['sBlockJsId'] )): ?> id="js_block_content_<?php echo $this->_aVars['sBlockJsId']; ?>"<?php endif; ?>>
<?php endif; ?>
		<?php
/**
 * [PHPFOX_HEADER]
 */


/**
 * 
 * 
 * @copyright       [YOUNET_COPYRIGHT]
 * @author          YouNet Company
 * @package         YouNet_Listing
 */
 echo '
<script type="text/javascript" language="JavaScript">
    $Core.remakePostUrl = function(){
        var sort = $("#search_sort").val();
        var show = $("#search_show").val();
        var when = $("#search_when").val();
        var location = $("#search_location").val();
        var city = $("#search_city").val();
        var zipcode = $("#search_zipcode").val();
        var country = $(\'#country_iso\').val();
        var childid= $(\'#js_country_child_id_value\').val();

        if(childid==null)
        {
            childid=0;
        }
        var url = window.location.href;

        if(url.match(/\/search.*?\//g))
        {

        }
        else
        {
            url = url.replace(/\/advancedmarketplace\//g, \'/advancedmarketplace/search/\');
        }

        if(url.match(/\/sort_.*?\//g))
        {
            url = url.replace(/\/sort_.*?\//g, \'/sort_\'+sort+\'/\');
        }
        else
        {
            url += \'sort_\'+sort+\'/\';
        }
        url = url.replace(/\/page_.*?\//g, \'/\');
        url = url.replace(/\/date_.*?\//g, \'/\');
        if(url.match(/\/show_.*?\//g))
        {
            url = url.replace(/\/show_.*?\//g, \'/show_\'+show+\'/\');
        }
        else
        {
            url += \'show_\'+show+\'/\';
        }
        if(url.match(/\/when_.*?\//g))
        {
            url = url.replace(/\/when_.*?\//g, \'/when_\'+when+\'/\');
        }
        else
        {
            url += \'when_\'+when+\'/\';
        }
        if(url.match(/\/location_.*?\//g))
        {
            url = url.replace(/\/location_.*?\//g, \'/location_\'+location+\'/\');
        }
        else
        {
            url += \'location_\'+location+\'/\';
        }
        if(url.match(/\/city_.*?\//g))
        {
            url = url.replace(/\/city_.*?\//g, \'/city_\'+city+\'/\');
        }
        else
        {
            url += \'city_\'+city+\'/\';
        }
        if(url.match(/\/zipcode_.*?\//g))
        {
            url = url.replace(/\/zipcode_.*?\//g, \'/zipcode_\'+zipcode+\'/\');
        }
        else
        {
            url += \'zipcode_\'+zipcode+\'/\';
        }
        if(url.match(/\/country_.*?\//g))
        {
            url = url.replace(/\/country_.*?\//g, \'/country_\'+country+\'/\');
        }
        else
        {
            url += \'country_\'+country+\'/\';
        }
        if(url.match(/\/childid_.*?\//g))
        {
            url = url.replace(/\/child_id_.*?\//g, \'/childid_\'+childid+\'/\');
        }
        else
        {
            url += \'childid_\'+childid+\'/\';
        }
        $("#event_search_form").attr(\'action\', url);
    }
</script>
'; ?>

<?php echo '
<style type="text/css">
	#country_iso{
   		width: 150px;
	}
</style>
'; ?>

<?php echo '
<script type="text/javascript" language="JavaScript">
    $Behavior.chsDSE = function() {
        var country = $("#country_iso");
        country.val("';  echo $this->_aVars['sCountry'];  echo '");
    }
</script>
'; ?>

<form id="event_search_form" method="post" onsubmit="$Core.remakePostUrl(); if($('#search_keywords').val()=='<?php echo Phpfox::getPhrase('advancedmarketplace.keywords'); ?>...'){$('#search_keywords').val('');}">
<?php echo '<div><input type="hidden" name="' . Phpfox::getTokenName() . '[security_token]" value="' . Phpfox::getService('log.session')->getToken() . '" /></div>'; ?>
    <input type="hidden" value="1" name="search[submit]">
    
    <table class="search_table" border="0" cellpadding="0" cellspacing="5">
        <tr>
            <td><?php echo Phpfox::getPhrase('advancedmarketplace.keywords'); ?>:</td>
            <td>
                <input id="search_keywords" type="text" name="search[search]" class="search_keyword">
            </td>
        </tr>
       
        <tr>
            <td><?php echo Phpfox::getPhrase('advancedmarketplace.sort'); ?>:</td>
            <td>
                <select id="search_sort" onchange="$Core.remakePostUrl();">
                    <option <?php if ($this->_aVars['sSort'] == 'latest'): ?>selected<?php endif; ?> value="latest"><?php echo Phpfox::getPhrase('advancedmarketplace.latest'); ?></option>
                    <option <?php if ($this->_aVars['sSort'] == 'most-viewed'): ?>selected<?php endif; ?> value="most-viewed"><?php echo Phpfox::getPhrase('advancedmarketplace.most_viewed'); ?></option>
                    <option <?php if ($this->_aVars['sSort'] == 'most-liked'): ?>selected<?php endif; ?> value="most-liked"><?php echo Phpfox::getPhrase('advancedmarketplace.most_liked'); ?></option>
                    <option <?php if ($this->_aVars['sSort'] == 'most-talked'): ?>selected<?php endif; ?> value="most-talked"><?php echo Phpfox::getPhrase('advancedmarketplace.most_discussed'); ?></option>
                    <option <?php if ($this->_aVars['sSort'] == 'most-reviewed'): ?>selected<?php endif; ?> value="most-reviewed"><?php echo Phpfox::getPhrase('advancedmarketplace.most_reviewed'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td><?php echo Phpfox::getPhrase('advancedmarketplace.show'); ?>:</td>
            <td>
                <select id="search_show" onchange="$Core.remakePostUrl();">
<?php if (count((array)$this->_aVars['aShows'])):  foreach ((array) $this->_aVars['aShows'] as $this->_aVars['aShow']): ?>
                        <option <?php if ($this->_aVars['sShow'] == $this->_aVars['aShow']['value']): ?>selected<?php endif; ?> value="<?php echo $this->_aVars['aShow']['value']; ?>"><?php echo $this->_aVars['aShow']['label']; ?></option>
<?php endforeach; endif; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><?php echo Phpfox::getPhrase('advancedmarketplace.when'); ?>:</td>
            <td>
                <select id="search_when" onchange="$Core.remakePostUrl();">
                    <option <?php if ($this->_aVars['sWhen'] == 'all-time'): ?>selected<?php endif; ?> value="all-time"><?php echo Phpfox::getPhrase('core.all_time'); ?></option>
                    <option <?php if ($this->_aVars['sWhen'] == 'this-month'): ?>selected<?php endif; ?> value="this-month"><?php echo Phpfox::getPhrase('core.this_month'); ?></option>
                    <option <?php if ($this->_aVars['sWhen'] == 'this-week'): ?>selected<?php endif; ?> value="this-week"><?php echo Phpfox::getPhrase('core.this_week'); ?></option>
                    <option <?php if ($this->_aVars['sWhen'] == 'today'): ?>selected<?php endif; ?> value="today"><?php echo Phpfox::getPhrase('core.today'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <td><?php echo Phpfox::getPhrase('advancedmarketplace.street'); ?>:</td>
            <td>
                <input id="search_location" value="<?php echo $this->_aVars['sLocation']; ?>" type="text" name="search[location]" class="search_keyword">
            </td>
        </tr>
        <tr>
            <td><?php echo Phpfox::getPhrase('advancedmarketplace.city'); ?>:</td>
            <td>
                <input id="search_city" type="text" value="<?php echo $this->_aVars['sCity']; ?>" name="search[city]" class="search_keyword">
            </td>
        </tr>
        <tr>
            <td><?php echo Phpfox::getPhrase('advancedmarketplace.zip_postal_code'); ?>:</td>
            <td>
                <input id="search_zipcode" type="text" value="<?php if (isset ( $this->_aVars['sZipCode'] )):  echo $this->_aVars['sZipCode'];  endif; ?>" name="search[zipcode]" class="search_keyword">
            </td>
        </tr>

        <tr>
            <td>
                <label for="country_iso"><?php echo Phpfox::getPhrase('advancedmarketplace.country'); ?>:</label>
            </td>
            <td >
                <select name="val[country_iso]" id="country_iso">
		<option value=""><?php echo Phpfox::getPhrase('core.select'); ?>:</option>
			<option class="js_country_option" id="js_country_iso_option_AF" value="AF"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_af') ? Phpfox::getPhrase('core.translate_country_iso_af') : 'Afghanistan'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_AX" value="AX"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ax') ? Phpfox::getPhrase('core.translate_country_iso_ax') : 'Aland Islands'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_AL" value="AL"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_al') ? Phpfox::getPhrase('core.translate_country_iso_al') : 'Albania'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_DZ" value="DZ"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_dz') ? Phpfox::getPhrase('core.translate_country_iso_dz') : 'Algeria'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_AS" value="AS"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_as') ? Phpfox::getPhrase('core.translate_country_iso_as') : 'American Samoa'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_AD" value="AD"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ad') ? Phpfox::getPhrase('core.translate_country_iso_ad') : 'Andorra'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_AO" value="AO"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ao') ? Phpfox::getPhrase('core.translate_country_iso_ao') : 'Angola'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_AI" value="AI"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ai') ? Phpfox::getPhrase('core.translate_country_iso_ai') : 'Anguilla'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_AQ" value="AQ"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_aq') ? Phpfox::getPhrase('core.translate_country_iso_aq') : 'Antarctica'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_AG" value="AG"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ag') ? Phpfox::getPhrase('core.translate_country_iso_ag') : 'Antigua and Barbuda'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_AR" value="AR"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ar') ? Phpfox::getPhrase('core.translate_country_iso_ar') : 'Argentina'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_AM" value="AM"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_am') ? Phpfox::getPhrase('core.translate_country_iso_am') : 'Armenia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_AW" value="AW"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_aw') ? Phpfox::getPhrase('core.translate_country_iso_aw') : 'Aruba'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_AU" value="AU"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_au') ? Phpfox::getPhrase('core.translate_country_iso_au') : 'Australia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_AT" value="AT"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_at') ? Phpfox::getPhrase('core.translate_country_iso_at') : 'Austria'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_AZ" value="AZ"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_az') ? Phpfox::getPhrase('core.translate_country_iso_az') : 'Azerbaijan'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_BS" value="BS"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_bs') ? Phpfox::getPhrase('core.translate_country_iso_bs') : 'Bahamas'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_BH" value="BH"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_bh') ? Phpfox::getPhrase('core.translate_country_iso_bh') : 'Bahrain'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_BD" value="BD"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_bd') ? Phpfox::getPhrase('core.translate_country_iso_bd') : 'Bangladesh'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_BB" value="BB"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_bb') ? Phpfox::getPhrase('core.translate_country_iso_bb') : 'Barbados'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_BY" value="BY"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_by') ? Phpfox::getPhrase('core.translate_country_iso_by') : 'Belarus'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_BE" value="BE"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_be') ? Phpfox::getPhrase('core.translate_country_iso_be') : 'Belgium'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_BZ" value="BZ"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_bz') ? Phpfox::getPhrase('core.translate_country_iso_bz') : 'Belize'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_BJ" value="BJ"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_bj') ? Phpfox::getPhrase('core.translate_country_iso_bj') : 'Benin'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_BM" value="BM"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_bm') ? Phpfox::getPhrase('core.translate_country_iso_bm') : 'Bermuda'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_BT" value="BT"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_bt') ? Phpfox::getPhrase('core.translate_country_iso_bt') : 'Bhutan'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_BO" value="BO"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_bo') ? Phpfox::getPhrase('core.translate_country_iso_bo') : 'Bolivia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_BA" value="BA"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ba') ? Phpfox::getPhrase('core.translate_country_iso_ba') : 'Bosnia and Herzegovina'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_BW" value="BW"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_bw') ? Phpfox::getPhrase('core.translate_country_iso_bw') : 'Botswana'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_BV" value="BV"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_bv') ? Phpfox::getPhrase('core.translate_country_iso_bv') : 'Bouvet Island'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_BR" value="BR"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_br') ? Phpfox::getPhrase('core.translate_country_iso_br') : 'Brazil'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_IO" value="IO"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_io') ? Phpfox::getPhrase('core.translate_country_iso_io') : 'British Indian Ocean Territory'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_BN" value="BN"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_bn') ? Phpfox::getPhrase('core.translate_country_iso_bn') : 'Brunei Darussalam'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_BG" value="BG"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_bg') ? Phpfox::getPhrase('core.translate_country_iso_bg') : 'Bulgaria'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_BF" value="BF"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_bf') ? Phpfox::getPhrase('core.translate_country_iso_bf') : 'Burkina Faso'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_BI" value="BI"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_bi') ? Phpfox::getPhrase('core.translate_country_iso_bi') : 'Burundi'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_KH" value="KH"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_kh') ? Phpfox::getPhrase('core.translate_country_iso_kh') : 'Cambodia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_CM" value="CM"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_cm') ? Phpfox::getPhrase('core.translate_country_iso_cm') : 'Cameroon'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_CA" value="CA"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ca') ? Phpfox::getPhrase('core.translate_country_iso_ca') : 'Canada'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_CV" value="CV"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_cv') ? Phpfox::getPhrase('core.translate_country_iso_cv') : 'Cape Verde'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_KY" value="KY"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ky') ? Phpfox::getPhrase('core.translate_country_iso_ky') : 'Cayman Islands'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_CF" value="CF"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_cf') ? Phpfox::getPhrase('core.translate_country_iso_cf') : 'Central African Republic'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_TD" value="TD"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_td') ? Phpfox::getPhrase('core.translate_country_iso_td') : 'Chad'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_CL" value="CL"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_cl') ? Phpfox::getPhrase('core.translate_country_iso_cl') : 'Chile'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_CN" value="CN"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_cn') ? Phpfox::getPhrase('core.translate_country_iso_cn') : 'China'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_CX" value="CX"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_cx') ? Phpfox::getPhrase('core.translate_country_iso_cx') : 'Christmas Island'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_CC" value="CC"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_cc') ? Phpfox::getPhrase('core.translate_country_iso_cc') : 'Cocos (Keeling) Islands'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_CO" value="CO"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_co') ? Phpfox::getPhrase('core.translate_country_iso_co') : 'Colombia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_KM" value="KM"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_km') ? Phpfox::getPhrase('core.translate_country_iso_km') : 'Comoros'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_CG" value="CG"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_cg') ? Phpfox::getPhrase('core.translate_country_iso_cg') : 'Congo'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_CD" value="CD"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_cd') ? Phpfox::getPhrase('core.translate_country_iso_cd') : 'Congo, the Democratic Republic of the'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_CK" value="CK"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ck') ? Phpfox::getPhrase('core.translate_country_iso_ck') : 'Cook Islands'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_CR" value="CR"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_cr') ? Phpfox::getPhrase('core.translate_country_iso_cr') : 'Costa Rica'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_CI" value="CI"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ci') ? Phpfox::getPhrase('core.translate_country_iso_ci') : 'Cote D\'Ivoire'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_HR" value="HR"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_hr') ? Phpfox::getPhrase('core.translate_country_iso_hr') : 'Croatia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_CU" value="CU"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_cu') ? Phpfox::getPhrase('core.translate_country_iso_cu') : 'Cuba'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_CY" value="CY"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_cy') ? Phpfox::getPhrase('core.translate_country_iso_cy') : 'Cyprus'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_CZ" value="CZ"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_cz') ? Phpfox::getPhrase('core.translate_country_iso_cz') : 'Czech Republic'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_DK" value="DK"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_dk') ? Phpfox::getPhrase('core.translate_country_iso_dk') : 'Denmark'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_DJ" value="DJ"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_dj') ? Phpfox::getPhrase('core.translate_country_iso_dj') : 'Djibouti'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_DM" value="DM"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_dm') ? Phpfox::getPhrase('core.translate_country_iso_dm') : 'Dominica'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_DO" value="DO"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_do') ? Phpfox::getPhrase('core.translate_country_iso_do') : 'Dominican Republic'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_EC" value="EC"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ec') ? Phpfox::getPhrase('core.translate_country_iso_ec') : 'Ecuador'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_EG" value="EG"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_eg') ? Phpfox::getPhrase('core.translate_country_iso_eg') : 'Egypt'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_SV" value="SV"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_sv') ? Phpfox::getPhrase('core.translate_country_iso_sv') : 'El Salvador'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_GQ" value="GQ"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_gq') ? Phpfox::getPhrase('core.translate_country_iso_gq') : 'Equatorial Guinea'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_ER" value="ER"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_er') ? Phpfox::getPhrase('core.translate_country_iso_er') : 'Eritrea'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_EE" value="EE"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ee') ? Phpfox::getPhrase('core.translate_country_iso_ee') : 'Estonia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_ET" value="ET"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_et') ? Phpfox::getPhrase('core.translate_country_iso_et') : 'Ethiopia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_FK" value="FK"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_fk') ? Phpfox::getPhrase('core.translate_country_iso_fk') : 'Falkland Islands (Malvinas)'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_FO" value="FO"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_fo') ? Phpfox::getPhrase('core.translate_country_iso_fo') : 'Faroe Islands'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_FJ" value="FJ"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_fj') ? Phpfox::getPhrase('core.translate_country_iso_fj') : 'Fiji'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_FI" value="FI"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_fi') ? Phpfox::getPhrase('core.translate_country_iso_fi') : 'Finland'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_FR" value="FR"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_fr') ? Phpfox::getPhrase('core.translate_country_iso_fr') : 'France'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_GF" value="GF"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_gf') ? Phpfox::getPhrase('core.translate_country_iso_gf') : 'French Guiana'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_PF" value="PF"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_pf') ? Phpfox::getPhrase('core.translate_country_iso_pf') : 'French Polynesia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_TF" value="TF"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_tf') ? Phpfox::getPhrase('core.translate_country_iso_tf') : 'French Southern Territories'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_GA" value="GA"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ga') ? Phpfox::getPhrase('core.translate_country_iso_ga') : 'Gabon'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_GM" value="GM"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_gm') ? Phpfox::getPhrase('core.translate_country_iso_gm') : 'Gambia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_GE" value="GE"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ge') ? Phpfox::getPhrase('core.translate_country_iso_ge') : 'Georgia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_DE" value="DE"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_de') ? Phpfox::getPhrase('core.translate_country_iso_de') : 'Germany'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_GH" value="GH"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_gh') ? Phpfox::getPhrase('core.translate_country_iso_gh') : 'Ghana'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_GI" value="GI"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_gi') ? Phpfox::getPhrase('core.translate_country_iso_gi') : 'Gibraltar'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_GR" value="GR"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_gr') ? Phpfox::getPhrase('core.translate_country_iso_gr') : 'Greece'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_GL" value="GL"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_gl') ? Phpfox::getPhrase('core.translate_country_iso_gl') : 'Greenland'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_GD" value="GD"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_gd') ? Phpfox::getPhrase('core.translate_country_iso_gd') : 'Grenada'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_GP" value="GP"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_gp') ? Phpfox::getPhrase('core.translate_country_iso_gp') : 'Guadeloupe'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_GU" value="GU"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_gu') ? Phpfox::getPhrase('core.translate_country_iso_gu') : 'Guam'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_GT" value="GT"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_gt') ? Phpfox::getPhrase('core.translate_country_iso_gt') : 'Guatemala'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_GG" value="GG"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_gg') ? Phpfox::getPhrase('core.translate_country_iso_gg') : 'Guernsey'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_GN" value="GN"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_gn') ? Phpfox::getPhrase('core.translate_country_iso_gn') : 'Guinea'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_GW" value="GW"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_gw') ? Phpfox::getPhrase('core.translate_country_iso_gw') : 'Guinea-Bissau'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_GY" value="GY"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_gy') ? Phpfox::getPhrase('core.translate_country_iso_gy') : 'Guyana'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_HT" value="HT"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ht') ? Phpfox::getPhrase('core.translate_country_iso_ht') : 'Haiti'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_HM" value="HM"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_hm') ? Phpfox::getPhrase('core.translate_country_iso_hm') : 'Heard Island and Mcdonald Islands'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_VA" value="VA"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_va') ? Phpfox::getPhrase('core.translate_country_iso_va') : 'Holy See (Vatican City State)'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_HN" value="HN"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_hn') ? Phpfox::getPhrase('core.translate_country_iso_hn') : 'Honduras'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_HK" value="HK"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_hk') ? Phpfox::getPhrase('core.translate_country_iso_hk') : 'Hong Kong'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_HU" value="HU"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_hu') ? Phpfox::getPhrase('core.translate_country_iso_hu') : 'Hungary'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_IS" value="IS"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_is') ? Phpfox::getPhrase('core.translate_country_iso_is') : 'Iceland'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_IN" value="IN"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_in') ? Phpfox::getPhrase('core.translate_country_iso_in') : 'India'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_ID" value="ID"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_id') ? Phpfox::getPhrase('core.translate_country_iso_id') : 'Indonesia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_IR" value="IR"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ir') ? Phpfox::getPhrase('core.translate_country_iso_ir') : 'Iran, Islamic Republic of'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_IQ" value="IQ"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_iq') ? Phpfox::getPhrase('core.translate_country_iso_iq') : 'Iraq'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_IE" value="IE"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ie') ? Phpfox::getPhrase('core.translate_country_iso_ie') : 'Ireland'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_IM" value="IM"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_im') ? Phpfox::getPhrase('core.translate_country_iso_im') : 'Isle of Man'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_IL" value="IL"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_il') ? Phpfox::getPhrase('core.translate_country_iso_il') : 'Israel'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_IT" value="IT"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_it') ? Phpfox::getPhrase('core.translate_country_iso_it') : 'Italy'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_JM" value="JM"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_jm') ? Phpfox::getPhrase('core.translate_country_iso_jm') : 'Jamaica'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_JP" value="JP"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_jp') ? Phpfox::getPhrase('core.translate_country_iso_jp') : 'Japan'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_JO" value="JO"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_jo') ? Phpfox::getPhrase('core.translate_country_iso_jo') : 'Jordan'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_KZ" value="KZ"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_kz') ? Phpfox::getPhrase('core.translate_country_iso_kz') : 'Kazakhstan'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_KE" value="KE"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ke') ? Phpfox::getPhrase('core.translate_country_iso_ke') : 'Kenya'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_KI" value="KI"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ki') ? Phpfox::getPhrase('core.translate_country_iso_ki') : 'Kiribati'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_KP" value="KP"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_kp') ? Phpfox::getPhrase('core.translate_country_iso_kp') : 'Korea, Democratic People\'s Republic of'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_KR" value="KR"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_kr') ? Phpfox::getPhrase('core.translate_country_iso_kr') : 'Korea, Republic of'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_KW" value="KW"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_kw') ? Phpfox::getPhrase('core.translate_country_iso_kw') : 'Kuwait'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_KG" value="KG"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_kg') ? Phpfox::getPhrase('core.translate_country_iso_kg') : 'Kyrgyzstan'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_LA" value="LA"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_la') ? Phpfox::getPhrase('core.translate_country_iso_la') : 'Lao People\'s Democratic Republic'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_LV" value="LV"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_lv') ? Phpfox::getPhrase('core.translate_country_iso_lv') : 'Latvia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_LB" value="LB"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_lb') ? Phpfox::getPhrase('core.translate_country_iso_lb') : 'Lebanon'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_LS" value="LS"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ls') ? Phpfox::getPhrase('core.translate_country_iso_ls') : 'Lesotho'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_LR" value="LR"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_lr') ? Phpfox::getPhrase('core.translate_country_iso_lr') : 'Liberia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_LY" value="LY"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ly') ? Phpfox::getPhrase('core.translate_country_iso_ly') : 'Libyan Arab Jamahiriya'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_LI" value="LI"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_li') ? Phpfox::getPhrase('core.translate_country_iso_li') : 'Liechtenstein'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_LT" value="LT"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_lt') ? Phpfox::getPhrase('core.translate_country_iso_lt') : 'Lithuania'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_LU" value="LU"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_lu') ? Phpfox::getPhrase('core.translate_country_iso_lu') : 'Luxembourg'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_MO" value="MO"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_mo') ? Phpfox::getPhrase('core.translate_country_iso_mo') : 'Macao'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_MK" value="MK"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_mk') ? Phpfox::getPhrase('core.translate_country_iso_mk') : 'Macedonia, the Former Yugoslav Republic of'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_MG" value="MG"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_mg') ? Phpfox::getPhrase('core.translate_country_iso_mg') : 'Madagascar'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_MW" value="MW"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_mw') ? Phpfox::getPhrase('core.translate_country_iso_mw') : 'Malawi'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_MY" value="MY"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_my') ? Phpfox::getPhrase('core.translate_country_iso_my') : 'Malaysia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_MV" value="MV"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_mv') ? Phpfox::getPhrase('core.translate_country_iso_mv') : 'Maldives'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_ML" value="ML"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ml') ? Phpfox::getPhrase('core.translate_country_iso_ml') : 'Mali'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_MT" value="MT"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_mt') ? Phpfox::getPhrase('core.translate_country_iso_mt') : 'Malta'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_MH" value="MH"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_mh') ? Phpfox::getPhrase('core.translate_country_iso_mh') : 'Marshall Islands'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_MQ" value="MQ"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_mq') ? Phpfox::getPhrase('core.translate_country_iso_mq') : 'Martinique'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_MR" value="MR"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_mr') ? Phpfox::getPhrase('core.translate_country_iso_mr') : 'Mauritania'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_MU" value="MU"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_mu') ? Phpfox::getPhrase('core.translate_country_iso_mu') : 'Mauritius'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_YT" value="YT"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_yt') ? Phpfox::getPhrase('core.translate_country_iso_yt') : 'Mayotte'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_MX" value="MX"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_mx') ? Phpfox::getPhrase('core.translate_country_iso_mx') : 'Mexico'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_FM" value="FM"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_fm') ? Phpfox::getPhrase('core.translate_country_iso_fm') : 'Micronesia, Federated States of'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_MD" value="MD"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_md') ? Phpfox::getPhrase('core.translate_country_iso_md') : 'Moldova, Republic of'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_MC" value="MC"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_mc') ? Phpfox::getPhrase('core.translate_country_iso_mc') : 'Monaco'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_MN" value="MN"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_mn') ? Phpfox::getPhrase('core.translate_country_iso_mn') : 'Mongolia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_ME" value="ME"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_me') ? Phpfox::getPhrase('core.translate_country_iso_me') : 'Montenegro'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_MS" value="MS"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ms') ? Phpfox::getPhrase('core.translate_country_iso_ms') : 'Montserrat'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_MA" value="MA"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ma') ? Phpfox::getPhrase('core.translate_country_iso_ma') : 'Morocco'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_MZ" value="MZ"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_mz') ? Phpfox::getPhrase('core.translate_country_iso_mz') : 'Mozambique'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_MM" value="MM"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_mm') ? Phpfox::getPhrase('core.translate_country_iso_mm') : 'Myanmar'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_NA" value="NA"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_na') ? Phpfox::getPhrase('core.translate_country_iso_na') : 'Namibia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_NR" value="NR"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_nr') ? Phpfox::getPhrase('core.translate_country_iso_nr') : 'Nauru'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_NP" value="NP"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_np') ? Phpfox::getPhrase('core.translate_country_iso_np') : 'Nepal'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_NL" value="NL"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_nl') ? Phpfox::getPhrase('core.translate_country_iso_nl') : 'Netherlands'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_AN" value="AN"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_an') ? Phpfox::getPhrase('core.translate_country_iso_an') : 'Netherlands Antilles'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_NC" value="NC"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_nc') ? Phpfox::getPhrase('core.translate_country_iso_nc') : 'New Caledonia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_NZ" value="NZ"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_nz') ? Phpfox::getPhrase('core.translate_country_iso_nz') : 'New Zealand'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_NI" value="NI"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ni') ? Phpfox::getPhrase('core.translate_country_iso_ni') : 'Nicaragua'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_NE" value="NE"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ne') ? Phpfox::getPhrase('core.translate_country_iso_ne') : 'Niger'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_NG" value="NG"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ng') ? Phpfox::getPhrase('core.translate_country_iso_ng') : 'Nigeria'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_NU" value="NU"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_nu') ? Phpfox::getPhrase('core.translate_country_iso_nu') : 'Niue'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_NF" value="NF"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_nf') ? Phpfox::getPhrase('core.translate_country_iso_nf') : 'Norfolk Island'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_MP" value="MP"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_mp') ? Phpfox::getPhrase('core.translate_country_iso_mp') : 'Northern Mariana Islands'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_NO" value="NO"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_no') ? Phpfox::getPhrase('core.translate_country_iso_no') : 'Norway'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_OM" value="OM"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_om') ? Phpfox::getPhrase('core.translate_country_iso_om') : 'Oman'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_PK" value="PK"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_pk') ? Phpfox::getPhrase('core.translate_country_iso_pk') : 'Pakistan'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_PW" value="PW"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_pw') ? Phpfox::getPhrase('core.translate_country_iso_pw') : 'Palau'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_PS" value="PS"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ps') ? Phpfox::getPhrase('core.translate_country_iso_ps') : 'Palestinian Territory, Occupied'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_PA" value="PA"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_pa') ? Phpfox::getPhrase('core.translate_country_iso_pa') : 'Panama'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_PG" value="PG"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_pg') ? Phpfox::getPhrase('core.translate_country_iso_pg') : 'Papua New Guinea'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_PY" value="PY"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_py') ? Phpfox::getPhrase('core.translate_country_iso_py') : 'Paraguay'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_PE" value="PE"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_pe') ? Phpfox::getPhrase('core.translate_country_iso_pe') : 'Peru'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_PH" value="PH"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ph') ? Phpfox::getPhrase('core.translate_country_iso_ph') : 'Philippines'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_PN" value="PN"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_pn') ? Phpfox::getPhrase('core.translate_country_iso_pn') : 'Pitcairn'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_PL" value="PL"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_pl') ? Phpfox::getPhrase('core.translate_country_iso_pl') : 'Poland'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_PT" value="PT"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_pt') ? Phpfox::getPhrase('core.translate_country_iso_pt') : 'Portugal'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_PR" value="PR"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_pr') ? Phpfox::getPhrase('core.translate_country_iso_pr') : 'Puerto Rico'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_QA" value="QA"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_qa') ? Phpfox::getPhrase('core.translate_country_iso_qa') : 'Qatar'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_RE" value="RE"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_re') ? Phpfox::getPhrase('core.translate_country_iso_re') : 'Reunion'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_RO" value="RO"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ro') ? Phpfox::getPhrase('core.translate_country_iso_ro') : 'Romania'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_RU" value="RU"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ru') ? Phpfox::getPhrase('core.translate_country_iso_ru') : 'Russian Federation'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_RW" value="RW"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_rw') ? Phpfox::getPhrase('core.translate_country_iso_rw') : 'Rwanda'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_BL" value="BL"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_bl') ? Phpfox::getPhrase('core.translate_country_iso_bl') : 'Saint Barthelemy'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_SH" value="SH"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_sh') ? Phpfox::getPhrase('core.translate_country_iso_sh') : 'Saint Helena'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_KN" value="KN"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_kn') ? Phpfox::getPhrase('core.translate_country_iso_kn') : 'Saint Kitts and Nevis'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_LC" value="LC"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_lc') ? Phpfox::getPhrase('core.translate_country_iso_lc') : 'Saint Lucia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_PM" value="PM"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_pm') ? Phpfox::getPhrase('core.translate_country_iso_pm') : 'Saint Pierre and Miquelon'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_VC" value="VC"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_vc') ? Phpfox::getPhrase('core.translate_country_iso_vc') : 'Saint Vincent and the Grenadines'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_WS" value="WS"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ws') ? Phpfox::getPhrase('core.translate_country_iso_ws') : 'Samoa'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_SM" value="SM"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_sm') ? Phpfox::getPhrase('core.translate_country_iso_sm') : 'San Marino'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_ST" value="ST"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_st') ? Phpfox::getPhrase('core.translate_country_iso_st') : 'Sao Tome and Principe'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_SA" value="SA"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_sa') ? Phpfox::getPhrase('core.translate_country_iso_sa') : 'Saudi Arabia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_SN" value="SN"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_sn') ? Phpfox::getPhrase('core.translate_country_iso_sn') : 'Senegal'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_RS" value="RS"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_rs') ? Phpfox::getPhrase('core.translate_country_iso_rs') : 'Serbia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_SC" value="SC"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_sc') ? Phpfox::getPhrase('core.translate_country_iso_sc') : 'Seychelles'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_SL" value="SL"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_sl') ? Phpfox::getPhrase('core.translate_country_iso_sl') : 'Sierra Leone'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_SG" value="SG"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_sg') ? Phpfox::getPhrase('core.translate_country_iso_sg') : 'Singapore'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_SK" value="SK"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_sk') ? Phpfox::getPhrase('core.translate_country_iso_sk') : 'Slovakia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_SI" value="SI"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_si') ? Phpfox::getPhrase('core.translate_country_iso_si') : 'Slovenia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_SB" value="SB"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_sb') ? Phpfox::getPhrase('core.translate_country_iso_sb') : 'Solomon Islands'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_SO" value="SO"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_so') ? Phpfox::getPhrase('core.translate_country_iso_so') : 'Somalia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_ZA" value="ZA"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_za') ? Phpfox::getPhrase('core.translate_country_iso_za') : 'South Africa'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_GS" value="GS"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_gs') ? Phpfox::getPhrase('core.translate_country_iso_gs') : 'South Georgia and the South Sandwich Islands'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_ES" value="ES"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_es') ? Phpfox::getPhrase('core.translate_country_iso_es') : 'Spain'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_LK" value="LK"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_lk') ? Phpfox::getPhrase('core.translate_country_iso_lk') : 'Sri Lanka'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_SD" value="SD"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_sd') ? Phpfox::getPhrase('core.translate_country_iso_sd') : 'Sudan'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_SR" value="SR"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_sr') ? Phpfox::getPhrase('core.translate_country_iso_sr') : 'Suriname'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_SJ" value="SJ"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_sj') ? Phpfox::getPhrase('core.translate_country_iso_sj') : 'Svalbard and Jan Mayen'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_SZ" value="SZ"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_sz') ? Phpfox::getPhrase('core.translate_country_iso_sz') : 'Swaziland'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_SE" value="SE"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_se') ? Phpfox::getPhrase('core.translate_country_iso_se') : 'Sweden'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_CH" value="CH"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ch') ? Phpfox::getPhrase('core.translate_country_iso_ch') : 'Switzerland'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_SY" value="SY"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_sy') ? Phpfox::getPhrase('core.translate_country_iso_sy') : 'Syrian Arab Republic'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_TW" value="TW"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_tw') ? Phpfox::getPhrase('core.translate_country_iso_tw') : 'Taiwan, Province of China'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_TJ" value="TJ"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_tj') ? Phpfox::getPhrase('core.translate_country_iso_tj') : 'Tajikistan'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_TZ" value="TZ"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_tz') ? Phpfox::getPhrase('core.translate_country_iso_tz') : 'Tanzania, United Republic of'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_TH" value="TH"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_th') ? Phpfox::getPhrase('core.translate_country_iso_th') : 'Thailand'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_TL" value="TL"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_tl') ? Phpfox::getPhrase('core.translate_country_iso_tl') : 'Timor-Leste'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_TG" value="TG"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_tg') ? Phpfox::getPhrase('core.translate_country_iso_tg') : 'Togo'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_TK" value="TK"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_tk') ? Phpfox::getPhrase('core.translate_country_iso_tk') : 'Tokelau'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_TO" value="TO"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_to') ? Phpfox::getPhrase('core.translate_country_iso_to') : 'Tonga'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_TT" value="TT"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_tt') ? Phpfox::getPhrase('core.translate_country_iso_tt') : 'Trinidad and Tobago'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_TN" value="TN"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_tn') ? Phpfox::getPhrase('core.translate_country_iso_tn') : 'Tunisia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_TR" value="TR"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_tr') ? Phpfox::getPhrase('core.translate_country_iso_tr') : 'Turkey'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_TM" value="TM"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_tm') ? Phpfox::getPhrase('core.translate_country_iso_tm') : 'Turkmenistan'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_TC" value="TC"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_tc') ? Phpfox::getPhrase('core.translate_country_iso_tc') : 'Turks and Caicos Islands'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_TV" value="TV"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_tv') ? Phpfox::getPhrase('core.translate_country_iso_tv') : 'Tuvalu'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_UG" value="UG"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ug') ? Phpfox::getPhrase('core.translate_country_iso_ug') : 'Uganda'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_UA" value="UA"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ua') ? Phpfox::getPhrase('core.translate_country_iso_ua') : 'Ukraine'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_AE" value="AE"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ae') ? Phpfox::getPhrase('core.translate_country_iso_ae') : 'United Arab Emirates'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_GB" value="GB"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_gb') ? Phpfox::getPhrase('core.translate_country_iso_gb') : 'United Kingdom'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_US" value="US"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_us') ? Phpfox::getPhrase('core.translate_country_iso_us') : 'United States'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_UM" value="UM"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_um') ? Phpfox::getPhrase('core.translate_country_iso_um') : 'United States Minor Outlying Islands'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_UY" value="UY"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_uy') ? Phpfox::getPhrase('core.translate_country_iso_uy') : 'Uruguay'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_UZ" value="UZ"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_uz') ? Phpfox::getPhrase('core.translate_country_iso_uz') : 'Uzbekistan'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_VU" value="VU"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_vu') ? Phpfox::getPhrase('core.translate_country_iso_vu') : 'Vanuatu'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_VE" value="VE"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ve') ? Phpfox::getPhrase('core.translate_country_iso_ve') : 'Venezuela'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_VN" value="VN"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_vn') ? Phpfox::getPhrase('core.translate_country_iso_vn') : 'Viet Nam'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_VG" value="VG"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_vg') ? Phpfox::getPhrase('core.translate_country_iso_vg') : 'Virgin Islands, British'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_VI" value="VI"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_vi') ? Phpfox::getPhrase('core.translate_country_iso_vi') : 'Virgin Islands, U.s.'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_WF" value="WF"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_wf') ? Phpfox::getPhrase('core.translate_country_iso_wf') : 'Wallis and Futuna'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_EH" value="EH"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_eh') ? Phpfox::getPhrase('core.translate_country_iso_eh') : 'Western Sahara'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_YE" value="YE"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_ye') ? Phpfox::getPhrase('core.translate_country_iso_ye') : 'Yemen'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_ZM" value="ZM"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_zm') ? Phpfox::getPhrase('core.translate_country_iso_zm') : 'Zambia'); ?></option>
			<option class="js_country_option" id="js_country_iso_option_ZW" value="ZW"><?php echo (Phpfox::getLib('locale')->isPhrase('core.translate_country_iso_zw') ? Phpfox::getPhrase('core.translate_country_iso_zw') : 'Zimbabwe'); ?></option>
		</select>
<?php if (isset($this->_aVars['aForms']['country_iso']))
{ 
echo '<script type="text/javascript"> $Behavior.setCountry = function() {$("#js_country_iso_option_' . $this->_aVars['aForms']['country_iso'] . '").attr("selected","selected"); } </script>';  }
?>
<?php Phpfox::getBlock('core.country-child', array()); ?>
            </td>
        </tr>
    </table>
    
    <div class="p_top_8">
        <input name="search[submit]" value="<?php echo Phpfox::getPhrase('advancedmarketplace.submit'); ?>" class="button" type="submit" />
        <input name="search[reset]" value="<?php echo Phpfox::getPhrase('advancedmarketplace.reset'); ?>" class="button" type="reset" /> 
    </div>    

</form>


		
		
<?php if (( isset ( $this->_aVars['sHeader'] ) && ( ! PHPFOX_IS_AJAX || isset ( $this->_aVars['bPassOverAjaxCall'] ) || isset ( $this->_aVars['bIsAjaxLoader'] ) ) ) || ( defined ( "PHPFOX_IN_DESIGN_MODE" ) && PHPFOX_IN_DESIGN_MODE ) || ( Phpfox ::getService('theme')->isInDnDMode())): ?>
	</div>
<?php if (isset ( $this->_aVars['aFooter'] ) && count ( $this->_aVars['aFooter'] )): ?>
	<div class="bottom">
		<ul>
<?php if (count((array)$this->_aVars['aFooter'])):  $this->_aPhpfoxVars['iteration']['block'] = 0;  foreach ((array) $this->_aVars['aFooter'] as $this->_aVars['sPhrase'] => $this->_aVars['sLink']):  $this->_aPhpfoxVars['iteration']['block']++; ?>

				<li id="js_block_bottom_<?php echo $this->_aPhpfoxVars['iteration']['block']; ?>"<?php if ($this->_aPhpfoxVars['iteration']['block'] == 1): ?> class="first"<?php endif; ?>>
<?php if ($this->_aVars['sLink'] == '#'): ?>
<?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'ajax/add.gif','class' => 'ajax_image')); ?>
<?php endif; ?>
					<a href="<?php echo $this->_aVars['sLink']; ?>" id="js_block_bottom_link_<?php echo $this->_aPhpfoxVars['iteration']['block']; ?>"><?php echo $this->_aVars['sPhrase']; ?></a>
				</li>
<?php endforeach; endif; ?>
		</ul>
	</div>
<?php endif; ?>
</div>
<?php endif;  unset($this->_aVars['sHeader'], $this->_aVars['sComponent'], $this->_aVars['aFooter'], $this->_aVars['sBlockBorderJsId'], $this->_aVars['bBlockDisableSort'], $this->_aVars['bBlockCanMove'], $this->_aVars['aEditBar'], $this->_aVars['sDeleteBlock'], $this->_aVars['sBlockTitleBar'], $this->_aVars['sBlockJsId'], $this->_aVars['sCustomClassName'], $this->_aVars['aMenu']); ?>

<?php if (isset ( $this->_aVars['sClass'] )): ?>
<?php Phpfox::getBlock('ad.inner', array('sClass' => $this->_aVars['sClass']));  endif; ?>
