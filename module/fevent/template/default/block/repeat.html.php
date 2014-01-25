
<script src="{$core_path}module/fevent/static/jscript/jquery.ui.core.js"></script>
  
{literal}
<style type="text/css">
	.table_repeat{
		padding-bottom: 15px;
	}
	.table_left_repeat{
		float:left;
		width:60px; 
		line-height: 24px;
	}
</style>
{/literal}

	<div class="table_repeat">
		<div class="table_left_repeat">{phrase var='fevent.repeats'}:</div>
		<div class="table_right">
			<select id="selrepeat">
			<option value="0" {if $txtrepeat==0}selected{/if}>{phrase var='fevent.daily'}</option>
			<option value="1" {if $txtrepeat==1}selected{/if}>{phrase var='fevent.weekly'}</option>
			<option value="2" {if $txtrepeat==2}selected{/if}>{phrase var='fevent.monthly'}</option>
			</select>
		</div>
	</div>

	<div class="table_repeat">
		<div class="table_left_repeat">{phrase var='fevent.end'}:</div>
		<div class="table_right">
			<input type="text" id="end_on" readonly="true"/>
		</div>
	</div>

	<div class="table_repeat">
		<div class="table_left_repeat">&nbsp;</div>
		<div class="table_right">
			<input type="submit" value="{phrase var='fevent.done'}" onclick="donerepeat();"/>
			<input type="submit" value="{phrase var='fevent.cancel'}" onclick="cancelrepeat({$value});"/>
		</div>
	</div>

<script type="text/javascript">
	
	$(function() {l}	
		$("#end_on").datepicker().val("{if $daterepeat!=""}{$daterepeat}{/if}")

	{r});
	
	function donerepeat()
	{l}
		var selrepeat=$('#selrepeat').val();
		var txtdisable=$('#end_on').attr("disabled");
		var bIsEdit=$('#bIsEdit').val();
		if(!txtdisable)
		{l}
			txtdisable=$('#end_on').val();
		{r}
		else
			txtdisable="";
		$.ajaxCall('fevent.donerepeat','relrepeat='+selrepeat+'&txtdisable='+txtdisable+'&bIsEdit='+bIsEdit);
		
		tb_remove();
	{r}
	
	function cancelrepeat(value)
	{l}
		var bIsEdit=$('#bIsEdit').val();
		var txtdisable=$('#end_on').val();
		if(value!=2)
			$('#cbrepeat').removeAttr("checked");
		if(!bIsEdit && txtdisable=="")
			$('.extra_info').css('display','block');
			
		tb_remove();
	{r}
	
	{literal}
	setTimeout(function(){
		$('.js_box_close').css("display","none");
	},10);
	{/literal}
</script>