var $sFormAjaxRequest = null;
var $bButtonSubmitActive = true;
var $ActivityFeedCompleted = {};
var $sCurrentSectionDefaultPhrase = null;
var $sCssHeight = '40px';
var $sCustomPhrase = null;
var $sCurrentForm = null;
var $sStatusUpdateValue = null;
var $iReloadIteration = 0;
var $oLastFormSubmit = null;
var bCheckUrlCheck = false;
var bCheckUrlForceAdd = false;
var aUrlChecked = {};
var wallFillterViewId = 'all';

function getSocialStreamFeeds() {
    $('.socialstream_get_feeds_link').hide(0, function() {
        $('.socialstream_get_feeds_img').show();
    });
    $.ajaxCall('socialstream.getFeeds');
}

$Core.isInView = function(elem) {
    if (!$Core.exists(elem)) {
        return false;
    }

    var docViewTop = $(window).scrollTop();
    var docViewBottom = docViewTop + $(window).height();

    var elemTop = $(elem).offset().top;
    var elemBottom = elemTop + $(elem).height();

    return ((docViewTop < elemTop) && (docViewBottom > elemBottom));
}

$Core.resetActivityFeedForm = function() 
{
    $('.activity_feed_form_attach li a').removeClass('active');
    $('.activity_feed_form_attach li a:first').addClass('active');
    $('.global_attachment_holder_section').hide();
    $('#global_attachment_status').show();

    bCheckUrlCheck = false;
    aUrlChecked = {};

    $('._uiMentionHighlighter').html('');
    $('.activity_feed_form_holder ._uiMentionTypeahead').val('');
    $('.activity_feed_form_button_status_info').hide();
    $('.activity_feed_form_button_status_info textarea').val('');

    $Core.resetActivityFeedErrorMessage();

    $sFormAjaxRequest = $('.activity_feed_form_attach li a.active').find('.activity_feed_link_form_ajax').html();

    $Core.activityFeedProcess(false);

    $('.js_share_connection').val('0');
    $('.feed_share_on_item a').removeClass('active');

    $.each($ActivityFeedCompleted, function() {
        this(this);
    });

    $('#js_add_location, #js_location_input, #js_location_feedback').hide();
    $('.activity_feed_form_button_position').show();
    $('#hdn_location_name, #val_location_name ,#val_location_latlng').val('');
    $('#btn_display_check_in').removeClass('is_active');    
}

$Core.resetActivityFeedErrorMessage = function() {
    $('#activity_feed_upload_error').hide();
    $('#activity_feed_upload_error_message').html('');
}

$Core.resetActivityFeedError = function(sMsg) {
    $('.activity_feed_form_share_process').hide();
    $('.activity_feed_form_button .button').removeClass('button_not_active');
    $bButtonSubmitActive = true;

    $('#activity_feed_upload_error').show();
    $('#activity_feed_upload_error_message').html(sMsg);
}

$Core.activityFeedProcess = function($bShow) {
    if ($bShow) {
        $bButtonSubmitActive = false;
        $('.activity_feed_form_share_process').show();
        $('.activity_feed_form_button .button').addClass('button_not_active');
    } else {
        $bButtonSubmitActive = true;
        $('#activity_feed_upload_error').hide();
        $('.activity_feed_form_share_process').hide();
        $('.activity_feed_form_button .button').removeClass('button_not_active');
        $('.egift_wrapper').hide();
    }
}

$Core.addNewPollOption = function() {
    $('.js_poll_feed_answer').append('<li><input type="text" name="val[answer][][answer]" value="" size="30" class="js_feed_poll_answer v_middle" /></li>');

    return false;
}

$Core.forceLoadOnFeed = function() {

	if('search' == oCore['core.section_module'].toLowerCase()){
		return false;
	}
	
    if ($iReloadIteration >= 2) {
        return;
    }

    if (!$Core.exists('#js_feed_pass_info')){
        return;
    }

    $iReloadIteration++;
    $('#feed_view_more_loader').show();
    $('.global_view_more').hide();

    setTimeout("$.ajaxCall('wall.viewMore', $('#js_feed_pass_info').html().replace(/&amp;/g, '&') + '&iteration=" + $iReloadIteration + '&viewId=' + wallFillterViewId + "', 'GET');", 1000);
}
/**
 * stupid method because it is use too many resource from our site.
 * need more portion in this way.
 * please tell the trust of this.
 */
$Core.handlePasteInFeed = function() {
}

