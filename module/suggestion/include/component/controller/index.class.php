<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * @copyright		[YOUNETCO]
 * @author  		NghiDV
 * @package  		Module_Suggestion
 * @version 		$Id: sample.class.php 1 2011-11-25 15:29:17Z YOUNETCO $
 */
class Suggestion_Component_Controller_Index extends Phpfox_Component
{
	/**
	 * Class process method which is used to execute this component.
         * 
	 */
         
	public function process()
	{   
            
            $view = $this->request()->get('view').'';
            
            if ($view == '')
                $this->url()->send('suggestion.view_incoming');            
            
            if ($view == 'redirect'){
                $iFriendId = (int)$this->request()->get('iFriendId');
                $iItemid = (int)$this->request()->get('iItemid');
                $sModule = $this->request()->get('sModule').'';
                $sRedirect = $this->request()->get('sRedirect').'';
                
                Phpfox::getService('suggestion.process')->approve($iFriendId, $iItemid, $iApprove=1, $sModule);
                $sLink = '';
                $aRedirect = explode('__', $sRedirect);
                
                $sLink = base64_decode($aRedirect[0]);
                
                for($i=1; $i<count($aRedirect); $i++){                    
                    $sLink .= base64_decode($aRedirect[$i]);                    
                }
                $this->url()->send(urldecode($sLink));
            }
            
            $this->template()->assign(array(
                'bShowPending'=>false,
                'bShowFilter'=>false
            ));
            
            $sSupportModule = Phpfox::getUserParam('suggestion.support_module');
            
            if($sSupportModule != ''){
                $sSupportModule = explode(',', $sSupportModule );
                $aSort[] = array(
                            'link'=> "all",
                            'phrase'=> Phpfox::getPhrase('suggestion.all')
                        );
                foreach($sSupportModule as $sModule){
                    if (Phpfox::isModule($sModule)){
                        $sSort = 'suggestion_'.$sModule;
                        $sModuleUpcase = ucfirst($sModule);
                        $aSort[] = array(
                            'link'=> $sModuleUpcase,
                            'phrase'=> $sModuleUpcase                    
                        );
                    }
                }
                
                $aFilter = array(Phpfox::getPhrase('suggestion.by')=>array(
                            'param'=> Phpfox::getPhrase('suggestion.filter'),
                            'default_phrase'=>$aSort[0]['phrase'],
                            'data'=> $aSort
                        ));
                
                
            }else{
                $aSort = array();
            }
            $aFilter2 = array('cs'=>array(
                            'key'=> 'aaa',
                            'value'=>'bbb'
                        ));
            
            $aBrowseParams = array(
                'module_id' => 'suggestion',
                'alias' => 's',
                'field' => 'suggestion_id',
                'table' => Phpfox::getT('suggestion')
            );
            
            /*
             * process filter display by module name
             * change default module to suggestion_<Filter>
             */
            $sFilter = $this->request()->get('Filter','');
            
            if ($sFilter != '' && $sFilter != 'all'){         
                
                $sFilter = Phpfox::getService('suggestion')->convertModule($sFilter);                
                $sFilter = ' AND s.module_id ="' . $sFilter . '"';
            }else{
                $sFilter = '';
            }
            
            $sView = $this->request()->get('view','incoming');
            
            $sKey = Phpfox::getService('suggestion')->getSearchKey($sView);
            
            if ($sKey != ''){//has key search
                $sKey = Phpfox::getLib('parse.input')->convert($sKey);
//                $sKeySearch = ' AND (user.full_name like "%'.$sKey.'%" OR user0.full_name like "%'.$sKey.'%" OR user1.full_name like "%'.$sKey.'%")';
                $sKeySearch = ' AND (user.full_name like "%'.$sKey.'%" OR user0.full_name like "%'.$sKey.'%")';
            }else{
                $sKey = Phpfox::getPhrase('suggestion.search_suggestions').'...';
                $sKeySearch = '';
            }                        
            
            $_SESSION['suggestion']['pending'] = 0;
            switch($sView){
                case 'all':
                        $this->search()->setCondition('AND s.processed = 1 AND (s.user_id = ' . Phpfox::getUserId() . ' OR s.friend_user_id=' .Phpfox::getUserId().') ' . $sFilter . $sKeySearch);
                    break;
                
                case 'my':
                        $this->search()->setCondition('AND s.user_id = ' . Phpfox::getUserId() . ' AND s.processed = 1' . $sFilter . $sKeySearch);
                    break;
                
                case 'friends':
                        $this->search()->setCondition('AND s.processed = 1 AND s.friend_user_id = ' . Phpfox::getUserId() . $sFilter . $sKeySearch);
                    break;
                
                case 'incoming':
                        $this->search()->setCondition('AND s.processed = 0 AND s.friend_user_id != s.user_id AND s.friend_user_id = '. Phpfox::getUserId() . $sFilter . $sKeySearch);                    
                    break;
                
                case 'pending':
                        $this->search()->setCondition('AND s.processed = 0 AND s.friend_user_id != s.user_id AND s.user_id = '. Phpfox::getUserId() . $sFilter . $sKeySearch);
                        $this->template()->assign(array('bShowFilter'=>true));                    
                    break;
            }
           
            
            $this->search()->set(array(
                    'type' => 'suggestion',
                    'field' => 's.suggestion_id',
                    'search_tool' => array(
                        'table_alias' => 's',
                        'search' => array(
                            'action' => Phpfox::permalink('suggestion', 'view_' . $view),
                            'default_value' => Phpfox::getPhrase('suggestion.search_suggestions').'...',
                            'name' => '',
                            'field' => ''
                        ),
                        'sort' => array(
                            'latest' => array('s.time_stamp', Phpfox::getPhrase('suggestion.latest'))
                        ),                        
                        'custom_filters' => $aFilter,
                        'show' => array(20, 50, 100)
                    )
                )
            );
            $this->search()->browse()->params($aBrowseParams)->execute();
            $aRows = $this->search()->browse()->getRows();
            
            $this->template()->assign(array(
                'sFullUrl' => Phpfox::getParam('core.path'),
                'aRows' => $aRows,                                
                'sKey' => $sKey,
                'sDefaultKey' => Phpfox::getPhrase('suggestion.search_suggestions').'...',
                'sView' => $sView
            ));
                        
            Phpfox::getLib('pager')->set(array(
                'page' => $this->search()->getPage(),
                'size' => $this->search()->getDisplay(),
                'count' => $this->search()->browse()->getCount())
            );
            
            $this->template()->setBreadcrumb(Phpfox::getPhrase('suggestion.suggestion'), $this->url()->makeUrl('suggestion'));
            Phpfox::getService('suggestion')->buildMenu();                  
            
	}

	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('suggestion.component_controller_index_clean')) ? eval($sPlugin) : false);
	}
}
?>