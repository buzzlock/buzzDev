<?php

defined('PHPFOX') or exit('NO DICE!');

class JobPosting_Service_Package_Package extends Phpfox_service {
	
	public function __construct()
	{	
		$this->_sTable = Phpfox::getT('jobposting_package');
        $this->_sTableData = Phpfox::getT('jobposting_package_data');
	}
	
	public function getById($package_id)
	{
		$aRow = $this->database()->select('*')
			->from($this->_sTable)
			->where('package_id = '.(int)$package_id)
			->execute('getRow');
			
		return $aRow;
	}
    
    public function getByDataId($iDataId, $bValid = false)
    {
        $sCond = 'pd.data_id = '.(int)$iDataId;
        
        if ($bValid)
        {
            $sCond .= ' AND pd.status = 3 AND (p.post_number <= 0 OR (p.post_number > 0 AND pd.remaining_post > 0)) AND (p.expire_type <= 0 OR (p.expire_type > 0 AND pd.expire_time > '.PHPFOX_TIME.'))';
        }
        
        $aRow = $this->database()->select('pd.*, p.*')
            ->from($this->_sTableData, 'pd')
            ->join($this->_sTable, 'p', 'p.package_id = pd.package_id')
            ->where($sCond)
            ->execute('getSlaveRow');
        
        return $aRow;
    }
	
	public function getPackages($iPage = 0, $iLimit = 0, $iCount = 0)
	{						
		$oSelect = $this -> database() 
						 -> select('*')
						 -> from($this->_sTable, 'pk');
						 
		$oSelect->limit($iPage, $iLimit, $iCount);

		$aPackages = $oSelect->execute('getRows');
		
	 	return $aPackages;
	}
	
	public function getItemCount()
	{			
		$oQuery = $this -> database()
						-> select('count(*)')
						-> from($this->_sTable,'pk');
						
		return $oQuery->execute('getSlaveField');
	}
    
    public function getPackageByDataId($iDataId)
    {
        $aPackage = $this->database()->select('p.*')
            ->from($this->_sTable, 'p')
            ->where('p.package_id = (SELECT pd.package_id FROM '.$this->_sTableData.' pd WHERE pd.data_id = '.$iDataId.')')
            ->execute('getRow');
        
        return $aPackage;
    }
    
    public function getBoughtPackages($iCompanyId, $bValid = false)
    {
        if ($bValid)
        {
            $aPackages = $this->database()->select('pd.*, p.*')
                ->from($this->_sTableData, 'pd')
                ->join($this->_sTable, 'p', 'p.package_id = pd.package_id')
                ->where('pd.company_id = '.$iCompanyId.' AND pd.status = 3 AND (p.post_number <= 0 OR (p.post_number > 0 AND pd.remaining_post > 0)) AND (p.expire_type <= 0 OR (p.expire_type > 0 AND pd.expire_time > '.PHPFOX_TIME.'))')
                ->order('pd.data_id ASC')
                ->execute('getSlaveRows');
        }
        else
        {
            $aPackages = $this->database()->select('pd.*, p.*')
                ->from($this->_sTableData, 'pd')
                ->join($this->_sTable, 'p', 'p.package_id = pd.package_id')
                ->where('pd.company_id = '.(int)$iCompanyId. " and pd.status = 3")
                ->order('pd.data_id ASC')
                ->execute('getSlaveRows');
        }
      
        if(count($aPackages))
        {
            foreach($aPackages as $k => $aPackage)
            {
            	$aPackages[$k]['status_text'] = Phpfox::getPhrase('jobposting.'.Phpfox::getService('jobposting.transaction')->getStatusNameById($aPackage['status']));
                $aPackages[$k]['fee_text'] = PHpfox::getService('jobposting.helper')->getTextParseCurrency($aPackage['fee']);
				
                if($aPackage['status']!=3)
				{
					$aPackages[$k]['expire_text'] = Phpfox::getPhrase('jobposting.n_a');
					$aPackages[$k]['expire_text_2'] = Phpfox::getPhrase('jobposting.n_a');
				}
				else
				{
					if($aPackage['expire_type'] == 0)
	                {
	                    $aPackages[$k]['expire_text'] = Phpfox::getPhrase('jobposting.never_expired');
						$aPackages[$k]['expire_text_2'] = Phpfox::getPhrase('jobposting.never_expired');
	                }
	                else
	                {
	                    $aPackages[$k]['expire_text'] = date('M j, Y', $aPackage['valid_time']).' - '.date('M j, Y', $aPackage['expire_time']);
						$aPackages[$k]['expire_text_2'] = Phpfox::getPhrase('jobposting.from').' '.date('M j, Y', $aPackage['valid_time']).' '.Phpfox::getPhrase('jobposting.to').' '.date('M j, Y', $aPackage['expire_time']);
	                }
				}
            }
        }
        
        return $aPackages;
    }
    
