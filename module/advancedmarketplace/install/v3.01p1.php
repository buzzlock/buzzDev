<?php

defined('PHPFOX') or exit('NO DICE!');

/**
 * create DATABASE For version 3.01p4
 * @by datlv
 *
 */
function ynam_install301p1() {
    //cheat phrase
    Phpfox::getLib('database')->query("
        UPDATE `" . Phpfox::getT('language_phrase') . "`
            SET `text` = 'Listings that Might Interest You', `text_default` = 'Listings that Might Interest You'
            WHERE `var_name` = 'listing_you_may_interested'
            AND `module_id` = 'advancedmarketplace'
            AND `product_id` = 'advanced_marketplace'
            AND `language_id` = 'en'
    ");

}

ynam_install301p1();

?>