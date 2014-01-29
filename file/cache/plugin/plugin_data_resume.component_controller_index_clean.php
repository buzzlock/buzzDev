<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = '$title_search = Phpfox::getPhrase(\'resume.advanced_search\');
	$view = $this->request()->get(\'view\');
	$textImport = Phpfox::getPhrase(\'resume.import_from_linkedin\');
	$textCreate = Phpfox::getPhrase(\'resume.create_new_resume\');
	$numberofresume = Phpfox::getService("resume.basic")->getItemCount(\'rbi.user_id=\'.Phpfox::getUserId());
	$total_allowed = Phpfox::getUserParam("resume.maximum_resumes");
	$is_import = true;
	$bIsAddedModule = Phpfox::isModule(\'socialbridge\');
	if($total_allowed > 0 && $numberofresume >= $total_allowed)
	{
		$is_import = false;
	}
	$core_path = Phpfox::getParam("core.path");
	$returnUrl = PHpfox::getLib("url")->makeUrl("resume.import");
	$url = $core_path.\'module/socialbridge/static/php/linkedin.php?callbackUrl=\'.urlencode($returnUrl);
	$icon = \'<img class="v_middle" alt="" src="\'.$core_path.\'theme/frontend/default/style/default/image/layout/section_menu_add.png">\';
	if(PHpfox::getLib("module")->getControllerName()=="index" && !$view)
	{
?>

<script type="text/javascript">
   $Behavior.loadContentResume = function(){
	var content = \'<span style="margin-left:90px;position:absolute;left:100px;top:16px;"><a onclick="advSearchDisplay();return false;tb_show(\\\'<?php echo $title_search; ?>\\\',$.ajaxBox(\\\'resume.advancedsearch\\\'))" href="javascript:void(0)"><?php echo $title_search; ?></a></span>\';
	$(\'.header_bar_search\').append(content);
        }
</script>

<?php 
	}
?>

<script language="javascript">
    
    function openWindow(anchor, options) {
 	
        var args = \'\';
        
        if (typeof(options) == \'undefined\') { var options = new Object(); }
        if (typeof(options.name) == \'undefined\') { options.name = \'win\' + Math.round(Math.random()*100000); }
        
        if (typeof(options.height) != \'undefined\' && typeof(options.fullscreen) == \'undefined\') {
            args += "height=" + options.height + ",";
        }
        
        if (typeof(options.width) != \'undefined\' && typeof(options.fullscreen) == \'undefined\') {
            args += "width=" + options.width + ",";
        }
        
        if (typeof(options.fullscreen) != \'undefined\') {
            args += "width=" + screen.availWidth + ",";
            args += "height=" + screen.availHeight + ",";
        }
        
        if (typeof(options.center) == \'undefined\') {
            options.x = 0;
            options.y = 0;
            args += "screenx=" + options.x + ",";
            args += "screeny=" + options.y + ",";
            args += "left=" + options.x + ",";
            args += "top=" + options.y + ",";
        }
        
        if (typeof(options.center) != \'undefined\' && typeof(options.fullscreen) == \'undefined\') {
            options.y=Math.floor((screen.availHeight-(options.height || screen.height))/2)-(screen.height-screen.availHeight);
            options.x=Math.floor((screen.availWidth-(options.width || screen.width))/2)-(screen.width-screen.availWidth);
            args += "screenx=" + options.x + ",";
            args += "screeny=" + options.y + ",";
            args += "left=" + options.x + ",";
            args += "top=" + options.y + ",";
        }
        
        if (typeof(options.scrollbars) != \'undefined\') { args += "scrollbars=1,"; }
        if (typeof(options.menubar) != \'undefined\') { args += "menubar=1,"; }
        if (typeof(options.locationbar) != \'undefined\') { args += "location=1,"; }
        if (typeof(options.resizable) != \'undefined\') { args += "resizable=1,"; }
        
        var win = window.open(anchor, options.name, args);
        return false;
        
    }
    
    $Behavior.loadMenuResume = function(){
    $().ready(function(){
        
           

    	<?php if(Phpfox::getUserParam(\'resume.can_create_resumes\')){?>
                 if(!$Core.exists(\'#create_resume\')){
	    	$(\'#section_menu\').eq(0).find(\'ul\').append(\'<li id="create_resume"><a href="<?php echo Phpfox::getLib("url")->makeUrl(\'resume.add\'); ?>"><?php  echo $icon.$textCreate;?></a></li>\');
                }
	    	<?php if($bIsAddedModule){?>
                    if(!$Core.exists(\'#resume_import\')){
		    	<?php if($is_import){?>
                            
		    		$(\'#section_menu\').eq(0).find(\'ul\').append(\'<li id="resume_import"><a onclick="openWindow(\\\'<?php echo $url; ?>\\\',{width:430,height:550,center:true});return false;" href="#"><?php  echo $icon.$textImport;?></a></li>\');
                           
		    	<?php }else{ ?>
		    		$(\'#section_menu\').eq(0).find(\'ul\').append(\'<li id="resume_import"><a onclick="$Core.box(\\\'resume.alertimport\\\');return false;" href="#"><?php  echo $icon.$textImport;?></a></li>\');	
		    	<?php } ?>
                             }
		    <?php } ?>    
    	<?php } ?>  
    	
		$(\'#section_menu\').eq(0).find(\'ul>li\').each(function(item,value){
		    if($(value).attr(\'id\')!="viewresume" && $(value).attr(\'id\')!="create_resume" && $(value).attr(\'id\')!="resume_import")
		    {
		       $(value).remove();  
		    }
	    }); 
    });
    

}
</script> '; ?>