<?php

require_once 'cli.php';

function download($file, $name)
{
    $type = filetype($file);
    
    header("Content-type: ".$type);
    header("Content-Disposition: attachment; filename=".$name);
    header("Content-Transfer-Encoding: binary");
    header('Pragma: no-cache');
    header('Expires: 0');
    
    set_time_limit(0);
    
    readfile($file);
}

$entry_id = trim($_GET['entry_id'],"/");

$aEntry = Phpfox::getService('contest.entry')->getContestEntryById($entry_id);

if($aEntry)
{
    $sFullTmp = Phpfox::getParam('core.dir_pic').$aEntry['image_path'];
	if (file_exists(sprintf($sFullTmp, '')))
    {
        $file = sprintf($sFullTmp, '');
    }
    else
    {
        $file = sprintf($sFullTmp, '_1024');
    }
    
    download($file, str_replace(" ", "_", $aEntry['title']).".jpg");
}
else
{
    echo Phpfox::getPhrase('contest.this_file_do_not_exist');
}
?>
