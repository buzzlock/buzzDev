<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 *
 * @copyright      YouNet Company
 * @author         VuDP, TienNPL
 * @package        Module_Resume
 * @version        3.01
 * 
 */
class Resume_Service_Viewme_Viewme extends Phpfox_Service
{
	/**
	 * Class Constructor
	 */
	public function __construct()
	{	
		$this->_sTable = Phpfox::getT('resume_viewme');
	}
	
	public function updateViewResume($aResume)
	{
		// Who view the resume	
		$iViewerId = Phpfox::getUserId();
		
		$aView = $this -> database() 
					   -> select('*')
					   -> from($this->_sTable)
					   -> where('user_id = ' . $iViewerId . " AND resume_id = " . $aResume['resume_id'])
					   -> execute('getRow');
		
		if(!$aView)
		{
			
			$aAdds = array(
				'user_id' 	=> $iViewerId,
				'resume_id' => $aResume['resume_id'],
				'owner_id'  => $aResume['user_id'],
				'time_stamp'=> PHPFOX_TIME,
				'total_view'=> 1
			);
			Phpfox::getService('resume.viewme.process') -> addView($aAdds);
		}
		else 
		{
			
			$aUpdates = array(
				'view_id'=> $aView['view_id'],
				'time_stamp' => PHPFOX_TIME,
				'total_view' => $aView['total_view'] + 1
			);
			Phpfox::getService('resume.viewme.process') -> updateView($aUpdates);
		}
		
		//Update total_view on Resume
		$this->database()->updateCounter('resume_basicinfo','total_view','resume_id',$aResume['resume_id']);
	}
	
	public function getViewList($aResumeIdList)
	{
		$sResumeIdList = implode(',', $aResumeIdList);
		
		// Get views
		$aViews = $this -> database() 
						-> select('*') 
						-> from($this->_sTable)
						-> where ('resume_id IN (' . $sResumeIdList . ') AND user_id = ' . Phpfox::getUserId() )
						-> execute('getRows');
		// Return result
		return $aViews;
	}
	
	public function getViewByIds($iViewerId, $iResumeId)
	{
		$aView = $this -> database() 
					   -> select('*')
					   -> from($this->_sTable)
					   -> where('user_id = ' . $iViewerId . " AND resume_id = " . $iResumeId)
					   -> execute('getRow');
					   
		return $aView;			   
	}
	
	public function updateMessageCount($iResumeId)
	{
		$aView = $this->getViewByIds( Phpfox::getUserId(), $iResumeId);
		
		if($aView)
		{
			$this->database()->updateCounter('resume_viewme', 'sent_messages','view_id', $aView['view_id']);
			return true;
		}
		return false;
	}
	
	/**
	 * Total Resumes which user has viewed.
	 */
	 
	 public function TotalResumesViewed()
	 {
	 	$totals = (int)Phpfox::getLib("database")->select('count(*)')
			-> from($this->_sTable)
			-> where('user_id='.Phpfox::getUserId().' and owner_id!='.Phpfox::getUserId())
			-> execute('getSlaveField');
			

		return $totals;
	 }

	public function updateNote($iResumeId, $sNote ="")
	{
		$iViewerId = Phpfox::getUserId();
		$this->database()->update($this->_sTable, array('note' => $sNote), "resume_id = {$iResumeId} AND user_id = {$iViewerId}");
		return TRUE;
	}
	
	public function getWhoViewed($iUserId = 0, $iLimit = 0)
	{
		$iCnt  = $this  -> database()->select('count(*) as count')
						  -> from($this->_sTable, 'rv')
						  -> leftjoin(Phpfox::getT('resume_basicinfo'), 'rb',"rb.user_id = rv.user_id and rb.is_published=1 and rb.status='approved'")
						  -> join(Phpfox::getT('user'), 'u',"u.user_id = rv.user_id")
						  -> where("rv.owner_id = {$iUserId}")
							-> execute('getSlaveField');
		
		$aCurrentPublishedResume = Phpfox::getService('resume')->getPublishedResumeByUserId($iUserId);
			
		$this  -> database()->select('rb.*,rv.time_stamp as viewed_timestamp,'.Phpfox::getUserField())
						  -> from($this->_sTable, 'rv')
						  -> leftjoin(Phpfox::getT('resume_basicinfo'), 'rb',"rb.user_id = rv.user_id and rb.is_published=1 and rb.status='approved'")
						  -> join(Phpfox::getT('user'), 'u',"u.user_id = rv.user_id")
						  -> where("rv.owner_id = {$iUserId}");
		
		if($aCurrentPublishedResume)
		{
			$this->database()->where("rv.resume_id = {$aCurrentPublishedResume['resume_id']}");
		}
		
		if($iLimit > 0)
		{
			$this -> database()-> limit($iLimit);
		}
		
		$aWhoViewed = $this -> database() -> execute('getRows');
		
		return array($iCnt,$aWhoViewed);
	}
}

?>