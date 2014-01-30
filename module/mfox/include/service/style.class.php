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
class Mfox_Service_Style extends Phpfox_Service {
    /**
     * Constructor.
     */
    function __construct()
    {
        $this->_sTable = Phpfox::getT('mfox_style');
    }

    /**
     * Add new style.
     * @param string $sName
     * @param array $aData
     * @return int
     */
    function add($sName, $aData)
    {
        return $this->database()->insert($this->_sTable, array(
            'name' => $sName,
            'is_publish' => 0,
            'data' => serialize($aData),
            'time_stamp' => PHPFOX_TIME,
        ));
    }
    
    /**
     * Edit style.
     * @param int $iStyleId
     * @param array $aData
     * @return bool
     */
    function edit($iStyleId, $aData)
    {
        return $this->database()->update($this->_sTable, array('data' => serialize($aData)), 'style_id = ' . (int) $iStyleId);
    }

    /**
     * remove style id
     * @param int $iStyleId
     * @return bool
     */
    function remove($iStyleId)
    {
        return $this->database()->delete($this->_sTable, 'style_id=' . $iStyleId);
    }

    /**
     * Publish style id
     * @param int $iStyleId
     */
    function publish($iStyleId)
    {
        $this->database()->update($this->_sTable, array('is_publish' => 0), 'style_id <>' . $iStyleId);
        $this->database()->update($this->_sTable, array('is_publish' => 1), 'style_id = ' . $iStyleId);
    }
    /**
     * Reset style.
     */
    function resetStyle()
    {
        $this->database()->update($this->_sTable, array('is_publish' => 0));
    }
    /**
     * Get the default style.
     * @return array
     */
    function getDefaultStyles()
    {
        return array(
            'homelistviewitem_id_background' => '#fff',
            'homelistviewitem_id_color' => '#000',
            'a_color' => '#3b5998',
            'purple_btn_background' => '#1c318a',
            'purple_btn_color' => '#fff',
            'header_background' => '#7B7B7B',
            'fm_guide_container_background' => '#404040',
            'leftnavi_menu_id_menu_color' => '#fff',
            'landingpage_background' => '#474747',
            'button_green_background' => '#6D7AC8',
            'login_form_input_color' => '#fff',
            'popupcontent_background' => '#FFF',
            'menu_footer_background' => '#EEE',
            'menu_footer_a_color' => '#000',
            'menu_footer_activebutton_background' => '#919191',
            'menu_footer_activebutton_a_color' => '#000',
            'comment_actions_background' => '#E7E7E7',
            'comment_actions_div_a_color' => '#a8a9ab',
            'comment_actions_div_a_likeactive_color' => '#3b5998',
            'feed_entry_color' => '#fff',
            'user_post_content_color' => '#787878',
            'slide_photo_content_background' => '#000',
            'extra_color' => '#b4b4b4',
        );
    }

    /**
     * @return string
     */
    function getStylesPattern()
    {
        $file = PHPFOX_DIR_MODULE . '/mfox/static/css/custom.css';
        return file_get_contents($file);
    }

    /**
     * Get pattern merged styles
     * @param array $aMergedStyles
     * @return string
     */
    function mergedStyles($aMergedStyles)
    {
        $org = $this->getStylesPattern();

        $reg = array();

        foreach ($aMergedStyles as $name => $value)
        {
            $name = '{' . $name . '}';
            $reg[$name] = $value;
        }
        return strtr($org, $reg);
    }
    /**
     * Get for edit.
     * @param int $iStyleId
     * @return array
     */
    public function getForEdit($iStyleId)
    {
        /**
         * @var array
         */
        $aRow = $this->database()
                ->select('*')
                ->from($this->_sTable)
                ->where('style_id = ' . (int) $iStyleId)
                ->execute('getRow');
        /**
         * @var array
         */
        $aStyles = array();
        if ($aRow)
        {
            $aStyles = unserialize($aRow['data']);
        }
        return $aStyles;
    }
    
    /**
     * Get custom CSS.
     * @return string
     */
    function _getCustomCss()
    {
        $aRow = $this->database()
                ->select('*')
                ->from($this->_sTable)
                ->where('is_publish=1')
                ->execute('getRow');
        if ($aRow)
        {
            $aStyles = unserialize($aRow['data']);
        }
        else
        {
            // $aStyles = $this->getDefaultStyles();
            return '/* no custom style */';
        }
        return $this->mergedStyles($aStyles);
    }

    /**
     * Get custom CSS.
     * @return string
     */
    function getCustomCss()
    {
        return $this->_getCustomCss();
    }
    /**
     * Get style list.
     * @param array $aConds
     * @param string $sSort
     * @param int $iPage
     * @param int $iLimit
     * @return array
     */
    public function get($aConds, $sSort = 'style.time_stamp DESC', $iPage = '', $iLimit = '')
	{		
        /**
         * @var int
         */
		$iCnt = $this->database()->select('COUNT(style.style_id)')
			->from(Phpfox::getT('mfox_style'), 'style')
			->where($aConds)
			->order($sSort)
			->execute('getSlaveField');	
        /**
         * @var array
         */
		$aItems = array();
		if ($iCnt)
		{		
			$aItems = $this->database()->select('style.*')
				->from(Phpfox::getT('mfox_style'), 'style')
				->where($aConds)
				->order($sSort)
				->limit($iPage, $iLimit, $iCnt)
				->execute('getSlaveRows');	
		}	
		return array($iCnt, $aItems);
	}
    /**
     * Update style status. For ajax.
     * @param int $iStyleId
     * @param int $iAction
     */
    public function updateStyleStatus($iStyleId, $iAction)
    {
        $this->database()->update($this->_sTable, array('is_publish' => 0), 'style_id <>' . $iStyleId);
        $this->database()->update($this->_sTable, array('is_publish' => $iAction), 'style_id = ' . $iStyleId);
    }
    
    /**
     * Delete multi-styles.
     * @param array $aStyleIds
     * @return boolean
     */
    public function deleteStyles($aStyleIds)
    {
        foreach($aStyleIds as $iStyleId)
        {
            $this->remove((int) $iStyleId);
        }
        
        return true;
    }
}
