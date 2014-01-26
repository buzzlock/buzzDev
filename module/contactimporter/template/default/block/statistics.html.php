   <?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright        [PHPFOX_COPYRIGHT]
 * @author          younetco
 * @package          Module_Contactimporter
 * @version         
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<ul class="action">
 <li><a>
 	<span>
 		{phrase var='contactimporter.remaining_invitations'} 
 	</span>
 	<span>
 		<font color="red" style="font-weight:bold"><span id="remainInvitations">{$statistics.remain}</span></font>
 	</span>
 	</a></li> 
 <li><a>
 	<span>
 		{phrase var='contactimporter.total_sent_invitations'} 
 	</span>
 	<span>
 		<font color="red" style="font-weight:bold">{$total_invitation}</font>
 	</span>
 </a></li> 
 
 {*<li>
    <ul>
        <li><a>{phrase var='contactimporter.your_contacts_from_emails'}: <span><font color="red" style="font-weight:bold">{if isset($statistics.emails)}{$statistics.emails}{else}0{/if}</font></span></a></li>
        <li><a>{phrase var='contactimporter.your_contacts_from_socials'}: <span><font color="red" style="font-weight:bold">{if isset($statistics.socials)}{$statistics.socials}{else}0{/if}</font></span></a></li>
    </ul>
 </li>
 *} 
</ul>
{if $statistics.remain>0}
	<a href="#" style="float:left;margin-left: 5px;" onclick="if (confirm('{phrase var='core.are_you_sure'}')) {left_curly} $.ajaxCall('contactimporter.removeRemainingInvitations', '');  {right_curly} return false;">{phrase var='contactimporter.remove_remaining_invitations'}</a>
	<div style="clear: both"></div>
{/if}
