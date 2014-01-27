
{if !$bNeedToBeConfig}
<p style="text-align:center">
	<a href="#" onclick="showDonationIndex('{phrase var='donation.page_donation_title_homepage'}', {$iPageId}, '{$sUrl}'); return false;">
		<img src='{$sImg}' />
	</a>
</p>
{else}
<p style="text-align:center">
	<a href="{url link='admincp.donation'}">
		<img src='{$sImg}' />
	</a>
</p>
<p style="text-align:center">
	<a href="{url link='admincp.donation'}">
		{phrase var='donation.donation_setting'}
	</a>
</p>

{/if}
{literal}
<script type="text/javascript" language="javascript">
		function showDonationIndex(title, iPageId, sUrl){  
	tb_show(title,$.ajaxBox('donation.detail','iPageId=' + iPageId + '&sUrl=' + sUrl));
}
</script>
{/literal}