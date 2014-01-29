<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class profilecompleteness_component_controller_admincp_settings extends Phpfox_Component{
    public function process(){
        $aRow=phpfox::getService('profilecompleteness.process')->getProfileCompletenessSettings();        
        if($this->request()->get('profileCompletenessSettings'))
        {
            $val=$this->request()->get('val');
            $is_temp=0;          
            $is_hexa=phpfox::getService("profilecompleteness.process")->is_Hexa($val['gaugecolor']);
            if($is_hexa==0)
            {
                $is_temp=1;
                Phpfox_Error::set("Gauge Color in Hex is invalid");
                $aRow['gaugecolor']="";
            }
            if($is_temp==0)
            {               
                Phpfox::getService("profilecompleteness.process")->InsertProfileCompletenessSettings($val);
                $this->url()->send('current',null,Phpfox::getPhrase('profilecompleteness.update_global_settings_successfully'));
            }     
        }
        
        $this->template()->setHeader('izzyColor.js','module_profilecompleteness');
        $this->template()->setBreadCrumb(Phpfox::getPhrase('profilecompleteness.global_settings'),$this->url()->makeurl('admincp.profilecompleteness'));
        $this->template()->assign(array(
           'aRow' => $aRow,
        ));
    }
}
?>
