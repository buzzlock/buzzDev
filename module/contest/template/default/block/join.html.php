<form method="post" action="{url link='current'}" id='yncontest_join_form'>

    <div id="core_js_messages">
        <div class="error_message" style='display:none' id='yncontest_must_agree'> {phrase var='contest.you_must_agree_with_terms_and_conditions_to_join_this_contest'}</div>
    </div>
    
    <div class="table_right">
        {if $aContest.term_condition}
        <p class='yncontest_term_condition_scroll'>
        {$aContest.term_condition}
        </p>
        {/if}
    </div>

    <div class="clear" style='margin-top:15px'></div>
    
    <label><input type="checkbox"  id='yncontest_join_agree_term_condition'  name='val[agree_join]' /> {phrase var='contest.i_agree'}</label>

    <div class="clear" style='margin-top:15px'></div>

    <div class="table_clear">
        <ul class="table_clear_button">
            <li> <input type='button' id='yncontest_join_button' class="button" name='val[submit]' value='{phrase var='contest.join'}' onclick="yncontest.join.submitJoinContest({$aContest.contest_id}); return false;" /> </li>
        </ul>
        <div class="clear"></div>
    </div>

    <div id ="yn_contest_waiting_join" style='display:none'> {img theme='ajax/add.gif'} </div>

</form>
