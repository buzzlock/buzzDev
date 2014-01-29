/**
 *
 * @copyright      YouNet Company
 * @author         VuDP, TienNPL
 * @package        Module_Resume
 * @version        3.01
 * 
 */

/**
 *	Show popup save recording when user click on save button on flashplayer
 */
function publishResume(resume_id) {
	tb_show(oTranslations['resume.publish_resume'],$.ajaxBox('resume.publishResume', 'width=300&height=180&resume_id='+ resume_id));	
}

/**
 *	Display or hid advanced search box on Resume home page 
 */
function advSearchDisplay()
{
	var $form = $('#resume_adv_search');
	var $flag = $('#form_flag');
	if($flag.val() == 1)
	{
		$form.hide();
		$flag.val(0); 
	}
	else
	{
		$form.show();
		$flag.val(1);
	}
}

/**
 *  Favorite action
 */
function FavoriteAction(sActionType, iItemId) 
{
	if (sActionType == 'favorite') 
	{
		$('#js_favorite_link_unlike_' + iItemId).show();
		$('#js_favorite_link_like_' + iItemId).hide();
		$Core.box('resume.addFavorite', 400, 'id=' + iItemId);
	}
	else 
	{
		$('#js_favorite_link_like_' + iItemId).show();
		$('#js_favorite_link_unlike_' + iItemId).hide();
		$.ajaxCall('resume.deleteFavorite', 'id=' + iItemId, 'GET');
	}
}

/**
 * Note action 
 */
function NoteAction(sActionType, iItemId)
{
	if(sActionType == 'note')
	{
		$Core.box('resume.addNote', 400, 'id=' + iItemId);
	}
	else
	{
		$('#js_favorite_link_note_' + iItemId).show();
		$('#js_favorite_link_unnote_' + iItemId).hide();
		$.ajaxCall('resume.deleteNote', 'id=' + iItemId, 'GET');
	}
}

function showInProfileInfo(e)
{
    var checkbox = $(e);
    var iResumeId = checkbox.val();
    var iShowInProfile = 0;
    var sCheckboxId = checkbox.attr('id');
    if (checkbox.is(':checked'))
    {
        iShowInProfile = 1
    }
    $.ajaxCall('resume.showInProfileInfo', 'iResumeId=' + iResumeId + '&iShowInProfile=' + iShowInProfile + '&sCheckboxId=' + sCheckboxId, 'GET');
}