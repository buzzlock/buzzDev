<?php

defined('PHPFOX') or exit('NO DICE!');

class Contest_Component_Block_Statistic extends Phpfox_component {

    public function process() {
        $aStatistic = Phpfox::getService('contest.contest')->getStatistic();
		
        $this->template()->assign(array(
            'sHeader' => Phpfox::getPhrase('contest.statistic'),
            'aStatistic' => $aStatistic
                )
        );

        return 'block';
    }

}