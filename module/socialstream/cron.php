<?php

include "cli.php";

/**
 * @TODO IMPLEMENT LATER
 */
if (Phpfox::isModule("socialstream"))
{
    $aAgents = Phpfox::getService("socialstream.services")->getAllAgents();
    if (count($aAgents))
    {
        $oService = Phpfox::getService("socialstream.services");
        foreach ($aAgents as $aAgent)
        {
            $oService->getFeed($aAgent['user_id'], $aAgent['service_name']);
        }
    }
}

?>