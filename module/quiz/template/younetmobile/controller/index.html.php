{if count($aQuizzes)}
{foreach from=$aQuizzes name=quizzes item=aQuiz}
	{template file='quiz.block.entry'}
{/foreach}
{pager}
{else}
<div class="extra_info">
	{phrase var='quiz.no_quizzes_found'}
</div>
{/if}