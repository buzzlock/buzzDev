<?php
		
		$aUserJob = $_SESSION['aUserJobPosting'];
		
		$iUser = $aUserJob['user_id'];

		$iCompany = Phpfox::getLib("database")
                        ->select('company_id')
                        ->from(Phpfox::getT('user_field'))
                        ->where('user_id =' .$iUser)
						->execute('getField');
						
		$aCompany = Phpfox::getService('jobposting.company')->getForEdit($iCompany);
		$title = Phpfox::getPhrase('jobposting.working_at');
		if($aCompany && $aCompany['is_deleted']==0){
			$link = PHpfox::getLib("url")->makeUrl("jobposting.company").$iCompany."/";
?>


<div class="info">
	<div class="info_left">
		<?php echo $title;?>:
	</div>	
	<div class="info_right">
		<a href="<?php echo $link; ?>"><?php echo $aCompany['name']; ?></a>
	</div>	
</div>

<?php
	}
?>