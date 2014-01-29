<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = '$aUserJob = $_SESSION[\'aUserJobPosting\'];
		
		$iUser = $aUserJob[\'user_id\'];

		$iCompany = Phpfox::getLib("database")
                        ->select(\'company_id\')
                        ->from(Phpfox::getT(\'user_field\'))
                        ->where(\'user_id =\' .$iUser)
						->execute(\'getField\');
						
		$aCompany = Phpfox::getService(\'jobposting.company\')->getForEdit($iCompany);
		$title = Phpfox::getPhrase(\'jobposting.working_at\');
		if($aCompany && $aCompany[\'is_deleted\']==0){
			$link = PHpfox::getLib("url")->makeUrl("jobposting.company").$iCompany."/";
?>


<div class="info">
	<div class="info_left">
		<?php echo $title;?>:
	</div>	
	<div class="info_right">
		<a href="<?php echo $link; ?>"><?php echo $aCompany[\'name\']; ?></a>
	</div>	
</div>

<?php
	} $position_display = 1;
$aUserResume = $_SESSION[\'aUserResume\'];

   $aResume = Phpfox::getLib("database")
                    ->select(\'*\')
                    ->from(Phpfox::getT(\'resume_basicinfo\'))
                    ->where(\'status = "approved" and is_published = 1 and is_show_in_profile = 1 AND user_id = \' .  $aUserResume[\'user_id\'])
                    ->execute(\'getRow\');
  $allPermission = Phpfox::getService(\'resume.setting\')->getAllPermissions();
  if(isset($allPermission[\'position\']))
  {
      $position_display = $allPermission[\'position\'];
  }
  if($aResume && isset($allPermission[\'display_resume_in_profile_info\']) && $allPermission[\'display_resume_in_profile_info\']==1){
    $aCats = Phpfox::getService(\'resume.category\')->getCatNameList($aResume[\'resume_id\']);
    $aPrevious_job = Phpfox::getService(\'resume.experience\')->getLastWork($aResume[\'resume_id\']);
    $aResume[\'previous_job\'] = Phpfox::getPhrase(\'resume.n_a\');
    if($aPrevious_job){
        if($aPrevious_job[\'level_id\']>0)
        {
            $aResume[\'previous_job\'] = Phpfox::getService(\'resume.level\')->getLevelById($aPrevious_job[\'level_id\']);
        }
    }
    $highest_education = Phpfox::getService(\'resume.education\')->getLastEducation($aResume[\'resume_id\']);
    $aResume[\'highest_education\'] = Phpfox::getPhrase(\'resume.n_a\');
    if($highest_education){
        $aResume[\'highest_education\'] = $highest_education[\'school_name\'];
    }
?>
<div id="workresume" style="display:block;">
    <?php if($position_display==2){?>
<div class="title" style="margin-bottom: 9px;">
    <?php echo Phpfox::getPhrase(\'resume.work\'); ?>
</div>
    <?php } ?>
<div class="info">
	<div class="info_left">
		<?php echo Phpfox::getPhrase(\'resume.resume_name\'); ?>:
	</div>	
	<div class="info_right">
		<?php echo $aResume[\'headline\'];?>
	</div>	
</div>

<div class="info">
	<div class="info_left">
		<?php echo Phpfox::getPhrase(\'resume.category\'); ?>:
	</div>	
	<div class="info_right">
		
                <?php if(count($aCats)>0){
                    foreach($aCats as $key=>$aCat)
                        if($key == 0){
                    ?>
                    <a href="<?php echo Phpfox::getLib("url")->makeUrl(\'resume.category\').$aCat[\'category_id\']."/".$aCat[\'name_url\']."/"; ?>"><?php echo Phpfox::getLib(\'locale\')->convert($aCat[\'name\']); ?></a>
                        <?php }else{ ?>
                            | <a href="<?php echo Phpfox::getLib("url")->makeUrl(\'resume.category\').$aCat[\'category_id\']."/".$aCat[\'name_url\'];?>"><?php echo Phpfox::getLib(\'locale\')->convert($aCat[\'name\'])."/"; ?></a>
                  <?php }} ?>
	</div>	
</div>

<div class="info">
	<div class="info_left">
		<?php echo Phpfox::getPhrase(\'resume.previous\'); ?>:
	</div>	
	<div class="info_right">
		<?php echo $aResume[\'previous_job\'];?>
	</div>	
</div>

<div class="info">
	<div class="info_left">
		<?php echo Phpfox::getPhrase(\'resume.education\'); ?>:
	</div>	
	<div class="info_right">
		<?php echo $aResume[\'highest_education\'];?>
	</div>	
</div>

<div class="info">
	<div class="info_left">
		<?php echo Phpfox::getPhrase(\'resume.summary\'); ?>:
	</div>	
	<div class="info_right">
		<?php echo $aResume[\'summary_parsed\'];?>
	</div>	
</div>

<div class="info">
    <?php echo Phpfox::getPhrase(\'resume.to_view_full_resume_visit_a_href_link_here_a\', array(
        \'link\' => Phpfox::getLib("url")->makeUrl(\'resume.view\').$aResume[\'resume_id\']."/",
    )); ?>
</div>
    </div>
<?php if($position_display==1){?>
<script>
    $Behavior.moveBlock = function(){
        $(\'document\').ready(function(){
            $(\'#js_basic_info_data\').prepend($(\'#workresume\').html());
            $(\'#workresume\').html(\'\');
        });
    }
</script>

<?php
  }} '; ?>