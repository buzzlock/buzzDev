<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Videochannel_Component_Block_Related extends Phpfox_Component
{

    private function getServiceVideochannel()
    {
        $oObject = Phpfox::getService('videochannel');
        $oObject instanceof Videochannel_Service_Videochannel;
        return $oObject;
    }

    private function pager()
    {
        $oObject = Phpfox::getLib('pager');
        $oObject instanceof Phpfox_Pager;
        return $oObject;
    }

    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        if ($iVideoId = $this->request()->getInt('video_id'))
        {
            list($iCnt, $aVideos) = $this->getServiceVideochannel()->getRelatedVideosSuggestions($iVideoId, $this->request()->get('video_title'), ($this->request()->getInt('page_number') + 1));

            if (!count($aVideos))
            {
                return false;
            }

            $arSet = array(
                'page' => $this->request()->getInt('page_number'),
                'size' => Phpfox::getParam('videochannel.total_related_videos'),
                'count' => $iCnt
            );
            $this->pager()->set($arSet);

            if ($this->pager()->getLastPage() <= $this->request()->getInt('page_number'))
            {
                return false;
            }

            $arAssign = array(
                'aRelatedVideos' => $aVideos,
                'bIsLoadingMore' => true
            );
            $this->template()->assign($arAssign);
        }
        else
        {
            $aVideo = $this->getParam('aVideo');
            
            $iCategoryId = 0;
            if (count($aVideo['breadcrumb']))
            {
                foreach($aVideo['breadcrumb'] as $aCategory)
                {
                    $iCategoryId = $aCategory[2];
                }
            }
            
            list($iCnt, $aVideos) = $this->getServiceVideochannel()->getRelatedVideosSuggestions($aVideo['video_id'], $aVideo['title'], 0, true, false, $iCategoryId);

            if (!count($aVideos))
            {
                return false;
            }

            $arAssign = array(
                'sHeader' => Phpfox::getPhrase('videochannel.suggestions'),
                'aRelatedVideos' => $aVideos
            );
            $this->template()->assign($arAssign);

            $this->pager()->set(array('page' => $this->request()->getInt('page_number'), 'size' => Phpfox::getParam('videochannel.total_related_videos'), 'count' => $iCnt));

            if ($this->pager()->getTotalPages() > 1)
            {
                $arAssign = array(
                    'aFooter' => array(
                        Phpfox::getPhrase('videochannel.load_more_suggestions') => '#'
                    )
                );
                $this->template()->assign($arAssign);
            }

            return 'block';
        }
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('videochannel.component_block_related_clean')) ? eval($sPlugin) : false);
    }

}

?>