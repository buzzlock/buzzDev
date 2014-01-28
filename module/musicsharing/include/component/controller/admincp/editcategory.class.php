<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Musicsharing_Component_Controller_Admincp_EditCategory extends Phpfox_Component{
  public function process(){
        $aParentModule = $this->getParam('aParentModule');    
        if($aParentModule)
        {
            phpFox::getLib('session')->set('pages_msf',$aParentModule);
        }
        else
        {
            phpFox::getLib('session')->remove('pages_msf');
        }
        $title=$this->request()->get('title');
        if(isset($_POST['submit']))
        {
            $aVals = $this->request()->getArray('val');
            $category_id=$this->request()->get('iItemId');
            $title=$aVals['title'];
            $result=phpFox::getService("musicsharing.music")->updateCategory($category_id,$aVals['title']);
            $this->template()->assign(array("result"=>1,"core_path"=>phpFox::getParam("core.path")));
        }
        
        $this->template()->assign(array("title" => $title));
  }
}

?>
