<?php

defined('PHPFOX') or exit('NO DICE!');

class Contest_Component_Controller_View extends Phpfox_component
{
    public function process()
    {
        #View type
        $sView = $this->_getView();
        $this->setParam('sView', $sView);

        #Get contest
        $aCallback = $this->getParam('aCallback', false);
        $iContestId = $this->request()->getInt(($aCallback !== false ? $aCallback['request'] : 'req2'));
        Phpfox::getService('contest.contest.process')->checkAndUpdateStatusOfAContest($iContestId);

        $aContest = Phpfox::getService('contest.contest')->getContestById($iContestId, $bIsCache = false);

        if (!$aContest['can_view_browse_contest'] || $aContest['is_deleted'] == 1)
        {
            $this->url()->send('contest.error', array('status' => Phpfox::getService('contest.constant')->getErrorStatusNumber('invalid_permission')));
        }

        $this->_deleteNotification();

        $aContest = Phpfox::getService('contest.contest')->implementsContestFields($aContest);
        $aContest['total_view'] = Phpfox::getService('contest.contest.process')->viewContest($aContest['contest_id'], $aContest['total_view']);
        $this->setParam("aContest", $aContest);

        $this->template()->setBreadcrumb(Phpfox::getPhrase('contest.contest'), $this->url()->makeUrl('contest'));

        $is_hidden_action = Phpfox::getService('contest.permission')->canHideAction($aContest);

        $sTypeName = Phpfox::getService('contest.constant')->getContestTypeNameByTypeId($aContest['type']);

        //to make facebook know the image
        $sImageUrl = sprintf(Phpfox::getParam('core.url_pic').'contest/'.$aContest['image_path'], '_240');

        #Switch type of view
        #View entry detail
        if ($sView == 'entry')
        {
            $iEntryId = $this->request()->get('entry');

            $aEntry = Phpfox::getService('contest.entry')->getContestEntryById($iEntryId);

            if (!$aEntry)
            {
                Phpfox::getLib('url')->send('subscribe');
            }

            $this->_entryDeleteNotification();

            if (!$aEntry['can_view_entry_detail'])
            {
                Phpfox::getLib('url')->send('subscribe');
            }

            $aEntry['total_view'] = Phpfox::getService('contest.entry.process')->viewEntry($aEntry['entry_id'], $aEntry['total_view']);

            if ($sTypeName == 'photo')
            {
                $sImageUrl = sprintf(Phpfox::getParam('core.url_pic').$aEntry['image_path'], '_200');
            }
            if ($sTypeName == 'video')
            {
                $sImageUrl = sprintf(Phpfox::getParam('core.url_pic').$aEntry['image_path'], '_120');
            }

            $this->setParam('aEntry', $aEntry);

            $aEntry = $this->_entryImplementFields($aEntry);

            $sTemplateViewPath = Phpfox::getService('contest.entry')->getTemplateViewPath($aEntry['type']);

            //display box comment,like and share
            $this->_entryBoxComment($aEntry);

            $this->template()->setBreadCrumb(Phpfox::getLib('parse.output')->shorten($aEntry['contest_name'], 40, '...'), $this->url()->permalink('contest', $aContest['contest_id'], $aContest['contest_name']), true)->setBreadCrumb('', '', true)->setEditor(array('load' => 'simple'))->setMeta('description', $aEntry['title'])->setMeta('keywords', $this->template()->getKeywords($aEntry['title']))->setMeta('og:title', $aEntry['title'])->assign(array(
                'aEntry' => $aEntry,
                'sTemplateViewPath' => $sTemplateViewPath,
                'core_path' => Phpfox::getParam('core.path')));
        }
        else
        {
            #Default view of contest
            if ($sView == 'default')
            {
                $aSearchNumber = array(
                    12,
                    24,
                    36,
                    48);
                $sActionUrl = $this->url()->makeUrl('contest/'.$iContestId, array('view' => $this->request()->get('view')));

                $this->search()->set(array(
                    'type' => 'entry',
                    'field' => 'en.entry_id',
                    'search' => 'search',
                    'search_tool' => array(
                        'table_alias' => 'en',
                        'search' => array(
                            'action' => $sActionUrl,
                            'default_value' => Phpfox::getPhrase('contest.search_entries'),
                            'name' => 'search',
                            'field' => 'en.title'),
                        'sort' => array(
                            'latest' => array('en.time_stamp', Phpfox::getPhrase('contest.lastest')),
                            'most-viewed' => array('en.total_view', Phpfox::getPhrase('contest.most_viewed')),
                            'most-vote' => array('en.total_vote', Phpfox::getPhrase('contest.most_voted')),
                            'most-liked' => array('en.total_like', Phpfox::getPhrase('contest.most_liked')),
                            ),
                        'show' => $aSearchNumber)));

                $this->search()->setCondition('AND en.contest_id = '.$aContest['contest_id']);

                if ($is_hidden_action)
                {
                    $this->search()->setCondition('AND en.status = 1');
                }

                $aBrowseParams = array(
                    'module_id' => 'contest',
                    'alias' => 'en',
                    'field' => 'entry_id',
                    'table' => Phpfox::getT('contest_entry'),
                    'hide_view' => array('my'));

                $this->search()->browse()->params($aBrowseParams)->execute();
                $aEntries = $this->search()->browse()->getRows();

                Phpfox::getLib('pager')->set(array(
                    'page' => $this->search()->getPage(),
                    'size' => $this->search()->getDisplay(),
                    'count' => $this->search()->browse()->getCount()));

                foreach ($aEntries as $key => $aEntry)
                {
                    $aEntry['status_entry'] = $aEntry['status'];
                    $aEntry['approve'] = ($aEntry['status'] == 1) ? 0 : 1;
                    $aEntry['deny'] = ($aEntry['status'] == 2) ? 0 : 1;
                    $is_entry_winning = Phpfox::getService("contest.entry")->CheckExistEntryWinning($aEntry['entry_id']);
                    $aEntry['winning'] = ($aEntry['contest_status'] == 5 && $is_entry_winning == 0) ? 1 : 0;
                    $aEntry['offaction'] = 0;
                    if ($aEntry['contest_user_id'] != Phpfox::getUserId() && !PHpfox::isAdmin())
                    {
                        $aEntry['offaction'] = 1;
                    }

                    $aEntry = Phpfox::getService('contest.entry')->retrieveEntryPermission($aEntry);

                    $aEntries[$key] = $aEntry;
                }

                $this->template()->assign(array('aEntries' => $aEntries, 'corepath' => phpfox::getParam('core.path')));

                $global_moderation = array(
                    'name' => 'contestentry',
                    'ajax' => 'contest.moderateEntry',
                    'menu' => array(
                        array('phrase' => Phpfox::getPhrase('contest.approve'), 'action' => 'approve'),
                        array('phrase' => Phpfox::getPhrase('contest.deny'), 'action' => 'deny'),
                        ));

                if ($aContest['contest_status'] == 5)
                {
                    $global_moderation['menu'][] = array('phrase' => Phpfox::getPhrase('contest.set_as_winning_entries'), 'action' => 'set_as_winning_entries');
                }

                $this->setParam('global_moderation', $global_moderation);
            }

            #View winning entries
            if ($sView == 'winning')
            {
                $global_moderation = array(
                    'name' => 'contestentry',
                    'ajax' => 'contest.moderateEntry',
                    'menu' => array(array('phrase' => 'Delete from the list', 'action' => 'delete'), ));

                $this->setParam('global_moderation', $global_moderation);
            }

            #For announcement
            $aValidation = array('headline' => array('def' => 'required', 'title' => Phpfox::getPhrase('contest.fill_headline_for_announcement')), 'content' => array('def' => 'required', 'title' => Phpfox::getPhrase('contest.add_content_to_announcement')));
            $oValid = Phpfox::getLib('validator')->set(array('sFormName' => 'core_js_contest_form', 'aParams' => $aValidation));

            if ($aVals = $this->request()->getArray('val'))
            {
                if ($oValid->isValid($aVals))
                {
                    $aVals['user_id'] = Phpfox::getUserId();
                    $aVals['contest_id'] = $aContest['contest_id'];
                    $iId = Phpfox::getService('contest.announcement.process')->add($aVals);
                }
            }

            $announcement = 0;
            if ($iId = $this->request()->get('announcement'))
            {
                $announcement = $iId;
            }

            #Comment container
            $aContest['bookmark_url'] = Phpfox::permalink('contest', $aContest['contest_id'], $aContest['contest_name']);
            $this->_boxComment($aContest);

            $this->template()->assign(array('announcement' => $announcement, 'sContestWarningMessage' => $this->_getContestWarningMessage($aContest)));

            $aValidatorPhrases = Phpfox::getService('contest.helper')->getPhrasesForValidator();
            $this->template()->setPhrase($aValidatorPhrases);
        }

        $aContest['is_show_ending_soon_label'] = Phpfox::getService('contest.contest')->isShowContestEndingSoonLabel($aContest['contest_id']);

        $bIsShowRegisterService = false;
        if ($this->request()->get('registerservice') && Phpfox::getService('contest.permission')->canRegisterService($aContest['contest_id'], Phpfox::getUserId()))
        {
            $bIsShowRegisterService = true;
        }

        if ($sTypeName == 'music')
        {
            $this->template()->setHeader(array(
                'mediaelementplayer.min.css' => 'module_contest',
                'mejs-audio-skins.css' => 'module_contest',
                'mediaelement-and-player.min.js' => 'module_contest',
                'controller_player.js' => 'module_contest'
            ));
        }

        $this->template()->assign(array(
            'aContest' => $aContest,
            'sView' => $sView,
            'is_hidden_action' => $is_hidden_action,
            'showaction' => true,
            'aContestStatus' => Phpfox::getService('contest.constant')->getAllContestStatus(),
            'bIsShowRegisterService' => $bIsShowRegisterService))->setMeta('description', $aContest['contest_name'])->setMeta('keywords', $this->template()->getKeywords($aContest['contest_name']))->setMeta('og:title', $aContest['contest_name']);

        $this->template()->setHeader(array(
            'yncontest.css' => 'module_contest',
            'yncontest.js' => 'module_contest',
            'block.css' => 'module_contest',
            'jquery.validate.js' => 'module_contest',
            'jquery/plugin/jquery.highlightFade.js' => 'static_script',
            'jquery/plugin/jquery.scrollTo.js' => 'static_script',
            'quick_edit.js' => 'static_script',
            'comment.css' => 'style_css',
            'pager.css' => 'style_css',
            'feed.js' => 'module_feed',
            '<meta property="og:image" content="'.$sImageUrl.'" />',
            '<link rel="image_src" href="'.$sImageUrl.'" />'));
    }

