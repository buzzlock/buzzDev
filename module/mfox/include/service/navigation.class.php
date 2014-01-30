<?php
/**
 * @package mfox
 * @version 3.01
 */
defined('PHPFOX') or exit('NO DICE!');
/**
 * @author ductc@younetco.com
 * @package mfox
 * @subpackage mfox.service
 * @version 3.01
 * @since June 5, 2013
 * @link Mfox Api v3.0
 */
class Mfox_Service_Navigation extends Phpfox_Service {

    /**
     * Get navications for menu.
     * @return array
     */
    public function getNavigations()
    {
        /**
         * @var string
         */
        $sCacheId = $this->cache()->set('mfox_leftnavi');

        if (!($aRows = $this->cache()->get($sCacheId)))
        {
            /**
             * @var array
             */
            $aRows = $this->database()
                    ->select('*')
                    ->from(Phpfox::getT('mfox_leftnavi'))
                    ->order('sort_order ASC')
                    ->execute('getSlaveRows');

            $this->cache()->save($sCacheId, $aRows);
        }
        
        if (!is_array($aRows))
        {
            return array();
        }
        
        return $aRows;
    }
    /**
     * Update ordering.
     * @param array $aVal
     */
    public function updateOrdering($aVal)
    {
        foreach ($aVal as $iId => $iPosition)
        {
            $this->database()->update(Phpfox::getT('mfox_leftnavi'), array('sort_order' => (int) $iPosition), 'id = ' . (int) $iId);
        }
        $this->renewCache();
    }
    /**
     * Renew cache.
     */
    public function renewCache()
    {
        // clean the cache
        $sCacheId = $this->cache()->set('mfox_leftnavi');
        $this->cache()->remove($sCacheId);

        // reset the cache
        $this->cache()->set('mfox_leftnavi', 'navigation');
        $this->cache()->save('mfox_leftnavi', $this->getNavigations());
    }
    /**
     * Using to update multiple navigations.
     * @param array $aNavigations
     * @return bool
     */
    public function updateMultiple($aNavigations)
    {
        $bResult = true;
        foreach ($aNavigations as $aNavigation)
        {
            $bUpdate = $this->database()
                    ->update(Phpfox::getT('mfox_leftnavi'), array(
                'label' => $aNavigation['label']
                    ), 'id = ' . (int) $aNavigation['id']);
            $bResult = $bResult && $bUpdate;
        }
        // renew cache even if the update failed, it may have updated a few only
        $this->renewCache();
        return $bResult;
    }

    /**
     * Deletes one or more navigation entries from the DB, also renews cache
     * 
     * @param array $aIds only integers that correspond to their id in this table.
     * @return true
     */
    public function deleteMultiple($aIds)
    {
        foreach ($aIds as $iId)
        {
            $this->database()->delete(Phpfox::getT('mfox_leftnavi'), 'id = ' . (int) $iId);
        }
        $this->renewCache();
        return true;
    }
    /**
     * Using to update navigation status.
     * @param int $iNavigationId
     * @param int $iActive
     */
    public function updateNavigationStatus($iNavigationId = 0, $iActive = 0)
    {
        $this->database()->update(Phpfox::getT('mfox_leftnavi'), array('is_enabled' => (int) $iActive), 'id = ' . (int) $iNavigationId);
        $this->renewCache();
    }
    /**
     * Get for edit.
     * @staticvar null $aCache
     * @param int $iId
     * @return array
     */
    public function getForEdit($iId)
    {
        static $aCache = null;

        if ($aCache === null)
        {
            $aCache = $this->database()->select('*')
                    ->from(Phpfox::getT('mfox_leftnavi'))
                    ->where('id = ' . (int) $iId)
                    ->execute('getRow');
        }

        return $aCache;
    }
    /**
     * Using to update navigation.
     * @param int $iNavigationId
     * @param array $aVals
     * @return boolean
     */
    public function updateNavigation($iNavigationId, $aVals)
    {
        if ($this->database()->update(Phpfox::getT('mfox_leftnavi'), $aVals, 'id = ' . (int) $iNavigationId))
        {
            $this->renewCache();
            return true;
        }

        return false;
    }
    /**
     * Using to add navigation.
     * @param array $aVals
     * @return int
     */
    public function addNavigation($aVals)
    {
        if ($iNavigationId = $this->database()->insert(Phpfox::getT('mfox_leftnavi'), $aVals))
        {
            $this->renewCache();
            return $iNavigationId;
        }

        return 0;
    }

}
