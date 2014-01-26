<?php

/*
 * @copyright        [YouNet_COPYRIGHT]
 * @author           YouNet Development
 * @package          Module_Contactimporter
 * @version          2.06
 *
 */
defined('PHPFOX') or exit('NO DICE!');
?>
<?php

class contactimporter_Component_Controller_Admincp_edit extends Phpfox_Component
{

    public function process()
    {
        $provider_name = $this->request()->get('provider');
        if ($provider_name)
        {
            if ($this->request()->get('edit') == phpfox::getPhrase('core.submit'))
            {
                $provider_edit = $this->request()->get('provider_edit');
                Phpfox::getLib('phpfox.database')
                        ->update(Phpfox::getT('contactimporter_providers'), array('title' => $provider_edit['title'], 'enable' => $provider_edit['enable'], 'order_providers' => $provider_edit['order'], 'default_domain' => $provider_edit['default_domain']), 'name="' . $provider_name . '"');
                $this->url()->send('admincp.contactimporter.providers', null, Phpfox::getPhrase('contactimporter.the_infomation_of_provider_name_provider_was_updated_successfully',array('provider_name'=>$provider_name)));
            }

            //print_r($provider_edit);
            $provider = Phpfox::getLib('phpfox.database')->select('*')
                    ->from(Phpfox::getT('contactimporter_providers'), 'c')
                    ->where('c.name ="' . $provider_name . '"')
                    ->execute('getSlaveRow');
            //print_r($provider);
            $this->template()->assign(array('provider' => $provider));
            //return;
        }
        else
        {
            $this->url()->send('admincp.contactimporter.providers', null, Phpfox::getPhrase('contactimporter.you_must_select_provider_to_edit'));
            //$this->url->send('admincp.contactimporter.providers',null,'Hi');
        }
    }

}

?>