$Core.YouNet_Singer =
{
	deleteImage : function(iSingerId)
	{
		if (confirm(oTranslations['musicsharing.are_you_sure']))
		{
			$.ajaxCall('musicsharing.deleteSingerImage', 'iSingerId=' + iSingerId);
		}
		return false;
	}
}

