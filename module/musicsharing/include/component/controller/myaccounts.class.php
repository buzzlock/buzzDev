<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class musicsharing_component_controller_myaccounts extends Phpfox_Component
{
    public function process()
    {
        phpFox::isUser(true);
        $aParentModule = $this->getParam('aParentModule');    
        if($aParentModule)
        {
            phpFox::getLib('session')->set('pages_msf',$aParentModule);
        }
        else
        {
            phpFox::getLib('session')->remove('pages_msf');
        }
        $this->template()->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'),null);
        $info_user=phpFox::getService("musicsharing.cart.account")->getCurrentInfo(phpFox::getUserId());
        $info_account=phpFox::getService("musicsharing.cart.account")->getCurrentAccount(phpFox::getUserId());
        if(strlen($info_user['status'])>=41)
           $info_user['status']=substr($info_user['status'],0,41)."...";
        $user_group_id=phpFox::getService('musicsharing.cart.account')->getUserGroupId(phpFox::getUserId());
        $info_sellingsettings=phpFox::getService("musicsharing.cart.account")->getSellingSettings($user_group_id);
        $fee=$info_sellingsettings['comission_fee'];

        $AmountSeller=phpFox::getService("musicsharing.cart.account")->getAmountSeller(phpFox::getUserId());
        $_settings = phpFox::getService('musicsharing.music')->getSettings(0);
        $iPageSize = $_settings['number_solditem_summary'];
        $iPage = $this->request()->get('page');
        $list_total=$AmountSeller;
        $HistorySeller=phpFox::getService("musicsharing.cart.account")->getHistorySeller(phpFox::getUserId(),$iPage,$iPageSize,$list_total);
        phpFox::getLib('pager')->set(array('page' => $iPage, 'size' => $iPageSize, 'count' => $list_total));
        $this->template()->assign(array('iPage'=>$iPage,'iCnt'=>$iCnt))
                                ->setHeader('cache', array(
                                         'pager.css' => 'style_css'));
        $min_payout=$info_sellingsettings['min_payout'];
        $max_payout=$info_sellingsettings['max_payout'];
        $allow_request=0;
        $requested_amount=phpFox::getService("musicsharing.cart.account")->getTotalRequest(phpFox::getUserId());
        if($info_account['total_amount']>=$min_payout)
        {
            $allow_request=1;
        }
        
        $rest = $info_account['total_amount'] - $requested_amount;
        $this->template()->assign(array(
            'sDeleteBlock' => 'dashboard',
            'list_info' =>$list_info,
            'core_path' =>phpFox::getParam('core.path'),
            'user_id'   =>phpFox::getUserId(),
            'info_user' => $info_user,
            'info_account' => $info_account,
            'min_payout' => $min_payout,
            'max_payout' => $max_payout,
            'HistorySeller' => $HistorySeller,
            'allow_request' => $allow_request,
            'requested_amount'=>round($requested_amount,2),
            'current_amount'=>round($rest,2),
            'fee' => $fee,
            'user_id'=>phpFox::getUserId(),
             'currency' => phpFox::getService('core.currency')->getDefault(),  
            'cur_page'=>$this->request()->get('page')<=0?1:$this->request()->get('page')
        ));
       $this->template()->setHeader(array(
        'm2bmusic_tabcontent.js' => 'module_musicsharing' ,
        'm2bmusic_class.js' => 'module_musicsharing' ,
        'music.css' => 'module_musicsharing',
        'musicsharing_style.css' => 'module_musicsharing',
       ));
       
    }
}
?>
