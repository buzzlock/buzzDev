<?php 
defined('PHPFOX') or exit('NO DICE!');
class musicsharing_Component_Block_Categories extends Phpfox_Component 
{     
    public function process() 
    { 
		// phpFox::isUser(true);
		$cat = false;
        if($this->request()->get('cat')){
			$cat = $this->request()->get('cat');
        }

		$aCats = PhpFox::getService('musicsharing.music')->getCategories();
		
		foreach($aCats as $key => $aCat)
		{
			$aCat['title'] = Phpfox::getLib("locale")->convert($aCat['title']);
			$aCats[$key] = $aCat; 
		}

		$this->template()->assign(array(
			'sHeader' => phpFox::getPhrase('musicsharing.categories'),
			'sDeleteBlock' => 'dashboard',
			'aCats' => $aCats,
			"scat" => $cat,
		));
		
        return 'block';
    } 
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('musicsharing.component_block_categories_clean')) ? eval($sPlugin) : false);
	}
} 


?>