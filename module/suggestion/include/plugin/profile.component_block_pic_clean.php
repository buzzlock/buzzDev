<?php 

if (Phpfox::isModule('suggestion') && Phpfox::isUser()){
    

$sSuggestionLink = Phpfox::permalink('suggestion', null);
$sSuggestion = Phpfox::getPhrase('suggestion.suggestion');
$iTotalSuggestion = (int)Phpfox::getService('suggestion')->getTotalIncomingSuggestion(Phpfox::getUserId());

$sUserName = $this->request()->get('req1');
if ($sUserName == Phpfox::getUserBy('user_name')){
    

/*display total suggestion from 0*/
/*if ($iTotalSuggestion>1){*/
    $sSuggestion .= '<span>('.$iTotalSuggestion.')</span>';
/*}*/

$sBg = Phpfox::getParam('core.path')."module/suggestion/static/image/suggestion.png";
?>
<script language="javascript">
    $Behavior.loadPluginSuggestionBlockPic = function(){
        $().ready(function(){
            if (!$Core.exists('#suggestion_leftbar')){
                $('.sub_section_menu').find('ul').eq(0).append('<li class="suggestion" id="suggestion_leftbar"><a style="background:url(<?php  echo $sBg?>) no-repeat 0 50%;"href="<?php  echo $sSuggestionLink?>"><?php  echo $sSuggestion?></a></li>');
                $('.timeline_main_menu').find('ul').eq(0).append('<li class="suggestion"><a style="" href="<?php  echo $sSuggestionLink?>"><?php  echo $sSuggestion?></a></li>');
             }
        });    
    }
</script>
<style>
    .suggestion:hover{background-color:#EFF9FF}
</style>
<?php }?>

<?php } /*end check module*/?>