<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'foreach ($this->_aEvilEvents as $sKey => $sValue)
{
	if(preg_match(\'/\' . $sValue . \'/i\', strtolower(Phpfox::getParam(\'core.host\'))))
	{
		unset($this->_aEvilEvents[$sKey]);
	}
} if(!Phpfox::isMobile(false))
{
	if (Phpfox::getParam(\'core.wysiwyg\') == \'tiny_mce\')
	{
		foreach ($this->_aEvilEvents as $sKey => $sValue)
		{
			if ($sValue == \'style\')
			{
				unset($this->_aEvilEvents[$sKey]);
			}
		}
	}
} '; ?>