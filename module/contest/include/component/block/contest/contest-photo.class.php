<?php

defined('PHPFOX') or exit('NO DICE!');

class Contest_Component_Block_Contest_Contest_Photo extends Phpfox_component
{
    public function process()
    {
        $aContest = $this->getParam('aContest');
        
        $aButtonColor = array(
            'bgcolor_1' => Phpfox::getParam('contest.contest_buttons_bgcolor_1'),
            'bgcolor_2' => Phpfox::getParam('contest.contest_buttons_bgcolor_2'),
            'text_color' => Phpfox::getParam('contest.contest_buttons_text_color')
        );
        
        $aButtonColor['border_color'] = ($aButtonColor['bgcolor_2'] == '#2289FF') ? '#3075FF' : $aButtonColor['bgcolor_2'];
        
        $this->template()->assign(array(
            'aItem' => $aContest,
            'aButtonColor' => $aButtonColor
        ));
    }
}

?>