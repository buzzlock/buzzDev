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
	 */
	public function process()
	{   
            //redirect to all suggestion page
            $view = $this->request()->get('view').'';
            if ($view == '')
                $this->url()->send('suggestion.view_all');            
            
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
            $aBrowseParams = array(
                'module_id' => 'suggestion',
                'alias' => 's',
                'field' => 'suggestion_id',
                'table' => Phpfox::getT('suggestion')
            );
            
            /*
             * process filter
             * change default module to suggestion_<Filter>
             */
            $sFilter = $this->request()->get('Filter','');
            if ($sFilter != ''){         
                $sFilter = Phpfox::getService('suggestion')->convertModule($sFilter);
                $_SESSION['suggestion_params']['filter'] = $sFilter;
            }
            
            $sView = $this->request()->get('view','');
            switch($sView){
                case 'all':
                        $_SESSION['suggestion_params']['where'] = ' AND processed = 1 AND (sd.user_id ="' .Phpfox::getUserId(). '" OR sd.friend_user_id="' .Phpfox::getUserId(). '")';
                    break;
                
                case 'my':
                        $_SESSION['suggestion_params']['where'] = ' AND processed = 1 AND sd.user_id = "' . Phpfox::getUserId().'"';
                        
                    break;
                
                case 'friends':
                        $_SESSION['suggestion_params']['where'] = ' AND processed = 1 AND sd.friend_user_id = "' . Phpfox::getUserId() . '"';
                    break;
                
                case 'incoming':
                        $_SESSION['suggestion_params']['where'] = ' AND processed = 0';
                    break;
                
                case 'pending':
                        $this->template()->assign(array('bShowFilter'=>true));
                    break;
            }
            
            $sKey = Phpfox::getService('suggestion')->getSearchKey();
            if ($sKey != ''){//has key search
                
            }else{
                $sKey = Phpfox::getPhrase('suggestion.search_suggestions').'...';
            }
            
            $this->search()->set(array(
                    'type' => 'suggestion',
                    'field' => 's.suggestion_id',
                    'search_tool' => array(
                        'table_alias' => 's',
                        'search' => array(
                                'action' => Phpfox::permalink('suggestion', 'view_all'),
                                'default_value' => Phpfox::getPhrase('suggestion.search_suggestions').'...',
                                'name' => '',
                                'field' => ''
                        ),
                        'sort' => array(
                                            'latest' => array('s.time_stamp', Phpfox::getPhrase('suggestion.latest'))
                                        ),					
                        'custom_filters' => $aFilter,
                        'show' => array(1, 20, 50, 100)
                    )
                )
            );
            $this->search()->browse()->params($aBrowseParams)->execute();
            $aRows = $this->search()->browse()->getRows();
            
            $this->template()->assign(array(
                'aRows' => $aRows,
                'sKey' => $sKey,
            ));
            
            Phpfox::getLib('pager')->set(array(
                'page' => $this->search()->getPage(),
                'size' => $this->search()->getDisplay(),
//                'count' => $this->search()->browse()->getCount())
                'count' => 2)
            );
            
            //get module default icon
            $this->template()->setBreadcrumb(Phpfox::getPhrase('suggestion.suggestion'), $this->url()->makeUrl('suggestion'));
            Phpfox::getService('suggestion')->buildMenu();
            
	}

	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('suggestion.component_controller_sample_clean')) ? eval($sPlugin) : false);
	}
}

?>