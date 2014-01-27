<div class="ynfr-statistic-detail">
    {if $aTransaction.sBackUrl == 'admincp.fundraising.statistic'}
    <div class="table_header">
        {phrase var='fundraising.fundraising_statistic'}
    </div>
    {/if}

    <div>
        <h1>{phrase var='fundraising.transaction_detail'}</h1>
    </div>

    <h3>{phrase var='fundraising.order_detail'}</h3>

    <table class="ynfr-table">
        <tr><td>{phrase var='fundraising.member'}:</td><td>{$aTransaction.donor_name}</td></tr>
        <tr><td>{phrase var='fundraising.campaign_name'}:</td><td>{$aTransaction.campaign_name}</td></tr>
        <tr><td>{phrase var='fundraising.donate_date'}:</td><td>{$aTransaction.donate_date|date}</td></tr>
        <tr><td>{phrase var='fundraising.description'}:</td><td>{$aTransaction.description}</td></tr>
    </table>

    <h3>{phrase var='fundraising.payment_detail'}</h3>

    <table class="ynfr-table">
        <tr><td>{phrase var='fundraising.donation_amount'}:</td><td>{$aTransaction.donation_amount}</td></tr>
        <tr><td>{phrase var='fundraising.gateway_transaction_id'}:</td><td>{$aTransaction.transaction_id}</td></tr>
    </table>

    <div>
        <a href="#" onclick="history.back(); return false;">{phrase var='fundraising.back'}</a>
    </div>
</div>