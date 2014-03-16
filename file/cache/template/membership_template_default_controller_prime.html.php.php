<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: March 8, 2014, 5:09 pm */ ?>
<div id="primecontainer">
	<div id="primeslide">
			<br/><br/><br/><br/>
			<iframe 
				src="http://buzzlock.net/slides/welcome/index.html" 
				name="slide1" 
				scrolling="no" 
				align="left" 
				height = "373px" 
				width = "530px">
			</iframe>
	</div>
	<div id="primeform">
		<div id="formcontent">
			<div class="table">
				<form  method="post" action="<?php echo Phpfox::getLib('phpfox.url')->makeUrl('membership.prime'); ?>" autocomplete="off">
<?php echo '<div><input type="hidden" name="' . Phpfox::getTokenName() . '[security_token]" value="' . Phpfox::getService('log.session')->getToken() . '" /></div>'; ?>
					<div class="table_left">
<?php if (Phpfox::getParam('core.display_required')): ?><span class="required"><?php echo Phpfox::getParam('core.required_symbol'); ?></span><?php endif; ?>Payment Method
					</div>
					<div class="table_left">
						<select name="val[gate]" id="gate">
							<option id="gate" value="false">
								Select Gateway
							</option>
							<option id="gate"value="paypal">
								PayPal
							</option>										 
							<option id="gate" value="alertpay">
								Payza
							</option>									
						</select>
					</div>
					<div class="table_left">
						<label for="gate_mail">
<?php if (Phpfox::getParam('core.display_required')): ?><span class="required"><?php echo Phpfox::getParam('core.required_symbol'); ?></span><?php endif; ?>Account Email
						</label>
					</div>
					<div class="table_right">
						<input type="email" name="val[gateMail]" id="gate_mail" maxlength="64">
					</div><br/>
					<div class="table_left">
						<input type="submit"  class="button"  value="Join" name="val[update]"/>
					</div>
				
</form>

			</div>
		</div>
		<div id="gateway">
			<a href="<?php echo $this->_aVars['paypal']; ?>" target="_blank"><?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'buzzbiz/paypal.gif')); ?></a> 
			<a href="<?php echo $this->_aVars['payza']; ?>" target="_blank"><?php echo Phpfox::getLib('phpfox.image.helper')->display(array('theme' => 'buzzbiz/payza.gif')); ?></a>	
		</div>	
	</div>
</div>

	

