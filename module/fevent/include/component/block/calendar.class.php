<?php

/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
/**
 * 
 * 
 * @copyright       [YOUNET_COPYRIGHT]
 * @author          YouNet Company
 * @package         YouNet_Event
 */
class Fevent_Component_Block_Calendar extends Phpfox_Component
{
    public function process()
    {
        $sDate = str_replace('-', '/', $this->request()->get("date"));
        $aParentModule = $this->getParam('aParentModule');
        $bIsPage = $aParentModule['module_id'] == 'pages' ? $aParentModule['item_id'] : 0;
        $aUser = $this->getParam('aUser');
        $bIsProfile = !empty($aUser['user_id']) ? $aUser['user_id'] : false;
        $aJsEvents = Phpfox::getService('fevent')->getJsEvents($bIsPage, $bIsProfile);
        
        foreach ($aJsEvents as $iKey => $aEvent)
        {
            $aJsEvents[$iKey]['calendar'] = array();
            $is_repeat = $aEvent['isrepeat'];
            
            if ($is_repeat == 0 || $is_repeat == 1 || $is_repeat == 2)
            {
                $gmt_offset = $aEvent['start_gmt_offset']*3600;
                $start = $aEvent['start_time'] + $gmt_offset;
                $end = $aEvent['timerepeat'] + $gmt_offset;
                
                $hour = date('H', $start);
                $minute = date('i', $start);
                $day = date('d', $start);
                $month = date('m', $start);
                $year = date('Y', $start);
                
                while ($start <= $end)
                {
                    if ($is_repeat == 0)
                    {
                        $aJsEvents[$iKey]['calendar'][] = Phpfox::getTime('Y/m/d', $start-$gmt_offset);
                        $start += 86400; //1 day
                    }
                    elseif ($is_repeat == 1)
                    {
                        $aJsEvents[$iKey]['calendar'][] = Phpfox::getTime('Y/m/d', $start-$gmt_offset);
                        $start += 604800; //1 week
                    }
                    else
                    {
                        $aJsEvents[$iKey]['calendar'][] = Phpfox::getTime('Y/m/d', $start-$gmt_offset);
                        do
                        {
                            $month++;
                            if($month == 13)
                            {
                                $month = 1;
                                $year++;
                            }
                            $start = mktime($hour, $minute, 0, $month, $day, $year);
                        }
                        while(date('d', $start) != $day);
                    }
                }
            }
            else
            {
                $aJsEvents[$iKey]['calendar'][] = Phpfox::getTime('Y/m/d', $aEvent['start_time']);
            }
        }
        
        $this->template()->assign(array(
            'aJsEvents' => $aJsEvents,
            'sHeader' => Phpfox::getPhrase('fevent.calendar'),
            'sCorePath' => Phpfox::getParam('core.path'),
            'sDate' => $sDate,
            'sPhraseEvents' => Phpfox::getPhrase('fevent.menu_fevent_events_fad58de7366495db4650cfefac2fcd61')));

        return 'block';
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('fevent.component_block_calendar_clean')) ? eval($sPlugin) : false);
    }
}

?>