    private function _boxComment($aContest)
    {
        $this->setParam('aFeed', array(
            'comment_type_id' => 'contest',
            'privacy' => $aContest['privacy'],
            'comment_privacy' => $aContest['privacy_comment'],
            'like_type_id' => 'contest',
            'feed_is_liked' => isset($aContest['is_liked']) ? $aContest['is_liked'] : false,
            'feed_is_friend' => $aContest['is_friend'],
            'item_id' => $aContest['contest_id'],
            'user_id' => $aContest['user_id'],
            'total_comment' => $aContest['total_comment'],
            'total_like' => $aContest['total_like'],
            'feed_link' => $aContest['bookmark_url'],
            'feed_title' => $aContest['contest_name'],
            'feed_display' => 'view',
            'feed_total_like' => $aContest['total_like'],
            'report_module' => 'contest',
            'report_phrase' => Phpfox::getPhrase('contest.report_this_contest_entry'),
            'time_stamp' => $aContest['time_stamp']));
    }

    private function _deleteNotification()
    {
        if (Phpfox::isUser() && Phpfox::isModule('notification'))
        {
            Phpfox::getService('contest.participant')->removeAllFavoriteByContestId($this->request()->getInt('req2'));
            Phpfox::getService('notification.process')->delete('comment_contest', $this->request()->getInt('req2'), Phpfox::getUserId());
            Phpfox::getService('notification.process')->delete('contest_notice_follower', $this->request()->getInt('req2'), Phpfox::getUserId());
            Phpfox::getService('notification.process')->delete('contest_notice_join', $this->request()->getInt('req2'), Phpfox::getUserId());
            Phpfox::getService('notification.process')->delete('contest_invited', $this->request()->getInt('req2'), Phpfox::getUserId());
            Phpfox::getService('notification.process')->delete('contest_like', $this->request()->getInt('req2'), Phpfox::getUserId());
            Phpfox::getService('notification.process')->delete('contest_notice_close', $this->request()->getInt('req2'), Phpfox::getUserId());
        }
    }