    public function getToBuyPackages($iCompanyId)
    {
        $aPackages = $this->database()->select('p.*')
            ->from($this->_sTable, 'p')
            ->where('p.package_id NOT IN (SELECT pd.package_id FROM '.$this->_sTableData.' pd LEFT JOIN '.$this->_sTable.' p1 ON pd.package_id=p1.package_id WHERE pd.company_id = '.$iCompanyId.' AND pd.status = 3 AND (p1.post_number <= 0 OR (p1.post_number > 0 AND pd.remaining_post > 0)) AND (p1.expire_type <= 0 OR (p1.expire_type > 0 AND pd.expire_time > '.PHPFOX_TIME.'))) AND p.active = 1')
            ->order('p.package_id ASC')
            ->execute('getSlaveRows');
        
        if(count($aPackages))
        {
            foreach($aPackages as $k => $aPackage)
            {
            	
            	$aPackages[$k]['fee_text'] = PHpfox::getService('jobposting.helper')->getTextParseCurrency($aPackage['fee']);
				
                if($aPackage['expire_type'] == 0)
                {
                    $aPackages[$k]['expire_text'] = Phpfox::getPhrase('jobposting.never_expired');
                }
                else
                {
                    $aPackages[$k]['expire_text'] = Phpfox::getPhrase('jobposting.period').' '.$aPackage['expire_number'].' ';
                    switch($aPackage['expire_type'])
                    {
                        case 1:
                            $aPackages[$k]['expire_text'] .= ($aPackage['expire_number'] > 1) ? Phpfox::getPhrase('jobposting.day_plural') : Phpfox::getPhrase('jobposting.day_singular');
                            break;
                        case 2:
                            $aPackages[$k]['expire_text'] .= ($aPackage['expire_number'] > 1) ? Phpfox::getPhrase('jobposting.week_plural') : Phpfox::getPhrase('jobposting.week_singular');
                            break;
                        case 3:
                            $aPackages[$k]['expire_text'] .= ($aPackage['expire_number'] > 1) ? Phpfox::getPhrase('jobposting.month_plural') : Phpfox::getPhrase('jobposting.month_singular');
                    }
                }
            }
        }
        
        return $aPackages;
    }
    
    public function buildHtmlBoughtPackages($iCompanyId)
    {
        $sHtml = '<tr>
                        <th align="left">'.Phpfox::getPhrase('jobposting.package_name').'</th>
                        <th>'.Phpfox::getPhrase('jobposting.fee').'</th>
                        <th>'.Phpfox::getPhrase('jobposting.remaining_job_posts').'</th>
                        <th>'.Phpfox::getPhrase('jobposting.valid_time').'</th>		
                        <th>'.Phpfox::getPhrase('jobposting.payment_status').'</th>
                    </tr>';
        
        $aPackages = $this->getBoughtPackages($iCompanyId);
        if(is_array($aPackages) && count($aPackages))
        {
            foreach($aPackages as $k => $aPackage)
            {
                $sHtml .= '<tr'.(($k%2 != 0) ? ' class="on"' : '').'>
                        <td>'.$aPackage['name'].'</td>
                        <td class="t_center">$'.$aPackage['fee'].'</td>
                        <td class="t_center">'.(($aPackage['post_number'] == 0) ? Phpfox::getPhrase('jobposting.unlimited') : $aPackage['remaining_post']).'</td>
                        <td class="t_center">'.$aPackage['expire_text'].'</td>
                        <td class="t_center">'.$aPackage['status_text'].'</td>		
                    </tr>';
            }
        }
        else
        {
            $sHtml .= '<tr><td colspan="5"><div class="extra_info">'.Phpfox::getPhrase('jobposting.no_package_found').'</div></td></tr>';
        }
        
        return $sHtml;
    }
    
    public function buildHtmlToBuyPackages($iCompanyId)
    {
        $sHtml = '';
        
        $aPackages = $this->getToBuyPackages($iCompanyId);
        if(is_array($aPackages) && count($aPackages))
        {
            foreach($aPackages as $k => $aPackage)
            {
                $sHtml .= '<li><label><input type="checkbox" name="val[packages][]" value="'.$aPackage['package_id'].'" id="js_jc_package_'.$aPackage['package_id'].'" class="js_jc_package" fee_value="'.$aPackage['fee'].'" />';
                $sHtml .= $aPackage['name'];
                $sHtml .= ' - $'.$aPackage['fee'];
                $sHtml .= ' - '.(($aPackage['post_number']==0) ? Phpfox::getPhrase('jobposting.unlimited') : Phpfox::getPhrase('jobposting.remaining').$aPackage['post_number'].' '.Phpfox::getPhrase('jobposting.job_posts'));
                $sHtml .= ' - '.$aPackage['expire_text'].'</label></li>';
            }
        }
        else
        {
            $sHtml .= '<li><div class="extra_info">'.Phpfox::getPhrase('jobposting.no_package_found').'</div></li>';
        }
        
        return $sHtml;
    }
}

?>