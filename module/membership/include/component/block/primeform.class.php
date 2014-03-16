 <?php
 
class Membership_Component_Block_Primeform extends Phpfox_Component
{    
    public function process()
    {
 		$this->template()->assign(array('sHeader' => 'Buzzlock Prime'));        
        return 'block'; 
    }
}
 
?> 