    private function _getContestWarningMessage($aContest)
    {
        $sStatus = Phpfox::getService('contest.constant')->getContestStatusNameByStatusId($aContest['contest_status']);
        
        if ($sStatus == 'pending')
        {
            return Phpfox::getPhrase('contest.this_contest_is_pending_an_admins_approval');
        }
        
        if ($sStatus == 'denied')
        {
            return Phpfox::getPhrase('contest.this_contest_was_denied');
        }
        
        if ($sStatus == 'closed')
        {
            return Phpfox::getPhrase('contest.this_contest_is_closed');
        }
        
        if ($sStatus == 'on_going')
        {
            // contest has not started
            if ($aContest['begin_time'] > PHPFOX_TIME)
            {
                return Phpfox::getPhrase('contest.this_contest_will_start_on_begin_time_please_revisit_later', array('begin_time' => $aContest['begin_time_parsed']));
            }

            // user can submit entry or not
            if ($aContest['start_time'] <= PHPFOX_TIME && $aContest['stop_time'] >= PHPFOX_TIME)
            {
                // 0 is unlimited, no need to warn
                if ($aContest['number_entry_max'] != 0 && Phpfox::getService('contest.participant')->isJoinedContest(Phpfox::getUserId(), $aContest['contest_id']))
                {
                    $iNumberOfRemainingSubmitTime = $aContest['number_entry_max'] - Phpfox::getService('contest.entry')->getNumberOfSumittedEntryInAContestOfUser($aContest['contest_id'], Phpfox::getUserId());

                    if ($iNumberOfRemainingSubmitTime <= 0)
                    {
                        return Phpfox::getPhrase('contest.you_can_not_submit_you_have_reached_maximum_number_of_submitted_entry_number', array('number' => $aContest['number_entry_max']));
                    }
                    else
                    {
                        return Phpfox::getPhrase('contest.you_can_submit_number_entry_more', array('number' => $iNumberOfRemainingSubmitTime));
                    }
                }
            }
        }

        return '';
    }

