
<div id="menu_create_resume" class="page_section_menu page_section_menu_header">
    <ul>
        <li class="yns-add">
        	<a href="{url link='resume.add'}{if isset($id) && $id!=0}id_{$id}/{/if}">{phrase var='resume.basic_information'}</a>
        </li>
        
        {if $typesession>1}
        <li class="yns-summary"><a href="{url link='resume.summary'}{if isset($id) && $id!=0}id_{$id}/{/if}">{phrase var='resume.summary'}</a></li>
        {else}
        <li><span>{phrase var='resume.summary'}</span></li>
        {/if}
        
        {if $typesession>2}
        <li class="yns-experience"><a href="{url link='resume.experience'}{if isset($id) && $id!=0}id_{$id}/{/if}">{phrase var='resume.experience'}</a></li>
        {else}
        <li><span>{phrase var='resume.experience'}</span></li>
        {/if}
        
        {if $typesession>3}
        <li class="yns-education"><a href="{url link='resume.education'}{if isset($id) && $id!=0}id_{$id}/{/if}">{phrase var='resume.education'}</a></li>
        {else}
        <li><span>{phrase var='resume.education'}</span></li>
        {/if}
        
        {if $typesession>4}
        <li class="yns-skill"><a href="{url link='resume.skill'}{if isset($id) && $id!=0}id_{$id}/{/if}">{phrase var='resume.add_skill_expertise'}</a></li>
        {else}
        <li><span>{phrase var='resume.add_skill_expertise'}</span></li>
        {/if}
		{if $typesession>5}
		{literal}

		{/literal}
        <li class="yns-more-option">
			<a href="javascript:void(0)" >{phrase var='resume.more'}</a>
			<ul class="yns-viewmore-option">
				{if $typesession>5}
				<li class="yns-certi"><a href="{url link='resume.certification'}{if isset($id) && $id!=0}id_{$id}/{/if}">{phrase var='resume.certifications'}</a></li>
				{else}
				<li><span>{phrase var='resume.certifications'}</span></li>
				{/if}
				
				{if $typesession>6}
				<li class="yns-lang"><a href="{url link='resume.language'}{if isset($id) && $id!=0}id_{$id}/{/if}">{phrase var='resume.languages'}</a></li>
				{else}
				<li><span>{phrase var='resume.languages'}</span></li>
				{/if}
				
				{if $typesession>7}
				<li class="yns-public"><a href="{url link='resume.publication'}{if isset($id) && $id!=0}id_{$id}/{/if}">{phrase var='resume.publications'}</a></li>
				{else}
				<li><span>{phrase var='resume.publications'}</span></li>
				{/if}
				
				{if $typesession>8}
				<li class="yns-addition"><a href="{url link='resume.addition'}{if isset($id) && $id!=0}id_{$id}/{/if}">{phrase var='resume.additional_information'}</a></li>
				{else}
				<li><span>{phrase var='resume.additional_information'}</span></li>
				{/if}        
			</ul>
		</li>
		
		{else}
			<li><span>{phrase var='resume.more'}</span></li>
        {/if}
    </ul> 
</div>



<script type="text/javascript">
var typesession = '<?php echo $_SESSION['showmenu']; ?>';
{literal}
	$Behavior.loadMenuAdd = function(){
		if(typesession==0)
		{
			$('.yns-more-option ul').hide();
			$('.yns-more-option > a').addClass('more-down');
		}
		else
		{
			$('.yns-more-option ul').show();
			$('.yns-more-option').css('padding-bottom','26px');
			$(this).removeClass('more-down');
		}
		$('.yns-more-option > a').bind('click',function() {
			  if($(this).hasClass('more-down'))
			  {
				$('.yns-more-option').css('padding-bottom','26px');
				$('.yns-more-option ul').slideDown();
				$(this).removeClass('more-down');
			  }
			  else
			  {
				$('.yns-more-option ul').slideUp();
				$('.yns-more-option').css('padding-bottom','0px');
				$(this).addClass('more-down');
			  }
	        }
		);
	};
	{/literal}
</script>

