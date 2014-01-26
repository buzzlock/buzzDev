<?php

if(Phpfox::isModule('fanot') && $sProductId=='fanot')
{
    $this->template()->setHeader(array(
        'jquery.minicolors.css' => 'module_fanot',
        'jquery.minicolors.js' => 'module_fanot',
        '<script type="text/javascript">$Behavior.initColorPicker = function() { $(\'[name="val[value][notification_bgcolor]"]\').minicolors(); };</script>'
    ));
}
