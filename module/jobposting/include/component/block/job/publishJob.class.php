<?php

defined('PHPFOX') or exit('NO DICE!');

class jobposting_component_block_job_PublishJob extends Phpfox_component
{
    public function process()
    {
        $iJob = $this->request()->get('id');
        $bCanFeature = Phpfox::getService('jobposting.permission')->canFeaturePublishedJob($iJob);
        
        $iCompanyId = Phpfox::getService('jobposting.company')->getCompanyIdByUserId(Phpfox::getUserId());
		if (!$iCompanyId)
		{
			return Phpfox_Error::display(Phpfox::getPhrase('jobposting.you_have_not_created_a_company'));
		}
		
		$aPackages = Phpfox::getService('jobposting.package')->getBoughtPackages($iCompanyId, true);
        $aTobuyPackages = Phpfox::getService('jobposting.package')->getToBuyPackages($iCompanyId);
		
		$iCnt = count((array)$aPackages) + count((array)$aTobuyPackages);
		if ($iCnt == 0)
		{
			return Phpfox_Error::display(Phpfox::getPhrase('jobposting.unable_to_find_any_package'));
		}
		
        $this->template()->assign(array(
            'aPackages' => $aPackages,
            'aTobuyPackages' => $aTobuyPackages,
            'featurefee' => PHpfox::getService('jobposting.helper')->getTextParseCurrency(Phpfox::getParam("jobposting.fee_feature_job")),
            'iJob' => $iJob,
            'bCanFeature' => $bCanFeature
        ));

    }

}