$Core.cancelParseLinkInFeed = function() {
    $('#js_preview_link_attachment_custom_form_sub').remove();
    $('#js_status_attachment_link_cancel').hide();
    bCheckUrlCheck = false;
}
$Core.wallHandlePasteInLink = function(input, oParam) {
    var val = $("#js_global_attach_value").val();
    if (val == '' || val == 'http://') {
        $("#js_global_attach_value").val(oParam.url);
    }
}
$Core.wallHandlePasteInFeed = function(input, oParam) {
    var url = oParam.url;
    if ( typeof aUrlChecked[url] != 'undefined') {
        return;
    }
    aUrlChecked[url] = 1;

    bCheckUrlCheck = true;
    $('.activity_feed_form_share_process').show();
    $(input).closest('.global_attachment_holder_section').find('.js_preview_link_attachment_custom_form').remove();
    $(input).closest('.global_attachment_holder_section').prepend('<div id="js_preview_link_attachment_custom_form_sub" class="js_preview_link_attachment_custom_form"></div>');
    $Core.ajax('wall.preview', {
        type : 'POST',
        params : {
            'no_page_update' : '1',
            value : oParam.url
        },
        success : function($sOutput) {
            bCheckUrlCheck = false;

            if (substr($sOutput, 0, 1) == '{') {
                var $oOutput = $.parseJSON($sOutput);
                $Core.resetActivityFeedError($oOutput['error']);
                $bButtonSubmitActive = false;
                $('.activity_feed_form_button .button').addClass('button_not_active');
            } else {
                bCheckUrlForceAdd = true;
                $Core.activityFeedProcess(false);
                $('.activity_feed_form_share_process').hide();
                $('#js_status_attachment_link_cancel').show();
                $('#js_global_attach_value').val(url);
                // bCheckUrlCheck = false;
                $('#js_preview_link_attachment_custom_form_sub').html($sOutput);
            }
        }
    });

}
$Behavior.activityFeedAttachLink = function() {

    $('#js_global_attach_link').click(function() {
        $Core.activityFeedProcess(true);

        $Core.ajax('wall.preview', {
            params : {
                'no_page_update' : '1',
                value : $('#js_global_attach_value').val()
            },
            type : 'POST',
            success : function($sOutput) {
                $('#js_global_attachment_link_cancel').show();
                bCheckUrlCheck = false;
                if (substr($sOutput, 0, 1) == '{') {
                    var $oOutput = $.parseJSON($sOutput);
                    $Core.resetActivityFeedError($oOutput['error']);
                    $bButtonSubmitActive = false;
                    $('.activity_feed_form_button .button').addClass('button_not_active');
                } else {
                    $Core.activityFeedProcess(false);
                    $('#js_preview_link_attachment').html($sOutput);
                    $('#global_attachment_link_holder').hide();
                }
            }
        });
    });
}
$Behavior.activityFeedProcess = function() {

    if (!$Core.exists('#js_feed_content')) {
        $iReloadIteration = 0;
        return;
    }

    if ($Core.exists('.global_view_more')) {
        if ($Core.isInView('.global_view_more')) {
            $Core.forceLoadOnFeed();
        }

        $(window).scroll(function() {
            if ($Core.isInView('.global_view_more')) {
                $Core.forceLoadOnFeed();
            }
        });
    }

    $sFormAjaxRequest = $('.activity_feed_form_attach li a.active').find('.activity_feed_link_form_ajax').html();
    if ( typeof Plugin_sFormAjaxRequest == 'function') {
        Plugin_sFormAjaxRequest();
    }

    if ($Core.exists('.profile_timeline_header')) {
        $(window).scroll(function() {
            if (isScrolledIntoView('.profile_timeline_header')) {
                $('.timeline_main_menu').removeClass('timeline_main_menu_fixed');
                $('#timeline_dates').removeClass('timeline_dates_fixed');
            } else {
                if (!$('.timeline_main_menu').hasClass('timeline_main_menu_fixed')) {
                    $('.timeline_main_menu').addClass('timeline_main_menu_fixed');

                    if ($('#content').height() > 600) {
                        $('#timeline_dates').addClass('timeline_dates_fixed');
                    }
                }
            }
        });
    }

    /*
     $('#global_attachment_status textarea').keyup(function(){

     $Core.handlePasteInFeed($(this));}).bind('paste', function()
     {
     var that = this;
     setTimeout(function(){
     $Core.handlePasteInFeed(that);
     }, 0);

     });
     */

    $('#global_attachment_status textarea').focus(function() {
        if ($(this).val() == $('#global_attachment_status_value').html()) {
            $(this).val('');
            //$(this).css({height: '50px'});
            $('.activity_feed_form_button').show();
            $(this).addClass('focus');
            $('.activity_feed_form_button_status_info textarea').addClass('focus');
        }
    });

    $('.activity_feed_form_button_status_info textarea').focus(function() {
        //console.log('focus');
        var $sDefaultValue = $(this).val();
        var $bIsDefault = true;

        $('.activity_feed_extra_info').each(function() {
            if ($(this).html() == $sDefaultValue) {
                $bIsDefault = false;
                return false;
            }
        });

        if (($('#global_attachment_status textarea').val() == $('#global_attachment_status_value').html() && empty($sDefaultValue)) || !$bIsDefault) {
            $(this).val('');
            //$(this).css({height: '50px'});

            $(this).addClass('focus');
            $('#global_attachment_status textarea').addClass('focus');
        }
    });

    $('#js_activity_feed_form').submit(function() {
        
        if ($sCurrentForm == 'global_attachment_status') {
            var oStatusUpdateTextareaFilled = $('#global_attachment_status textarea');

            if ($sStatusUpdateValue == oStatusUpdateTextareaFilled.val()) {
                oStatusUpdateTextareaFilled.val('');
            }
        } else {
            var oCustomTextareaFilled = $('.activity_feed_form_button_status_info textarea');

            if ($sCustomPhrase == oCustomTextareaFilled.val()) {
                oCustomTextareaFilled.val('');
                $('#input_hidden_user_info').val('');
                $('#input_hidden_user_status').val('');
            }
        }

        if ($bButtonSubmitActive === false) {
            return false;
        }

        $Core.activityFeedProcess(true);

        if ($sFormAjaxRequest === null) {
            return true;
        }

        $('.js_no_feed_to_show').remove();

        if (bCheckUrlForceAdd) {
            $('.activity_feed_form_button_status_info textarea').val($('#global_attachment_status textarea').val());

            $sFormAjaxRequest = 'link.addViaStatusUpdate';
        }

        $(this).ajaxCall($sFormAjaxRequest);

        if (bCheckUrlForceAdd) {
            $('#js_preview_link_attachment_custom_form_sub').remove();
        }

        return false;
    });

    $('.activity_feed_form_attach li a').click(function() {
        $sCurrentForm = $(this).attr('rel');

        if ($sCurrentForm == 'view_more_link') {
            $('.view_more_drop').toggle();
            return false;
        } else {
            $('.view_more_drop').hide();
        }

        $('#js_preview_link_attachment_custom_form_sub').remove();
        $('#activity_feed_upload_error').hide();

        $('.global_attachment_holder_section').hide();
        $('.activity_feed_form_attach li a').removeClass('active');
        $(this).addClass('active');

        if ($(this).find('.activity_feed_link_form').length > 0) {
            $('#js_activity_feed_form').attr('action', $(this).find('.activity_feed_link_form').html()).attr('target', 'js_activity_feed_iframe_loader');
            $sFormAjaxRequest = null;
            if (empty($('.activity_feed_form_iframe').html())) {
                $('.activity_feed_form_iframe').html('<iframe id="js_activity_feed_iframe_loader" name="js_activity_feed_iframe_loader" height="200" width="500" frameborder="1" style="display:none;"></iframe>');
            }
        } else {
            $sFormAjaxRequest = $(this).find('.activity_feed_link_form_ajax').html();
        }

        $('#' + $(this).attr('rel')).show();
        $('.activity_feed_form_holder_attach').show();
        $('.activity_feed_form_button').show();

        var $oStatusUpdateTextarea = $('#global_attachment_status textarea');
        var $sStatusUpdateTextarea = $oStatusUpdateTextarea.val();
        $sStatusUpdateValue = $('#global_attachment_status_value').html();

        var $oCustomTextarea = $('.activity_feed_form_button_status_info textarea');
        var $sCustomTextarea = $oCustomTextarea.val();

        $sCustomPhrase = $(this).find('.activity_feed_extra_info').html();

        var $bHasDefaultValue = false;
        $('.activity_feed_extra_info').each(function() {
            if ($(this).html() == $sCustomTextarea) {
                $bHasDefaultValue = true;

                return false;
            }
        });

        if ($(this).attr('rel') != 'global_attachment_status') {
            $('.activity_feed_form_button_status_info').show();

            if ((empty($sCustomTextarea) && ($sStatusUpdateTextarea == $sStatusUpdateValue || empty($sStatusUpdateTextarea))) || ($sStatusUpdateTextarea == $sStatusUpdateValue && $bHasDefaultValue) || (!$bButtonSubmitActive && $bHasDefaultValue)) {
                /**
                 * reset default height
                 */
                $oCustomTextarea.val($sCustomPhrase).css({
                    height : $sCssHeight
                });
            } else if ($sStatusUpdateTextarea != $sStatusUpdateValue && $bButtonSubmitActive && !empty($sStatusUpdateTextarea)) {
                $oCustomTextarea.val($sStatusUpdateTextarea);
            }

            $('.activity_feed_form_button .button').addClass('button_not_active');
            $bButtonSubmitActive = false;
        } else {
            $('.activity_feed_form_button_status_info').hide();
            $('.activity_feed_form_button .button').removeClass('button_not_active');

            if (!$bHasDefaultValue && !empty($sCustomTextarea)) {
                $oStatusUpdateTextarea.val($sCustomTextarea);

                // colation $sCustomTextareaHidden
            } else if ($bHasDefaultValue && empty($sStatusUpdateTextarea)) {
                $oStatusUpdateTextarea.val($sStatusUpdateValue).css({
                    height : $sCssHeight
                });
            }

            $bButtonSubmitActive = true;
        }

        if ($(this).hasClass('no_text_input')) {
            $('.activity_feed_form_button_status_info').hide();
        }

        if ($(this).attr('rel') == 'global_attachment_photo') {
            if (((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i)))) {
                // if ($('#Filedata').length < 1) /* it means we already added it and triggered mobileInit() */
                $('#js_piccup_upload').remove();
                $('.activity_feed_form_button .button').hide().after('<div id="js_piccup_upload"><input type="button" name="Filedata" id="Filedata" value="Choose photo"></div>');
                mobileInit();
            }
        } else {
            $('.activity_feed_form_button .button').show();
            $('#js_piccup_upload').hide();
        }

        return false;
    });
}

