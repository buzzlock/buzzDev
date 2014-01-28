<?php

defined('PHPFOX') or exit('NO DICE!');

class Petition_Component_Controller_Index extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
      {
         if ($this->request()->get('req2') == 'main')
         {
               return Phpfox::getLib('module')->setController('error.404');
         }
         
         (($sPlugin = Phpfox_Plugin::get('petition.component_controller_index_process_start')) ? eval($sPlugin) : false);

         if (($iRedirectId = $this->request()->get('redirect')) && ($aRedirectPetition = Phpfox::getService('petition')->getPetitionForEdit($iRedirectId)))
         {
               Phpfox::permalink('petition', $aRedirectPetition['petition_id'], $aRedirectPetition['title'], true);
         }		

         Phpfox::getUserParam('petition.view_petitions', true);
         
         //b-230190         
         $aParentModule = $this->getParam('aParentModule');			
         
         if (($iRedirectId = $this->request()->getInt('redirect'))
               && ($aPetition = Phpfox::getService('petition')->getPetitionForEdit($iRedirectId))
               && $aPetition['module_id'] != 'petition'
               && Phpfox::hasCallback($aPetition['module_id'], 'getPetitionRedirect')
         )
         {
               if (($sForward = Phpfox::callback($aPetition['module_id'] . '.getPetitionRedirect', $aPetition['petition_id'])))
               {	
                     $this->url()->forward($sForward);
               }
         }

         //e-230190
         
         if (defined('PHPFOX_IS_AJAX_CONTROLLER'))
         {
               $bIsProfile = true;
               $aUser = Phpfox::getService('user')->get($this->request()->get('profile_id'));
               $this->setParam('aUser', $aUser);
         }
         else 
         {		
               $bIsProfile = $this->getParam('bIsProfile');	
               if ($bIsProfile === true)
               {                  
                     $aUser = $this->getParam('aUser');                        
               }
         }		
         
         if (($iDeleteId = $this->request()->getInt('delete')))
         {
               if (Phpfox::getService('petition.process')->delete($iDeleteId))
               {
                  $this->url()->send('petition', null, Phpfox::getPhrase('petition.petition_successfully_deleted'));
               }
               else
               {                  
                  return Phpfox_Error::display(Phpfox::getPhrase('petition.unable_to_find_the_petition_you_are_trying_to_delete'));
               }
         }
         
         /**
          * Check if we are going to view an actual petition instead of the petition index page.
          * The 2nd URL param needs to be numeric.
          */		
         
         //b-230190
         if ($aParentModule === null && $this->request()->getInt('req2') && !Phpfox::isAdminPanel())
         {
               return Phpfox::getLib('module')->setController('petition.view');
         }
         //e-230190
                 
         $this->setParam('sTagType', 'petition');
         
         $this->template()->setTitle(($bIsProfile ? Phpfox::getPhrase('petition.full_name_s_petitions', array('full_name' => $aUser['full_name'])) : Phpfox::getPhrase('petition.petition_title')))->setBreadCrumb(($bIsProfile ? Phpfox::getPhrase('petition.petitions') : Phpfox::getPhrase('petition.petition_title')), ($bIsProfile ? $this->url()->makeUrl($aUser['user_name'], 'petition') : $this->url()->makeUrl('petition')));
         
         $sView = $this->request()->get('view');				
         
         $this->search()->set(array(
                     'type' => 'petition',
                     'field' => 'petition.petition_id',				
                     'search_tool' => array(
                           'table_alias' => 'petition',
                           'search' => array(
                                 'action' => $aParentModule != null ? $this->url()->makeUrl($aParentModule['module_id'], array($aParentModule['item_id'],'petition')) : ($bIsProfile === true ? $this->url()->makeUrl($aUser['user_name'], array('petition', 'view' => $this->request()->get('view'))) : $this->url()->makeUrl('petition', array('view' => $this->request()->get('view')))),
                                 'default_value' => Phpfox::getPhrase('petition.search_petition_dot'),
                                 'name' => 'search',
                                 'field' => array('petition.title', 'petition_text.description','petition_text.target','petition_text.petition_goal')
                           ),
                           'sort' => array(						
                                 'latest' => array('petition.time_stamp', Phpfox::getPhrase('petition.latest')),
                                 'most-signed'=>array('petition.total_sign', Phpfox::getPhrase('petition.most_signed')),
                                 'most-liked' => array('petition.total_like', Phpfox::getPhrase('petition.most_liked')),
                                 'most-popular' => array('petition.total_view', Phpfox::getPhrase('petition.most_popular')),
						   'featured' => array('petition.is_featured', Phpfox::getPhrase('petition.featured'))
                           ),
                           'show' => array(10, 15,20)
                     )
               )
         );
         
         $aBrowseParams = array(
               'module_id' => 'petition',
               'alias' => 'petition',
               'field' => 'petition_id',
               'table' => Phpfox::getT('petition'),
               'hide_view' => array('pending', 'my')				
         );
         
         $aFilterMenu = array();
         
         if (!defined('PHPFOX_IS_USER_PROFILE') && !defined('PHPFOX_IS_PAGES_VIEW'))
         {
               $aFilterMenu = array(
                     Phpfox::getPhrase('petition.all_petitions') => '',
                     Phpfox::getPhrase('petition.my_petitions') => 'my',
               );
               
               if (!Phpfox::getParam('core.friends_only_community') && Phpfox::isModule('friend'))
               {
                     $aFilterMenu[Phpfox::getPhrase('petition.friends_petitions')] = 'friend';	
               }
               /*
		   list($iTotalFeatured, $aFeatured) = Phpfox::getService('petition')->getFeatured();
		   if ($iTotalFeatured)
		   {
			   $aFilterMenu[Phpfox::getPhrase('petition.featured_petitions') . '<span class="pending">' . $iTotalFeatured . '</span>'] = 'featured';
		   }
		   */
               if (Phpfox::getUserParam('petition.can_approve_petitions'))
               {
                     $iPendingTotal = Phpfox::getService('petition')->getPendingTotal();
                     
                     if ($iPendingTotal)
                     {
                           $aFilterMenu[Phpfox::getPhrase('petition.pending_petitions') . (Phpfox::getUserParam('petition.can_approve_petitions') ? '<span class="pending">' . $iPendingTotal . '</span>' : 0)] = 'pending';
                     }
               }
               $aFilterMenu[Phpfox::getPhrase('petition.help')] =  'petition.help.view_help';
               
               $this->template()->buildSectionMenu('petition', $aFilterMenu);
         }

         (($sPlugin = Phpfox_Plugin::get('petition.component_controller_index_process_search')) ? eval($sPlugin) : false);		
	    
	    $iStatus = 2;
	    $sStatus = $this->request()->get('status');
	    if($sStatus != '')
	    {               
		    $iStatus = (int)$sStatus;
	    }
	    
         switch ($sView)
         {
               case 'pending':
                     Phpfox::isUser(true);
                     if (Phpfox::getUserParam('petition.can_approve_petitions'))
                     {
                           $this->search()->setCondition('AND petition.is_approved = 0');
                     }				
                     break;
			/*
			case 'victory':
				   Phpfox::isUser(true);
				   if (Phpfox::getUserParam('petition.can_approve_petitions'))
				   {
					    $this->search()->setCondition('AND petition.is_approved = 1 AND petition.petition_status = 3');
				   }				
				   break;
			
			case 'featured':
				   Phpfox::isUser(true);
				   $this->search()->setCondition('AND petition.module_id = "petition" AND petition.petition_status = 2 AND petition.is_featured = 1');				
				   break;
			*/
               case 'my':
                     Phpfox::isUser(true);
                     $this->search()->setCondition('AND petition.module_id = "petition" AND petition.user_id = ' . Phpfox::getUserId());
				 
					 if($sStatus == '')
					 {
						$iStatus = 0;
					 }
				 
                     break;
               default:                     
                     if ($bIsProfile === true)
                     {
                        $this->search()->setCondition("AND petition.petition_status > 0 AND petition.module_id = 'petition' AND petition.user_id = " . $aUser['user_id'] . " AND petition.is_approved IN(" . ($aUser['user_id'] == Phpfox::getUserId() ? '0,1' : '1') . ") AND petition.privacy IN(" . (Phpfox::getParam('core.section_privacy_item_browsing') ? '%PRIVACY%' : Phpfox::getService('core')->getForBrowse($aUser)) . ")");
                     }
                     else if ($aParentModule != null && defined('PHPFOX_IS_PAGES_VIEW'))
                     {
                        $this->search()->setCondition("AND petition.module_id = '" . $aParentModule['module_id'] . "' AND petition.item_id  = " . $aParentModule['item_id'] . " AND petition.privacy IN(%PRIVACY%)");
                     }
                     else
                     {
                        $this->search()->setCondition("AND petition.module_id = 'petition' AND petition.privacy IN(%PRIVACY%)");
                     }
				 if(!$bIsProfile)
				 {
				    $this->search()->setCondition("AND petition.is_approved = 1");  
				 }                     
				 
				 if (!($this->search()->isSearch()) && !Phpfox::isMobile() && !$bIsProfile && $sView != 'listing' && $sView != 'friend' && !($aParentModule != null && defined('PHPFOX_IS_PAGES_VIEW')))
				 {
				   $this->search()->setCondition("AND petition.petition_status = 2");
				 }
				 
				 if($sView == 'friend' && $sStatus == '')
				 {
					$iStatus = 0;
				 }
			
                     break;
         }
	    
         $this->setParam(array('iStatus'=>$iStatus));
         if ($this->request()->get(($bIsProfile === true ? 'req3' : 'req2')) == 'category')
         {			
               if ($aPetitionCategory = Phpfox::getService('petition.category')->getCategory($this->request()->getInt(($bIsProfile === true ? 'req4' : 'req3'))))
               {
                     $this->template()->setBreadCrumb(Phpfox::getPhrase('petition.category'));		
                     
                     $this->search()->setCondition('AND petition_category.category_id = ' . $this->request()->getInt(($bIsProfile === true ? 'req4' : 'req3')) . ' AND petition_category.user_id = ' . ($bIsProfile ? (int) $aUser['user_id'] : 0));
                     
                     $this->template()->setTitle(Phpfox::getLib('locale')->convert($aPetitionCategory['name']));
                     $this->template()->setBreadCrumb(Phpfox::getLib('locale')->convert($aPetitionCategory['name']), $this->url()->makeUrl('current'), true);
                     
                     $this->search()->setFormUrl($this->url()->permalink(array('petition.category', 'view' => $this->request()->get('view')), $aPetitionCategory['category_id'], $aPetitionCategory['name']));
               }			
         }
         elseif ($this->request()->get(($bIsProfile === true ? 'req3' : 'req2')) == 'tag')
         {
               if (($aTag = Phpfox::getService('tag')->getTagInfo('petition', $this->request()->get(($bIsProfile === true ? 'req4' : 'req3')))))
               {
                     $this->template()->setBreadCrumb(Phpfox::getPhrase('tag.topic') . ': ' . $aTag['tag_text'] . '', $this->url()->makeUrl('current'), true);				
                     $this->search()->setCondition('AND tag.tag_text = \'' . Phpfox::getLib('database')->escape($aTag['tag_text']) . '\'');	
               }
         }
         
	   $aFeatured = array();
      
	   if (($this->request()->get(($bIsProfile === true ? 'req3' : 'req2')) !== 'tag') && !$bIsProfile && !$this->search()->isSearch() && $aParentModule === null && !isset($aPetitionCategory))
	   {
		  $aFeatured = array(true);
	   }
	   
	   if ($this->search()->isSearch())
	   {	
		   $iStatus = $this->request()->getInt('status');
		   
		   if(!empty($iStatus))
		   {				
			   $this->search()->setCondition('AND petition.petition_status = '.$iStatus);			
		   }
		   
		   $startDate = $this->request()->get('from');
		   
		   if(!empty($startDate))
		   {
			   $aDate = explode('_',$startDate,3);
			   $iStartTime = Phpfox::getLib('date')->mktime(23, 59, 59, isset($aDate[0]) ? $aDate[0] : 0, isset($aDate[1]) ? $aDate[1] : 0 , isset($aDate[2]) ? $aDate[2]:0);		
			   $this->search()->setCondition('AND petition.end_time >= '.$iStartTime);				
		   }
		   
		   $endDate = $this->request()->get('to');
		   
		   if(!empty($endDate))
		   {
			   $aDate = explode('_',$endDate,3);
			   $iEndTime = Phpfox::getLib('date')->mktime(23, 59, 59, isset($aDate[0]) ? $aDate[0] : 0, isset($aDate[1]) ? $aDate[1] : 0 , isset($aDate[2]) ? $aDate[2]:0);		
			   $this->search()->setCondition('AND petition.end_time <= '.$iEndTime);				
		   }
	   }     
         $this->search()->browse()->params($aBrowseParams)->execute();
         
         $aRows = $this->search()->browse()->getRows();
	    $aItems = array();
         if(!empty($aRows))
         { 
		  $aCloses = '';
            foreach($aRows as $iKey => $aRow)
            {
               if($aRow['end_time'] < PHPFOX_TIME && $aRow['petition_status'] == 2 && $aRow['is_approved'])
               {
			   $aCloses .= $aRow['petition_id'].',';                  
			   if($bIsProfile)
			   {
				 $aRow['petition_status'] = 1;
				 $aItems[] = $aRow;
			   }
			   
			   if($aRow['is_directsign'] == 1)
			   {
					Phpfox::getLib('cache')->remove('petition_directsign');
			   }
               }
			else
			{
			  $aItems[] = $aRow;
			}
            }
		  
		  if($aCloses != '')
		  {
			Phpfox::getService('petition.process')->close($aCloses);
		  }		  
         }
	   
         Phpfox::getLib('pager')->set(array('page' => $this->search()->getPage(), 'size' => $this->search()->getDisplay(), 'count' => $this->search()->browse()->getCount()));
         
         Phpfox::getService('petition')->getExtra($aItems, 'user_profile');
         
         (($sPlugin = Phpfox_Plugin::get('petition.component_controller_index_process_middle')) ? eval($sPlugin) : false);
         
         $this->template()->setMeta('keywords', Phpfox::getParam('petition.petition_meta_keywords'));
         $this->template()->setMeta('description', Phpfox::getParam('petition.petition_meta_description'));
         if ($bIsProfile)
         {
               $this->template()->setMeta('description', '' . $aUser['full_name'] . ' has ' . $this->search()->browse()->getCount() . ' petitions.');
         }
         
         foreach ($aItems as $iKey => $aItem)
         {
         
               $this->template()->setMeta('keywords', $this->template()->getKeywords($aItem['title']));	
               if (!empty($aItem['tag_list']))
               {
                     $this->template()->setMeta('keywords', Phpfox::getService('tag')->getKeywords($aItem['tag_list']));
               }
         }
	   
         /**
          * Here we assign the needed variables we plan on using in the template. This is used to pass
          * on any information that needs to be used with the specific template for this component.
          */
         $this->template()->assign(array(
                           'corepath' => Phpfox::getParam('core.path'),
                           'aFeatured' => $aFeatured,
                           'iCnt' => $this->search()->browse()->getCount(),
                           'aItems' => $aItems,
                           'sSearchBlock' => Phpfox::getPhrase('petition.search_petitions_dot'),
                           'bIsProfile' => $bIsProfile,
                           'sTagType' => ($bIsProfile === true ? 'petition_profile' : 'petition'),
                           'sPetitionStatus' => $this->request()->get('status'),
                           'iShorten' => Phpfox::getParam('petition.preview_length_in_index'),
                           'sView' => $sView					
                     )
               )
               ->setHeader('cache', array(
                     'jquery/plugin/jquery.highlightFade.js' => 'static_script',				
                     'quick_edit.js' => 'static_script',				
                     'comment.css' => 'style_css',
                     'pager.css' => 'style_css',
                     'global.css' => 'module_petition',
					 'mobile.css' => 'module_petition',
                     'feed.js' => 'module_feed',
                     
               )
         );
         
         $this->setParam('global_moderation', array(
                     'name' => 'petition',
                     'ajax' => 'petition.moderation',
                     'menu' => array(
                           array(
                                 'phrase' => Phpfox::getPhrase('petition.delete'),
                                 'action' => 'delete'
                           ),
                           array(
                                 'phrase' => Phpfox::getPhrase('petition.approve'),
                                 'action' => 'approve'
                           )					
                     )
               )
         );
         
         (($sPlugin = Phpfox_Plugin::get('petition.component_controller_index_process_end')) ? eval($sPlugin) : false);		
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		$this->template()->clean(array(
				'iCnt',
				'aItems',
				'sSearchBlock',
                        'aFeatured'
			)
		);
		
		(($sPlugin = Phpfox_Plugin::get('petition.component_controller_index_clean')) ? eval($sPlugin) : false);
	}
}

?>
