<?php if(PHPFOX::getLib("request")->get("req1") == "advancedmarketplace") { ?>
	<?php if(phpfox::getParam('advancedmarketplace.can_print_a_listing') == true) {?>
	<li><span>&middot;</span></li>
	<li>
		<a class="remove_otheraction" target="_blank" href="<?php echo PHPFOX::getLib("url")->permalink('advancedmarketplace.print', $this->_aVars["aListing"]["listing_id"]); ?>">
			<?php echo PHPFOX::getPhrase("advancedmarketplace.print"); ?>
		</a>
	</li>
	<?php } ?>
	<?php if (((int)PHPFOX::getUserId()) !== ((int)$this->_aVars["aListing"]["user_id"])) { ?>
		<li><span>&middot;</span></li>
		<li>
			<a class="remove_otheraction" target="_blank" onclick="tb_show('<?php echo PHPFOX::getPhrase("advancedmarketplace.rating"); ?>', $.ajaxBox('advancedmarketplace.ratePopup', 'height=300&width=550&id=<?php echo $this->_aVars["aListing"]["listing_id"]; ?>')); return false;" href="#">
				<?php echo PHPFOX::getPhrase("advancedmarketplace.review"); ?>
			</a>
		</li>
		<li><span>&middot;</span></li>
		<li>
			<a class="remove_otheraction" target="_blank" onclick="$Core.composeMessage({user_id: <?php echo $this->_aVars["aListing"]["user_id"]; ?>}); return false;" href="#">
				<?php echo PHPFOX::getPhrase("advancedmarketplace.contact_seller"); ?>
			</a>
		</li>
	<?php } ?>
<?php } ?>
