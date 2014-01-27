{if $bInHomepage}
    {module name='jobposting.company.featured-slideshow'}	
{/if}

{if !count($aCompanies)}
	<div>{phrase var='jobposting.no_companies_found'}</div>
{else}
{foreach from=$aCompanies item=aCompany}
	{template file='jobposting.block.company.entry'}
{/foreach}

{if Phpfox::getUserParam('jobposting.can_approve_company') || Phpfox::getUserParam('jobposting.can_delete_company_other_user')}
{moderation}
{/if}

{pager}
{/if}
