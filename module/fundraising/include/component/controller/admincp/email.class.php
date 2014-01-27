<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Controller_Admincp_Email extends Phpfox_Component
{
	/**
	 * Class process method which is used to execute this component.
	 */
	public function process()
	{

		$iTypeId = 0;
        if ($this->request()->getArray('val')) {
            $aVals = $this->request()->getArray('val');

            if($aVals['type_id'] != 0)
			{
				$iTypeId = $aVals['type_id'];
                Phpfox::getService('fundraising.mail.process')->addEmailTemplate($aVals);
			}
        }

        $aValidation = array(
            'type_id' => array(
                'title' => Phpfox::getPhrase('fundraising.select_a_email_template_type'),
                'def' => 'required'
            ),
        );

        $oValidator = Phpfox::getLib('validator')->set(array('sFormName' => 'js_form', 'aParams' => $aValidation));

        $aTypes = Phpfox::getService('fundraising.mail')->getAllTypes();

        $this->template()->assign(array(
            'aTypes' => $aTypes,
			'iCurrentTypeId' => $iTypeId,
            'sCreateJs' => $oValidator->createJS(),
            'sGetJsForm' => $oValidator->getJsForm(),
        ))
        ->setTitle(Phpfox::getPhrase('fundraising.fundraising_title'))
        ->setBreadCrumb(Phpfox::getPhrase('fundraising.fundraising_title'),  $this->url()->makeUrl('admincp.fundraising.email'))
        ->setBreadCrumb(Phpfox::getPhrase('fundraising.email_templates'), null, true)
        ->setEditor();
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('fundraising.component_controller_admincp_email_clean')) ? eval($sPlugin) : false);
	}
}

?>