
{literal}
<script type="text/javascript">


	$Behavior.initContestFormValidation = function(){
		$('.yncontest_add_edit_form').each(function(index) {
			yncontest.initializeValidator($(this));

		});
	};
</script>

<script type="text/javascript">
   $Behavior.setContestDescEditor = function(){
         Editor.setId("yn_contest_add_description");

        $("a[rel='js_contest_block_main']").bind("click", function(){
            Editor.setId("yn_contest_add_description");
        });

        $("a[rel='js_contest_block_email_conditions']").bind("click", function(){
            Editor.setId("message");
        });

     };

</script>

{/literal}

{if $bIsEdit && $sTab != ''}
{literal}
<script type="text/javascript">
    $Behavior.yncontestPageSectionMenuRequest = function() {
        $Core.pageSectionMenuShow('#js_contest_block_{/literal}{$sTab}{literal}');
    }

</script>
{/literal}
{/if}


{template file='contest.block.contest.add-edit.form-main-info'}

{if ($bIsEdit)}
	{template file='contest.block.contest.add-edit.form-email-condition'}
	{module name='contest.contest.add-edit.form-invite-friend' contest_id=$aForms.contest_id}
	{template file='contest.block.contest.add-edit.form-setting'}
{/if}

{if $bIsEdit && $bIsHavingPublish}

<script type="text/javascript">
    setTimeout(function() {l}
        yncontest.addContest.showPayPopup({$aForms.contest_id});
    {r}, 1000);
   
</script>
{/if}




