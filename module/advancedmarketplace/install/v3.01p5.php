<?php

defined('PHPFOX') or exit('NO DICE!');

/**
 * create DATABASE For version 3.01p5
 * @by datlv
 *
 */
function ynam_install301p5() {
    $oDatabase = Phpfox::getLib('database') ;

    if (Phpfox::isModule('socialpublishers'))
    {
        $aRow = $oDatabase->select('*')
            ->from(Phpfox::getT('socialpublishers_modules'))
            ->where('product_id = "advanced_marketplace" AND module_id = "module_id"')
            ->execute('getRow');

        if(!isset($aRow) && !$aRow)
        {
            $oDatabase->insert(Phpfox::getT('socialpublishers_modules'),
                array(
                    'product_id' => 'advanced_marketplace',
                    'module_id' => 'advancedmarketplace',
                    'title' => 'advancedmarketplace.advancedmarketplace',
                    'is_active' => 1,
                    'facebook' => 1,
                    'twitter' => 1,
                    'linkedin' => 1,
                )
            );
        }
    }
}

ynam_install301p5();

?>