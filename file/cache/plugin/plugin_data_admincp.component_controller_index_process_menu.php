<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = '@eval(base64_decode("ICAgICAgICAgJG0gPSAiZmV2ZW50IjsKCSRwID0ieW91bmV0ZXZlbnQiOwogICAgaWYocGhwZm94Ojppc01vZHVsZSgkbSkpCiAgICB7CiAgICAgICAgaWYoIXBocGZveDo6aXNNb2R1bGUoJ3lvdW5ldGNvcmUnKSkKICAgICAgICB7CiAgICAgICAgICAgIHBocGZveDo6Z2V0TGliKCdkYXRhYmFzZScpLT51cGRhdGUocGhwZm94OjpnZXRUKCdwcm9kdWN0JyksYXJyYXkoJ2lzX2FjdGl2ZSc9PjApLCdwcm9kdWN0X2lkID0gIicuJHAuJyInKTsKICAgICAgICAgICAgcGhwZm94OjpnZXRMaWIoJ2RhdGFiYXNlJyktPnVwZGF0ZShwaHBmb3g6OmdldFQoJ21vZHVsZScpLGFycmF5KCdpc19hY3RpdmUnPT4wKSwnbW9kdWxlX2lkID0gIicuJG0uJyInKTsKICAgICAgICAgICAgcGhwZm94OjpnZXRMaWIoJ2NhY2hlJyktPnJlbW92ZSgpOwogICAgICAgIH0KICAgIH0KICAgIA==")); if (phpfox::isModule(\'younetcore\'))
{
    phpfox::getService(\'younetcore.core\')->reverifiedModules(); 
    $aModules = phpfox::getService(\'younetcore.core\')->checkYouNetProducts($aModules);
} '; ?>