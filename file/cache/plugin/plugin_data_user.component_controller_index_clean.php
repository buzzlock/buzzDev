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
<?php '; ?>