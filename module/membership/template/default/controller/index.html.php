<!DOCTYPE html>
<html>	
	<body>
	<div id="bizbody">		
		<div id="buzzbiz_1">
			<a href="#" id="pop1">{img theme= 'buzzbiz/buzzbiz1.png' alt='1. Signup		- Free to join.'}</a>
		</div>		
		<div id="buzzbiz_2">
			<a href="#" id="pop2">{img theme= 'buzzbiz/buzzbiz2.png' alt='2. Buzz	  - Share your link.'}</a>
		</div>		
		<div id="buzzbiz_3">
			<a href="#" id="pop3">{img theme= 'buzzbiz/buzzbiz2.png' alt='3. Lock	  - Lock-in your income.'}</a>
		</div>
		<div id="buzzbiz_form">			
			<form  method="post" action="{url link='membership.index'}" autocomplete="off">
				<br>
				<div id="buzzbiz_logo">{img theme= 'buzzbiz/buzzbizLogo.png'}
				</div>
				<div class="separate">
				</div>
				<span id="buzzbiz_error">
					<strong>{$error}</strong>
				</span><br/>	
				<div class="table">
					<div class="table_left">
						<label for="first_name">
							{required}{phrase var='user.first_name'}: <a href="#" id="name">[ ? ]</a>
						</label>
					</div>
					<div class="table_right">
						<input type="text" name="val[firstname]" id="firstname" maxlength="20">
					</div>
				</div>
				<div class="table">
					<div class="table_left">			
						<label for="last_name">
							{required}{phrase var='user.last_name'}:
						</label>
					</div>
					<div class="table_right">
						<input type="text" name="val[lastname]" id="lastname" maxlength="20">
					</div>				
				</div>			
				<br/>
				<div class="separate">
				</div><br/>
				<div class="table">
					<div class="table_left">
						<label for="address">
							{required}Address: <a href="#" id="address">[ ? ]</a>
						</label>
					</div>
					<div class="table_right">
						<input type="text" name="val[address]" id="address" maxlength="100">
					</div>
				</div>
				<div class="table">
					<div class="table_left">
						<label for="apt">
							Apartment Number:
						</label>
					</div>
					<div class="table_right">
						<input type="text" name="val[apt]" id="apt" maxlength="10">
					</div>
				</div>
			<div class="table">
				<div class="table_left">
					<label for="country">{required}{phrase var='user.location'}:</label>
				</div>
				<div class="table_right">
					{select_location}
					{module name='core.country-child' country_force_div=true}
				</div>		
			</div>
			<div class="table">
				<div class="table_left">
					<label for="city">
						{required}City:
					</label>
				</div>
				<div class="table_right">
					<input type="text" name="val[city]" id="city" maxlength="100">
				</div>
			</div>
			<div class="table">
				<div class="table_left">
					<label for="zip">
						{required}Postal/Zip Code:
					</label>
				</div>
				<div class="table_right">
					<input type="text" name="val[zip]" id="zip" maxlength="5">
				</div>
			</div><br/>
			<div class="separate">
			</div><br/>
			<div class="table">
				<div class="table_left">
					<label for="password1">
						{required}{phrase var='user.password'}: <a href="#" id="pass">[ ? ]</a>
					</label>
				</div>							
				<div class="table_right">
					<input type="password" name="val[password1]" id="password1" maxlength="64">
				</div>
			</div>
			<div class="table">
				<div class="table_left">
					<label for="password2">
						{required}Confirm Password:
					</label>
				</div>
				<div class="table_right">
					<input type="password" name="val[password2]" id="password2" maxlength="64">
				</div>
			</div><br/>
			<div class="separate">
			</div><br/>
			<div class="table">
				<div class="table_left">
					{required}Where do we pay you? <a href="#" id="gate">[ ? ]</a>
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
			</div>
			<div class="table">
				<div class="table_left">
					<label for="gate_mail">
						{required}Account Email:
					</label>
				</div>
				<div class="table_right">
					<input type="email" name="val[gateMail]" id="gate_mail" maxlength="64">
				</div>
			</div><br/>
			<div class="table">
				<div class="table_left">
					Need an account?
				</div>
				<div class="table_left">								
					<a href="{$paypal}" target="_blank">{img theme= 'buzzbiz/paypal.gif'}</a> 
					<a href="{$payza}" target="_blank">{img theme= 'buzzbiz/payza.gif'}</a>							
				</div>
			</div>
			<div class="separate">
			</div><br/>
			<div class="table">						
				<div class="table_left">
					<label for="ssn1">
						{required}SSN or TIN: <a href="#" id="ssn">[ ? ]</a>
					</label>
				</div>
				<div class="table_right">
					<input type="password" name="val[ssn1]" id="ssn" maxlength="9">
				</div>
			</div>
			<div class="table">
				<div class="table_left">
					<label for="ssn2">
						{required}Confirm SSN or TIN:
					</label>
				</div>
				<div class="table_right">
					<input type="password" name="val[ssn2]" id="ssn2" maxlength="9">
				</div>
			</div><br/>
			<div class="table"> 
				<div class="table_left">
					Need a TIN?
				</div>
				<div class="table_left">
					<a href="{$irs}" target="_blank">{img theme= 'buzzbiz/irsGov.png'}</a>
				</div>
			</div>
			<div class="separate">
			</div>
			<div class="table">
				<div class="table_left">
					<input type="checkbox" name="val[isterms]" value="1" id="terms">
					<label for="terms">
						{required}I've read and agree to the <a href="#" id="term">Terms</a>.
					</label>
				</div>
			</div>
			<div class="table">
				<div class="table_left">
					<div class="p_4">
						<span id="buzzbiz_required_text">
							<strong>{required}Required</strong>
						</span><br>
						<span id="buzzbiz_error">
							<strong>{$error}</strong>
						</span>	
					</div>
				</div>
			</div><br/>
			<div class="table">
				<div class="table_left">
					<input type="submit"  class="button"  value="Join" name="val[update]"/>
				</div> 
			</div>				
			</form>
		</div>
	</div>
	</body>
</html>