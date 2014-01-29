
{literal}
<style type="text/css">
	.account{
		line-height: 30px;
	}
	.account_right{
		margin-left: 100px;
		display:block;
	}
	.account_left{
		float:left;
		display:block;
	}
</style>
{/literal}

{literal}
<style>
	#right{
	display:none;
	}
	.content3{
		width: 100% !important;
	}
	#content_holder{
		overflow:hidden;
	}
</style>
{/literal}

{if $type=="employee"}
	{template file='resume.block.service_whoview'}
{/if}
{if $type=="employer" || $type=="employ"}
	{template file='resume.block.service_view'}
{/if}
{if $type==""}
	<div>
		{phrase var='resume.please_choose_the_service_which_you_want_to_use'}
		<div style="padding-top: 10px; margin-left: 30px;">
			<a href="{$url}account/type_employer/"><input type="button" style="cursor: pointer;" value="{phrase var='resume.view_resume'}"/></a>
			<a href="{$url}account/type_employee/"><input type="button" style="cursor: pointer;" value="{phrase var='resume.who_viewed_me'}"/></a>
			<a href="{$url}account/type_employ/"><input type="button" style="cursor: pointer;" value="{phrase var='resume.both'}"/></a>
		</div>
	</div>
{/if}

