<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

/**
 *
 *
 * @copyright		[YOUNET_COPYRIGHT]
 * @author  		minhTA
 */
class Advancedphoto_Component_Ajax_Ynalbum_Ajax extends Phpfox_Ajax
{

	public function removeAlbumTag()
	{
		if ($iAlbumId = Phpfox::getService('advancedphoto.tag.process')->deleteAlbumTag($this->get('tag_id')))
		{
//		    $this->call(' $Core.photo_tag.init({' . Phpfox::getService('advancedphoto.tag')->getJs($iPhoto) . '});');
//			$sJsAlbumTagContent = Phpfox::getService('advancedphoto.tag')->getJsAlbumTagContent($iAlbumId);
//			$this->call('$(\'#ynadvphoto_album_in_this_album\').html(' . $sJsAlbumTagContent . ');');
		}
	}

	public function addAlbumTag()
    {
		$aVals = $this->get('val');
		$bIsFirst = $this->get('bIsFirst');

		$this->val('#ynadvphoto_abum_tag_user_id', '0')->val('#advphoto_album_tag_input', '');
		if (($iTagId = Phpfox::getService('advancedphoto.tag.process')->addAlbumTag($aVals['tag'])))
		{
			$aTag = Phpfox::getService('advancedphoto.tag')->getTagContentOfAlbum($iTagId);
			//work around for comma problem
			if($bIsFirst)
			{
				$sTagJs = Phpfox::getService('advancedphoto.tag')->getJsAlbumTagEntry($aTag, $bIsAddCommaAtBegin = false);
			}
			else
			{
				$sTagJs = Phpfox::getService('advancedphoto.tag')->getJsAlbumTagEntry($aTag, $bIsAddCommaAtBegin = true);
			}
//			$sTagJs = Phpfox::getService('advancedphoto.tag')->removeLastCommaForTagList($sTagJs);
		    $this->append('#ynadvphoto_album_in_this_album', $sTagJs)->call('$(\'#ynadvphoto_album_in_this_album\').parent().show();');
			$this->alert(Phpfox::getPhrase('advancedphoto.tagging_successfully'), '', 300, 150, true);
		    $this->call('$(\'#ynadvphoto_album_in_this_album\').html(ltrim($(\'#ynadvphoto_album_in_this_album\').html(), \', \'));');
			//this below line needs modifying
		}
    }

	public function saveOrder()
	{
		Phpfox::isUser(true);
		$sData = $this->get('data');
		$iIds = explode(',', $sData);
		//album with the biggest order value gonna be the first one
		$iOrder = count($iIds);

		//we should have revert machenism here
		// this is needed to be optimized
		foreach($iIds as $iId)
		{
			Phpfox::getService('advancedphoto.album.process')->updateOrderOfAlbum($iId, $iOrder);
			$iOrder--;
		}

	}

	public function feature()
	{
		Phpfox::isUser(true);
		Phpfox::getUserParam('advancedphoto.can_feature_album', true);

		if (Phpfox::getService('advancedphoto.album.process')->feature($this->get('album_id'), $this->get('type')))
		{
		    if ($this->get('type') == '1')
		    {
				$sHtml = '<a href="#" title="' . Phpfox::getPhrase('advancedphoto.un_feature_this_album') . '" onclick="$.ajaxCall(\'advancedphoto.ynalbum.feature\', \'album_id=' . $this->get('album_id') . '&amp;type=0\'); return false;">' . Phpfox::getPhrase('advancedphoto.un_feature') . '</a>';
		    }
		    else
		    {
				$sHtml = '<a href="#" title="' . Phpfox::getPhrase('advancedphoto.feature_this_album') . '" onclick="$.ajaxCall(\'advancedphoto.ynalbum.feature\', \'album_id=' . $this->get('album_id') . '&amp;type=1\'); return false;">' . Phpfox::getPhrase('advancedphoto.feature') . '</a>';
		    }

		    $this->html('#js_album_feature_' . $this->get('album_id'), $sHtml)->alert(($this->get('type') == '1' ? Phpfox::getPhrase('advancedphoto.album_successfully_featured') : Phpfox::getPhrase('advancedphoto.album_successfully_un_featured')));
		    if ($this->get('type') == '1')
		    {
				$this->addClass('#js_photo_ablum_id_' . $this->get('album_id'), 'row_featured_image');
				$this->call('$(\'#js_photo_album_id_' . $this->get('album_id') . '\').find(\'.js_featured_album:first\').show();');
		    }
		    else
		    {
				$this->removeClass('#js_photo_album_id_' . $this->get('album_id'), 'row_featured_image');
				$this->call('$(\'#js_photo_album_id_' . $this->get('album_id') . '\').find(\'.js_featured_album:first\').hide();');
		    }
		}
	}

	public function popupSlider() {
		$iAlbumId = $this->get('aid');

		Phpfox::getBlock('advancedphoto.popupslider', array(
			"aAlbum" => array("album_id" => $iAlbumId)
		));
	}

	public function badgeCode() {
		$iAlbumId = $this->get('aid');
		$sFrameUrl = Phpfox::permalink("advancedphoto.album-badge", $iAlbumId, "");
		echo "&lt;iframe src=\"{$sFrameUrl}\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:640px; height:528px;\" allowTransparency=\"true\"&gt;&lt;/iframe&gt;";
		// Phpfox::getBlock('advancedphoto.badge', array(
			// "aAlbum" => array("album_id" => $iAlbumId)
		// ));
	}
}

?>
