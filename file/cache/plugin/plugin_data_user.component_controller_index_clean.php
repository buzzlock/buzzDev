<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = '?>

<script type=\'text/javascript\'>
(function() { 
	$Behavior.removePhotoMenuFromPrivacySettingList = function () {
		if($("[name=\'val[privacy][photo.display_on_profile]\']").length >0) { 
			$("[name=\'val[privacy][photo.display_on_profile]\']").parent().parent().hide();
		}
	};


	$Behavior.removePhotoMenuFromItemSettingList = function () {
		if($("[name=\'val[photo.default_privacy_setting][photo.default_privacy_setting]\']").length >0) { 
			$("[name=\'val[photo.default_privacy_setting][photo.default_privacy_setting]\']").parent().parent().parent().parent().hide();
		}
	};
}());

</script>
<?php $sSuggestion = Phpfox::getPhrase(\'suggestion.recommendation_and_suggestion_settings\');
    $iPageId=1;
    if(!phpfox::getUserParam("suggestion.enable_friend_suggestion"))
        $iPageId=0;
    if(!phpfox::getUserParam("suggestion.enable_friend_recommend") && !phpfox::getUserParam("suggestion.enable_friend_suggestion_popup") && !phpfox::getUserParam("suggestion.enable_content_suggestion_popup"))
        $iPageId=0;
?>
<script type="text/javascript" language="javascript">
    $Behavior.onLoadPlugin_suggestion = function() 
    {
        $(".page_section_menu").find("li").find("a").click(function(evt) {
        $(\'#privacy_holder_table\').find("#js_privacy_block_suggestion").hide();
    });  
	        
    if ( ($("#content").find("ul").first().find("#suggestion_tab").length == 0) && ($("#content").find("ul").first().find("li").first().hasClass(\'active\')))
    {
        <?php 
            if ($iPageId)
            {
        ?>	
            $(\'#privacy_holder_table\').find("form").append($("<div id=\\"js_privacy_block_suggestion\\" class=\\"js_privacy_block page_section_menu_holder\\" style=\\"display: none;\\">"));
            $("#content").find("ul").first().append("<li><a ref=\\"js_privacy_block_suggestion\\" id=\\"suggestion_tab\\" href=\\"#\\" onclick=\\"showSuggestion(this); return false;\\"><?php echo $sSuggestion;?></a></li>");
    
    
            <?php 
                if ($this->request()->get(\'tab\') == \'suggestion_tab\')
                { 
            ?>	 
                    $("#suggestion_tab").click();  
            <?php
                }
            }
            ?>  
    }
    };
    function showSuggestion(obj){
    	
    	$(\'.page_section_menu_holder\').hide();
        $this = $(obj); 
    	$this.unbind();
        $(\'#privacy_holder_table\').find("#js_privacy_block_suggestion").load($.ajaxBox(\'suggestion.config\'));
        $(\'#privacy_holder_table\').find("#js_privacy_block_suggestion").show();

        $this.parents("ul").find(".active").removeClass("active");
        $this.parent().addClass("active");
    }
</script> '; ?>