<script src="{$corepath}mobiletemplate/static/jscript/idangerous.swiper-2.0.min.js" type="text/javascript"></script>
<script src="{$corepath}mobiletemplate/static/jscript/movies-app.js" type="text/javascript"></script>
<div class="header_profile" id="no-drag" data-snap-ignore="true">
	<ul class="navItems" id="scroller">
		<div class="swiper-container swiper-nav">
			<div class="swiper-wrapper">
				<div class="swiper-slide ">
					<li class="navItem slide" id="m_i_about">
						<a href="{url link=''$aUser.user_name'.info'}">
						<div class="navContent">
							<i class="icon-about"></i>
						</div>
						<div class="navCaption">
							{phrase var='mobiletemplate.about'}
						</div> </a>
					</li>
				</div>
				<div class="swiper-slide">
					<li class="navItem slide" id="m_i_friends">
						<a href="{url link=''$aUser.user_name'.friend'}">
						<div class="navContent">

							<div class="facepile">

								{if $countFriends == 1}
								<i class="facepile-full" style="background-image: url({$listOfFriendImage.0.large});"></i>
								{*$listOfFriendImage.0.small*}

								{/if}
								{if $countFriends == 2}
								<i class="facepile-2" style="background-image: url({$listOfFriendImage.0.large});"></i>
								<i class="facepile-2" style="background-image: url({$listOfFriendImage.1.large});"></i>
								{/if}
								{if $countFriends > 2 && $countFriends < 6}
								<i class="facepile-2 facebig" style="background-image: url({$listOfFriendImage.0.large});"></i>
								<div class="facepileRow">
									<i class="facepileItem" style="background-image: url({$listOfFriendImage.1.large});"></i>
									<i class="facepileItem" style="background-image: url({$listOfFriendImage.2.large});"></i>
								</div>
								{/if}
								{if $countFriends >= 6}
								<div class="facepileRow">
									<i class="facepileItem" style="background-image: url({$listOfFriendImage.0.small})"></i>
									<i class="facepileItem" style="background-image: url({$listOfFriendImage.1.small})"></i>
									<i class="facepileItem" style="background-image: url({$listOfFriendImage.2.small})"></i>
								</div>
								<div class="facepileRow">
									<i class="facepileItem" style="background-image: url({$listOfFriendImage.3.small})"></i>
									<i class="facepileItem" style="background-image: url({$listOfFriendImage.4.small})"></i>
									<i class="facepileItem" style="background-image: url({$listOfFriendImage.5.small})"></i>
								</div>
								{/if}

							</div>

						</div>
						<div class="navCaption">
							{phrase var='mobiletemplate.friends'}
						</div> </a>
					</li>
				</div>
				<div class="swiper-slide ">
					<li class="navItem slide" id="m_i_photos">
						<a href="{url link=''$aUser.user_name'.photo'}">
						<div class="navContent">
							{if isset($latestPhotoPathLarge)}
							<i class="l_photo" style="background-image: url({$latestPhotoPathLarge})"></i>
							{/if}
							
						</div>
						<div class="navCaption">
							{phrase var='mobiletemplate.photos'}
						</div> </a>
					</li>
				</div>
				<div class="swiper-slide ">
					<li class="navItem slide" id="m_i_likes">
						<a href="{url link=''$aUser.user_name'.pages'}"> <div class="navContent">

						</div>
						<div class="navCaption">
							{phrase var='mobiletemplate.likes'}
						</div> </a>
					</li>
				</div>
				<div class="swiper-slide ">
					<li class="navItem slide" id="m_i_note">
						<a href="{url link=''$aUser.user_name'.blogs'}"> <div class="navContent">

						</div>
						<div class="navCaption">
							{phrase var='mobiletemplate.blog'}
						</div> </a>
					</li>
				</div>
			</div>
		</div>
	</ul>
</div>