$Behavior.activityFeedLoader = function() {
    /**
     * Click on adding a new comment link.
     */
    $('.js_feed_entry_add_comment').click(function() {
        $('.js_comment_feed_textarea').each(function() {
            if ($(this).val() == $('.js_comment_feed_value').html()) {
                $(this).removeClass('js_comment_feed_textarea_focus');
                $(this).val($('.js_comment_feed_value').html());
                $(this).closest('._uiCommentComposer').find('._uiMentionHidden').val('');
            }

            $(this).parents('.comment_mini').find('.feed_comment_buttons_wrap').hide();
        });

        $(this).parents('.js_parent_feed_entry:first').find('.comment_mini_content_holder').show();
        $(this).parents('.js_parent_feed_entry:first').find('.feed_comment_buttons_wrap').show();

        if ($(this).parents('.js_parent_feed_entry:first').find('.js_comment_feed_textarea').val() == $('.js_comment_feed_value').html()) {
            $(this).parents('.js_parent_feed_entry:first').find('.js_comment_feed_textarea').val('');
        }
        $(this).parents('.js_parent_feed_entry:first').find('.js_comment_feed_textarea').focus().addClass('js_comment_feed_textarea_focus');
        $(this).parents('.js_parent_feed_entry:first').find('.comment_mini_textarea_holder').addClass('comment_mini_content');
        $(this).parents('.js_parent_feed_entry:first').find('.js_feed_comment_form').find('.comment_mini_image').show();

        var iTotalComments = 0;
        $(this).parents('.js_parent_feed_entry:first').find('.js_mini_feed_comment').each(function() {
            iTotalComments++;
        });

        if (iTotalComments > 2) {
            $.scrollTo($(this).parents('.js_parent_feed_entry:first').find('.js_comment_feed_textarea_browse:first'), 340);
        }

        return false;
    });

    /**
     * Comment textarea on focus.
     */
    $('.js_comment_feed_textarea').click(function() {
        $Core.commentFeedTextareaClick(this);
    });

    $('#js_captcha_load_for_check_submit').submit(function() {

        if (function_exists('' + Editor.sEditor + '_wysiwyg_feed_comment_form')) {
            eval('' + Editor.sEditor + '_wysiwyg_feed_comment_form(this);');
        }

        $oLastFormSubmit.parent().parent().find('.js_feed_comment_process_form:first').show();
        $(this).ajaxCall('comment.add', $oLastFormSubmit.getForm());

        return false;
    });

    $('.js_comment_feed_form').submit(function() {
        if ($Core.exists('#js_captcha_load_for_check')) {
            $('#js_captcha_load_for_check').css({
                top : getPageScroll()[1] + (getPageHeight() / 5),
                left : '50%',
                'margin-left' : '-' + (($('#js_captcha_load_for_check').width() / 2) + 12) + 'px',
                display : 'block'
            });

            $oLastFormSubmit = $(this);

            return false;
        }

        if (function_exists('' + Editor.sEditor + '_wysiwyg_feed_comment_form')) {
            eval('' + Editor.sEditor + '_wysiwyg_feed_comment_form(this);');
        }
        $(this).parent().parent().find('.js_feed_comment_process_form:first').show();
        var el = $(this).find('._uiMentionHidden');
        
        if(el.val() == $('.js_comment_feed_value:first').text()){
            el.val('');
        }
        $(this).ajaxCall('comment.add');
        return false;
    });

    $('.js_comment_feed_new_reply').click(function() {

        var oParent = $(this).parents('.js_mini_feed_comment:first').find('.js_comment_form_holder:first');
        if ((Editor.sEditor == 'tiny_mce' || Editor.sEditor == 'tinymce') && isset(tinyMCE) && isset(tinyMCE.activeEditor)) {
            $('.js_comment_feed_form').find('.js_feed_comment_parent_id:first').val($(this).attr('rel'));
            tinyMCE.activeEditor.focus();
            if ( typeof ($.scrollTo) == 'function') {
                $.scrollTo('.js_comment_feed_form', 800);
            }
            return false;
        }

        oParent.html($('.js_feed_comment_form').html());
        oParent.find('.js_feed_comment_parent_id:first').val($(this).attr('rel'));

        oParent.find('.js_comment_feed_textarea:first').focus();
        $Core.commentFeedTextareaClick(oParent.find('.js_comment_feed_textarea:first'));

        $Core.loadInit();
        /*$Behavior.activityFeedLoader();*/

        return false;
    });

    $('.comment_mini').hover(function() {

        $('.feed_comment_delete_link').hide();
        $(this).find('.feed_comment_delete_link:first').show();

    }, function() {

        $('.feed_comment_delete_link').hide();

    });

}
/**
 * include this issue in method.
 */