    private function _entryImplementFields($aEntry)
    {
        $format_datetime = 'M j, Y g:i a';
        $aEntry['bookmark_url'] = Phpfox::permalink('contest', $aEntry['contest_id'], $aEntry['contest_name']).'entry_'.$aEntry['entry_id'].'/';
        $aEntry['bitlyUrl'] = Phpfox::getService('contest.entry.process')->getShortBitlyUrl($aEntry['bookmark_url']);
        $aEntry['is_voted'] = Phpfox::getService('contest.entry.process')->isVoted(Phpfox::getUserId(), $aEntry['entry_id']);
        $aEntry['submit_date'] = Phpfox::getTime($format_datetime, $aEntry['time_stamp']);
        $aEntry['approve_date'] = Phpfox::getTime($format_datetime, $aEntry['approve_stamp']);
        $aEntry['previous'] = Phpfox::getService("contest.entry")->getContestEntryBesideId($aEntry['entry_id'], $aEntry['contest_id'], 'previous');
        $aEntry['next'] = Phpfox::getService("contest.entry")->getContestEntryBesideId($aEntry['entry_id'], $aEntry['contest_id'], 'next');
        $aEntry['approve'] = $aEntry['status_entry'] == 1 ? 0 : 1;
        $aEntry['deny'] = $aEntry['status_entry'] == 2 ? 0 : 1;
        $is_entry_winning = Phpfox::getService("contest.entry")->CheckExistEntryWinning($aEntry['entry_id']);
        $aEntry['winning'] = ($aEntry['contest_status'] == 5 && $is_entry_winning == 0) ? 1 : 0;
        $aEntry['offaction'] = 0;
        if ($aEntry['contest_user_id'] != Phpfox::getUserId() && !PHpfox::isAdmin())
        {
            $aEntry['offaction'] = 1;
        }
        if (!$aEntry['bitlyUrl'])
        {
            $aEntry['bitlyUrl'] = $aEntry['bookmark_url'];
        }
        return $aEntry;
    }

