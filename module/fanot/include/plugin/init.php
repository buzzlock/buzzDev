<?php
if(Phpfox::isModule('fanot'))
{
    $bgcolor = (Phpfox::getParam('fanot.notification_bgcolor')!='') ? Phpfox::getParam('fanot.notification_bgcolor') : '#CAD1DE';
	Phpfox::getLib('template')->setHeader(' <style type=\"text/css\"> .fanotui .fanot_item:hover {background-color: '.$bgcolor.' !important;} .fanotui .fanot_selected {background: '.$bgcolor.' !important;} </style> ');
}
?>