$Core.commentFeedTextareaClick = function($oObj) 
{
    if ($($oObj).val() == $('.js_comment_feed_value').html()) {
        $($oObj).val('');
        $($oObj).closest('._uiCommentComposer').find('._uiMentionHidden').val('');
    }

    $($oObj).addClass('js_comment_feed_textarea_focus').addClass('is_focus');
    $($oObj).parents('.comment_mini').find('.feed_comment_buttons_wrap:first').show();

    $($oObj).closest('.js_feed_comment_form').find('.comment_mini_textarea_holder:first').addClass('comment_mini_content');
    $($oObj).closest('.js_feed_comment_form').find('.comment_mini_image:first').show();
    /*p($($oObj).parent().parent().html());*/
}

$ActivityFeedCompleted.link = function() {
    $bButtonSubmitActive = true;

    $('#global_attachment_link_holder').show();
    $('.activity_feed_form_button .button').removeClass('button_not_active');
    $('#js_preview_link_attachment').html('');
    $('#js_global_attach_value').val('http://');
}

$ActivityFeedCompleted.photo = function() {
    $bButtonSubmitActive = true;

    $('#global_attachment_photo_file_input').val('');
}
var sToReplace = '';

$Core.createEmotions = function(wraper) {
    var len = Typeahead.emotions.length;
    
    if (!len) {
        return;
    }
    if (!wraper.find('.div_emotions_wraper').length) {
        var list1 = $('<div class="div_emotions_wraper">').appendTo(wraper);
        list1.html('<img src="' + oParams.sJsHome + 'file/pic/emoticon/default/happy.png" class="v_middle" \="" />');
        var list2 = $('<div class="div_emotions_list">').appendTo(list1);
        var html = '';
        for (var i = 0; i < len; ++i) 
        {
            var obj = Typeahead.emotions[i];
            html += '<span title="'+obj.title+'" icon="'+obj.text +'" class="emotion_items">' + '<img width="16" height="16" src="SJSHOME/file/pic/emoticon/PACKAGE/IMAGE" text-id="TEXT" />'.replace('SJSHOME', oParams.sJsHome).replace('IMAGE', obj.image).replace('PACKAGE', obj.package_path).replace('TITLE', obj.title).replace('TEXT', obj.text) +'</span>';
        }
        list2.html(html);
    }
}
$Behavior.tagger = function() {
    
    if($.isEmptyObject(Typeahead.data))
    {
        $.ajaxCall('wall.getFriendsData');
    }
    
    $Core.createEmotions($('#user_status_area'));
    $Core.createEmotions($('#user_info_area'));

    $('.js_feed_comment_process_form').hide();

    $('#user_status_area .emotion_items').bind('click', function() {
        var ele = $(this);
        var text = ele.attr('icon');
        var textarea = ele.closest('._uiPostComposer').find('textarea');
        textarea.trigger('focus');
        var caret = textarea.caret().start;
        var val = textarea.val();        
        val = val.substr(0, caret) + text + val.substr(caret);
        textarea.val(val);
        textarea.trigger('changed');
    });

    $('#user_info_area .emotion_items').bind('click', function() {
        var ele = $(this);
        var text = ele.attr('icon');
        var textarea = ele.closest('._uiPostComposer').find('textarea');
        var caret = textarea.caret().start;
        textarea.trigger('focus');
        var val = textarea.val();
        val = val.substr(0, caret) + text + val.substr(caret);
        textarea.val(val);
        textarea.trigger('changed');
    });

    $('#js_global_attachment_link_cancel').bind('click', function() {
        $('#js_global_attachment_link_cancel').hide();
    })
    if ($('.textarea_post_user_status').length >= 1) {
        $.each($('.textarea_post_user_status'), function(key, value) {
            //console.log(value);
            Typeahead.create($(value), {
                minHeight : 40,
                deltaHeight : 20,
                tagsKey : 'user_status',
                onParseLink : $Core.wallHandlePasteInFeed
            });
        });
    }

    if ($('#activity_feed_textarea_status_info').length) {
        Typeahead.create($('#activity_feed_textarea_status_info'), {
            minHeight : 40,
            deltaHeight : 20,
            tagsKey : 'user_status',
            onParseLink : $Core.wallHandlePasteInLink
        });
    };

    /**
     * replace comment on the fly.
     */
    function decorateCommentMini(ele) {
        var t = '\x3Cdiv class=\"_uiCommentComposer\"\x3E\n\t\x3Cdiv class=\"_uiMentionTypeAheadContainer\"\x3E\n\t\t\x3Cdiv class=\"_uiMentionHighlighterArea _uiCommentHighlighterArea\"\x3E\n\t\t\t\x3Cdiv class=\"innerWrap\"\x3E\n\t\t\t\t\x3Cspan class=\"_uiMentionHighlighter\" style=\"\"\x3E  \x3C\x2Fspan\x3E\n\t\t\t\t\x3C\x2Fdiv\x3E\n\t\t\t\x3C\x2Fdiv\x3E\n\t\t\x3Cdiv class=\"_uiTypeaheaMentiondArea\"\x3E\n\t\t\x3Cdiv class=\"innerWrap\"\x3E\n\t\t\x3Ctextarea cols=\"60\" rows=\"1\" name=\"val[plain_text]\" class=\"js_comment_feed_textarea _uiMentionTypeahead text js_comment_feed_textarea_focus\" id=\"js_feed_comment_form_textarea_ID\" style=\"\"\x3E{adding_your_comment}\x3C\x2Ftextarea\x3E\n\t\t\t\x3Cdiv style=\"position:absolute;\" class=\"js_feed_comment_process_form\"\x3E{writing_your_comment}\x3Cimg src=\"[CORE_URL]theme\x2Ffrontend\x2Fdefault\x2Fstyle\x2Fdefault\x2Fimage\x2Fajax\x2Fadd.gif\" alt=\"\"\x3E\x3C\x2Fdiv\x3E\n\t\t\x3C\x2Fdiv\x3E\n\t\t\x3C\x2Fdiv\x3E\n\t\t\x3Cinput type=\"hidden\" name=\"val[text]\" class=\"_uiMentionHidden\" value=\"\"\x3E\n\t\x3C\x2Fdiv\x3E\n\x3C\x2Fdiv\x3E';
        t = t.replace('{adding_your_comment}',$('.js_comment_feed_value:first').text());
        var id = ele.attr('id').replace('js_feed_comment_form_textarea_', '').replace(/\s+/m, '');
        var newId = 'js_feed_comment_form_textarea_' + id;
        t = t.replace('js_feed_comment_form_textarea_ID', newId).replace('[CORE_URL]', oParams.sJsHome);
        ele.parent().append(t);
        ele.remove();
        return newId;
    }

    if ($('.js_comment_feed_textarea').length) {
        $.each($('.js_comment_feed_textarea'), function(key, value) {
            var ele = $(value);
            if (!ele.hasClass('_uiMentionTypeahead')) {
                //console.log(ele);
                var newId = decorateCommentMini(ele);
                value = $('#' + newId);
                $(value).bind('click',function(evt){
                    $Core.commentFeedTextareaClick($(this));
                });
            }            
            Typeahead.create($(value), {
                minHeight : 20,
                deltaHeight : 0
            });
        });
    }
    return;
};

