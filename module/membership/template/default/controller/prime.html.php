<html>
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
				<form  method="post" action="{url link='membership.prime'}" autocomplete="off">
					<div class="table_left">
						{required}Payment Method
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
							<option id="gate" value=""									
						</select>
					</div>
					<div class="table_left">
						<label for="gate_mail">
							{required}Account Email
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
			<a href="{$paypal}" target="_blank">{img theme= 'buzzbiz/paypal.gif'}</a> 
			<a href="{$payza}" target="_blank">{img theme= 'buzzbiz/payza.gif'}</a>	
		</div>	
	</div>
</div>
<script type="text/javascript" src="{link url=''}.js"></script>
</html>

	
