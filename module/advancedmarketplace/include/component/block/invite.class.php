<?php


defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Component_Block_Invite extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		if (!Phpfox::isUser())
		{
			return false;
		}
		$bIsViewMore = false;
		$iLimit = 5;
		list($iCnt, $aEventInvites) = Phpfox::getService('advancedmarketplace')->getUserInvites($iLimit);
		if(empty($aEventInvites))
		{
			$this->template()->assign(array(
                    'aEventInvites' => NULL,
				));
			return false;
		}
		if($iCnt > $iLimit)
		{
			$bIsViewMore = true;
		}
		$this->template()->assign(array(
                    'sHeader' => Phpfox::getPhrase('advancedmarketplace.invites'),
                    'corepath'=>phpfox::getParam('core.path'),
                    'aEventInvites' => $aEventInvites,
					'bIsViewMore' => $bIsViewMore
				));

		if ($iCnt)
		{
			$this->template()->assign(array(
					'aFooter' => array(
						'View All (' . $iCnt . ')' => $this->url()->makeUrl('advancedmarketplace', array('view' => 'invites'))
					)
				)
			);
		}

		return 'block';
	}

	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_block_invite_clean')) ? eval($sPlugin) : false);
	}
}

?>