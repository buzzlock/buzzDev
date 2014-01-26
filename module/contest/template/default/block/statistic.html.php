{literal}
<style>
    .statistic li{
            line-height: 22px;
    }
</style>
{/literal}
<ul class="statistic">
    <li>
        {phrase var='contest.all_contests'}: {$aStatistic.total_contests}
    </li>
    <li>
        {phrase var='contest.participants'}: {$aStatistic.total_participants}
    </li>
    <li>
        {phrase var='contest.blog_contest'}: {$aStatistic.total_blog_contests}
    </li>
    <li>
        {phrase var='contest.music_contest'}: {$aStatistic.total_music_contests}
    </li>
    <li>
        {phrase var='contest.video_contest'}: {$aStatistic.total_video_contests}
    </li>
    <li>
        {phrase var='contest.photo_contest'}: {$aStatistic.total_photo_contests}
    </li>
</ul>