var Typeahead = {
    /**
     * cached data for later used.
     */
    cached : {
    },
    data : {
    },
    emotions : [],
    init : function(ele, opts) {
        if (ele.attr('typeahead-init')) {
            return;
        }
        ele.attr('typeahead-init', '1');
        Typeahead.create(ele, opts);
    },
    setData: function(obj)
    {
        Typeahead.data= obj;
    },
    sharedTags : {},
    create : function(ele, newOpts) {
        /**
         * waiting time from keydown to process text
         */
        var time = 20;
        /**
         *timeout id
         */
        var timeId = 0;

        /**
         *  token position
         */
        var tokenPos = 0;

        /**
         * token string
         */
        var tokenStr = '';

        /**
         * input element
         */
        var input = $(ele);

        /**
         * closed
         */
        var container = input.closest('._uiMentionTypeAheadContainer');

        /**
         * dom
         */
        var hidden = container.find('._uiMentionHidden');

        /**
         *dom
         */
        var hightlight = container.find('._uiMentionHighlighter');

        /**
         * ac list is hidding
         */
        var isHide = true;

        /**
         * ul list
         */
        var ul = 0;

        /**
         * replace element.
         */
        var reg2 = /(\[x\=\d+\])([^\[]+)(\[\/x\])/gi;

        /**
         * searchLink regular
         */
        var linkReg = /(http|https):\/\/(\w|\-|\_)+(\.\w{2,})+([\w#!:.?+=&%@!\-\/])*(\s|\n)/i;

        var opts = $.extend({
            min : 0,
            max : 10,
            onSubmit : false,
            minHeight : 0,
            deltaHeight : 0,
            remoteUrl : false,
            maxDisplay : 10,
            autoParseLink : true,
            onParseLink : false,
            tagsKey : false,
        }, newOpts);

        var tagsKey = 'all';

        if (opts.tagsKey == false) {
            tagsKey = input.attr('id');
        }

        if ( typeof Typeahead.sharedTags[tagsKey] == 'undefined') {
            Typeahead.sharedTags[tagsKey] = {};
        }

        /**
         * escape regular expression
         */
        function escape(text) {
            return text.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
        }

        function filterData(keyword) {
            var rows = new Array();
            var length = Typeahead.data.length;
            var taken = keyword.substr(1).replace(/$/g, '').split(/\s+/g);

            for (var i = 0; i < taken.length; ++i) {
                taken[i] = escape(taken[i]);
            }

            var num = taken.length;
            var reg = new RegExp('(^|\\s+)(' + taken.join('|') + ')', 'gi');

            // console.log(reg);

            for (var i = 0; i < length && rows.length <= 30; i++) {
                var obj = Typeahead.data[i];
                var match = obj.text.match(reg);
                // console.log(match);

                if (match && match.length >= num) {
                    rows.push(obj);
                }
            }

            var ret = {
                'q' : keyword,
                'rows' : rows
            }
            return ret;
        }

        /**
         * create ul dom
         */
        function createUl() {
            ul = $('#_uiAcList');
            if (ul.length == 0) {
                ul = $('<ul class="_uiFlyLayer _uiAcList" id="_uiAcList">').appendTo('body');
            }
        }

        /**
         * hide ul list
         */
        function hideUl() {
            isHide = true;
            ul && ul.hide();
        }

        /**
         *show
         */
        function showUl() {
            var offset = input.offset();
            ul.css({
                left : offset.left,
                top : offset.top + input.height(),
                width : input.width()
            }).show();
            isHide = false;
        }

        /**
         * move list up
         */
        function goUp() {
            var e = ul.find('li.active');
            e = e.prev();

            if (e.length == 0) {
                e = ul.find('li.item:first');
            }
            ul.find('li').removeClass('active');
            e.addClass('active');
        }

        /**
         * move list down
         */
        function goDown() {
            var e = ul.find('li.active');
            e = e.next();

            if (e.length == 0) {
                e = ul.find('li.item:first');
            }
            ul.find('li').removeClass('active');
            e.addClass('active');
        }

        /**
         * get current active item
         */
        function getActiveLi() {
            if (isHide) {
                return false;
            }
            var e = ul.find('li.active');

            if (e.length == 0) {
                e = ul.find('.item:first');
            }

            if (e.length == 0) {
                return false;
            }

            return e;
        }

        /**
         * create item
         * @param Object {type, id, text, photo}
         */
        function createLi(obj) {
            var html = '<img class="lfloat mright5" width="32" height="32" src="' + obj.photo + '" />' + obj.text + '';
            var li = $('<li class="clearfix item">').appendTo(ul);
            li.html(html);
            li.bind('mousedown', function(evt) {
                selectHandle(obj);
            }).bind('mouseover', function(evt) {
                $(this).addClass('active');
            }).bind('mouseout', function(evt) {
                $(this).removeClass('active');
            });
        }

        function createList(data) {
            createUl();
            ul.html('');
            Typeahead.cached[data.q] = data;

            if (data.q != tokenStr) {
                hideUl();
                return;
            }

            var tags = Typeahead.sharedTags[tagsKey];

            var count = 0;
            for (var i = 0; i < data.rows.length; ++i) {
                var o = data.rows[i];
                var id = o.type + '.' + o.id;
                if ( typeof tags[id] != 'undefined')
                    continue;
                createLi(data.rows[i]);
                if (++count > opts.maxDisplay) {
                    break;
                };
            }

            /**
             *check if empty content.
             */
            if (!count) {
                hideUl();
            } else {
                showUl();
            }

        }

        /**
         * pre process
         */
        function processInput() {
            /**
             * check caret pos
             */
            caretPos = input.caret().start;

            var val = input.val();
			val += " ";
			
            /**
             * get query string
             */
            var q = val.substr(0, caretPos);

            if (opts.onParseLink && !bCheckUrlCheck) {
                var match = val.match(linkReg);

                if (match) {
                    var url = match[0].replace(/\s|\n/g, '');

                    input.trigger('parseLink', {
                        'url' : url
                    });
                }
            }

            /**
             * get token pos
             */
            tokenPos = q.lastIndexOf('@');

            if (tokenPos < 0) {
                return '';
            };

            q = q.substr(tokenPos, caretPos);

            /**
             * do not check if token string is the same
             */
            if (q == tokenStr) {
                return;
            }

            /**
             * apply new tokenStr to check
             */
            tokenStr = q;

            if (tokenStr == '') {
                return;
            }

            if (tokenStr.length < opts.min || tokenStr.length > opts.max) {
                hideUl();
                return;
            }
            /**
             *check in cached
             */
            if ( typeof Typeahead.cached[tokenStr] == 'object') {
                createList(Typeahead.cached[tokenStr]);
                return;
            }

            if (opts.remoteUrl) {
                /**
                 * get data from remote
                 */
                $.getJSON(opts.remoteUrl, {
                    'q' : tokenStr
                }, createList);
            } else {
                createList(filterData(tokenStr));
            }

        }

        var oldText = '';

        /**
         * update hidden val
         */
        function updateHidden(val) {
            val = val.replace('﻿', '');
            var html = val.replace(reg2, '<b>$2</b>').replace(/\n/gmi, '<br />')

            hidden.val(val);
            hightlight.html(html + '  ');

            var newVal = val.replace(reg2, '$2﻿');
            var caret = input.caret().start;

            var height = hightlight.height();
            /**
             * autoglow
             */
            hightlight.css({
                width : input.width()
            });
            if (height > opts.minHeight) {
                input.css({
                    'height' : height + opts.deltaHeight
                });
            }

            if (oldText && oldText != newVal) {
                input.val(newVal);
                /**
                 * it cause issue on IE: could not enter 
                 */
                //input.caret(caret, caret);

            }
            oldText = newVal;
        }

        var isProcessing = false;

        /**
         * var
         */
        function processHidden() {
            if (isProcessing) {
                return;
            }

            isProcessing = true;

            var tags = Typeahead.sharedTags[tagsKey];

            var of = input.caret();
            var val = input.val();

            for (var i in tags) {
                var tag = tags[i];
                /**
                 * found tag,now replace by token
                 */
                var len = tag.text.length;
                var pos = val.search(tag.text);
                if (pos > -1) {
                    val = val.substr(0, pos) + tag.token + val.substr(pos + len);
                } else {
                    delete (tags[i]);
                }
            }

            Typeahead.sharedTags[tagsKey] = tags;
            updateHidden(val);

            var id = hidden.attr('id');

            if (null == id) {

            } else if (id == 'input_hidden_user_status') {
                $('#input_hidden_user_info').val(val);
            } else if (id == 'input_hidden_user_info') {
                $('#input_hidden_user_status').val(val);
            }

            isProcessing = false;
        }

        function selectHandle(obj) {
            hideUl();
            var pos1 = tokenPos;
            var pos2 = input.caret().start;
            var text = obj.text + '﻿';
            var newCaretPos = pos1 + text.length + 1;

            /**
             * update input val
             */
            var val = input.val();
            val = val.substr(0, pos1) + text + ' ' + val.substr(pos2);
            input.val(val);
            input.caret(newCaretPos, newCaretPos);
            input.focus();

            /**
             * update hidden val
             */
            var id = obj.type + '.' + obj.id;
            var token = '[x=' + obj.id + ']' + obj.text + '[/x]';
            Typeahead.sharedTags[tagsKey][id] = {
                'text' : text,
                'text2' : obj.text,
                'token' : token
            };
            processHidden();
        }

        /**
         *
         */
        function keyupHandle(evt) {
            processInput();
            //  s.length > 0 ? getFriends(s) : ac.hide();
            timeId = window.setTimeout(processHidden, time);
        }

        /**
         * handle event keydown
         * @param Event evt
         * @return void
         */
        function keydownHandle(evt) {

            if (evt && evt.keyCode) {
                var keyCode = evt.keyCode
                if (!evt.shiftKey && !evt.altKey && !evt.ctrlKey) {

                    if (false == isHide) {

                        if (38 == keyCode) {
                            // move up
                            evt.preventDefault();
                            goUp();
                        } else if (40 == keyCode) {
                            // move down
                            evt.preventDefault();
                            goDown();
                        } else if (13 == keyCode || 9 == keyCode) {
                            evt.preventDefault();
                            // enter
                            var li = getActiveLi();
                            if (li) {
                                li.trigger('mousedown');
                            }
                        } else if (9 == keyCode || 27 == keyCode) {
                            //escape
                            evt.preventDefault();
                            hideUl();

                        } else if (37 == keyCode) {

                        } else if (39 == keyCode) {

                        }
                    } else {
                        if (13 == keyCode && opts.onSubmit) {
                            evt.preventDefault();
                            opts.onSubmit(input);
                        }
                    }
                    //console.log(keyCode);
                    return;
                }
                /**
                 *stop changed.
                 */
                if (evt.ctrlKey && (86 == evt.keyCode || 67 == evt.keyCode)) {

                }
            }
        }

        function clickHandle() {
            ul && hideUl();
        }

        /**
         *
         * @param {Object} evt
         * @param {Object} param
         */
        function parseLinkHandle(evt, param) {
            opts.onParseLink(input, param);
        }

        /**
         *
         * @param {Object} evt
         * @param {Object} param
         */
        function parseLinkChanged(evt, param) {
            attatchedLink = param.attatchedLink;
        }

        function focus(evt) {
            if (input.val() == input.attr('placeholder')) {
                input.val('');
            }
        }

        function changedHandle() {
            //console.log('changed handle');
            processHidden();
        }


        hightlight.css({
            'width' : input.width()
        });

        input.bind('keyup', keyupHandle).bind('keydown', keydownHandle).bind('paste', processHidden).bind('cut', processHidden).bind('parseLink', parseLinkHandle).bind('changed', changedHandle)
        $(document).bind('click', clickHandle);
        window.setTimeout(processHidden, 1000);
    }
};