    private function _entryBoxComment($aEntry)
    {
        $this->setParam('aFeed', array(
            'comment_type_id' => 'contest_entry',
            'privacy' => $aEntry['privacy'],
            'comment_privacy' => $aEntry['privacy_comment'],
            'like_type_id' => 'contest_entry',
            'feed_is_liked' => isset($aEntry['is_liked']) ? $aEntry['is_liked'] : false,
            'feed_is_friend' => $aEntry['is_friend'],
            'item_id' => $aEntry['entry_id'],
            'user_id' => $aEntry['user_id'],
            'total_comment' => $aEntry['total_comment'],
            'total_like' => $aEntry['total_like'],
            'feed_link' => $aEntry['bookmark_url'],
            'feed_title' => $aEntry['title'],
            'feed_display' => 'view',
            'feed_total_like' => $aEntry['total_like'],
            'report_module' => 'contest',
            'report_phrase' => Phpfox::getPhrase('contest.report_this_contest_entry'),
            'time_stamp' => $aEntry['time_stamp'],
            'type_id' => 'contest_entry'));
    }

    private function _entryDeleteNotification()
    {
        if (Phpfox::isUser() && Phpfox::isModule('notification'))
        {
            Phpfox::getService('notification.process')->delete('contest_entry_vote', $this->request()->getInt('req3'), Phpfox::getUserId());
            Phpfox::getService('notification.process')->delete('contest_entry_invited', $this->request()->getInt('req3'), Phpfox::getUserId());
            Phpfox::getService('notification.process')->delete('contest_entry_like', $this->request()->getInt('req3'), Phpfox::getUserId());
            Phpfox::getService('notification.process')->delete('comment_contest_entry', $this->request()->getInt('req3'), Phpfox::getUserId());
            Phpfox::getService('notification.process')->delete('contest_notice_approveentry', $this->request()->getInt('req3'), Phpfox::getUserId());
        }
    }

    private function _getView()
    {
        $sView = 'default';

        $stmpView = $this->request()->get('view');
        if ($stmpView == 'participants' || $stmpView == 'winning')
        {
            $sView = $stmpView;
        }

        $sAction = $this->request()->get('action');
        $iItemId = $this->request()->get('itemid');
        if ($sAction == 'add' || $iItemId)
        {
            $sView = 'add';
        }

        $iEntryId = $this->request()->get('entry');
        if ($iEntryId)
        {
            $sView = 'entry';
        }

        return $sView;
    }
}
