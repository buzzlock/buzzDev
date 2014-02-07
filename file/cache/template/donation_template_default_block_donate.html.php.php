<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: February 6, 2014, 9:56 pm */ ?>

<?php if (! $this->_aVars['bNeedToBeConfig']): ?>
<p style="text-align:center">
	<a href="#" onclick="showDonationIndex('<?php echo Phpfox::getPhrase('donation.page_donation_title_homepage'); ?>', <?php echo $this->_aVars['iPageId']; ?>, '<?php echo $this->_aVars['sUrl']; ?>'); return false;">
		<img src='<?php echo $this->_aVars['sImg']; ?>' />
	</a>
</p>
<?php else: ?>
<p style="text-align:center">
	<a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl('admincp.donation'); ?>">
		<img src='<?php echo $this->_aVars['sImg']; ?>' />
	</a>
</p>
<p style="text-align:center">
	<a href="<?php echo Phpfox::getLib('phpfox.url')->makeUrl('admincp.donation'); ?>">
<?php echo Phpfox::getPhrase('donation.donation_setting'); ?>
	</a>
</p>

<?php endif;  echo '
<script type="text/javascript" language="javascript">
		function showDonationIndex(title, iPageId, sUrl){  
	tb_show(title,$.ajaxBox(\'donation.detail\',\'iPageId=\' + iPageId + \'&sUrl=\' + sUrl));
}
</script>
'; ?>

