<?php

defined('PHPFOX') or exit('NO DICE!');

class Mfox_Component_Ajax_Ajax extends Phpfox_Ajax {
    /**
     * Manage navigation ordering.
     */
    public function manageNavigationOrdering()
    {
        /**
         * @var array
         */
        $aVals = $this->get('val');
        Phpfox::getService('mfox.navigation')->updateOrdering($aVals['ordering']);
    }
    /**
     * Update navigation status.
     */
    public function updateNavigationStatus()
    {
        Phpfox::getService('mfox.navigation')->updateNavigationStatus($this->get('id'), $this->get('active'));
    }
    /**
     * Update style status.
     */
    public function updateStyleStatus()
    {
        Phpfox::getService('mfox.style')->updateStyleStatus($this->get('id'), $this->get('active'));
    }
}

?>