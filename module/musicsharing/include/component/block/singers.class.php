<?php 
defined('PHPFOX') or exit('NO DICE!');  
class musicsharing_Component_Block_Singers extends Phpfox_Component 
{     
    public function process() 
    {
		//phpFox::isUser(true);
		$sfsid = false;
        if($this->request()->get('singer')){
			$sfsid = $this->request()->get('singer');
        }
		$a=phpFox::getPhrase('musicsharing.singers');
		$this->template()->assign(array(
			'sHeader' => $a,
			'sDeleteBlock' => 'dashboard',
			'aSingers' =>phpFox::getService('musicsharing.music')->getSingers(),
			'sfsid' => $sfsid,
		));
		return 'block';
    } 
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_block_singers_clean')) ? eval($sPlugin) : false);
    }
} 
  
?>