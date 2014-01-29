<?php
	$user_id_viewer = Phpfox::getUserId();
    $bViewResumeRegistration = Phpfox::getService('resume.account')->checkViewResumeRegistration($user_id_viewer);
	$_SESSION['bViewResumeRegistration'] = $bViewResumeRegistration;
?>
<script type="text/javascript">
	$Behavior.closeNote = function(){
		$('#aToolTip').remove();
	};
</script>

<?php ?>
<script type="text/javascript">
	oCore['core.disable_hash_bang_support'] = 1;
</script>
<?php ?>