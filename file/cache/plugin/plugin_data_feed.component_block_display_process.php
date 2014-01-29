<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = '//	THIS PLUGIN IS NOT USED defined(\'PHPFOX\') or exit(\'NO DICE!\');

    if(Phpfox::isModule(\'socialstream\'))
    {
        foreach ( $aRows as $iKey => $Row )
        {
            if(isset($Row[\'feed_image\']))
            {
                $aRows[$iKey][\'feed_image\'] = str_replace("&cfs=1", "", $Row["feed_image"]);
                if(isset($aRows[$iKey][\'more_feed_rows\']))
                {
                    foreach( $aRows[$iKey][\'more_feed_rows\'] as $iKey1 => $MoreRow)
                    {
                        if(isset($MoreRow[\'feed_image\']))
                        {
                            $aRows[$iKey][\'more_feed_rows\'][$iKey1][\'feed_image\'] = str_replace("&cfs=1", "", $MoreRow["feed_image"]);
                        }
                    }
                }
            }
        }
    } '; ?>