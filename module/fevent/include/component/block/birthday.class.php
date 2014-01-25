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
class Fevent_Component_Block_Birthday extends Phpfox_Component
{
    public function process()
    {
        if (!Phpfox::getParam('friend.enable_birthday_notices'))
        {
            return false;
        }
        
        if (!Phpfox::isUser())
        {
            return false;
        }

        $aBirthdays = Phpfox::getService('friend')->getBirthdays(Phpfox::getuserId());

        $bIsEventSection = (Phpfox::getLib('module')->getFullControllerName() == 'fevent.index' ? true : false);
        if (!Phpfox::isModule('fevent'))
        {
            $bIsEventSection = true;
        }
        
        if ($bIsEventSection && empty($aBirthdays) && (Phpfox::getParam('friend.show_empty_birthdays') == false))
        {
            return false;
        }
        
        $aUpcomingEvents = array();
        if (!$bIsEventSection)
        {
            $this->search()->set(array(                
                    'type' => 'fevent',
                    'field' => 'm.event_id',                
                    'search_tool' => array(
                        'default_when' => 'upcoming',
                        'when_field' => 'start_time',
                        'when_upcoming' => true,
                        'table_alias' => 'm',    
                        'sort' => array(
                            'latest' => array('m.start_time', 'Latest', 'ASC')                        
                        ),
                        'show' => array(5)
                    )
                )
            );            
            
            $aBrowseParams = array(
                'module_id' => 'fevent',
                'alias' => 'm',
                'field' => 'event_id',
                'table' => Phpfox::getT('fevent'),
                'hide_view' => array('pending', 'my')
            );            
            
            $this->search()->setCondition('AND m.view_id = 0 AND m.privacy IN(%PRIVACY%)');
            $this->search()->browse()->params($aBrowseParams)->execute();
            
            $aUpcomingEvents = $this->search()->browse()->getRows();
        }
        
        $this->template()->assign(array(
                'aSearchTool' => '',
                'aUpcomingEvents' => $aUpcomingEvents,
                'aBirthdays' => $aBirthdays,
                'bIsEventSection' => $bIsEventSection,
                'sHeader' => ($bIsEventSection ? Phpfox::getPhrase('friend.birthdays') : Phpfox::getPhrase('fevent.upcoming_events')),
                //'sDeleteBlock' => 'dashboard'
            )
        );

        return 'block';
    }
}