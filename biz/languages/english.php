<?php
/*
# ------------------------------------------------------------------------ #
# Software Name: EzyGold - The Ultimate Network Marketing Suite            #
# Written by: C. Era Setiawan                                              #
# Website: http://www.ezygold.com                                          #
# Copyright ©2007-2012 All Rights Reserved.                                #
# ------------------------------------------------------------------------ #
# COPYRIGHT AND LICENSE AGREEMENT.                                         #
# REDISTRIBUTION OF THIS SCRIPT OR ANY MODIFICATIONS                       #
# OF THIS SCRIPT IN ANY FORM IS STRICTLY PROHIBITED!                       #
#                                                                          #
# There are no warranties expressed or implied of any kind, and by using   #
# this code you agree to indemnify Era Setiawan and EzyGold, from any      #
# and all liability that might arise from it's use.                        #
# ------------------------------------------------------------------------ #
# PUBLISH YOUR LANGUAGE FILE                                               #
# ------------------------------------------------------------------------ #
# If you found any grammatical errors and omissions in this translation,   #
# please notify us by sending error details to translator_email below      #
# ------------------------------------------------------------------------ #
# If you want to publish your translated language file on our site, send   #
# a copy of your file to ezygold.com                                       #
# ------------------------------------------------------------------------ #
*/

if (!defined('EZYGOLD')) {
  die("This file cannot be accessed directly.");
}

# ------------------------------------------------------------------------ #
# When you make a new translation, fill out the following five variables.  #
# ------------------------------------------------------------------------ #
$translation_iso = 'EN';
$translation = 'English';
$translator_name = 'Chanifah';
$translator_email = 'mylist@ezygold.com';
$translator_url = 'http://www.ezygold.com/';

$translator_version = '2.3.450';
$translator_vupdate = '5';
# ------------------------------------------------------------------------ #
# ------------------------------------------------------------------------ #

// Set this to the character encoding of your translation
$LNG['langiso'] = "en";
$LNG['charset'] = "utf-8";

# ------------------------------------------------------------------------ #
# ------------------------------------------------------------------------ #

// Global
$LNG['g_custom_header'] = "My Site";
$LNG['g_form_submit_short'] = "Go";
$LNG['g_username'] = "Username";
$LNG['g_url'] = "URL";
$LNG['g_title'] = "Title";
$LNG['g_description'] = "Description";
$LNG['g_category'] = "Category";
$LNG['g_category_manage'] = "(<a href='index.php?a=admin&amp;b=categories'>manage</a>)"; //new > v2.2.80402
$LNG['g_email'] = "Email";
$LNG['g_url_id'] = "URL ID";
$LNG['g_url_id_sef'] = "SE Friendly URL ID";
$LNG['g_user_image'] = "User Image";
$LNG['g_user_photo'] = "User image (optional)<br />Maximum in %s pixels and %s image format only.";
$LNG['g_password'] = "Password";
$LNG['g_passwordx'] = "Retype Password";
$LNG['g_average'] = "Average";
$LNG['g_this_period'] = "";
$LNG['g_today'] = "Today";
$LNG['g_yesterday'] = "Yesterday";
$LNG['g_daily'] = "Daily";
$LNG['g_this_month'] = "This Month";
$LNG['g_last_month'] = "Last Month";
$LNG['g_monthly'] = "Monthly";
$LNG['g_this_week'] = "This Week";
$LNG['g_last_week'] = "Last Week";
$LNG['g_weekly'] = "Weekly";
$LNG['g_pv'] = 'Pageviews';
$LNG['g_overall'] = 'Overall';
$LNG['g_in'] = 'In';
$LNG['g_out'] = 'Out';
$LNG['g_unq_pv'] = "Unique PVs";
$LNG['g_tot_pv'] = "Total PVs";
$LNG['g_unq_in'] = "Unique In";
$LNG['g_tot_in'] = "Total In";
$LNG['g_unq_out'] = "Unique Out";
$LNG['g_tot_out'] = "Total Out";
$LNG['g_invalid_u_or_p'] = "Invalid username or password. Please try again.";
$LNG['g_unconfirmed_acc'] = "Unconfirmed account.<br /><br />Please confirm it by follow the instruction sent to the account email."; //new > v2.2.80401
$LNG['g_invalid_u'] = "Invalid username. Please try again.";
$LNG['g_invalid_p'] = "Invalid password. Please try again.";
$LNG['g_invalid_ip'] = "Invalid IP Address. Access restriction applied."; //new > v2.1.80214
$LNG['g_session_expired'] = "Your session has expired.  Please try again.";
$LNG['g_errorwarn'] = "Warning!"; //new > v2.2.91730
$LNG['g_error'] = "Error";
$LNG['g_delete_install'] = "For security reasons, you must delete the install directory before the script will run.";
$LNG['g_accdetails'] = "Details";
$LNG['g_id'] = "Id";
$LNG['g_upline'] = "Upline";
$LNG['g_referrer'] = "Referrer";
$LNG['g_sponsor'] = "Sponsor";
$LNG['g_sponsorlist'] = "Sponsor List";
$LNG['g_passedup'] = "Passed Up"; //new > v2.2.80610
$LNG['g_fullname'] = "Full Name";
$LNG['g_fistname'] = "Firstname"; //new > v2.2.80612
$LNG['g_lastname'] = "Lastname"; //new > v2.2.80612
$LNG['g_egold'] = "E-gold Account";
$LNG['g_no_egold'] = "E-gold Account Empty";
$LNG['g_no_paypal'] = "PayPal Account Empty"; //new > v2.1.80214
$LNG['g_no_lreserve'] = "LibertyReserve Account Empty"; //new > v2.1.80214
$LNG['g_no_alertpay'] = "Payza Account Empty"; //new > v2.2.80612
$LNG['g_no_solidtrustpay'] = "SolidTrustPay Account Empty"; //new > v2.3.442
$LNG['g_no_perfectmoney'] = "PerfectMoney Account Empty"; //new > v2.3.442
$LNG['g_alertpay'] = "Payza Account"; //new
$LNG['g_safepay'] = "SafePay Account";
$LNG['g_mbookers'] = "MoneyBookers Account";
$LNG['g_paypal'] = "PayPal Account";
$LNG['g_lreserve'] = "LibertyReserve Account"; //new > v2.1.80214
$LNG['g_solidtrustpay'] = "SolidTrustPay Account"; //new > v2.3.429
$LNG['g_perfectmoney'] = "PerfectMoney Account"; //new > v2.3.429
$LNG['g_mypayment'] = "Bank Details";
$LNG['g_payout_memo'] = null;
$LNG['g_address'] = "Address";
$LNG['g_state'] = "City / State";
$LNG['g_country'] = "Country";
$LNG['g_dlist'] = "<font color=grey>|<strong>x</strong>:<strong>y</strong>| where <strong>x</strong> is the sponsor level and <strong>y</strong> is the member id, <strong>x = 2</strong> refer to the <strong>direct sponsor</strong><br />Example: %s</font>"; //update > v2.2.80612
$LNG['g_dliner'] = "Total Direct Referrals";
$LNG['g_cyclingfromid'] = "Recycling from Id"; //new
$LNG['g_passedupfromid'] = "Passed Up from Id:"; //new > v2.2.80610
$LNG['g_splovr'] = "Spillover from Sponsor";
$LNG['g_spillover'] = "Spillover System";
$LNG['g_optinme'] = "Receive Message from Administrator";
$LNG['g_myezyalert'] = "Access Latest Account Update using <a href='http://www.ezygold.com/ezyalert' title='EzyAlert - EzyGold Notifier' target='_blank'>EzyAlert</a>";
$LNG['g_confirmed_acc'] = "Confirmed Account"; //new > v2.2.80401
$LNG['g_no'] = "No";
$LNG['g_yes'] = "Yes";
$LNG['g_online'] = "Online";
$LNG['g_offline'] = "Offline";
$LNG['g_hidden'] = "Hidden";
$LNG['g_prev'] = "Previous page";
$LNG['g_next'] = "Next page";
$LNG['g_enter_captchatext'] = "Enter the text as it is shown below (in CaSe sEnSiTiVe):";
$LNG['g_recaptcha_invalid'] = "<font color=#FF0000>The words wasn't entered correctly. Please try it again.</font>"; //new > v2.2.91730
$LNG['g_enter_etokentext'] = "eToken Key:"; //new > v2.1.80214
$LNG['g_enter_etokenpin'] = "Enter the Access PIN (in CaSe sEnSiTiVe):"; //new > v2.1.80214
$LNG['g_enter_pagepass'] = "Please enter password to access this page:"; //new > v2.2.91730
$LNG['g_enter_pagepass_invalid'] = "<font color=#FF0000>The password wasn't entered correctly. Please try it again.</font>"; //new > v2.2.91730
$LNG['g_enter_page_expired'] = "<font color=#CC0000><strong>The page you requested cannot be displayed right now.</strong><br /><em>It may be temporarily unavailable, already expired, or you may not have permission to view this page.</em></font>"; //new > v2.2.91730
$LNG['g_hint'] = "Help hint";
$LNG['g_referrals'] = "Referrals";
$LNG['g_direct_referrals'] = "Direct Referrals";
$LNG['g_total_referrals'] = "Total Referrals";
$LNG['g_expired'] = "Expired";
$LNG['g_level'] = "Level";
$LNG['g_activities'] = "Activities";
$LNG['g_ip_address'] = "IP Address";
$LNG['g_registration_date'] = "Registration Date"; //new
$LNG['g_expiration_date'] = "Expiration Date"; //new
$LNG['g_maintenance_date'] = "Maintenance Date"; //new
$LNG['g_loging_date'] = "Login Date";
$LNG['g_loging_ip'] = "Login IP";
$LNG['g_hits'] = "Hits";
$LNG['g_joinfee'] = "Registration Fee";
$LNG['g_recycling'] = "Recycling Times"; //new
$LNG['g_reentry'] = "Reentry"; //new v2.2.91730
$LNG['g_cycling'] = "Cycling"; //update v2.2.91730
$LNG['g_renewal'] = "Renewal Times";
$LNG['g_adminfo'] = "Admin Note";
$LNG['g_bannedip'] = "To many connection!<br>You are not allowed to access this page, please contact site administrator for details.";
$LNG['g_showstat'] = "Show site statistic to public";
$LNG['g_statsfor'] = "Show statistic for";
$LNG['g_button_submit'] = "Submit &rarr;";
$LNG['g_button_cancel'] = "Cancel"; //new
$LNG['g_vercode_reload'] = "Reload Verification Code";
$LNG['g_api_key'] = "API Key (random characters)";
$LNG['g_license_key'] = "Your EzyGold License Key";
$LNG['g_private_key'] = "Your EzyGold Private Label Key (authorization to remove \"Powered by\" text)"; //new
$LNG['g_button_update'] = "Update &rarr;";
$LNG['g_button_reset'] = "Reset";
$LNG['g_button_startedover'] = "Started Over"; //new
$LNG['g_button_load'] = "Load";
$LNG['g_button_back'] = "&larr; Back";
$LNG['g_button_continue'] = "Continue &rarr;"; //new > v2.2.91730
$LNG['g_insert_done'] = "Insert done."; //new > v2.2.80401
$LNG['g_update_done'] = "Update done.";
$LNG['g_insert_failed'] = "Insert failed."; //new > v2.2.91730
$LNG['g_update_failed'] = "Update failed."; //new > v2.2.91730
$LNG['g_details'] = "Descriptions";
$LNG['g_noreferrer'] = "Access Denied";
$LNG['g_order'] = "Product List";
$LNG['g_news'] = "Site News";
$LNG['g_terms'] = "Terms and Conditions"; //new > v2.2.80401
$LNG['g_faqs'] = "Frequently Asked Questions";
$LNG['g_testimonials'] = "Testimonials";
$LNG['g_short_asc'] = "Click here to sort Ascending";
$LNG['g_short_desc'] = "Click here to sort Descending";
$LNG['g_actions'] = "Actions";
$LNG['g_text_delete'] = "Delete";
$LNG['g_sendto'] = "Send To";
$LNG['g_receivedfrom'] = "Received From";
$LNG['g_admin'] = "Administrator";
$LNG['g_superadmin'] = "Super Administrator"; //new > v2.2.91730
$LNG['g_loading'] = "Loading...";
$LNG['g_openurl'] = "Open website...";
$LNG['g_feature_disabled'] = "<font color='red'>This feature has disabled by system.</font>";
$LNG['g_noreferrer_details'] = "<h3>Sorry!</h3><br />You cannot access this page without a valid referrer.";
$LNG['g_noreferrer_nouser'] = "<fieldset style='background-color: #FFDDDD'>You have inserted an invalid referrer username. Please contact the person who has invited you to join this site and ask his/her correct username.</fieldset>";
$LNG['g_noreferrer_getuser'] = "Enter your referrer or sponsor username below and click Submit.";
$LNG['g_noreferrer_randtxt'] = "<p><br />or click button below to get a random referrer.<br />%s</p>"; //new > v2.3.429
$LNG['g_noreferrer_randbtn'] = "Get Random Referrer"; //new > v2.3.429
$LNG['g_accounts'] = "Accounts";
$LNG['g_manual_payout'] = "We will pay your available commissions and rewards manually.";
$LNG['g_options'] = "Options";
$LNG['g_donotedit_warn'] = "<font color='red'><b>WARNING:</b> do not alter or modify unless you know what you're doing.</font>";
$LNG['g_inqueue'] = "Email in queue";
$LNG['g_from'] = "From";
$LNG['g_to'] = "To";
$LNG['g_settings'] = "settings"; //new
$LNG['g_dateselector'] = "Date Selector"; //new
$LNG['g_colorselector'] = "Color Selector"; //new > v2.2.80612
$LNG['g_separate_with_commas'] = "(separate with commas)"; //new
$LNG['g_biz_limitation'] = "<font color=red><b>Biz! version limitation</b> .:: Feature was disabled for security reasons.</font>"; //new
$LNG['g_del_record_max'] = "<font color=red><b>Biz! version limitation</b> .:: Delete one or more records to enable this form.</font>"; //new
$LNG['g_sales'] = "Sales"; //new > v2.1.80214
$LNG['g_balance'] = "Balance"; //new > v2.1.80214
$LNG['g_all'] = "All"; //new > v2.1.80214
$LNG['g_oops'] = "<h2>Oops something went wrong!</h2>Either the site is offline or under maintenance. We apologize and have logged the IP Address. Please try your request again or if you know who your site administrator is let them know too."; //new > v2.1.80214
$LNG['g_proversion'] = "Pro! version only"; //new > v2.2.80612
$LNG['g_memberarea'] = "Member Area"; //new > v2.2.80612
$LNG['g_memberid'] = "Member ID"; //new > v2.2.91730
$LNG['g_buy_now'] = "Buy Now"; //new > v2.2.80612
$LNG['g_requiredform'] = "Please complete the form"; //new > v2.2.90809
$LNG['g_earn_from'] = "From referral"; //new > v2.2.90809
$LNG['g_testmode'] = "Test Mode"; //new > v2.2.91730
$LNG['g_qualified'] = "QUALIFIED"; //new > v2.3.429
$LNG['g_other'] = "Other"; //new > v2.3.429

//new > v2.2.91730
$LNG['g_optional'] = "Optional";
$LNG['g_free'] = "Free";
$LNG['g_bnext'] = "Next";
$LNG['g_old'] = "Old";
$LNG['g_new'] = "New";
$LNG['g_fee'] = "fee";
$LNG['g_ewallet'] = "E-Wallet";
$LNG['g_enable'] = "Enable";
$LNG['g_disable'] = "Disable";
$LNG['g_registeredpayplan'] = "Registered Payplan";
$LNG['g_default'] = "Default";

//new > v2.2.80612
$LNG['g_hours'] = "Hour(s)";
$LNG['g_days'] = "Day(s)";
$LNG['g_weeks'] = "Week(s)";
$LNG['g_months'] = "Month(s)";
$LNG['g_years'] = "Year(s)";

// Purchased Items (ezyCart plugin)
$LNG['bough_menu'] = "My Items";
$LNG['bough_header'] = "Purchased Items";
$LNG['bough_subheader'] = "Below are your purchased items.";
$LNG['bough_ads'] = "My Ads"; //new > v2.3.450
$LNG['bough_ads_header'] = "Manage My Ads";
$LNG['bough_ads_subheader'] = "You can manage your ads below, you may purchase new ads if the option is available.";

// Upgrade and Renewal
$LNG['renew_upgrade'] = "Upgrade";
$LNG['renew_header'] = "Account Renewal";
$LNG['renew_subheader'] = "Renew your account and continue receive membership benefits.";
$LNG['renew_upgrade_header'] = "Upgrade Account";
$LNG['renew_upgrade_subheader'] = "Upgrade your account and get more benefits.";
$LNG['renew_upgrade_changepayplan'] = "<a href='index.php?a=client&b=updateplan'>Click here</a> if you want to change or upgrade your current Membership Type."; //new > v2.2.91730
$LNG['renew_not_required'] = "Not Required by System.";
$LNG['renew_link'] = "[<a href='%s' title='{$LNG['renew_subheader']}'>renew</a>]";
$LNG['renew_upgrade_link'] = "[<a href='%s' title='{$LNG['renew_upgrade_subheader']}'>upgrade</a>]";
$LNG['renew_ewtotalpay'] = "Total need to pay"; //new > v2.2.91730
$LNG['renew_ewpay_confirm'] = "Are you sure want to renew your account and make payment using your E-Wallet balance?"; //new > v2.2.91730

// Update Membership //new > v2.2.91730
$LNG['updateplan_header'] = "Update Membership Type";
$LNG['updateplan_subheader'] = "You can change or update your current membership type by choosing one from the following available membership types.";
$LNG['updateplan_btn_next'] = "Continue &rarr;";
$LNG['updateplan_upgradenote'] = "<a href='index.php?a=client&b=upgrade'>Click here</a> to continue with the upgrade process.";
$LNG['updateplan_not_required'] = "Not Required by System.";
$LNG['updateplan_link'] = "[<a href='%s' title='{$LNG['renew_subheader']}'>renew</a>]";
$LNG['updateplan_upgrade_link'] = "[<a href='%s' title='{$LNG['renew_upgrade_subheader']}'>upgrade</a>]";
$LNG['updateplan_ewtotalpay'] = "Total need to pay"; //new > v2.2.91730
$LNG['updateplan_ewpay_confirm'] = "Are you sure want to renew your account and make payment using your E-Wallet balance?";
$LNG['updateplan_subspay_info'] = "<font color='#BB0000' size='1'><em>*) after payment complete (new subscription payment for your new membership <strong>%s</strong> setup successfuly), you can <u>cancel</u> your current subscription for '%s' membership.</em></font>";

// WAP Access //new > v2.2.80612
$LNG['wap_login_header'] = "WAP Access System";
$LNG['wap_login'] = "Login";
$LNG['wap_login_user'] = "Username";
$LNG['wap_login_pass'] = "Password";
$LNG['wap_logout'] = "Logout";
$LNG['wap_print_name'] = "Name: ";
$LNG['wap_print_email'] = "Email: ";
$LNG['wap_print_hits'] = "Referral URL Hits: ";
$LNG['wap_print_actref'] = "Active Referrals: ";
$LNG['wap_print_inactref'] = "Inactive Referrals: ";
$LNG['wap_print_servertm'] = "Server Time: ";
$LNG['wap_print_newmbr'] = "New Referrals: ";
$LNG['wap_print_newtx'] = "Latest Transactions: ";

// Direct Payment //new > v2.2.80612
$LNG['dirpay_header'] = "Payment Details";
$LNG['dirpay_subheader'] = "Send your payment to recipient below";
$LNG['dirpay_no_user'] = "<font color=red>Invalid recipient!</font>";
$LNG['dirpay_is_paid'] = "<font color=green>Already Paid!</font>";
$LNG['dirpay_userdetails'] = "Recipient Details";
$LNG['dirpay_confirmpay'] = "<font color=green>Already make a payment?</font> confirm your payment <a href='%s%s'>here</a>.";
$LNG['dirpay_confirmsubject'] = "Payment confirmation of %s for %s...";
$LNG['dirpay_payoptions'] = "Payment Options";
$LNG['dirpay_hist_refearning'] = "Ref Earning";
$LNG['dirpay_processer_notset'] = "Payment processor not ready, please contact <a href='%s'>%s</a> for details.";

// Edit Account
$LNG['edit_referrals'] = "Referrals";
$LNG['edit_header'] = "Edit Account";
$LNG['edit_subheader'] = "You can edit your details using the form below";
$LNG['edit_info_edited'] = "Your account has been successfully edited.";
$LNG['edit_password_blank'] = "<font color=red>Leave empty to keep the current password</font>";
$LNG['edit_startover'] = "Click <b><a href='%s'>here</a></b> to update your account details.";

// Genealogy Tree
$LNG['genealogy_menu'] = "Genealogy";
$LNG['genealogy_disabled'] = "<font color=red>Genealogy Report has been disabled by system</font>";
$LNG['genealogy_tree_open'] = "Open All Genealogy Tree";
$LNG['genealogy_tree_close'] = "Close All Genealogy Tree";

// Subscriber List //new > v2.2.80401
$LNG['subscriber_menu'] = "Subscribers";
$LNG['subscriber_disabled'] = "<font color=red>Subscribers Report has been disabled by Administrator.</font>";
$LNG['subscriber_tb_date'] = "Date";

// Broadcast emails //new > v2.2.80612
$LNG['broadcast_menu'] = "Broadcast";
$LNG['broadcast_disabled'] = "<font color=red>Email broadcaster has been disabled by Administrator.</font>";

// Responder emails //new > v2.2.80612
$LNG['responder_menu'] = "Responders";
$LNG['responder_disabled'] = "<font color=red>Email auto responders has been disabled by Administrator.</font>";

// Sales History
$LNG['sales_header'] = "History";
$LNG['sales_tb_id'] = "ID";
$LNG['sales_tb_amount'] = "Amount";
$LNG['sales_tb_batch'] = "Transaction Id";
$LNG['sales_tb_date'] = "Date";
$LNG['sales_tb_product'] = "Product";
$LNG['sales_tb_customer'] = "Customer";
$LNG['sales_tb_status'] = "Status";
$LNG['sales_tb_status0'] = "Cancel";
$LNG['sales_tb_status1'] = "Active";
$LNG['sales_tb_status2'] = "Pending";
$LNG['sales_tb_status3'] = "Unpaid"; //new > v2.2.80612
$LNG['sales_rport_earn'] = "Earning";
$LNG['sales_rport_date'] = "Order Date";
$LNG['sales_rport_dayexp'] = "Expiry Date";
$LNG['sales_rport_dayexp_never'] = "Never";
$LNG['sales_rport_qty'] = "Quantity";
$LNG['sales_rport_payee'] = "Payee";
$LNG['sales_rport_aff'] = "Affiliate ID";
$LNG['sales_rport_domain'] = "Domain";
$LNG['sales_rport_key'] = "License";
$LNG['sales_rport_info'] = "Info";
$LNG['sales_rport_download'] = "Download";
$LNG['sales_rport_extended'] = "Extended";
$LNG['sales_rport_newsales'] = "Create New Sales History";
$LNG['sales_rport_itemprices'] = "<font color=#666666>Product Prices: <strong>%s</strong> / %s / %s</font>"; //new > v2.2.80612
$LNG['sales_rport_delsales'] = "Are you sure want to delete this sales history?"; //new
$LNG['sales_rport_doaffcmlist'] = "Also generate commission (if exist) to available affiliates and send notify emails";
$LNG['sales_rport_sendmail'] = "Send notify email to related customer about this sales";
$LNG['sales_rport_newdone'] = "Add new sales history done.";
$LNG['sales_current_month'] = "Month to date sales";
$LNG['sales_rport_key_disable'] = ", enter minus sign (-) to disable using license key"; //new
$LNG['sales_rport_apicart'] = "API"; //new > v2.2.91730
$LNG['sales_rport_process'] = "Process"; //new > v2.3.450

$LNG['sales_client_filesize'] = "File size";
$LNG['sales_client_domain'] = "License Key for domain";
$LNG['sales_client_domain_ask'] = "Are you sure want to generate %s License Key for domain";
$LNG['sales_client_license'] = "License Key";
$LNG['sales_client_generate'] = "Generate License";
$LNG['sales_client_invoice'] = "To complete your order, send a payment <b>%s</b> to:<br/>%s"; //new > v2.2.80612
$LNG['sales_client_contactpay'] = "<font color='#666666'><em>Please contact us for details about the payment methods.</em></font>"; //new > v2.2.80612
$LNG['sales_client_orderstatus'] = "Order Status:"; //new > v2.2.91730
$LNG['sales_client_ordernote'] = "Note:"; //new > v2.2.91730
$LNG['sales_client_ordertracking'] = "Tracking:"; //new > v2.2.91730

$LNG['contact_customer_header'] = "Customers Newsletter";
$LNG['contact_customer_group'] = "Send to Customers Who Purchase";
$LNG['contact_customer_apiproduct'] = "Other - from API"; //new > v2.2.91730
$LNG['contact_customer_orderstatus'] = "Orders Status"; //new > v2.2.90809
$LNG['contact_customer_orderstatus_error'] = "Select at least one orders status option."; //new > v2.2.90809
$LNG['contact_customer_status'] = "Customers Status"; //new > v2.2.80612
$LNG['contact_customer_status_error'] = "Select at least one customers status option."; //new > v2.2.80612
$LNG['contact_customer_all_items'] = "All Items";
$LNG['contact_customer_sent'] = "%s customers were emailed.";
$LNG['contact_customer_failed'] = "%s customers were not emailed.";

// History Account
$LNG['history_header'] = "History";
$LNG['history_btn_manualpay'] = "Manual E-Gold Payment:";
$LNG['history_tb_id'] = "ID";
$LNG['history_tb_amount'] = "Amount";
$LNG['history_tb_batch'] = "Batch-Id";
$LNG['history_tb_tmstamp'] = "Time Stamp";
$LNG['history_tb_memo'] = "Memo";
$LNG['history_tb_status'] = "Status";
$LNG['history_rport_earn'] = "Earning";
$LNG['history_rport_ewallet_earn'] = "<em>(E-Wallet Withdraw: %s / %s)</em>";
$LNG['history_rport_ewallet_unpaid'] = "<em>(E-Wallet Unpaid: %s / %s)</em>";
$LNG['history_rport_savingearn'] = "Admin Earning";
$LNG['history_rport_memberearn'] = "Member Earning";
$LNG['history_rport_try'] = "Trying";
$LNG['history_rport_wait'] = "Waiting";
$LNG['history_rport_pending'] = "Pending"; //new > v2.2.80612
$LNG['history_rport_unpaid'] = "Unpaid"; //new > v2.2.80612
$LNG['history_rport_onhold'] = "OnHold";
$LNG['history_rport_dayin'] = "Entry Date";
$LNG['history_rport_paytype'] = "Merchant";
$LNG['history_rport_payee'] = "Payee";
$LNG['history_rport_amount'] = "Amount";
$LNG['history_rport_fromto'] = "From - To ID";
$LNG['history_rport_batch'] = "Batch #";
$LNG['history_rport_info'] = "Info";
$LNG['history_fltr_label'] = "Filter Records"; //new > v2.2.80401
$LNG['history_fltr_startdate'] = "Start Date"; //new > v2.2.80401
$LNG['history_fltr_enddate'] = "End Date"; //new > v2.2.80401
$LNG['history_fltr_fromuname'] = "From Username"; //new > v2.2.80401
$LNG['history_fltr_touname'] = "To Username"; //new > v2.2.80401
$LNG['history_fltr_merchant'] = "Merchant"; //new > v2.2.80401
$LNG['history_fltr_btn_enable'] = "Enable Filter"; //new > v2.2.80401
$LNG['history_fltr_btn_reset'] = "Reset Filter"; //new > v2.2.80401
$LNG['history_act_follow_status'] = "Also change the status of related transaction histories (if any)"; //new > v2.3.422

$LNG['history_withdraw'] = "Withdraw Request"; //new > v2.2.80612
$LNG['history_aval_blnc'] = "Your Available Balance";
$LNG['history_amount_withdraw'] = "Amount to Withdraw";
$LNG['history_proc_withdraw'] = "Withdraw Using"; //new > v2.1.80214
$LNG['history_min_withdraw'] = "Minimum Amount to Withdraw is"; //new > v2.2.80612
$LNG['history_btn_withdraw'] = "Withdraw";
$LNG['history_alert_withdraw'] = "<font color=red size=1><em>*) Currently you can withdraw your balance once per day.</em></font>"; //update > v2.2.91730
$LNG['client_withdraw_confirm'] = "Do you want to withdraw";
$LNG['client_withdraw_memo'] = "Cash withdraw from %s";
$LNG['client_withdraw_oke'] = "Withdraw success, batch #";
$LNG['client_withdraw_err'] = "Withdraw failed, error: ";

//new > v2.2.91730
$LNG['history_ewallet'] = "My E-Wallet";
$LNG['history_ewallet_withdraw'] = "Amount to withdraw";
$LNG['history_ewallet_transfer'] = "Amount to transfer";
$LNG['history_ewallet_transfer_to'] = "Transfer to username";
$LNG['history_ewallet_transfer_toconfirm'] = "<em><font color='#339933'>Are you sure you want to transfer this amount to username <strong>%s</strong>?, please confirm it by enter your account password in the form provided below.</font></em>";
$LNG['history_ewallet_withdraw_alert'] = "<font color=red size=1><em>*) Make sure your %s account(s) already <a href='index.php?a=client&b=edit'>setup</a> correctly before continue.</em></font>";
$LNG['history_ewallet_transfer_alert'] = "<font color=red size=1><em>*) Make sure the recipient username: <b>%s</b> is correct before clicking the Submit button.</em></font>";
$LNG['history_ewallet_fee'] = "Fee: %s";
$LNG['history_ewallet_capfee'] = "(maximum fee is %s)";
$LNG['history_ewallet_minwithdraw'] = "Minimum amount to withdraw is <strong>%s</strong>";
$LNG['history_ewallet_mintransfer'] = "Minimum amount to transfer is <strong>%s</strong>";
$LNG['history_ewallet_receive'] = "Amount to receive";
$LNG['history_ewallet_in'] = "EWallet-In";
$LNG['history_ewallet_out'] = "EWallet-Out";
$LNG['history_ewallet_withdraw_to'] = "E-Wallet withdraw";
$LNG['history_ewallet_withdraw_fee'] = "E-Wallet withdraw fee";
$LNG['history_ewallet_transfer_in'] = "Transfer from";
$LNG['history_ewallet_transfer_out'] = "Transfer to";
$LNG['history_ewallet_transfer_fee'] = "Transfer fee";

// Payouts
$LNG['payout_list'] = "Maximum data per page";
$LNG['payout_min'] = "Minimum commissions payout: <b><a href='index.php?a=admin&amp;b=settings#min2payout'>%s</a></b>";
$LNG['payout_generate'] = "Generate Pay Out for";
$LNG['payout_generate_nofilter'] = "Overide payment processor filter?"; //new > v2.2.80612
$LNG['payout_memo_min2pay'] = "Commissions Payment"; //new > v2.2.80612
$LNG['payout_payeeacc'] = "Get Payee Account From"; //new
$LNG['payout_payeeacc_users'] = "User Account"; //new
$LNG['payout_payeeacc_divegold'] = "Transaction History"; //new
$LNG['payout_add_memo'] = "Additional payment description"; //new > v2.2.80612
$LNG['payout_manualpay'] = "Manual Payments";
$LNG['payout_paid'] = "Mark as PAID";
$LNG['payout_memo_pay'] = "pay out";
$LNG['payout_btn_select'] = "Select All";
$LNG['payout_btn_download'] = "Download File";
$LNG['payout_btn_pay'] = "Start Remote Payments";

// Download Files
$LNG['dl_menu'] = "Downloads";
$LNG['dl_filename'] = "File Name";
$LNG['dl_startdownload'] = "Download File";

// Online Video
$LNG['video_menu'] = "Video List";

// Gateway Page
$LNG['gateway_header'] = "Gateway Page";
$LNG['gateway_text'] = "To deter cheating, a gateway page has been put up. Click the link below to enter the site.";
$LNG['gateway_vote'] = "Enter and vote";
$LNG['gateway_no_vote'] = "Enter without voting";

// Install
$LNG['install_header'] = "Install";
$LNG['install_securykey'] = "Random characters as passwords key - Domain Security Key";
$LNG['install_adminpass1'] = "Administrator Username";
$LNG['install_adminpass2'] = "Administrator Password";
$LNG['install_welcome'] = "Welcome to the EzyGold Installation System<br />Fill out the form below to install the script";
$LNG['install_sql_prefix'] = "Table prefix - only change this if you are running more than one script from the same database";
$LNG['install_domain'] = "Your domain name (without http:// and www.)";
$LNG['install_plugin'] = "Please select your plugin site (type of your site)";
$LNG['install_sampledata'] = "Install sample members data (YES = install sample data)";
$LNG['install_error_chmod'] = "Could not write to settings_sql.php.  Make sure you CHMOD 666 settings_sql.php.";
$LNG['install_error_sql'] = "Could not connect to the SQL database.  Please go back and check your SQL settings.";
$LNG['install_done'] = "EzyGold has been installed on your server. For security reason, do not forget to remove the <b>install</b> directory.";
$LNG['install_done_note'] = "EzyGold has also created an administrator account under the name you filled earlier.<br />Use that passwords to manage your site.";
$LNG['install_done_pass'] = "Your {$LNG['install_adminpass1']}: <b>%s</b> and {$LNG['install_adminpass2']}: <b>%s</b>";
$LNG['install_done_thankyou'] = "If there are any problems or bugs found, do not hesitate to <a href='http://www.ezygold.com/helpdesk' title='EzyGold Support' target='_blank'>contact us</a>. Thank you for using EzyGold!.";
$LNG['install_your'] = "Your Site";
$LNG['install_admin'] = "Admin";
$LNG['install_manual'] = "Manual";
$LNG['install_mismatch'] = " ---> version mismatch"; //new > v2.3.450

$LNG['install_sqlfile_check'] = "<font size=1>Rename the <strong>000_settings_sql.php</strong> file to <strong>settings_sql.php</strong> before continue.</font>"; //new > v2.2.80612
$LNG['install_sqlfile_none'] = "<font size=1>File not found. Upload this file to your server.</font>"; //new > v2.2.80612

$LNG['upgrade_header'] = "Upgrade";
$LNG['upgrade_welcome'] = "Welcome to Upgrade System. Before you upgrade, remember to back up your data.";
$LNG['upgrade_version'] = "Please make sure that you are upgrading from version %s.";
$LNG['upgrade_error_version'] = "Upgrading is only supported for latest version or higher.";
$LNG['upgrade_done'] = "Your site has been upgraded. Delete this directory now.";

// Cash Gifting Details //new > v2.2.80612
$LNG['cashgift_detail_header'] = "Cash Gifting Details";
$LNG['cashgift_history_subheader'] = "<br /><br />If you didn't receive gift from your receiving line for reasonable time, you can <a href='index.php?a=client&amp;b=feedback'>contact us</a> for further action.";
$LNG['cashgift_level'] = "Level";
$LNG['cashgift_qualified'] = "Qualified";
$LNG['cashgift_qualified_need'] = " :: [you need to get %s qualifier(s)]";
$LNG['cashgift_status'] = "Gift Status";
$LNG['cashgift_status_unconfirmed'] = "<font color=red>Unconfirmed</font>";
$LNG['cashgift_status_waiting'] = "<font color=navy>Waiting approval</font> from %s";
$LNG['cashgift_status_confirmed'] = "<font color=green>Confirmed</font> by %s";
$LNG['cashgift_pay_inviter'] = "Send your cash gift <strong>%s</strong> to your inviter details below:";
$LNG['cashgift_pay_hoster'] = "Send your cash gift <strong>%s</strong> to your hoster details below:";
$LNG['cashgift_pay_done'] = "After sending your cash gift, <a href='%s'>click here</a> to confirm your payment";
$LNG['cashgift_pay_updone'] = "After sending your cash gift, confirm your payment using form below.";
$LNG['cashgift_apprwaiting'] = "<br />Gift waiting for your approval: %s";
$LNG['cashgift_sendmtd'] = "Gift Sending Method:";
$LNG['cashgift_sendsn'] = "Payment Code / Tracking:";
$LNG['cashgift_payfrom'] = "Gift from:";
$LNG['cashgift_upgradepayfrom'] = "Gift Upgrade from:";
$LNG['cashgift_paythrough'] = "Pay through:";
$LNG['cashgift_apprconfirm'] = "%s\\n\\rAre you sure want to approve (confirm) this gifting?\\n\\rWarning! You cannot reverse this process.";
$LNG['cashgift_postconfirm'] = "Are you sure want to confirm your gift?";
$LNG['cashgift_payviaconfirm'] = "[<a href='%s'>Confirm</a>]";
$LNG['cashgift_upgradeto'] = "Upgrade Level to:";
$LNG['cashgift_upgradeconfirm'] = "Are you sure want to upgrade your level?\\n\\rWarning! You cannot cancel this process.";
$LNG['cashgift_btnconfirm'] = "Confirm";

// Join
$LNG['join_header'] = "Registration";
$LNG['join_user'] = "Account Login";
$LNG['join_website'] = "Website";
$LNG['join_accdetails'] = "Account Details";
$LNG['join_paywith'] = "Payment Method";
$LNG['join_startover'] = "Click <a href='javascript:history.back(1);'>here</a> to re-enter your account details";
$LNG['join_error_fullname'] = "Enter your full name.";
$LNG['join_error_username'] = "Enter a valid username: use only letters and numbers.";
$LNG['join_error_username_duplicate'] = "Enter a valid username: your username is already in use.";
$LNG['join_error_url'] = "Enter a valid URL.";
$LNG['join_error_email'] = "Enter a valid email address.";
$LNG['join_error_emailban'] = "Email address declined, please use another email address."; //new > v2.2.80612
$LNG['join_error_emailexist'] = "Email address already in use."; //new > v2.2.80612
$LNG['join_error_title'] = "Enter a title for your web site.";
$LNG['join_error_description'] = "Enter a description for your web site.";
$LNG['join_error_password'] = "Enter a password.";
$LNG['join_error_password_mismatch'] = "Entered password mismatch.";
$LNG['join_error_urlbanner'] = "Enter a valid banner. Leave it blank if you don't have one.  It must be smaller than";
$LNG['join_error_time'] = "Do not refresh the registration confirmation page.";
$LNG['join_error_captcha'] = "The word you entered does not match the image.";
$LNG['join_error_bnarysys'] = "Select your position preference";
$LNG['join_error_isagree'] = "You must agree with our Terms and Conditions"; //new > v2.2.80401
$LNG['join_error_disabled'] = "<font color=red>Sorry, currently we do not accept new registration!</font><br /><em>Please contact us for more details.</em>"; //new > v2.2.90809
$LNG['join_thanks'] = "Thank you for joining!"; //upgrade > v2.2.80401
$LNG['join_need_confirm'] = "You need to confirm your registration by follow the activation instruction sent to your email."; //new > v2.2.80401
$LNG['join_change_warning'] = "Put this code to promote your referral link. If you change the code, it might not work."; //upgrade > v2.2.80401
$LNG['join_welcome'] = "Welcome to %s";
$LNG['join_welcome_confirm'] = "please confirm your registration at %s"; //new > v2.2.80401
$LNG['join_confirm_success'] = "Thank you for confirm your registration, please login using form below."; //new > v2.2.80401
$LNG['join_confirm_failed'] = "Invalid confirmation link."; //new > v2.2.80401
$LNG['join_confirm_done'] = "This account already confirmed. Thank you."; //new > v2.2.80401
$LNG['join_resend_nopass'] = "- hidden- [check the previous email]"; //new > v2.2.80401
$LNG['join_welcome_admin'] = "A new member has joined your site.";
$LNG['join_approve'] = "Your site will be listed when the admin of the site approves it.";
$LNG['join_type'] = "Account type";
$LNG['join_standard'] = "Standard";
$LNG['join_notice'] = "Your Username will appear in your site referral link, and must contain alphanumeric characters only. Please make a note of your details, and PLEASE double check your email address!<br /><br /><em>Please use your VALID email address to ensure you get your registration confirmation! Free email accounts are notorious for deleting business opportunity email, and may likely delete ours. We hate spam as much as you do, your details are safe and we will never spam you or share your details with anyone.</em>"; //new > v2.2.80401
$LNG['join_button'] = "Sign Me Up"; //new > v2.3.429

$LNG['email_renew_account'] = "Thank you :: account renewal success";
$LNG['email_renew_account_admin'] = "A new account renewal has complete.";
$LNG['email_renew_thanks'] = "Thank you for renew your membership!.";

//new > v2.2.91730
$LNG['email_updateplan_account'] = "Thank you :: update membership success";
$LNG['email_updateplan_account_admin'] = "A new account update has complete.";
$LNG['email_updateplan_thanks'] = "Thank you for updating your membership!.";

// Link Code
$LNG['link_code_header'] = "Banner Ads";
$LNG['link_code_statsby'] = "Site Stats by";
$LNG['premade_ads_header'] = "Premade Ads"; //new > v2.2.80401
$LNG['premade_ads_textads'] = "<strong>Can be beneficial in building traffic as well as gaining sales.</strong><br /><br />Text advertisements are not blocked by ad blockers. As opposed to online banners and other graphical advertisements, static text ads are never blocked by ad blocking software. As such, every impression will be visible to all site visitors which results in more visitors to your website replication."; //new > v2.2.80612
$LNG['premade_ads_emailads'] = "<strong>Reliable and an effective way to dramatically boost your click-through rates.</strong><br /><br />If you have a list of opt-in e-mail addresses (your leads, i.e. customers and/or subscribers who have given you permission to contact them), then e-mail promotions can be a highly profitable promotional technique!<br /><br />If your leads know and trust you as someone who they can depend on to provide quality information and products, it's really easy to write a personal letter of recommendation. Your leads respect your opinion, so when you present them with good information, they're going to act! They're going to click through your referral link!"; //new > v2.2.80401
$LNG['premade_ads_classads'] = "<strong>Classified ads in advertisement are extremely profitable.</strong><br /><br />A text link can easily present peoples with a short sentence or paragraph that clearly explains what they will gain, how they benefit, or what they will learn by clicking on it.<br /><br />Also, it's easy to place a text link or position it as informative resource for peoples as opposed to blatant advertisement, it's a strategy that has been statistically proven to make peoples more receptive to clicking through provided link!"; //new > v2.2.80401
$LNG['premade_ads_textlink'] = "<strong>Did you know that text links are an excellent addition to your marketing strategy?</strong><br /><br />A text link strategically placed on your web site can be an easy but effective way to drive traffic to your referral URL. The beauty of text links is that you can easily present your visitors with a short sentence or paragraph that clearly explains what they will gain, how they benefit, or what they will learn by clicking on it. Your visitors will see you as providing them with a valuable resource, and this means they'll be more open to any offer we present them with because they don't feel like they're about to be \"sold\" to."; //new > v2.2.80401
$LNG['preads_textads_pretext'] = "<strong>Insert this text ad promotion now!</strong><br />Copy and paste the code in the box below into the source of your web page where you would like it to appear. Your <em>username</em> is automatically inserted into any links."; //new > v2.2.80401
$LNG['preads_emailads_pretext'] = "<strong>Send this e-mail promotion now!</strong><br />We recommend you use the above subject line with this e-mail promotion. It's been tested extensively to guarantee maximum interest. Just copy and paste it directly into your e-mail.<br /><br /><strong>Copy and paste</strong> the code in the box below into a blank e-mail. Your <em>username</em> is automatically inserted into the link."; //new > v2.2.80401
$LNG['preads_textlink_pretext'] = "<strong>Insert this text link promotion now!</strong><br />Copy and paste the code in the box below into the source of your web page where you would like it to appear. Your <em>username</em> is automatically inserted into any links."; //new > v2.2.80401
$LNG['preads_classads_pretext'] = "<strong>Insert this classified ad now!</strong><br />Copy and paste the code in the box below into the source of your advertisement or newsletter or e-zine where you would like the classified ad to appear. Your <em>username</em> is automatically inserted into any links."; //new > v2.2.80401
 
$LNG['premade_ads_banner'] = "<strong>Use our banners to dramatically increase your click-through rates.</strong><br /><br />We are constantly testing, researching and updating our banners, staying aware of industry shifts and always using the latest technologies. These banners have been designed, tested, and proven to attract the highest click-through rates!<br /><br /><strong>Using an image in your web site is a surefire way to encourage peoples to click through.</strong><br /><br />One of the best ways to grab the attention of internet surfers, is to simply add an image to your site.  The image gives them a lot of the information they need, without making them slow down to read.<br /><br />A good image equates to a carefully written paragraph, full of descriptive text. It can show (rather than tell) people exactly what they're getting."; //new > v2.2.80401
$LNG['preads_banner_pretext'] = "<strong>Insert this banner or image ad promotion now!</strong><br />Copy and paste the code in the box below into the source of your web page where you would like the banner or image ad to appear. Your <em>username</em> is automatically inserted into any links."; //new > v2.2.80401

// Lost Password
$LNG['lost_pw_title'] = "Lost Password"; //new > v2.2.80612
$LNG['lost_pw_header'] = "Lost Password %s"; //update > v2.2.80612
$LNG['lost_pw_forgot'] = "Forgot your password?";
$LNG['lost_pw_get'] = "Get Password";
$LNG['lost_pw_emailed'] = "Please check your email for further instructions.";
$LNG['lost_pw_email'] = "To pick a new password for your site, just go to this URL:";
$LNG['lost_pw_new'] = "Enter a New Password";
$LNG['lost_pw_set_new'] = "Set New Password";
$LNG['lost_pw_finish'] = "Your password has been set to the new password you have just chosen.";

// Main Page
$LNG['main_header'] = "Sites List";
$LNG['main_all'] = "All Sites";
$LNG['main_method'] = "Ranking method";
$LNG['main_members'] = "Members";
$LNG['main_menu_home'] = "Home";
$LNG['main_menu_info'] = "Details";
$LNG['main_menu_program'] = "Programs";
$LNG['main_menu_sites'] = "Sites"; // new > v2.2.90809
$LNG['main_menu_faq'] = "FAQ";
$LNG['main_menu_join'] = "Register";
$LNG['main_menu_contact'] = "Contact";
$LNG['main_menu_news'] = "News";
$LNG['main_menu_terms'] = "Terms"; // new > v2.2.80401
$LNG['main_menu_subscriber'] = "Subscriber";
$LNG['main_menu_random'] = "Random Member";
$LNG['main_menu_search'] = "Search";
$LNG['main_menu_lost_code'] = "Lost Code";
$LNG['main_menu_lost_password'] = "Lost Password";
$LNG['main_menu_edit'] = "Edit Member Info";
$LNG['main_menu_client'] = "Login";
$LNG['main_menu_testimonials'] = "Testimonials";
$LNG['main_featured'] = "Featured Member";
$LNG['main_executiontime'] = "Loading Time";
$LNG['main_queries'] = "Queries";
$LNG['main_powered'] = "Powered by";
$LNG['main_visitor'] = "Online Visitor";
$LNG['main_visitor_guests'] = "Guest";
$LNG['main_visitor_members'] = "Member";
$LNG['main_image_screenshot'] = "Screenshot";

// User Site Directory // new > 2.2.90809
$LNG['sites_header'] = "Member Site Directory";
$LNG['sites_cat'] = "Category:";

// Ranking Table
$LNG['table_stats'] = "Stats";
$LNG['table_unique'] = "Unique";
$LNG['table_total'] = "Total";
$LNG['table_rank'] = "Rank";
$LNG['table_title'] = "Title";
$LNG['table_description'] = "Description";
$LNG['table_movement'] = "Movement";
$LNG['table_up'] = "Up";
$LNG['table_down'] = "Down";
$LNG['table_neutral'] = "Neutral";

// Rate and Review
$LNG['rate_header'] = "Rate and Review";
$LNG['rate_rating'] = "Rating";
$LNG['rate_review'] = "Review - No HTML allowed";
$LNG['rate_thanks'] = "Thank you for your rating.";
$LNG['rate_error'] = "You have already rated this site.";
$LNG['rate_back'] = "Back to Stats";
$LNG['rate_email_admin'] = "A new review has been posted at your site.";

// Contact Form
$LNG['contact_header'] = "Contact Us";
$LNG['contact_name'] = "Name";
$LNG['contact_email'] = "Email";
$LNG['contact_subject'] = "Subject";
$LNG['contact_body'] = "Message - No HTML allowed";
$LNG['contact_reff'] = "Referrer (optional)";
$LNG['contact_button'] = "Submit";
$LNG['contact_error_name'] = "Enter your name.";
$LNG['contact_error_email'] = "Enter a valid email address.";
$LNG['contact_error_subject'] = "Enter your message subject.";
$LNG['contact_error_body'] = "Enter your message content.";
$LNG['contact_error_captcha'] = "The word you entered does not match the image.";
$LNG['contact_error_etoken'] = "The Access PIN you entered is not recognized."; //new > v2.1.80214
$LNG['contact_email_sent'] = "Thank you, your message was sent.";
$LNG['contact_email_sentext'] = "<font color=#666666>Name: %s<br/>Email: <font color=#0000cc><u>%s</u></font><br />Subject: %s</font>"; //new > v2.2.90808

// Subscriber Form
$LNG['subscriber_header'] = "Subscriber Form";
$LNG['subscriber_sponsor'] = "Sponsor Username";
$LNG['subscriber_name'] = "Name";
$LNG['subscriber_email'] = "Email";
$LNG['subscriber_phone'] = "Phone #"; //new > v2.2.80401
$LNG['subscriber_emtype'] = "Email Format Preferrence";
$LNG['subscriber_button'] = "Submit";
$LNG['subscriber_error_sponsor'] = "Enter a valid sponsor username here.";
$LNG['subscriber_error_name'] = "Enter a name here.";
$LNG['subscriber_error_email'] = "Enter a valid email address.";
$LNG['subscriber_error_emtype'] = "Select your email format preference.";
$LNG['subscriber_error_captcha'] = "The word you entered does not match the image.";
$LNG['subscriber_done'] = "Thank you for subscribing.<br /><br />Your name: %s";
$LNG['subscriber_confirm'] = "Thank you for subscribing.<br /><br />Please confirm your subscription within 24 hours by follow the instruction sent to %s"; //new > v2.2.80401
$LNG['subscriber_exist'] = "Your email <b>%s</b> already registered. Thank you.";
$LNG['subscriber_info'] = "Your Details";
$LNG['subscriber_invalid'] = "Subscriber not found.";
$LNG['subscriber_update_done'] = "Thank you for updating.<br /><br />Your name: %s";
$LNG['subscriber_remove'] = "Are you sure want to remove from our list?";
$LNG['subscriber_remove_done'] = "Your details was removed from our list.";
$LNG['subscriber_remove_button'] = "Unsubscribe Me";
$LNG['subscriber_already'] = "Already Subscribed?"; //new > v2.2.80401
$LNG['subscriber_already_info'] = "Enter your subscribed email in the form below to get full access of our site."; //new > v2.2.80401

// Search
$LNG['search_keyword'] = "Keyword";
$LNG['search_header'] = "Search";
$LNG['search_off'] = "The search feature has been disabled.";
$LNG['search_no_site'] = "Sorry, no sites matching your criteria were found.";
$LNG['search_prev'] = "Previous";
$LNG['search_next'] = "Next";
$LNG['search_displaying_results'] = "Displaying %s to %s of %s results for <b>%s</b>.";

// Stats
$LNG['stats_header'] = "Stats";
$LNG['stats_info'] = "Info";
$LNG['stats_member_since'] = "Member Since";
$LNG['stats_carefee_date'] = "Maintenance Date"; //new
$LNG['stats_rating_avg'] = "Average Rating";
$LNG['stats_rating_num'] = "Number of Ratings";
$LNG['stats_rate'] = "Rate and Review This Site";
$LNG['stats_reviews'] = "Reviews";
$LNG['stats_allreviews'] = "Show All Reviews";
$LNG['stats_week'] = "Week";
$LNG['stats_highest'] = "Highest";

// ssi.php
$LNG['ssi_top'] = "Top %s Sites";
$LNG['ssi_new'] = "%s Newest Members";
$LNG['ssi_all'] = "All Sites";

 //new > v2.2.91730
$LNG['giftpass_menu'] = "GiftPass";
$LNG['giftpass_header'] = "GiftPass";
$LNG['giftpass_note'] = "If you have GiftPass code, you can use it here by entering your code in the form below. If you enter valid GiftPass code, this payment procedure will be processed instantly after clicking the Submit button.";
$LNG['giftpass_code'] = "Code:";
$LNG['giftpass_nicetry'] = "<font size=1 color=red><i>Invalid Code #%s</i></font>";

// User Control Panel
$LNG['client_header'] = "User Control Panel";
$LNG['client_login'] = "Login";
$LNG['client_urlid_note'] = "Here is your main referral link to promote:"; //update > v2.3.429
$LNG['client_logout'] = "Logout";
$LNG['client_welcome'] = "Welcome to the user control panel. Use the links to the left to manage your account.";
$LNG['client_thankyou_paid'] = "Thank you for your payment. Please login using form below.";
$LNG['client_create_newacc'] = "Create new account"; //new > v2.2.80612
$LNG['client_created'] = "Created";
$LNG['client_neverexpd'] = "Never Expired";
$LNG['client_accexpd'] = "Expired";
$LNG['client_status'] = "Account Status";
$LNG['client_payplan_status'] = "Membership Status"; //new > v2.2.91730
$LNG['client_payplan_info'] = "Membership Note"; //new > v2.2.91730
$LNG['client_referrals'] = "Referral List";
$LNG['client_referrals_subheader'] = "Below you can see your Referral list";
$LNG['client_referrals_subheader_passedup'] = "Referral passed up to your sponsor"; //new > v2.2.80610
$LNG['client_referrals_display'] = "Display"; //new > v2.2.91730
$LNG['client_referrals_direct'] = "Personal Referral"; //new > v2.3.450

$LNG['client_referraldisplay'] = "Display Referrals Option";
$LNG['client_referraldisplay_personal'] = "Personal referrals only";
$LNG['client_referraldisplay_level'] = "Referrals on level";
$LNG['client_referraldisplay_default'] = "All referrals";

//new > v2.2.90809
$LNG['client_accoverview'] = "Account Overview";
$LNG['client_membershipname'] = "Membership Name";
$LNG['client_totalearning'] = "Total Earning";
$LNG['client_totalpaid'] = "Total Paid";
$LNG['client_totalunpaid'] = "Total Unpaid";
$LNG['client_totalewallet'] = "E-Wallet Balance";
$LNG['client_membershiptype'] = "Available Membership Types";

//new > v2.3.45o
$LNG['client_totalpoin'] = "Point Balance";

//new > v2.2.91730
$LNG['client_updatepayplan'] = "Update Summary";
$LNG['client_updatepayplan_note'] = "Current membership type: <p style='padding-left:33px;'>%s</p> Register new membership type: <p style='padding-left:33px;'>%s</p>";
$LNG['client_updatepayplan_done'] = "Please wait to continue...";

$LNG['client_referrals_ctrldownline_action_act'] = "Approve payment status"; //new > v2.2.80612
$LNG['client_referrals_ctrldownline_action_deact'] = "Decline payment status"; //new > v2.2.80612
$LNG['client_referrals_ctrldownline_action_dis'] = "Disable (no privilege)"; //new > v2.2.80612
$LNG['client_referrals_ctrldownline_activate'] = "Referral: %s\\nPayment for: %s\\n\\nAre you sure you want to APPROVE payment from this referral?"; //new > v2.2.80612
$LNG['client_referrals_ctrldownline_deactivate'] = "Referral: %s\\nPayment for: %s\\n\\nAre you sure you want to DECLINE payment from this referral?"; //new > v2.2.80612
$LNG['client_referrals_ctrldownline_activateby'] = "Confirmed by "; //new > v2.2.80612
$LNG['client_referrals_ctrldownline_deactivateby'] = "Unconfirmed by "; //new > v2.2.80612
$LNG['client_referrals_ctrldownline_confirmed'] = " (Confirmed)"; //new > v2.2.80612
$LNG['client_referrals_ctrldownline_unconfirmed'] = " (Unconfirmed)"; //new > v2.2.80612

$LNG['client_genealogy'] = "Genealogy Report";
$LNG['client_history'] = "Transaction History";

//new > v2.2.91730
$LNG['client_giftpass'] = "Manage GiftPass";
$LNG['client_giftpass_subheader'] = "You can use available GiftPass code below to register your new referrals or you can use it to upgrade or renew existing account.";
$LNG['client_giftpass_code'] = "GiftPass Code";
$LNG['client_giftpass_enddate'] = "End Date";
$LNG['client_giftpass_none'] = "<font color='#999999'>[ none ]</font>";
$LNG['client_giftpass_usedate'] = "Used Date";
$LNG['client_giftpass_available'] = "<font color='#999999'>[ Available ]</font>";
$LNG['client_giftpass_disable'] = "<font color=red>GiftPass feature has been disabled by Administrator.</font>";
$LNG['client_giftpass_order'] = "Purchase GiftPass";
$LNG['client_giftpass_orderbtn'] = "Order"; //new > v2.3.450
$LNG['client_giftpass_order_note'] = "<p>Using GiftPass code people be able to register an account, update or renew their membership instantly. It's a best tool to recruit new referrals offline or for whom who want to register but cannot use one of our payment method options.</p>"; //update > v2.3.450
$LNG['client_giftpass_noitem'] = "Please <a href='index.php?a=client&b=feedback'>contact us</a> to get details how to purchase the GiftPass codes."; //new > v2.3.450

$LNG['client_campstat_menu'] = "Campaign";
$LNG['client_campstat'] = "Campaign Link Stats";
$LNG['client_campstat_subheader'] = "You can create and use campaign tracking in your username when promote your referral url. It's useful to track your campaign performance. To do this add the campaign tracker after your username in the referral link, separated with '-' character.<br /><br />Examples:<br /><br />Your referral link is <strong>%s</strong><br /><br />* Campaign tracker '<strong>myblog</strong>' (e.g. promote the link in your blog):<br /><em>&nbsp; &rarr; New refersl link: %s</em><br /><br />* Campaign tracker '<strong>email</strong>' (e.g. promote through email campaign):<br /><em>&nbsp; &rarr; New refersl link: %s</em>";
$LNG['client_campstat_uctext'] = "Tracking";
$LNG['client_campstat_ucref'] = "Referrer";
$LNG['client_campstat_uchits'] = "Hits";
$LNG['client_campstat_noref'] = "<em>- Direct visit or bookmark -</em>";
$LNG['client_campstat_disable'] = "<font color=red>Campaign tracking feature has been disabled by Administrator.</font>";

$LNG['client_leads'] = "Subscribers List"; //new > v2.2.80401
$LNG['client_leads_subheader'] = "Below you can see your Subscribers list"; //new > v2.2.80401
$LNG['client_leads_remove'] = "Are you sure want to remove all checked subscribers?"; //new > v2.2.80612

$LNG['client_broadcast'] = "Email Broadcaster"; //new > v2.2.80612
$LNG['client_broadcast_subheader'] = "You can send emails to your downline and/or subscribers using form below."; //new > v2.2.80612
$LNG['client_broadcast_archive'] = "Archive Broadcasted"; //new > v2.2.80612
$LNG['client_broadcast_manage'] = "Manage Email Broadcast"; //new > v2.2.80612
$LNG['client_broadcast_to'] = "Recipients"; //new > v2.2.80612
$LNG['client_broadcast_meto'] = "Personal referrals only"; //new > v2.3.450
$LNG['client_broadcast_btnstart'] = "Start Broadcasting"; //new > v2.2.80612
$LNG['client_broadcast_warning'] = "<em><font color='#CC0000'>Broadcast disable until <strong>%s</strong></font></em>"; //new > v2.2.91730

$LNG['client_responder'] = "Email Auto Responders"; //new > v2.2.80612
$LNG['client_responder_subheader'] = "You can setup email auto responders for your downline and/or subscribers using form below."; //new > v2.2.80612
$LNG['client_responder_archive'] = "Archive Email Auto Responders"; //new > v2.2.80612
$LNG['client_responder_manage'] = "Manage Email Auto Responders"; //new > v2.2.80612
$LNG['client_responder_to'] = "Recipients"; //new > v2.2.80612
$LNG['client_responder_days'] = "Message send after registration date (in days)"; //new > v2.2.80612
$LNG['client_responder_day'] = "Day"; //new > v2.2.80612
$LNG['client_responder_btnsave'] = "Submit"; //new > v2.2.80612
$LNG['client_responder_btndel'] = "Delete"; //new > v2.2.80612
$LNG['client_responder_delwarn'] = "Are you sure want to delete this email auto responder?"; //new > v2.2.80612

$LNG['client_leads_import'] = "Import New Subscribers"; //new > v2.2.80612
$LNG['client_leads_import_info'] = "Insert your subscribers into the form below, one subscriber for one line."; //new > v2.2.80612
$LNG['client_leads_import_example'] = "<strong>Example:</strong><br /><font size=1><strong><font color=red>Full Name</font>; <font color=red>Email Address</font>; Phone Number; <font color=red>Format Email</font>; User OS; Browser Agent; IP Address</strong></font><br /><br /><font face='Courier New, Courier, monospace' size=1>Sample Name; email@domainname.com; 06234-100-222; Text; Win XP; FireFox 2; 23.234.111.34<br />Cute Name; mailbox@sitename.com;; Html; Mac OS; Safari; 127.123.234.34<br />Lead Name; address@yoursite.com;; Both; Windows XP; Internet Explorer;<br />Nice Name; email@email.com; 01456-789-001; Html;; Firefox 2.0; 127.00.001.1<br /><br />Format Email = [<font color=red>Text</font>|<font color=red>Html</font>|<font color=red>Both</font>], default = Text</font><br />"; //new > v2.2.80612
$LNG['client_leads_import_separator'] = "Field Separator"; //new > v2.2.80612
$LNG['client_leads_import_submit'] = "Start Import Subscribers"; //new > v2.2.80612

$LNG['client_history_subheader'] = "Below is your transaction history";
$LNG['client_history_new'] = "Create New Transaction History"; //new
$LNG['client_history_delete'] = "Are you sure want to delete this transaction history?"; //new
$LNG['client_download'] = "Download Files";
$LNG['client_renewal'] = "Account Renewal";
$LNG['client_relogincycling'] = "Swap login session to this username"; //new
$LNG['client_carefee'] = "Maintenance Fee"; //new
$LNG['client_carefee_onetime'] = "This is <strong>one time</strong> payment of Maintenance Fee"; //new
$LNG['client_carefee_indays'] = "Your Maintenance Fee will be valid from <strong>%s</strong> to <strong>%s</strong>"; //new
$LNG['client_carefee_tilldate'] = "Your Maintenance Fee will be valid until <strong>%s</strong>"; //new
$LNG['client_download_note'] = "If you experience any download problems, please avoid using the download manager.";
$LNG['client_logout_message'] = "You are now logged out of the user control panel.";
$LNG['client_login_long'] = "Login with your username and password or your OpenID.";
$LNG['client_openid'] = "OpenID";
$LNG['client_openid_error_server'] = "Unable to find OpenID server for %s.";
$LNG['client_openid_error_join'] = "You must registered before you can access the user control panel.";
$LNG['client_openid_error_general'] = "An error occurred while processing your login.  Please try again.";
$LNG['client_openid_error_cancel'] = "You must grant access to proceed.  Please try again.";
$LNG['client_openid_error_from_server'] = "Error from server: %s";
$LNG['client_warn_not_pay'] = "<strong>IMPORTANT INFORMATION!!!</strong><br />Please be aware that you will not earn commissions until you have upgraded
your account. To maximize your earnings upgrade and activate your account today!.";
$LNG['client_warn_bad_email'] = "<strong>NOTE!!!</strong><br />It's seem your email address <b>%s</b> is not valid (please update it now).";
$LNG['client_warn_expired'] = "<strong>NOTE!!!</strong><br /><font color=#999999>Your account expired in %s day(s).</font><br /><em>&rarr; Expired account will not receive any rewards and commissions.</em>";
$LNG['client_warn_is_expired'] = "<strong>WARNING!!!</strong><br />Your account already expired (%s day(s)).<br />You'll not receive any rewards and commissions when your account expired.";
$LNG['client_total_referrals'] = "Your total direct referrals per visitors";

$LNG['client_totalmyreferral'] = "Total Personal Referrals";
$LNG['client_totalreferral'] = "Total Referrals";
$LNG['client_totalhits'] = "Referral Link Hits";

$LNG['client_latest_news'] = "Latest News";
$LNG['client_more_news'] = "<a href='%s' target='_blank'>More</a> News...";
$LNG['client_payback'] = "Refund to your new members - as benefactor";
$LNG['client_payback_input'] = "(in <b>%</b>, 0 = disabled)";
$LNG['client_optinme'] = "Receive emails from site Administrator";
$LNG['client_mybypass'] = "Give your new members to this username (manual downline placement)"; //new
$LNG['client_mybypassto'] = "Give away new member to this username"; //new
$LNG['client_mylanguage'] = "Default language for my member area";

$LNG['client_video'] = "Online Video"; //new > v2.2.91730
$LNG['client_video_note'] = "Click the video title to display the video."; //new > v2.2.91730

$LNG['client_dlbsites'] = "Programs List";
$LNG['client_dlbsites_subheader'] = "Below you can see the downline builder program list";
$LNG['client_dlbsites_form'] = "<strong>Insert your site referral username or id:</strong>";
$LNG['client_dlbsites_edit'] = "Manage My Downline Builder Site"; //new > v2.2.91730

$LNG['client_spr_details'] = "Sponsor Details"; //new
$LNG['client_spr_name'] = "Sponsor Name"; //new
$LNG['client_spr_username'] = "Sponsor Username"; //new
$LNG['client_spr_email'] = "Sponsor Email"; //new

// Offline Payment Confirmation //new > v2.2.80612
$LNG['client_payconfirm'] = "Payment Confirmation";
$LNG['client_payconfirm_subheader'] = "If you already sent your payment, please confirm it by using form below by include your payment details (method of payment, date the payment sent, reference number, etc).";
$LNG['client_payconfirm_details'] = "Payment Details";
$LNG['client_payconfirm_submit'] = "Thak you for your confirmation, we will check your payment.";

// Feedback
$LNG['client_feedback'] = "Feedback";
$LNG['client_feedback_subheader'] = "If you have any feedback, questions or would like to give a testimonial, please use the form below.";

// Messenger
$LNG['client_messenger'] = "Messenger";
$LNG['client_messenger_subheader'] = "Use the form below to send a message to your upline or downline (if applicable).";

// Tell A Friend //new
$LNG['client_ttf'] = "Tell A Friend";
$LNG['client_ttf_subheader'] = "Use form below to send invitation to your friend(s) to join your network.";
$LNG['client_ttf_name'] = "Friend Name %s";
$LNG['client_ttf_email'] = "Friend Email %s";
$LNG['client_ttf_sent'] = "Invitation successfully sent to your friend(s)"; //new > v2.2.80401

// Affiliate
$LNG['client_affiliate'] = "Affiliate";
$LNG['client_affiliate_header'] = "Affiliate Program";
$LNG['client_affiliate_subheader'] = "Join our Affiliate Program and earn commissions based upon the products that you sell!";
$LNG['client_affiliate_urlid'] = "Your Affiliate URL ID";
$LNG['client_affiliate_product'] = "Product Name";
$LNG['client_affiliate_commission'] = "Commission";
$LNG['client_affiliate_level'] = "Level";
$LNG['client_affiliate_cplproducts'] = "Featured products";
$LNG['client_affiliate_recentsales'] = "Your recent sales";
$LNG['client_affiliate_clickhere'] = "Click here";
//new > v2.2.80401
$LNG['client_affiliate_unregisternote'] = "<font color=red><strong>Currently you are not registered to our Affiliate Program.</strong></font><br /><br />To register our affiliate program, please read our affiliate program terms and condition below and click the join button.";
$LNG['client_affiliate_terms'] = "Insert your affiliate program terms and condition here...";
$LNG['client_affiliate_joinagree'] = "I Agree";
$LNG['client_affiliate_joinbutton'] = "Join Affiliate Program";
$LNG['client_affiliate_registernote'] = "<font color=green><strong>Currently you are registered to our Affiliate Program.</strong></font><br /><br />You can unregister from our affiliate program anytime, and after unregistered, all referred sales will not credited to your account.<br /><br />If you want to unregister from our affiliate program, please use button below to continue.";
$LNG['client_affiliate_unregagree'] = "I Agree";
$LNG['client_affiliate_unregbutton'] = "Unregister Me from Affiliate Program";

// Direct Payment //new > v2.2.80612
$LNG['client_dirpay_steps'] = "Step %s, payment amount: %s";
$LNG['client_dirpay_pay'] = "Make Payment!";

// Manage Ads 2.3.450
$LNG['client_usersads_uatitle'] = "Ads Title";
$LNG['client_usersads_uaurl'] = "Ads URL";
$LNG['client_usersads_uacontent'] = "Ads Content";
$LNG['client_usersads_uacontentmax'] = "(maximum characters: %s)";
$LNG['client_usersads_uacontent_banner'] = "Banner URL";
$LNG['client_usersads_status'] = "Status";
$LNG['client_usersads_status_0waiting'] = "Waiting";
$LNG['client_usersads_status_1running'] = "Running";
$LNG['client_usersads_status_2pause'] = "&rarr; Pause";
$LNG['client_usersads_status_3review'] = "&rarr; Review";
$LNG['client_usersads_status_4finish'] = "&rarr; Finish";
$LNG['client_usersads_status_9hold'] = "&rarr; Hold";
$LNG['client_usersads_manage'] = "Update My Ads";
$LNG['client_usersads_order'] = "Purchase Ads Credits";
$LNG['client_usersads_orderbtn'] = "Order";
$LNG['client_usersads_noitem'] = "Please <a href='index.php?a=client&b=feedback'>contact us</a> to get details how to purchase the Ads Credits.";
$LNG['client_usersads_uakeywords'] = "Keywords (separated by comma)";
$LNG['client_usersads_udsempty'] = "please update this ads";

$LNG['client_usersads_isbanner'] = "<strong>Banner Image:</strong>";
$LNG['client_usersads_nobanner'] = "No banner file available";
$LNG['client_usersads_agtype'] = "Ads Type";
$LNG['client_usersads_agbase'] = "Ads Credit Based";
$LNG['client_usersads_uastartdate'] = "Start Date";
$LNG['client_usersads_uaenddate'] = "End Date";
$LNG['client_usersads_uaruntime'] = "Last Runtime";
$LNG['client_usersads_uacredits'] = "Ads Credit";
$LNG['client_usersads_uacredits_note'] = "Unlimited";
$LNG['client_usersads_uahits'] = "Impression";
$LNG['client_usersads_uaclicks'] = "Clicks";

$LNG['client_usersads_updatestatus'] = "Update My Ads Status";
$LNG['client_usersads_optupdated'] = "Member Ads Updated.";
$LNG['client_usersads_statusupdated'] = "Member Ads Status Updated.";

// Admin > Approve New Members
$LNG['a_approve_header'] = "Inactive Members List";
$LNG['a_approve'] = "Approve";
$LNG['a_extend'] = "Extend"; //new > v2.2.80612
$LNG['a_suspend'] = "Suspend"; //new > v2.2.80612
$LNG['a_approve_full'] = "(also generate available payment for members)";
$LNG['a_approve_status'] = "(change status only)";
$LNG['a_approve_action'] = "<br /><em><strong>Note:</strong> To activate member account and generate the sponsors commissions (if any), click the Approve icon <img src='{$CONF['site_url']}/templates/{$CONF['default_skin']}/images/b_act.gif'> above.</em>"; //new > v2.3.429
$LNG['a_ask_approve'] = "Are you sure want to approve this member and also generate available payments for it?\\r\\n\\r\\nMember username: %s\\r\\nMembership: %s"; // update > 2.2.91730
$LNG['a_ask_approve_dt'] = "Are you sure want to approve this member (%s) and also mark all the member payments as paid?"; //new > v2.2.91729
$LNG['a_ask_extend'] = "Are you sure want to extend this member (%s) expiration date and also generate available payments from this process?"; //new > v2.2.80612
$LNG['a_ask_approve_delete'] = "Are you sure want to approve or delete selected member(s)?";
$LNG['a_approve_none'] = "There are no members waiting to be approved.";
$LNG['a_approve_done'] = "The member has been approved.";
$LNG['a_approve_dones'] = "The members have been approved.";
$LNG['a_approve_sel'] = "With selected:";
$LNG['a_approve_showexpired'] = "Show Expired only"; //new > v2.2.80612

// Admin > Approve New Reviews
$LNG['a_approve_rev_header'] = "Approve New Reviews";
$LNG['a_approve_rev_none'] = "There are no reviews waiting to be approved.";
$LNG['a_approve_rev_done'] = "The review has been approved.";
$LNG['a_approve_rev_dones'] = "The reviews have been approved.";

// Admin > Manage Custom Pages
$LNG['a_man_pages_header'] = "Manage Custom Pages";

// Admin > Create Custom Page
$LNG['a_create_page_header'] = "Create Custom Page";
$LNG['a_create_page_id'] = "Page ID";
$LNG['a_create_page_error_id'] = "<font color=red>The page ID can contain only letters, numbers, and underscores, and the first character cannot number.</font><br /><br />Please go back and correct the page ID.";
$LNG['a_create_page_error_id_duplicate'] = "There is already a custom page with that page ID. Please go back and select a new page ID.";
$LNG['a_create_page_error_file_duplicate'] = "There is already a custom file with that page ID. Please go back and select a new page ID.";
$LNG['a_create_page_created'] = "<b>The page has been created.</b><br />";
$LNG['a_create_page_created_all'] = "Site Custom Page:<br />You will have to manually add a link to %s in template.html.<br />";
$LNG['a_create_page_created_user'] = "Members Custom Page:<br />You will have to manually add a link to %s in template_user.html.";
$LNG['a_create_page_avalfor'] = "Page Available for";
$LNG['a_create_page_oto'] = "Set the Page as OTO (One Time Offer) Page"; //new > v2.2.80612
$LNG['a_create_page_otoval'] = "One Time Offer Page Showing Interval"; //new > v2.2.80612
$LNG['a_create_page_ototheme'] = "Using Site Default Theme as Header and Footer"; //new > v2.2.80612
$LNG['a_create_page_otostart'] = "One Time Offer Page Start Date (leave empty to disable)"; //new > v2.2.80612
$LNG['a_create_page_otoend'] = "One Time Offer Page End Date (leave empty to disable)"; //new > v2.2.80612
$LNG['a_create_page_ototags'] = "<strong>Available One Time Offer Page Tags:</strong>"; //new > v2.2.80612
$LNG['a_create_page_ototagval'] = "&nbsp;.::&nbsp;<strong>%s</strong> =  display a link to close the page and remind it later, the page will show up in the next interval.<br />&nbsp;.::&nbsp;<strong>%s</strong> = display a link to close the page and skip it, the page will never show up again in the future."; //update > v2.2.90809
$LNG['a_create_page_otobpass'] = "<font size=1><em>One Time Offer Page Secret (by pass) URL. You can insert or add URL redirection in the <u>redir</u> query</em></font>"; //new > v2.2.80612
$LNG['a_create_page_otoitemtags'] = "<strong>Available \"Buy Now\" button Tags from eStore Items:</strong>"; //new > v2.2.80612
$LNG['a_create_page_active'] = "Page Status";
$LNG['a_create_page_dripfeed'] = "Setup the days for page drip feed"; //new > v2.2.91730
$LNG['a_create_page_dripfeedexp'] = "Page drip feed expiration in days"; //new > v2.2.91730
$LNG['a_create_page_password'] = "Setup password for the page (leave empty to disable)"; //new > v2.2.91730
$LNG['a_create_page_expdate'] = "Page expiration date (leave empty to disable)"; //new > v2.2.91730
$LNG['a_page_aval_all'] = "Public";
$LNG['a_page_aval_free'] = "Inactive or Free members";
$LNG['a_page_aval_paid'] = "All Active members";
$LNG['a_page_aval_member'] = "members";
$LNG['a_page_disabled'] = "<font color='#FF0000'><em>The page you looking for is unavailable at the moment or was removed from the server.</em></font>"; //new > v2.2.91730
$LNG['a_page_dripfeed_title'] = "Private Contents"; //new > v2.2.91730
$LNG['a_page_linkcode'] = "HTML Code Link to the page"; //new > v2.3.429
$LNG['a_page_linkcode_public'] = "<font size=1><em>Insert the html code below to your public theme files (example: menu in the template.html or template_user.html file). It will link to your custom page.</em></font>"; //new > v2.3.429
$LNG['a_page_linkcode_usercp'] = "<font size=1><em>Insert the html code below to your User CP theme files (example: menu in the users/client.html file). It will link to your custom page.</em></font>"; //new > v2.3.429

// Admin > Delete Member
$LNG['a_del_header'] = "Delete Member";
$LNG['a_del_headers'] = "Delete Members";
$LNG['a_del_done'] = "The member has been deleted.";
$LNG['a_del_dones'] = "The members have been deleted.";
$LNG['a_del_warn'] = "Are you sure you want to delete %s?";
$LNG['a_del_multi'] = "these %s members";

// Admin > Delete Transaction Histories // new > v2.1.80214
$LNG['a_del_admhistory_header'] = "Delete Transaction History";
$LNG['a_del_admhistory_done'] = "Transaction History has been deleted.";
$LNG['a_del_admhistory_warn'] = "Transaction History <b>%s</b><br />Are you sure you want to delete this transaction history?";
$LNG['a_del_admhistory_warns'] = "Are you sure you want to delete these <b>%s</b> transaction histories?";
$LNG['a_del_admhistory_invalid_id'] = "Invalid transaction history ID.  Please try again.";

// Admin > Delete Bad Word
$LNG['a_del_bad_word_header'] = "Delete Filtered Word";
$LNG['a_del_bad_word_headers'] = "Delete Filtered Words";
$LNG['a_del_bad_word_done'] = "The filtered word has been deleted.";
$LNG['a_del_bad_word_dones'] = "The filtered words have been deleted.";
$LNG['a_del_bad_word_warn'] = "Are you sure you want to delete %s from the filtered words list?";
$LNG['a_del_bad_word_multi'] = "these %s words";
$LNG['a_del_bad_word_invalid_id'] = "Invalid filtered word ID.  Please try again.";

// Admin > Delete Custom Page
$LNG['a_del_page_header'] = "Delete Custom Page";
$LNG['a_del_page_headers'] = "Delete Custom Pages";
$LNG['a_del_page_done'] = "The custom page has been deleted.";
$LNG['a_del_page_dones'] = "The custom pages have been deleted.";
$LNG['a_del_page_warn'] = "Are you sure you want to delete %s custom pages?";
$LNG['a_del_page_multi'] = "these %s";
$LNG['a_del_page_invalid_id'] = "Invalid custom page ID.  Please try again.";

// Admin > Delete Download Files
$LNG['a_del_file_header'] = "Delete File";
$LNG['a_del_file_headers'] = "Delete Files";
$LNG['a_del_file_done'] = "File has been deleted.";
$LNG['a_del_file_dones'] = "Files have been deleted.";
$LNG['a_del_file_warn'] = "Are you sure you want to delete %s?";
$LNG['a_del_file_multi'] = "these %s files";
$LNG['a_del_file_alert'] = "<font color=red><b>WARNING:</b> This process also delete related (existence) file.</font>"; // new > v2.2.80401
$LNG['a_del_file_invalid_id'] = "Invalid file ID. Please try again.";

// Admin > Delete Videos  // new > v2.2.91730
$LNG['a_del_video_header'] = "Delete Video";
$LNG['a_del_video_headers'] = "Delete Videos";
$LNG['a_del_video_done'] = "Video file has been deleted.";
$LNG['a_del_video_dones'] = " Video files have been deleted.";
$LNG['a_del_video_warn'] = "Are you sure you want to delete %s?";
$LNG['a_del_video_multi'] = "these %s video files";
$LNG['a_del_video_alert'] = "<font color=red><b>WARNING:</b> This process also delete related (existence) file.</font>";
$LNG['a_del_video_invalid_id'] = "Invalid video file ID. Please try again.";

// Admin > Delete Banners
$LNG['a_del_banner_header'] = "Delete Banner";
$LNG['a_del_banner_headers'] = "Delete Banners";
$LNG['a_del_banner_done'] = "Banner file has been deleted.";
$LNG['a_del_banner_dones'] = "Banner files have been deleted.";
$LNG['a_del_banner_warn'] = "Are you sure you want to delete %s?";
$LNG['a_del_banner_multi'] = "these <b>%s</b> banners";
$LNG['a_del_banner_invalid_id'] = "Invalid banner ID. Please try again.";

// Admin > Delete Responders
$LNG['a_del_responders_header'] = "Delete Responders";
$LNG['a_del_responders_done'] = "Responder has been deleted.";
$LNG['a_del_responders_warn'] = "Responder <b>%s</b><br />Are you sure you want to delete this responder message?";
$LNG['a_del_responders_warns'] = "Are you sure you want to delete these <b>%s</b> responder messages?";
$LNG['a_del_responders_invalid_id'] = "Invalid responder message ID.  Please try again.";

// Admin > Delete Custom Field
$LNG['a_del_fields_header'] = "Delete User Custom Fields";
$LNG['a_del_fields_done'] = "Custom field has been deleted.";
$LNG['a_del_fields_warn'] = "User Field: <b>%s</b><br />Are you sure you want to delete this custom field (and all user data belong to this field)?";
$LNG['a_del_fields_warns'] = "Are you sure you want to delete these <b>%s</b> custom fields (and all user data belong to this fields)?";
$LNG['a_del_fields_invalid_id'] = "Invalid custom field ID.  Please try again.";

// Admin > Delete Download Group //new >v2.1.80214
$LNG['a_del_dlgrup_header'] = "Delete Download Group";
$LNG['a_del_dlgrup_all'] = "Remove group ONLY (excluding members and files related to this group).<br /><font color=red>If unchecked, ALL members and files belong to this group also deleted, this process may take several minutes to be done.</font>";
$LNG['a_del_dlgrup_allconfirm'] = "Are you sure want to delete ALL members and files belong to this group?";
$LNG['a_del_dlgrup_done'] = "Download Group has been deleted.";
$LNG['a_del_dlgrup_warn'] = "Download Group <b>%s</b><br />Are you sure you want to delete this Download Group?";
$LNG['a_del_dlgrup_warns'] = "Are you sure you want to delete these <b>%s</b> Download Groups?";
$LNG['a_del_dlgrup_invalid_id'] = "Invalid Download Group ID.  Please try again.";

// Admin > Delete FAQs
$LNG['a_del_faqs_header'] = "Delete FAQ";
$LNG['a_del_faqs_done'] = "FAQ has been deleted.";
$LNG['a_del_faqs_warn'] = "FAQ <b>%s</b><br />Are you sure you want to delete this FAQ?";
$LNG['a_del_faqs_warns'] = "Are you sure you want to delete these <b>%s</b> FAQs?";
$LNG['a_del_faqs_invalid_id'] = "Invalid FAQ ID.  Please try again.";

// Admin > Delete Email in Queue // new > v2.2.80402
$LNG['a_del_trashinqueue'] = "Trash all emails in queue";
$LNG['a_del_inqueue'] = "Are you sure want to trash all emails in sending queue?";

// Admin > Delete News
$LNG['a_del_news_header'] = "Delete News";
$LNG['a_del_news_done'] = "News has been deleted.";
$LNG['a_del_news_warn'] = "News <b>%s</b><br />Are you sure you want to delete this news?";
$LNG['a_del_news_warns'] = "Are you sure you want to delete these <b>%s</b> news?";
$LNG['a_del_news_invalid_id'] = "Invalid news ID.  Please try again.";

// Admin > Delete Feedback
$LNG['a_del_fback_header'] = "Delete Feedback";
$LNG['a_del_fback_done'] = "Feedback has been deleted.";
$LNG['a_del_fback_warn'] = "Feedback <b>%s</b><br />Are you sure you want to delete this feedback?";
$LNG['a_del_fback_warns'] = "Are you sure you want to delete these <b>%s</b> feedback?";
$LNG['a_del_fback_invalid_id'] = "Invalid feedback ID.  Please try again.";

// Admin > Delete Messenger
$LNG['a_del_msgr_header'] = "Delete Messages";
$LNG['a_del_msgr_done'] = "Message has been deleted.";
$LNG['a_del_msgr_warn'] = "Message <b>%s</b><br />Are you sure you want to delete this message?";
$LNG['a_del_msgr_warns'] = "Are you sure you want to delete these <b>%s</b> message?";
$LNG['a_del_msgr_invalid_id'] = "Invalid message ID.  Please try again.";

// Admin > Delete Subscribers
$LNG['a_del_lead_header'] = "Delete Subscriber";
$LNG['a_del_lead_headers'] = "Delete Subscribers";
$LNG['a_del_lead_done'] = "Subscriber has been deleted.";
$LNG['a_del_lead_dones'] = "Subscribers have been deleted.";
$LNG['a_del_lead_warn'] = "Subscriber <b>%s</b><br />Are you sure you want to delete this subscriber?";
$LNG['a_del_lead_warns'] = "Are you sure you want to delete these <b>%s</b> subscribers?";
$LNG['a_del_lead_invalid_id'] = "Invalid lead ID.  Please try again.";

// Admin > Delete Review
$LNG['a_del_rev_header'] = "Delete Review";
$LNG['a_del_rev_headers'] = "Delete Reviews";
$LNG['a_del_rev_done'] = "The review has been deleted.";
$LNG['a_del_rev_dones'] = "The reviews have been deleted.";
$LNG['a_del_rev_warn'] = "Are you sure you want to delete this review?";
$LNG['a_del_rev_warns'] = "Are you sure you want to delete these reviews?";
$LNG['a_del_rev_invalid_id'] = "Invalid review ID.  Please try again.";

// Admin > Edit Member
$LNG['a_edit_header'] = "Edit Member";
$LNG['a_edit_site_is'] = "Account Status";
$LNG['a_edit_active'] = "Active";
$LNG['a_edit_inactive'] = "Inactive or Free";
$LNG['a_edit_pending'] = "Pending"; //new
$LNG['a_edit_blocked'] = "Blocked";
$LNG['a_edit_expired'] = "Expired";
$LNG['a_edit_suspended'] = "Suspended"; //new > v2.2.91730
$LNG['a_edit_edited'] = "The member has been edited.";
$LNG['a_edit_adminfo'] = "Member Info";
$LNG['a_edit_taglabel'] = "Member Tag or Label"; //new > v2.2.91730

// Admin > View Member
$LNG['a_view_header'] = "View Member Details";
$LNG['a_view_autologin'] = "Login to member area"; //new > v2.1.80214
$LNG['a_view_adminfo'] = "Admin Note About this Member";
//new > v2.2.91730
$LNG['a_view_usrpp_reg'] = "Register member to this payplan";
$LNG['a_view_usrpp_act'] = "Register member to this payplan and activated";
$LNG['a_view_usrpp_view'] = "View member payplan";
$LNG['a_view_usrpp_edit'] = "Edit member payplan";
$LNG['a_view_usrpp_del'] = "Delete member payplan";
$LNG['a_view_confirm_usrpp_reg'] = "Are you sure you want to register the payplan for:\\r\\n &rarr; Member: %s\\r\\n &rarr; Membership or PayPlan: %s";
$LNG['a_view_confirm_usrpp_act'] = "Are you sure you want to register and activate the payplan for:\\r\\n &rarr; Member: %s\\r\\n &rarr; Membership or PayPlan: %s";
$LNG['a_view_confirm_usrpp_del'] = "Are you sure you want to remove the member payplan?\\r\\n &rarr; Member: %s\\r\\n &rarr; Membership or PayPlan: %s";

// Admin > Tree Member
$LNG['a_tree_header'] = "Member Genealogy";

// Admin > Add New Member //new
$LNG['a_add_header'] = "Insert New Member";
$LNG['a_add_upliner'] = "Upline Details";
$LNG['a_add_member'] = "Member Details";
$LNG['a_add_nopay'] = "Add new member WITHOUT generate the transactions (commission, reward, etc)";
$LNG['a_add_gotoself'] = "Insert new member and back to this page";
$LNG['a_add_gotoedit'] = "Insert new member and go to member Edit Page";
$LNG['a_add_button'] = "Add";
$LNG['a_add_error_ref'] = "Referrer username not found.";
$LNG['a_add_error_spr'] = "Sponsor username not found.";
// add bulk member //new > v2.2.80612
$LNG['a_addmbr_header'] = "Add New Members";
$LNG['a_addmbr_legend'] = "Add New Member";
$LNG['a_addmbr_exist'] = "The email address (%s) already exist";
$LNG['a_addmbr_email'] = "Send <a href='index.php?a=admin&amp;b=tpl_emails&amp;f=c3Vic2NyaWJlcl93ZWxjb21lX2VtYWls'>Welcome Email</a> to this Member";
$LNG['a_addmbr_done'] = "New Member Added";
$LNG['a_addmbr_import'] = "Import New Members";
$LNG['a_addmbr_import_info'] = "Insert your member list into the form below, one member for one line.";
$LNG['a_addmbr_import_example'] = "<strong>Example:</strong><br /><strong><font color=navy>#</font>; <font color=red>Full Name</font>; <font color=red>Email Address</font>; <font color=red>Username</font>; <font color=red>Password</font>; <font color=red>PayPlan ID</font>; Country ID; Referrer Username; Sponsor Username</strong><br /><br /><font face='Courier New, Courier, monospace' size=1>1; Sample Name; email@domainname.com; samnam; pass123; 1; ID;;<br />2; Cute Name; mailbox@sitename.com; cutnam; abc123; 1; US; samnam; samnam<br />5; First Name; address@emailbox.com; finam; xxx123; 2; FR; samnam; cutnam<br />9; Lead Name; address@yoursite.com; lenam; pass001; 1; US; cutnam; cutnam<br />14; Nice Name; email@email.com; nicnam; password; 2; NG; cutnam; lenam<br /><br />Data will inserted based on <font color=navy>#</font> ascending order.</font><br />"; //update >v2.2.91730
$LNG['a_addmbr_import_separator'] = "Field Separator";
$LNG['a_addmbr_import_submit'] = "Start Import Members";

// Admin > Company Members Genealogy //new
$LNG['a_genea_header'] = "Company Members Genealogy";
$LNG['a_genea_generated'] = "Show Genealogy";
$LNG['a_genea_payplan'] = "Membership Name";
$LNG['a_genea_limit_width'] = "Limit Tree Width";
$LNG['a_genea_limit_deep'] = "Limit Tree Deep";
$LNG['a_genea_username'] = "Starting from Username";
$LNG['a_genea_show_status'] = "Show Members with Status";
$LNG['a_genea_status_active'] = "Active Only";
$LNG['a_genea_status_all'] = "Active and Inactive";
$LNG['a_genea_admindirref'] = "Administrator Direct Referral";

// Admin > Edit Bad Word
$LNG['a_edit_bad_word_header'] = "Edit Filtered Word";
$LNG['a_edit_bad_word_edited'] = "The filtered word has been edited.";

// Admin > Edit Custom Page
$LNG['a_edit_page_header'] = "Edit Custom Page";
$LNG['a_edit_page_content'] = "Content - You can use HTML here";
$LNG['a_edit_page_edited'] = "The page has been edited.";

// Admin > Edit Review
$LNG['a_edit_rev_header'] = "Edit Review";
$LNG['a_edit_rev_edited'] = "The review has been edited.";

// Admin > Responder Group //new > v2.2.90809
$LNG['a_respogrup_header'] = "Manage Responders Group";
$LNG['a_respogrup_tb_title'] = "Group Name";
$LNG['a_respogrup_tb_responder'] = "Responder";
$LNG['a_respogrup_title_form'] = "Create/Update Responders Group";
$LNG['a_respogrup_button_create'] = "Create Responders Group";
$LNG['a_respogrup_gorder'] = "Sorting Order";
$LNG['a_respogrup_adminfo'] = "Description";
$LNG['a_respogrup_status'] = "Status";
$LNG['a_respogrup_status0'] = "Disable";
$LNG['a_respogrup_status1'] = "Enable";
$LNG['a_respogrup_update_dayout'] = "Update Day Out"; //new > 2.2.91730
$LNG['a_respogrup_sortupdate'] = "Update Sorting Order"; // new > v2.3.450

// Admin > Responder
$LNG['a_responders_header'] = "Manage Responders";
$LNG['a_responders_tb_subject'] = "Subject";
$LNG['a_responders_message'] = "Message";
$LNG['a_responders_tb_type'] = "Send To";
$LNG['a_responders_tb_date'] = "Entry";
$LNG['a_responders_tb_dayout'] = "Day Out";
$LNG['a_responders_title_form'] = "Create/Update Responder";
$LNG['a_responders_button_create'] = "Create News";
$LNG['a_responders_fromto'] = "From-To";
$LNG['a_responders_dayout'] = "Message send after registration date (in days)";
$LNG['a_responders_status'] = "Responder Status";
$LNG['a_responders_status0'] = "Disabled";
$LNG['a_responders_status1'] = "Send to Members (All)";
$LNG['a_responders_status2'] = "Send to Subscribers";
$LNG['a_responders_status3'] = "Send to All (Members and Subscribers)";
$LNG['a_responders_status4'] = "Send to Members (Active Only)"; //new > v2.2.80401
$LNG['a_responders_status5'] = "Send to Members (Inactive or Free only)"; //new > v2.2.80401
$LNG['a_responders_status1a'] = "Members (All)";
$LNG['a_responders_status2a'] = "Subscribers";
$LNG['a_responders_status3a'] = "All (Members and Subscribers)";
$LNG['a_responders_status4a'] = "Members (Active)"; //new > v2.2.80401
$LNG['a_responders_status5a'] = "Members (Inactive or Free)"; //new > v2.2.80401
$LNG['a_responders_group'] = "Responder Group (<a href='index.php?a=admin&amp;b=responder_group'>manage</a>)"; //new > v2.2.90809
$LNG['a_responders_tag'] = "Available Tags:<br /><b>%s</b>";
$LNG['a_responders_tag_note'] = "<br /><b>%s</b> tags are available for members only and <b>%s</b> tags are available for subscribers only."; //update
$LNG['a_responders_estore_tag_note'] = "<br /><b>%s</b> tags are available for customers only (from eStore plug-in)."; //new > v2.2.90809

// Admin > Referral Contest / Race //new > v2.2.91730
$LNG['a_refrace_header'] = "Manage Referrer Contest or Race";
$LNG['a_refrace_tb_title'] = "Contest Title";
$LNG['a_refrace_tb_startdate'] = "Start Date";
$LNG['a_refrace_tb_enddateref'] = "End Date / Max Referrals";
$LNG['a_refrace_tb_unlimited'] = "Unlimited";
$LNG['a_refrace_legend'] = "Create or Update Referrer Contest";
$LNG['a_refrace_rrtitle'] = "Contest Title";
$LNG['a_refrace_rrdatestart'] = "Start Date";
$LNG['a_refrace_rrdateend'] = "End Date";
$LNG['a_refrace_rrmaxref'] = "Max Referrers to Win Contest";
$LNG['a_refrace_rrrwdlist'] = "Rewards List";
$LNG['a_refrace_rrpplan'] = "Available for PayPlan";
$LNG['a_refrace_rrbanuname'] = "Exclude Member Username";
$LNG['a_refrace_rrcmgen'] = "Generate Contest Reward Automatically";
$LNG['a_refrace_rrstatus'] = "Referrer Contest Status";
$LNG['a_refrace_rrstatus0'] = "Stopped";
$LNG['a_refrace_rrstatus1'] = "Running";
$LNG['a_refrace_rrstatus2'] = "Closed";
$LNG['a_refrace_closedate'] = "Closed Date";
$LNG['a_refrace_winnerlist'] = "The Winners";
$LNG['a_refrace_button_create'] = "Create New Contest";
$LNG['a_refrace_error_rrtitle'] = "Please enter the Referrer Contest title";
$LNG['a_refrace_error_rrdatestart'] = "Please setup the date where Contest begin";

// Admin > Delete Referral Contest //new > v2.2.91730
$LNG['a_del_refrace_header'] = "Delete Referrer Contest or Race";
$LNG['a_del_refrace_done'] = "Referrer Contest or Race has been deleted.";
$LNG['a_del_refrace_warn'] = "Referrer Contest or Race: <b>%s</b><br />Are you sure you want to delete this referrer contest?";
$LNG['a_del_refrace_warns'] = "Are you sure you want to delete these <b>%s</b> referrer contests?";
$LNG['a_del_refrace_invalid_id'] = "Invalid Referrer Contest ID. Please try again.";

// Admin > Manage GiftPass //new > v2.2.91730
$LNG['a_giftpass_header'] = "Manage GiftPass (Free Registration/Upgrade/Renew Coupon Code)";
$LNG['a_giftpass_tb_title'] = "GiftPass Code";
$LNG['a_giftpass_tb_startdate'] = "Start Date";
$LNG['a_giftpass_tb_enddateref'] = "End Date / Available";
$LNG['a_giftpass_tb_nodateset'] = "<font color='#999999'>[ none ]</font>";
$LNG['a_giftpass_tb_unlimited'] = "Unlimited";
$LNG['a_giftpass_legend'] = "Generate GiftPass Code (ePin)";
$LNG['a_giftpass_datestart'] = "Start Date";
$LNG['a_giftpass_dateend'] = "End Date";
$LNG['a_giftpass_maxuse'] = "Max GiftPass Available";
$LNG['a_giftpass_idref'] = "Referrer Id";
$LNG['a_giftpass_value'] = "GiftPass Value";
$LNG['a_giftpass_generatedfor'] = "How Many GiftPass Will Generated?";
$LNG['a_giftpass_codeformat'] = "GiftPass Code Format";
$LNG['a_giftpass_status'] = "GiftPass Status";
$LNG['a_giftpass_status0'] = "Inactive";
$LNG['a_giftpass_status1'] = "Active";
$LNG['a_giftpass_status2'] = "Used";
$LNG['a_giftpass_tostatus'] = "Update Status to:";
$LNG['a_giftpass_useddate'] = "Last Used Date";
$LNG['a_giftpass_usedbyid'] = "Used by Member Id";
$LNG['a_giftpass_button_create'] = "Generate New GiftPass";
$LNG['a_giftpass_error_codeformat'] = "Please enter the GiftPass Code format";
$LNG['a_giftpass_error_datestart'] = "Please setup the date where GiftPass will be available to use";
$LNG['a_giftpass_disabled'] = "GiftPass feature disabled, to enabled this feature please check the option in the <a href=index.php?a=admin&b=settings>General Settings</a> page";

// Admin > Delete GiftPass //new > v2.2.91730
$LNG['a_del_giftpass_header'] = "Delete GiftPass";
$LNG['a_del_giftpass_done'] = "GiftPass Code(s) has been deleted.";
$LNG['a_del_giftpass_warn'] = "GiftPassCode: <b>%s</b><br />Are you sure want to delete this code?";
$LNG['a_del_giftpass_warns'] = "Are you sure you want to delete these <b>%s</b> GiftPass Codes?";
$LNG['a_del_giftpass_invalid_id'] = "Invalid GiftPass Id. Please try again.";

// Admin > Custom Fields
$LNG['a_fields_header'] = "Manage User Custom Fields";
$LNG['a_fields_tb_txt'] = "Text (will be shown near of form field)";
$LNG['a_fields_tb_frm'] = "Field";
$LNG['a_fields_tb_order'] = "Order Priority";
$LNG['a_fields_title_form'] = "Create/Update Custom Fields";
$LNG['a_fields_button_create'] = "Create New Field";
$LNG['a_fields_txterror'] = "Error Text (will be shown when field empty)";
$LNG['a_fields_frmtype'] = "Field Type";
$LNG['a_fields_options'] = "Options (available for SELECT field type only)";
$LNG['a_fields_txtsize'] = "Character Size (available for TEXT and SELECT field type only)";
$LNG['a_fields_required'] = "Required Field";
$LNG['a_fields_myregex'] = "Field Validation Simple Regular Expression (optional)"; // new > v2.2.80612
$LNG['a_fields_avalfor'] = "Available For"; // new > v2.2.80612
$LNG['a_fields_signup'] = "Available on Registration Form";
$LNG['a_fields_pubview'] = "Visible for sponsor or referral"; // new > v2.2.80612
$LNG['a_fields_frmlock'] = "Enable field lock"; // new > v2.2.91730
$LNG['a_fields_frmorder'] = "Field Order";
$LNG['a_fields_errorfrm'] = "Field name not recognized, please use another field name";
$LNG['a_fields_errortxt'] = "Text cannot empty";
$LNG['a_fields_usrlock'] = "Click to UNLOCK member fields in the User CP"; // new > v2.2.91730
$LNG['a_fields_usrunlock'] = "Click to LOCK member fields in the User CP"; // new > v2.2.91730
$LNG['a_fields_examplecode'] = "Html code example:"; // new > v2.2.450

// Admin > Download Group //new > v2.1.80214
$LNG['a_dlgrup_header'] = "Manage Download Group";
$LNG['a_dlgrup_tb_title'] = "Group Name";
$LNG['a_dlgrup_tb_files'] = "Files";
$LNG['a_dlgrup_tb_members'] = "Members";
$LNG['a_dlgrup_tb_expired'] = "Exp Days"; // new > v2.2.80401
$LNG['a_dlgrup_title_form'] = "Create/Update Download Group";
$LNG['a_dlgrup_button_create'] = "Create Download Group";
$LNG['a_dlgrup_expired'] = "Expired after (in days)"; // new > v2.2.80401
$LNG['a_dlgrup_gorder'] = "Sorting Order"; // new > v2.2.80612
$LNG['a_dlgrup_adminfo'] = "Description";
$LNG['a_dlgrup_status'] = "Status";
$LNG['a_dlgrup_status0'] = "Disable";
$LNG['a_dlgrup_status1'] = "Enable";
$LNG['a_dlgrup_status2'] = "Enable (based on files availability)"; // new > v2.2.80612
$LNG['a_dlgrup_sortupdate'] = "Update Sorting Order"; // new > v2.3.450

// Admin > FAQs
$LNG['a_faqs_header'] = "Manage FAQs";
$LNG['a_faqs_tb_title'] = "Question";
$LNG['a_faqs_tb_type'] = "Sorting Order";
$LNG['a_faqs_tb_date'] = "Entry";
$LNG['a_faqs_tb_date_update'] = "Update";
$LNG['a_faqs_title_form'] = "Create/Update FAQ";
$LNG['a_faqs_button_create'] = "Create FAQ";
$LNG['a_faqs_fromto'] = "From-To";
$LNG['a_faqs_emtype'] = "Email Type";
$LNG['a_faqs_status'] = "Status";
$LNG['a_faqs_status0'] = "Hidden";
$LNG['a_faqs_status1'] = "Available";
$LNG['a_faqs_content'] = "Answer";
$LNG['a_faqs_sortupdate'] = "Update Sorting Order"; // new > v2.3.443

// Admin > News
$LNG['a_news_header'] = "Manage News";
$LNG['a_news_tb_title'] = "Title";
$LNG['a_news_tb_type'] = "Type";
$LNG['a_news_tb_date'] = "Entry";
$LNG['a_news_tb_date_update'] = "Update";
$LNG['a_news_title_form'] = "Create/Update News";
$LNG['a_news_button_create'] = "Create News";
$LNG['a_news_fromto'] = "From-To";
$LNG['a_news_emtype'] = "Email Type";
$LNG['a_news_status'] = "Status";
$LNG['a_news_status0'] = "Hidden";
$LNG['a_news_status1'] = "Available";
$LNG['a_news_status2'] = "Private";
$LNG['a_news_type'] = "News Type";
$LNG['a_news_type0'] = "All";
$LNG['a_news_type1'] = "Site News";
$LNG['a_news_type2'] = "Newsletter";
$LNG['a_news_type3'] = "SMS";
$LNG['a_news_content_text'] = "Text Content / SMS";
$LNG['a_news_content'] = "HTML Content / News";
$LNG['a_news_tag'] = "Available Tags:<br /><b>%s</b>";
$LNG['a_news_tag_note'] = "<br /><b>%s</b> tags are available for members only";
$LNG['a_news_tag_note1'] = " and <b>%s</b> tags available for subscribers only"; // new > v2.2.90809

// Admin > Feedbacks
$LNG['a_fback_header'] = "Manage Feedback";
$LNG['a_fback_tb_subject'] = "Subject";
$LNG['a_fback_tb_type'] = "Type";
$LNG['a_fback_tb_date'] = "Entry";
$LNG['a_fback_title_form'] = "Reply Feedback";
$LNG['a_fback_button_create'] = "Create Feedback";
$LNG['a_fback_isread'] = "Already Opened";
$LNG['a_fback_type'] = "Feedback Type";
$LNG['a_fback_type0'] = "Testimonial";
$LNG['a_fback_type1'] = "Normal";
$LNG['a_fback_type2'] = "Important";
$LNG['a_fback_type3'] = "Urgent";
$LNG['a_fback_content'] = "Feedback Content";
$LNG['a_fback_archive'] = "Latest Feedback Archive";

// Admin > Messanger
$LNG['a_msgr_header'] = "Manage Messages";
$LNG['a_msgr_tb_subject'] = "Subject";
$LNG['a_msgr_tb_type'] = "Type";
$LNG['a_msgr_tb_date'] = "Entry";
$LNG['a_msgr_title_form'] = "Message";
$LNG['a_msgr_button_create'] = "Create Message";
$LNG['a_msgr_isread'] = "Already Opened";
$LNG['a_msgr_type'] = "Message Type";
$LNG['a_msgr_type0'] = "Testimonial";
$LNG['a_msgr_type1'] = "Normal";
$LNG['a_msgr_type2'] = "Important";
$LNG['a_msgr_type3'] = "Urgent";
$LNG['a_msgr_content'] = "Message Content";
$LNG['a_msgr_sendemail'] = "Also send as Email";
$LNG['a_msgr_disabled'] = "<font color='red'>Messenger System has been disabled by Administrator</font>";

// Admin > Store Settings  //new > v2.2.91730
$LNG['a_store_header'] = "Store Settings";
$LNG['a_store_general'] = "General Settings"; //update > v2.3.442
$LNG['a_store_advance'] = "Advance Settings"; //new > v2.3.442
$LNG['a_store_onapi'] = "API Status";
$LNG['a_store_orderdelay'] = "Delay between the same order (in seconds)";
$LNG['a_store_defraud'] = "Fraud Control Type";
$LNG['a_store_defraud_type0'] = "None (disable)";
$LNG['a_store_defraud_type1'] = "Transaction Number";
$LNG['a_store_defraud_type2'] = "Time Delay";
$LNG['a_store_affiliate'] = "Affiliate Program"; //new > v2.3.450
$LNG['a_store_affiliateban'] = "Username Banned from Affiliate Program (separated with comma)"; //new > v2.3.450
$LNG['a_store_defaultaff'] = "New Member Affiliate Status"; //new > v2.3.450
$LNG['a_store_cursymb'] = "Currency Symbol (<strong>$</strong>, <strong>Rp.</strong> <em>&amp;euro;</em> or <strong>&euro;</strong>, <em>&amp;yen;</em> or <strong>&yen;</strong>,, etc.)";
$LNG['a_store_curcode'] = "Currency Code (USD, IDR, EUR, JPY, etc.)";
$LNG['a_store_serviceopt'] = "Service Status";
$LNG['a_store_otheropt'] = "Shipping Status";
$LNG['a_store_defcommissionlist'] = "Default Commission List";
$LNG['a_store_defbuyercommission'] = "Default Buyer Commission";
$LNG['a_store_defxupcommissionlist'] = "Default X-Up Level # and Commission List";
$LNG['a_store_deftaxlist'] = "Default VAT List";
$LNG['a_store_defsnhlist'] = "Default S&amp;H List";
$LNG['a_store_refcmtracking'] = "Commission Tracking Rule"; //new > v2.3.406
$LNG['a_store_refcmtrackingospr'] = "Using original buyer sponsor (if exist)"; //new > v2.3.406
$LNG['a_store_refcmtrackinglink'] = "Using referrer from referral link (affiliate url)"; //new > v2.3.406
$LNG['a_store_setautoinc'] = "Start Sales ID from (default from ID=1)"; //new > v2.3.442
$LNG['a_store_license'] = "eStore License Key"; //new > v2.3.442

// Admin > Invitation - Tell A Friend //new
$LNG['a_invite_header'] = "Invitation - Tell A Friend";
$LNG['a_invite_legend'] = "Setting and Templates";
$LNG['a_invite_limit'] = "Generate and Send to";
$LNG['a_invite_disable'] = "Feature is Disabled";
$LNG['a_invite_ttf'] = "Name and Email form";
$LNG['a_invite_top'] = "Header Template (Plain Text)";
$LNG['a_invite_subject'] = "Default Subject (<em>editable by member</em>)"; //new > v2.3.422
$LNG['a_invite_content'] = "Default Message (Plain Text, <em>editable by member</em>)"; //new > v2.2.91730
$LNG['a_invite_message'] = "Message Content (Plain Text)"; //new > v2.3.422
$LNG['a_invite_bottom'] = "Footer Template (Plain Text)";
$LNG['a_invite_tags'] = "Available tags:";
$LNG['a_invite_rectags'] = "Recipient tags:"; //new > v2.3.422

// Admin > PHP Configuration //new > v2.1.80214
$LNG['a_phpconf_header'] = "PHP Configuration";

// Admin > Site Stats //new > v2.2.80612
$LNG['a_sstats_header'] = "Site Statistics";
$LNG['a_sstats_tb_date'] = "Date";
$LNG['a_sstats_tb_time'] = "Time";
$LNG['a_sstats_tb_ref'] = "User";
$LNG['a_sstats_tb_ip'] = "IP";
$LNG['a_sstats_tb_from'] = "Referrer";
$LNG['a_sstats_tb_to'] = "Destination";
$LNG['a_sstats_type'] = "Type";
$LNG['a_sstats_value'] = "ID";
$LNG['a_sstats_track'] = "Track";
$LNG['a_sstats_agent'] = "Browser Agent";
$LNG['a_sstats_os'] = "Operating System";
$LNG['a_sstats_track_visit'] = "Visit";
$LNG['a_sstats_track_login'] = "Login";
$LNG['a_sstats_track_download'] = "Download";
$LNG['a_sstats_refgroup'] = "Group by referrer";

// Admin > Subscribers
$LNG['a_lead_header'] = "Subscribers List";
$LNG['a_lead_tb_name'] = "Name";
$LNG['a_lead_tb_email'] = "Email";
$LNG['a_lead_tb_phone'] = "Phone #"; //new > v2.2.80401
$LNG['a_lead_tb_date'] = "Entry";
$LNG['a_lead_title_form'] = "Manage Subscriber";
$LNG['a_lead_type'] = "Email Type";
$LNG['a_lead_out'] = "Latest Sent";
$LNG['a_lead_sys'] = "OS";
$LNG['a_lead_agent'] = "User Agent";
$LNG['a_lead_ip'] = "IP Address";
$LNG['a_lead_latest'] = "Latest Subscribers"; //new > v2.2.80401
$LNG['a_lead_refun'] = "Sponsor Username"; //update > v2.2.91730
$LNG['a_lead_text'] = "Leads"; //new > v2.2.80612
$LNG['a_lead_unsubscribe'] = "<font color='#CC0000'>Unsubscribe</font>"; //update > v2.2.91730

// add subscriber //new
$LNG['a_addlead_header'] = "Add New Subscribers";
$LNG['a_addlead_legend'] = "Add New Subscriber";
$LNG['a_addlead_exist'] = "The email address (%s) already exist";
$LNG['a_addlead_email'] = "Send <a href='index.php?a=admin&amp;b=tpl_emails&amp;f=c3Vic2NyaWJlcl93ZWxjb21lX2VtYWls'>Welcome Email</a> to this Subscriber"; //update > v2.2.80612
$LNG['a_addlead_done'] = "New Subscriber Added";
$LNG['a_addlead_import'] = "Import New Subscribers"; //new > v2.2.80401
$LNG['a_addlead_import_info'] = "Insert your subscriber list into the form below, one subscriber for one line."; //new > v2.2.80401
$LNG['a_addlead_import_example'] = "<strong>Example:</strong><br /><strong><font color=red>Full Name</font>; <font color=red>Email Address</font>; Phone Number; Format Email; Sponsor ID; User OS; Browser Agent; IP Address</strong><br /><br /><font face='Courier New, Courier, monospace' size=1>Sample Name; email@domainname.com; 06234-100-222; Text;;; Internet Explorer; 23.234.111.34<br />Cute Name; mailbox@sitename.com;; Html; 22; Mac OS; Safari; 127.123.234.34<br />First Name; address@emailbox.com; 89001-1234; Text; 1; Windows Vista; Firefox 2.0; 127.123.234.34<br />Lead Name; address@yoursite.com;; Both; 3; Windows XP; Firefox 1.0;<br />Nice Name; email@email.com; 01456-789-001; Html;;; Firefox 2.0; 127.00.001.1<br /><br />Format Email = [<font color=red>Text</font>|<font color=red>Html</font>|<font color=red>Both</font>], default = Text</font><br />"; //new > v2.2.80401
$LNG['a_addlead_import_separator'] = "Field Separator"; //new > v2.2.80401
$LNG['a_addlead_import_submit'] = "Start Import Subscribers"; //new > v2.2.80401
// remove subscriber in batch //new > v2.2.80612
$LNG['a_leads_remove'] = "Remove Subscribers";
$LNG['a_leads_removedone'] = "%s lead(s) removed from database."; //new > v2.2.91730
$LNG['a_leads_remove_info'] = "Insert subscriber emails that you want to remove or delete into the form below, separated with Field Separator.";
$LNG['a_leads_remove_example'] = "<strong>Example:</strong><br /><strong>Email_Address_1; Email_Address_2; Email_Address_3; Email_Address_4</strong><br /><br /><font face='Courier New, Courier, monospace' size=1>email@domainname.com; mailbox@sitename.com; address@emailbox.com; address@yoursite.com; email@email.com</font><br /><br /><strong><font color=red>WARNING!</font></strong> This process CANNOT be undone.<br />";
$LNG['a_leads_remove_separator'] = "Field Separator";
$LNG['a_leads_remove_warning'] = "Are you sure want to remove these email list?";
$LNG['a_leads_remove_submit'] = "Start Remove Subscribers";

// Admin > Newsletters
$LNG['a_email_header'] = "Newsletters";
$LNG['a_email_title_form'] = "Manage Newsletters";
$LNG['a_email_archive'] = "Newsletters Archive";
$LNG['a_email_member_status'] = "Members Status";
$LNG['a_email_subject'] = "Subject";
$LNG['a_email_priority'] = "Message Priority"; //new > v2.1.80214
$LNG['a_email_message'] = "Message (Plain Text)"; //update > v2.1.80214
$LNG['a_email_htmlmessage'] = "Message (HTML)"; //new > v2.1.80214
$LNG['a_email_asbatch'] = "Sending in Batch (about <a href='index.php?a=admin&amp;b=settings#Other Settings'>%s</a> emails per hours, recommended for shared or slow server)";
$LNG['a_email_asnews'] = "Also save as News - {$LNG['a_email_htmlmessage']} only."; //update > v2.1.80214
$LNG['a_email_datesend'] = "Send at a Specific Date (empty to disable)"; //new > v2.2.90809
$LNG['a_email_button'] = "Start Sending";
$LNG['a_email_testbutton'] = "Test Sending"; //new > v2.2.90809
$LNG['a_email_to_all'] = "All (Members and Subscribers)";
$LNG['a_email_to_allmbr'] = "All Members";
$LNG['a_email_to_leads'] = "Subscribers";
$LNG['a_email_msg_sent'] = "An email has been sent to %s";
$LNG['a_email_not_sent'] = "An email couldn't be sent to %s";
$LNG['a_email_queue'] = "Prepare to sending %s email(s) done.";
$LNG['a_email_sent'] = "%s members were emailed.";
$LNG['a_email_failed'] = "%s members were not emailed.";
$LNG['a_email_sendat'] = "Newsletter scheduled send at %s"; //new > v2.2.90809
$LNG['a_email_link_manage'] = "Click <a href='%s'>here</a> to manage Newsletters, SMS archive and News";

// Admin > SMS Sender //new > v2.2.901730
$LNG['a_smser_header'] = "SMS Sender";
$LNG['a_smser_title_form'] = "Manage SMS";
$LNG['a_smser_archive'] = "SMS Archive";
$LNG['a_smser_member_status'] = "Members Status";
$LNG['a_smser_priority'] = "Message Priority";
$LNG['a_smser_message'] = "Message Content";
$LNG['a_smser_asbatch'] = "Sending in Batch (about <a href='index.php?a=admin&amp;b=settings#Other Settings'>%s</a> sms per hours)";
$LNG['a_smser_datesend'] = "Send at a Specific Date (empty to disable)";
$LNG['a_smser_button'] = "Start Sending";
$LNG['a_smser_testbutton'] = "Test Sending";
$LNG['a_smser_to_all'] = "All (Members and Subscribers)";
$LNG['a_smser_to_allmbr'] = "All Members";
$LNG['a_smser_to_leads'] = "Subscribers";
$LNG['a_smser_msg_sent'] = "Message has been sent to %s";
$LNG['a_smser_not_sent'] = "Message couldn't be sent to %s";
$LNG['a_smser_queue'] = "Prepare to sending %s sms message(s) done.";
$LNG['a_smser_sent'] = "%s members were smsed.";
$LNG['a_smser_failed'] = "%s members were not smsed.";
$LNG['a_smser_sendat'] = "SMS messages scheduled send at %s";
$LNG['a_smser_link_manage'] = "Click <a href='%s'>here</a> to manage Newsletters, SMS archive and News";

// Admin > Email Template
$LNG['a_tpl_email_header'] = "Manage Email and SMS Templates"; //update > v2.2.91730
$LNG['a_tpl_email_subject'] = "Subject";
$LNG['a_tpl_email_body'] = "Body Plain Text";
$LNG['a_tpl_email_bodyhtml'] = "Body HTML";
$LNG['a_tpl_email_bodysms'] = "SMS Message"; //new > v2.2.91730
$LNG['a_tpl_email_managesms'] = "Manage SMS Content"; //new > v2.2.91730
$LNG['a_tpl_email_list'] = "Please select an email or sms template to update:"; //update > v2.2.91730
$LNG['a_tpl_email_manage'] = "Manage Email Template";
$LNG['a_tpl_email_load'] = "Load";
$LNG['a_tpl_email_reset'] = "Reset";
$LNG['a_tpl_email_manage_done'] = "Manage email template %s done.";
$LNG['a_tpl_email_manage_err'] = "Manage email template %s failed.";

// Admin > Page Template
$LNG['a_tpl_page_header'] = "Manage Page Templates";
$LNG['a_tpl_page_content'] = "Content";
$LNG['a_tpl_page_list'] = "Please select a page template to update:";
$LNG['a_tpl_page_path'] = "File path location:"; //new > v2.2.80401
$LNG['a_tpl_page_manage'] = "Manage Page Template";
$LNG['a_tpl_page_load'] = "Load";
$LNG['a_tpl_page_reset'] = "Reset";
$LNG['a_tpl_page_manage_done'] = "Manage page template %s done.";
$LNG['a_tpl_page_manage_err'] = "Manage page template %s failed.";
$LNG['a_tpl_page_mainmenu'] = "Main Template Page";
$LNG['a_tpl_page_adminmenu'] = "Admin Template Page";
$LNG['a_tpl_page_membermenu'] = "Member Template Page";
$LNG['a_tpl_page_frontmenu'] = "Main Page"; //new > v2.1.80214
$LNG['a_tpl_page_detailsmenu'] = "Details Page";
$LNG['a_tpl_page_squeeze'] = "Squeeze Page"; //new > v2.2.80401
$LNG['a_tpl_page_squeezeform'] = "Squeeze Page Form"; //new > v2.2.80612
$LNG['a_tpl_page_css'] = "CSS Style File";
$LNG['a_tpl_page_clientmenu'] = "Client Area";
$LNG['a_tpl_page_lngmenu'] = "Language File";
$LNG['a_tpl_page_countrylist'] = "Country List File";
$LNG['a_tpl_page_subheader'] = "Sub Header File";
$LNG['a_tpl_page_testimonial'] = "Testimonials Page"; //new > v2.2.80401
$LNG['a_tpl_page_terms'] = "Terms and Conditions File"; //new > v2.2.80401

// Admin > Logout
$LNG['a_logout_message'] = "You are now logged out of the admin.";

// Admin > Main
$LNG['a_header'] = "Admin CP";
$LNG['a_main'] = "Welcome to the Admin Control Panel. Use the links to the left to manage your site.";
$LNG['a_main_approve'] = "There is 1 inactive or free user.";
$LNG['a_main_approves'] = "There are %s inactive or free users.";
$LNG['a_main_approve_rev'] = "There is 1 review waiting to be approved.";
$LNG['a_main_approve_revs'] = "There are %s reviews waiting to be approved.";
$LNG['a_main_your'] = "EzyGold installation version";
$LNG['a_main_latest'] = "EzyGold latest release";
$LNG['a_main_new'] = "Visit <a href='http://www.ezygold.com/' title='www.EzyGold.com' target='_blank'>EzyGold.com</a> website";
$LNG['a_main_summember'] = "Total Members";
$LNG['a_main_sumvisitor'] = "Total Visitor";
$LNG['a_main_phpversion'] = "PHP version";
$LNG['a_main_localip'] = "Your IP Address";
$LNG['a_main_serverip'] = "Server IP Address";
$LNG['a_main_docroot'] = "Document root path";
$LNG['a_main_latestactive'] = "Latest 10 New Members"; //update
$LNG['a_main_topmost'] = "Top Most Members"; //new > v2.1.80214
$LNG['a_main_basedon'] = "Based on"; //new > v2.1.80214
$LNG['a_main_dlinerbased'] = "Total Direct Referrals"; //new > v2.1.80214
$LNG['a_main_hitsbased'] = "Referral Site Hits"; //new > v2.1.80214
$LNG['a_main_salesbased'] = "Best Sellers"; //new > v2.1.80214
$LNG['a_main_toppos1'] = "Position 1"; //new > v2.1.80214
$LNG['a_main_toppos2'] = "Position 2"; //new > v2.1.80214
$LNG['a_main_toppos3'] = "Position 3"; //new > v2.1.80214
$LNG['a_main_must_wysiwyg'] = "You must be on WYSIWYG mode!"; //new > v2.2.80612
$LNG['a_main_sidebar_open'] = "Open Sidebar Menu"; //new > v2.2.91730
$LNG['a_main_sidebar_close'] = "Close Sidebar Menu"; //new > v2.2.91730

// Admin > Manage Members
$LNG['a_man_header'] = "Manage Members";
$LNG['a_man_view'] = "Details";
$LNG['a_man_clone'] = "Duplicate"; //new
$LNG['a_man_edit'] = "Edit";
$LNG['a_man_delete'] = "Delete";
$LNG['a_man_email'] = "Email";
$LNG['a_man_all'] = "Select All";
$LNG['a_man_none'] = "Select None";
$LNG['a_man_del_sel'] = "Delete Selected";
$LNG['a_man_force_logout'] = "Force Logout"; //new
$LNG['a_man_renewlink'] = "Renew maintenance date only"; //new > v2.2.80612
$LNG['a_man_renewxtdlink'] = "Renew and generate commissions"; //new > v2.2.80612
$LNG['a_man_renewpending'] = "Approve manual Maintenance Fee payment for this member"; //new
$LNG['a_man_renewpending_xtd'] = "Approve manual Maintenance Fee payment and generate available commissions for this member"; //new > v2.2.80612
$LNG['a_man_showpending'] = "Show Pending only"; //new > v2.2.80612
$LNG['a_man_shownopayplan'] = "Show members without PayPlan"; //new > v2.2.91730
$LNG['a_man_showwith'] = "Show members with "; //new > v2.2.91730
$LNG['a_man_unconfirmed'] = "UNCONFIRMED"; //new > v2.2.80401
$LNG['a_man_showbytag'] = "Filter by members tag or label"; //new > v2.2.91730

// Admin > Manage Bad Words
$LNG['a_man_bad_words_header'] = "Profanity User Site Filter";
$LNG['a_man_bad_words_instructions'] = "Enter a word and its replacement below.  For example, you could enter \"hell\" in the word field and \"h***\" in the replacement field.";
$LNG['a_man_bad_words_instructions_matching'] = "Exact matching will only match the exact word.  Global matching will match anything containing the word.  So, global matching of \"hell\" would also match \"shell\" and \"hello\".";
$LNG['a_man_bad_words_word'] = "Word";
$LNG['a_man_bad_words_replacement'] = "Replacement";
$LNG['a_man_bad_words_matching'] = "Matching";
$LNG['a_man_bad_words_exact'] = "Exact";
$LNG['a_man_bad_words_global'] = "Global";
$LNG['a_man_bad_words_filter'] = "Filter Word";
$LNG['a_man_bad_words_filtered'] = "\"%s\" has been added to the profanity filter.";

// Admin > Manage Download Files
$LNG['a_man_files_header'] = "Manage Download Files";
$LNG['a_man_files_man'] = "File Manager";
$LNG['a_man_files_date_add'] = "Added";
$LNG['a_man_files_date_update'] = "Update"; //new
$LNG['a_man_files_tb_name'] = "File";
$LNG['a_man_files_tb_cat'] = "Category"; //new > v2.1.80214
$LNG['a_man_files_tb_fullname'] = "Name";
$LNG['a_man_files_dir'] = "Full path to download directory:";
$LNG['a_man_files_examine'] = "Files Examiner"; //new > v2.2.80401
$LNG['a_man_files_button'] = "Start Crawling All Files";
$LNG['a_man_files_crawl_done'] = "Crawl all files done.";
$LNG['a_man_files_crawl_failed'] = "Crawling files failed."; //new > v2.2.80401
$LNG['a_man_files_size'] = "File size";
$LNG['a_man_files_descr'] = "Description";
$LNG['a_man_files_url_img'] = "URL address image location";
$LNG['a_man_files_hits'] = "Download count";
$LNG['a_man_files_cat'] = "File category"; //new > v2.1.80214
$LNG['a_man_files_cat_manage'] = "(<a href='index.php?a=admin&amp;b=filecats'>manage</a>)"; //new > v2.1.80214
$LNG['a_man_files_path'] = "File path"; //new > v2.2.80401
$LNG['a_man_files_gone'] = " (<font color=red>Not found!</font>)"; //new > v2.2.80401
$LNG['a_man_files_type'] = "File status";
$LNG['a_man_files_type_0'] = "Public Access";
$LNG['a_man_files_type_1'] = "Members Only";
$LNG['a_man_files_type_2'] = "Disabled for download";
$LNG['a_man_files_type_3'] = "For Sale";
$LNG['a_man_files_sticky'] = "Sticky (always on top and mark as bold)"; //new > v2.1.80214
$LNG['a_man_files_edit_done'] = "Update file done.";
$LNG['a_man_files_upload_done'] = "File is valid, and was successfully uploaded.";
$LNG['a_man_files_upload_failed'] = "Possible file upload attack!";
$LNG['a_man_files_not_exist'] = "File does not exist. Make sure you specified correct file name.";
$LNG['a_man_files_not_allowed'] = "File type not allowed, please contact system administrator.";
$LNG['a_man_files_no_download'] = "Access Denied!";
$LNG['a_man_files_expired_download'] = "Access Denied!, download expired.";
$LNG['a_man_files_no_paid_download'] = "Access Denied!, for licensed download only.";
$LNG['a_man_files_no_active_download'] = "Access Denied!, manual verification required.";
$LNG['a_man_files_off_payment_download'] = "Offline process!, manual verification required."; //new >v2.2.80612
$LNG['a_man_files_flog_name'] = "Log file";
$LNG['a_man_files_flog_open'] = "open";
$LNG['a_man_files_flog_reset'] = "reset";
$LNG['a_man_files_flog_reset_done'] = "Log file have been emptied.";
$LNG['a_man_files_notexist'] = "File not found!";
$LNG['a_man_files_resetlog_alert'] = "Are you sure want to reset downloads log file?";
$LNG['a_man_files_examine_alert'] = "Are you sure want to examine all files, this process will also check the files existence?"; //new >v2.2.80401
$LNG['a_man_files_avalformbr'] = "File Available for Members";
$LNG['a_man_files_groupdl'] = "Included in Download Group"; //new >v2.1.80214

// Admin > Manage Videos //new > v2.2.91730
$LNG['a_man_videos_header'] = "Manage Videos";
$LNG['a_man_videos_man'] = "Video Manager";
$LNG['a_man_videos_date_update'] = "Update";
$LNG['a_man_videos_tb_name'] = "Source";
$LNG['a_man_videos_tb_cat'] = "Category";
$LNG['a_man_videos_tb_title'] = "Video Title";
$LNG['a_man_videos_file'] = "Select the video file";
$LNG['a_man_videos_image'] = "Select the video tumbnail or splash image";
$LNG['a_man_videos_embed'] = "Video embed code";
$LNG['a_man_videos_examine'] = "Files Examiner";
$LNG['a_man_videos_size'] = "File size";
$LNG['a_man_videos_descr'] = "Description";
$LNG['a_man_videos_sizew'] = "Display width";
$LNG['a_man_videos_sizeh'] = "Display height";
$LNG['a_man_videos_source'] = "Video source";
$LNG['a_man_videos_cat'] = "Video category";
$LNG['a_man_videos_cat_manage'] = "(<a href='index.php?a=admin&amp;b=filecats'>manage</a>)";
$LNG['a_man_videos_path'] = "URL for the video file";
$LNG['a_man_videos_gone'] = " (<font color=red>Not found!</font>)";
$LNG['a_man_videos_type'] = "Video status";
$LNG['a_man_videos_type_0'] = "Public Access";
$LNG['a_man_videos_type_1'] = "Members Only";
$LNG['a_man_videos_type_2'] = "Disabled";
$LNG['a_man_videos_autoplay'] = "Autoplay video when page load";
$LNG['a_man_videos_splashimg'] = "Enable splash image";
$LNG['a_man_videos_edit_done'] = "Update video details done.";
$LNG['a_man_videos_internal'] = "Load Video File";
$LNG['a_man_videos_external'] = "Embed Video Source";
$LNG['a_man_videos_examine_alert'] = "Are you sure want to examine all files, this process will also check the files existence?";
$LNG['a_man_videos_avalformbr'] = "File Available for Members";
$LNG['a_man_videos_tag'] = "Video tag";

// Admin > Manage Banners
$LNG['a_man_banners_header'] = "Manage Banners";
$LNG['a_man_banners_man'] = "Banner Manager";
$LNG['a_man_banners_date_add'] = "Date Add";
$LNG['a_man_banners_tb_fullname'] = "Banner Title";
$LNG['a_man_banners_file'] = "File";
$LNG['a_man_banners_dir'] = "Full path to banners directory:"; //new
$LNG['a_man_banners_button'] = "Start Crawling All Banner Files";
$LNG['a_man_banners_crawl_done'] = "Crawl all banners done.";
$LNG['a_man_banners_size'] = "File size";
$LNG['a_man_banners_type'] = "Banner status";
$LNG['a_man_banners_type_0'] = "Hidden";
$LNG['a_man_banners_type_1'] = "Available";
$LNG['a_man_banners_edit_done'] = "Update banner done.";
$LNG['a_man_banners_upload_done'] = "Banner file is valid, and was successfully uploaded.";
$LNG['a_man_banners_upload_failed'] = "Possible banner file upload attack!";
$LNG['a_man_banners_not_exist'] = "Banner file does not exist. Make sure you specified correct file name.";
$LNG['a_man_banners_not_allowed'] = "File type not allowed, please contact system administrator.";
$LNG['a_man_banners_no_download'] = "Access Denied!";

// Admin > Manage Text Ads //new >v2.2.80401
$LNG['a_man_textads_header'] = "Manage Text Ads";
$LNG['a_man_textads_list'] = "Text Ads List";
$LNG['a_man_textads_new'] = "Create New Text Ads";
$LNG['a_man_textads_create'] = "Create";
$LNG['a_man_textads_predelete'] = "Are you sure want to delete \\'%s\\' text ads?";
$LNG['a_man_textads_seldelete'] = "Are you sure want to delete these selected text ads?";
$LNG['a_man_textads_preview'] = "Preview Text Ads"; //Update >v2.2.80612
$LNG['a_man_textads_update'] = "Update Text Ads";
$LNG['a_man_textads_title'] = "Text Ads Title";
$LNG['a_man_textads_content'] = "Text Ads Content";
$LNG['a_man_textads_bwidth'] = "Box Width";
$LNG['a_man_textads_bheight'] = "Box Height";
$LNG['a_man_textads_ocolor'] = "Outline Color";
$LNG['a_man_textads_ttcolor'] = "Title Text Color";
$LNG['a_man_textads_lcolor'] = "Link Color";
$LNG['a_man_textads_txcolor'] = "Text Color";
$LNG['a_man_textads_txbcolor'] = "Text Background Color";
$LNG['a_man_textads_fface'] = "Font Family";
$LNG['a_man_textads_fsize'] = "Font Size";
$LNG['a_man_textads_lfsize'] = "Link Font Size";
$LNG['a_man_textads_created'] = "The new text ads has been created.";
$LNG['a_man_textads_updated'] = "The text ads has been updated.";
$LNG['a_man_textads_deleted'] = "The text ads has been deleted.";

// Admin > Manage PopOver //new >v2.2.80401
$LNG['a_man_popover_header'] = "Manage PopOver";
$LNG['a_man_popover_update'] = "Update PopOver Settings";

$LNG['a_man_popover_status1'] = "PopOver Source: URL";
$LNG['a_man_popover_status2'] = "PopOver Source: Content";

$LNG['a_man_popover_status'] = "PopOver Status";
$LNG['a_man_popover_url'] = "PopOver Source URL";
$LNG['a_man_popover_content'] = "PopOver Content";
$LNG['a_man_popover_dwidth'] = "Dimensions Width";
$LNG['a_man_popover_dheight'] = "Dimensions Height";

$LNG['a_man_popover_tpos'] = "Top Possition";
$LNG['a_man_popover_lpos'] = "Left Possition";
$LNG['a_man_popover_slide'] = "Text Align";
$LNG['a_man_popover_sticky'] = "Content Scrolling";
$LNG['a_man_popover_bwidth'] = "Border Width";
$LNG['a_man_popover_bstyle'] = "Border Style";
$LNG['a_man_popover_bdcolor'] = "Border Color";
$LNG['a_man_popover_bgcolor'] = "Background Color";

$LNG['a_man_popover_delay'] = "Padding";
$LNG['a_man_popover_cookie'] = "Appearing limit to same visitor (using cookies, 0 = disable)";
$LNG['a_man_popover_cookie_id'] = "Cookie name";

$LNG['a_man_popover_shade'] = "Shade page background when PopOver appear";
$LNG['a_man_popover_shade_level'] = "Shade Level (0 = no shade, 100 = maximum)";
$LNG['a_man_popover_shade_speed'] = "Conten Border";
$LNG['a_man_popover_shade_color'] = "Shading Color";

$LNG['a_man_popover_created'] = "New PopOver has been created.";
$LNG['a_man_popover_updated'] = "PopOver has been updated.";

// Admin > Manage pre-Made Ads //new >v2.2.80401
$LNG['a_man_preads_header'] = "Manage Premade Ads";
$LNG['a_man_preads_list'] = "Premade Ads List";
$LNG['a_man_preads_new'] = "Create New Premade Ads";
$LNG['a_man_preads_create'] = "Create";
$LNG['a_man_preads_predelete'] = "Are you sure want to delete \\'%s\\' premade ads?";
$LNG['a_man_preads_seldelete'] = "Are you sure want to delete these selected premade ads?";
$LNG['a_man_preads_update'] = "Update Premade Ads";
$LNG['a_man_preads_title'] = "Premade Ads Title";
$LNG['a_man_preads_content'] = "Premade Ads Content";
$LNG['a_man_preads_adsformat'] = "Ads Format";
$LNG['a_man_preads_adsstatus'] = "Ads Status";
$LNG['a_man_preads_adspreview'] = "Premade Ads Preview";
$LNG['a_man_preads_created'] = "The new premade ads has been created.";
$LNG['a_man_preads_updated'] = "The premade ads has been updated.";
$LNG['a_man_preads_deleted'] = "The premade ads has been deleted.";
$LNG['a_man_preads_textads'] = "Text Ads";
$LNG['a_man_preads_emailads'] = "Email Promotion";
$LNG['a_man_preads_classads'] = "Classified Ads";
$LNG['a_man_preads_textlink'] = "Text Link";

// Admin > Manage Reviews
$LNG['a_man_rev_header'] = "Manage Reviews";
$LNG['a_man_rev_enter'] = "To manage the reviews of a site, enter the member's username below.";
$LNG['a_man_rev_id'] = "ID";
$LNG['a_man_rev_rev'] = "Review";
$LNG['a_man_rev_date'] = "Date";

// Admin > Menu
$LNG['a_menu'] = "Menu";
$LNG['a_menu_main'] = "Main";
$LNG['a_menu_approve'] = "Approve Members";
$LNG['a_menu_history'] = "Transaction History";
$LNG['a_menu_payout'] = "Payout Members";
$LNG['a_menu_withdraw'] = "Withdraw Request"; //new > v2.2.91730
$LNG['a_menu_leads'] = "Manage Subscribers";
$LNG['a_menu_addleads'] = "Add Subscribers"; //new
$LNG['a_menu_manage'] = "Manage Members";
$LNG['a_menu_addnew'] = "Add New Member"; //new
$LNG['a_menu_genealogy'] = "Members Genealogy"; //new
$LNG['a_menu_settings'] = "General Settings";
$LNG['a_menu_payplans'] = "PayPlan Settings"; //new > v2.2.91730
$LNG['a_menu_payments'] = "Payment Settings"; //new > v2.2.91730
$LNG['a_menu_manage_bad_words'] = "Profanity Filter";
$LNG['a_menu_dlbuilder'] = "Downline Builder";
$LNG['a_menu_addons'] = "Manage Addons"; //new
$LNG['a_menu_themes'] = "Site Themes";
$LNG['a_menu_ads_categories'] = "Ads Categories"; //new > v2.1.80214
$LNG['a_menu_file_categories'] = "File & Video Categories"; //new > v2.1.80214 //update > v2.2.91730
$LNG['a_menu_site_categories'] = "Site Categories";
$LNG['a_menu_approve_reviews'] = "Approve Reviews";
$LNG['a_menu_manage_reviews'] = "Manage Reviews";
$LNG['a_menu_email'] = "Newsletters";
$LNG['a_menu_smser'] = "SMS Sender"; //new > v2.2.91730
$LNG['a_menu_delete_review'] = "Delete Review";
$LNG['a_menu_logout'] = "Logout";
$LNG['a_menu_delete'] = "Delete Member";
$LNG['a_menu_edit'] = "Edit Member";
$LNG['a_menu_adsgroup'] = "Manage Ads Group"; //new > v2.3.450
$LNG['a_menu_usersads'] = "Manage Member Ads";
$LNG['a_menu_itemized'] = "Manage Categories";
$LNG['a_menu_items'] = "Manage Items";
$LNG['a_menu_sales'] = "Sales History";
$LNG['a_menu_email_customers'] = "Contact Customers";
$LNG['a_menu_store'] = "Store Settings"; //new > v2.2.91730
$LNG['a_menu_create_page'] = "Create Page";
$LNG['a_menu_manage_pages'] = "Manage Pages";
$LNG['a_menu_manage_news'] = "Manage News";
$LNG['a_menu_manage_feedbacks'] = "Manage Feedbacks";
$LNG['a_menu_messenger'] = "Messenger System";
$LNG['a_menu_invite'] = "Tell A Friend"; //new
$LNG['a_menu_respondergroup'] = "Manage Groups"; //new > v2.2.90809
$LNG['a_menu_responder'] = "Manage Messages";
$LNG['a_menu_manage_faqs'] = "Manage FAQ";
$LNG['a_header_members'] = "Members";
$LNG['a_header_settings'] = "Settings";
$LNG['a_header_reviews'] = "Reviews";
$LNG['a_header_payments'] = "Payments";
$LNG['a_header_pages'] = "Custom Pages";
$LNG['a_header_notification'] = "Notifications";
$LNG['a_header_responders'] = "Autoresponders";
$LNG['a_header_estore'] = "eStore";
$LNG['a_header_marketing'] = "Marketing Tools"; //new > v2.1.80214
$LNG['a_header_tools'] = "Utilities";
$LNG['a_menu_download_group'] = "Download Group"; //new > v2.1.80214
$LNG['a_menu_manage_files'] = "Manage Files";
$LNG['a_menu_manage_videos'] = "Manage Videos"; //new > v2.2.91730
$LNG['a_menu_manage_banners'] = "Manage Banners";
$LNG['a_menu_manage_textads'] = "Manage Text Ads"; //new > v2.2.80401
$LNG['a_menu_manage_preads'] = "Premade Ads"; //new > v2.2.80401
$LNG['a_menu_manage_popover'] = "Manage PopOver"; //new > v2.2.80401
$LNG['a_menu_refrace'] = "Referrer Contest"; //new > v2.2.91730
$LNG['a_menu_usrcampaign'] = "Members Campaign"; //new > v2.2.91730
$LNG['a_menu_giftpass'] = "Manage GiftPass"; //new > v2.2.91730
$LNG['a_menu_email_templates'] = "Email & SMS Templates";
$LNG['a_menu_page_templates'] = "Update Page Templates";
$LNG['a_menu_user_fiels'] = "User Custom Fields";
$LNG['a_menu_folderlock'] = "Protect Folders"; //new > v2.2.91730
$LNG['a_menu_admaccs'] = "Manage Administrator"; //new > v2.2.91730
$LNG['a_menu_manage_db'] = "Database Tools";
$LNG['a_menu_myquery'] = "Custom DB Query"; //new

// Admin > Folder Protection  //new > v2.2.91730
$LNG['a_folderlock_header'] = "Protect Folders";
$LNG['a_folderlock_note1'] = "You can protect your folders using your EzyGold login system, the protection will depend with the member status. You can protect any folders under your EzyGold installation path.";
$LNG['a_folderlock_note2'] = "To protect your folder, create a new file using any text editor, copy and paste code below and save it using name '.htaccess' (without quote), then upload the file to any folder you want to protect. Make sure the folders location (you want to protect) under your EzyGold installation folder. It's required to make the login redirection work.";
$LNG['a_folderlock_legend'] = "Generate .htaccess File";
$LNG['a_folderlock_protect'] = "Select folder to protect: ";
$LNG['a_folderlock_payplan'] = "Select PayPlan: ";
$LNG['a_folderlock_button'] = "Create .htaccess File";
$LNG['a_folderlock_warning'] = "<font color=red>Note:</font> This process will create new .htaccess file to the selected folder above and will <b>replace</b> the existing .htaccess file.";
$LNG['a_folderlock_success'] = "<font color=green>Folder protection success, .htaccess file created.</font>";
$LNG['a_folderlock_failed'] = "<font color=red>Folder protection failed, cannot write .htaccess file.</font>";
$LNG['a_folderlock_errordir'] = "<font color=red>Invalid folder, cannot write .htaccess file.</font>";

// Admin > Settings
$LNG['a_s_header'] = "General Settings";
$LNG['a_s_help'] = "Help";

// Admin > Database Tools
$LNG['a_db_header'] = "Database Tools";
$LNG['a_db_optimized'] = "Optimize Table";
$LNG['a_db_repaired'] = "Repair Table";
$LNG['a_db_optimized_done'] = "Optimization complete.";
$LNG['a_db_optimized_err'] = "Optimization failed.";
$LNG['a_db_repaired_done'] = "Repair Databse Done.";
$LNG['a_db_repaired_err'] = "Repair Database Failed.";
$LNG['a_db_backup'] = "Backup Database";
$LNG['a_db_backup_note'] = "To backup the database please click on the {$LNG['a_db_backup']} button.";
$LNG['a_db_backup_done'] = "Database backup complete.";
$LNG['a_db_restore'] = "Restore";
$LNG['a_db_restore_note'] = "To restore the database please pick a previously saved file backup.";
$LNG['a_db_restore_warn'] = "WARNING!: All your current databases tables and records will be OVERWRITTEN with the contents of the backup file!";
$LNG['a_db_restore_confirm'] = "It will replace all your exising database with backup. Do you really want to proceed?";
$LNG['a_db_restore_done'] = "Database restore complete.";
$LNG['a_db_restore_err'] = "Database restore failed.";
$LNG['a_db_restore_incompatible'] = "Uploaded file is not valid database backup file.";
$LNG['a_db_tablename'] = "Table Name";
$LNG['a_db_create'] = "Create";
$LNG['a_db_tablerecords'] = "Records";
$LNG['a_db_tablesize'] = "Size";
$LNG['a_db_dbname'] = "Database Name";
$LNG['a_db_autobackup'] = "AutoBackup Database"; //new > v2.2.80401
$LNG['a_db_autobackup_info'] = "This feature will allow the system to automatically backup the database and send the backup file through email below as attachment based on backup interval."; //new > v2.2.80401
$LNG['a_db_autobackup_days'] = "Backup interval (in days, set 0 to disable)"; //new > v2.2.80401
$LNG['a_db_autobackup_email'] = "Send database backup file to this email address"; //new > v2.2.80401
$LNG['a_db_autobackup_latest_date'] = "Latest database automatically backup executed:"; //new > v2.2.80401
$LNG['a_db_autobackup_txt_enable'] = "<font color=green>ENABLE</font>"; //new > v2.2.80401
$LNG['a_db_autobackup_txt_disable'] = "<font color=red>DISABLE</font>"; //new > v2.2.80401
$LNG['a_db_autobackup_txt_never'] = "Never"; //new > v2.2.80401
$LNG['a_db_autobackup_subject'] = "AutoBackup:"; //new > v2.2.80401

// Admin > Database Tools (date export) //new
$LNG['a_dbex_header'] = "Database Table Export";
$LNG['a_dbex_tbname'] = "Table Name";
$LNG['a_dbex_included'] = "Included";
$LNG['a_dbex_field'] = "Field";
$LNG['a_dbex_label'] = "Label";
$LNG['a_dbex_tbl_users'] = "Members";
$LNG['a_dbex_tbl_leads'] = "Subscribers";
$LNG['a_dbex_tbl_transaction'] = "Transaction Histories";
$LNG['a_dbex_tbl_sales'] = "Sales Records";
$LNG['a_dbex_txtrec'] = "Records";
$LNG['a_dbex_delim'] = "Delimiter";
$LNG['a_dbex_quote'] = "Quote";
$LNG['a_dbex_qrywhere'] = "Custom Query (WHERE)"; //new v2.2.91730
$LNG['a_dbex_btnexp'] = "Export";

// Admin > Custom Database Query //new
$LNG['a_qry_header'] = "Custom Database Query";
$LNG['a_qry_query'] = "Manual Query";
$LNG['a_qry_query_info'] = "Please use this query box carefully otherwise your database will be unpredictable.<br />You need SQL and MySQL knowledge to use this. Make sure you know what you do.";
$LNG['a_qry_query_btn'] = "Execute";
$LNG['a_qry_findreplace'] = "Find and Replace";
$LNG['a_qry_findreplace_info'] = "Use form below to find and replace data within your database.";
$LNG['a_qry_findreplace_table'] = "Select Table";
$LNG['a_qry_findreplace_field'] = "Select Field";
$LNG['a_qry_findreplace_find'] = "Find what";
$LNG['a_qry_findreplace_replace'] = "Replace with";
$LNG['a_qry_findreplace_btn'] = "Replace All";
$LNG['a_qry_query_done'] = "Custom query executed successfuly (%s).";
$LNG['a_qry_findreplace_done'] = "Find and Replace executed successfuly.";
$LNG['a_qry_query_error'] = "Custom query error: <br /><font color=red><em>%s</em></font>";
$LNG['a_qry_findreplace_error'] = "Find and Replace error: ";
$LNG['a_qry_confirmation'] = "WARNING!!! Are you sure want to submit the database query?";

// Admin > Common and Settings
$LNG['a_s_general'] = "Site Settings";
$LNG['a_s_admin_password'] = "Admin password";
$LNG['a_s_admin_ipaccess'] = "Allow accessing Admin CP from this IP Address only (ie. %s)"; //new > v2.1.80214
$LNG['a_s_admin_ipaccess_empty'] = "<font color=red>Leave empty to disable this feature</font>"; //new > v2.1.80214
$LNG['a_s_admin_etoken'] = "Verify Admin CP login attempt using EzyGold eToken"; //new > v2.1.80214
$LNG['a_s_admin_etoken_confirm'] = "WARNING!\\nYou need to setup your eToken application before enable this feature.\\n\\nAre you sure want to enable eToken verification feature?"; //new > v2.2.80401
$LNG['a_s_site_name'] = "The name of your site";
$LNG['a_s_site_url'] = "Your site URL (without trailing slash /)";
$LNG['a_s_full_path'] = "Your site path (without trailing slash /)";
$LNG['a_s_default_language'] = "Default language";
$LNG['a_s_dlgroup'] = "Member Download Group"; //new > v2.1.80214
$LNG['a_s_dlgroup_date'] = "Download Group Efective Date"; //new > v2.2.80401
$LNG['a_s_dlgroup_manage'] = "(<a href='index.php?a=admin&amp;b=download_group'>manage</a>)"; //new > v2.1.80214
$LNG['a_s_site_emailname'] = "Email from name"; //new > v2.2.91730
$LNG['a_s_site_email'] = "Your email address";
$LNG['a_s_site_phone'] = "Your phone number (to receive sms message)";
$LNG['a_s_default_skin'] = "Please select available templates below as default template";
$LNG['a_s_pay_email'] = "Your valid payment email address (ie. <i>\"my biz\" &lt;pay@sitename.com&gt;</i>)";
$LNG['a_s_currencysym'] = "Currency Symbol ($, Rp., &yen;, &euro; etc., provided for reporting only)";
$LNG['a_s_currencycode'] = "Currency Code (USD, IDR, JPY, EUR, etc., provided for reporting only)";
$LNG['a_s_defgroupdl'] = "Default Download Group"; //new > v2.1.80214
$LNG['a_s_defgroupar'] = "Default Responders Group"; //new > v2.2.90809
$LNG['a_s_landingpage'] = "Default Landing Page"; //new > v2.1.80214
$LNG['a_s_sitetype'] = "Type of Site (plug-in)";
$LNG['a_s_wysiwygtb'] = "Text Editor Toolbar"; //new
$LNG['a_s_wysiwygtb_0'] = "Advance"; //new
$LNG['a_s_wysiwygtb_1'] = "Default"; //new
$LNG['a_s_wysiwygtb_2'] = "Basic"; //new
$LNG['a_s_squeezer'] = "Enable squezze page"; //new > v2.2.80401

$LNG['a_s_payment'] = "PayPlan Settings";
$LNG['a_s_manageplan'] = "Manage PayPlan"; //new > v2.2.90809
$LNG['a_s_manageplan_info'] = "You can manage your PayPlan below, to edit or update your payplan click the edit icon (<img src='{$CONF['site_url']}/templates/{$CONF['default_skin']}/images/b_edit.gif' border='0' title='{$LNG['a_man_edit']}'>) and to create new payplan click the duplicate icon (<img src='{$CONF['site_url']}/templates/{$CONF['default_skin']}/images/b_sck.gif' border='0' title='{$LNG['a_man_clone']}'>). You may also set the PayPlan status from list below."; //new > v2.2.90809
$LNG['a_s_planinorder'] = "Allow register available PayPlan in the order only"; //new > v2.2.91730
$LNG['a_s_followrefplan'] = "Always follow sponsor PayPlan"; //update > v2.2.91730
$LNG['a_s_extendedmatrix'] = "Enable extended matrix (only if Self Cycle option enable)"; //new > v2.2.91730
$LNG['a_s_extendedmatrixlevel'] = "%s by level width and %s by level deep"; //new > v2.2.91730
$LNG['a_s_bizplan'] = "PayPlan or Membership name";
$LNG['a_s_planinfo'] = "PayPlan or Membership description (optional)"; //new > v2.2.90809
$LNG['a_s_cloneplan'] = "Are you sure you want to duplicate this *%s* PayPlan or Membership?"; //new > v2.2.90809
$LNG['a_s_delplan'] = "Are you sure you want to remove this *%s* PayPlan or Membership?\\n\\nWarning! this process cannot be reverse."; //new > v2.2.90809
$LNG['a_s_delplandone'] = "The PayPlan or Membership successfuly removed."; //new > v2.2.91729
$LNG['a_s_delplanfail'] = "The PayPlan or Membership cannot be removed."; //new > v2.2.91729
$LNG['a_s_aval4plan'] = "Available for PayPlan or Membership"; //new > v2.2.91729
$LNG['a_s_planstatus'] = "PayPlan status"; //new > v2.2.90809
$LNG['a_s_planstatus0'] = "Disable"; //update > v2.2.91730
$LNG['a_s_planstatus1'] = "Enable (available in the registration and/or upgrade page)"; //update > v2.2.91730
$LNG['a_s_planstatus2'] = "Enable (available in the upgrade page only)"; //update > v2.2.91730
$LNG['a_s_planstatus3'] = "Private (use internally by system)"; //update > v2.2.91730
$LNG['a_s_set_planstatus'] = "Set PayPlan Status"; //new > v2.2.91729
$LNG['a_s_set_planstatus_done'] = "Set PayPlan Status done."; //new > v2.2.91729
$LNG['a_s_planparentid'] = "Parent PayPlan"; //new > v2.2.90809
$LNG['a_s_plandisporder'] = "PayPlan display order"; //new > v2.2.901730
$LNG['a_s_payname'] = "Account name (ie. your domain name)";
$LNG['a_s_adminfee'] = "Administration Settings";
$LNG['a_s_joinfee'] = "Registration or Membership Fee";
$LNG['a_s_joinfee_0free'] = "(0 = free account)";
$LNG['a_s_expday'] = "Membership period (leave empty to disable)";
$LNG['a_s_warnexpday'] = "Expired Alert (in days, before Account expired)"; //update
$LNG['a_s_payduration'] = "Subscription duration (0 = until cancelled)"; //new > 2.2.91730
$LNG['a_s_trialacc'] = "Enable Trial Account"; //new > 2.2.91730
$LNG['a_s_trialfee'] = "Trial amount (leave empty or set to 0 to enable free trial)"; //new > 2.2.91730
$LNG['a_s_trialperiod'] = "Trial account period (in days)"; //new > 2.2.91730
$LNG['a_s_carefee'] = "Maintenance Fee (additional administration fee, 0 = disable)"; //new
$LNG['a_s_carepayday'] = "Maintenance Phase (leave empty to disable)"; //new
$LNG['a_s_isallfee'] = "Add Maintenance Fee in the first {$LNG['a_s_joinfee']}"; //new > v2.1.80214
$LNG['a_s_graceday'] = "Grace period before account expired or pending (in days, 0 = disable)"; //new > v2.2.80612
$LNG['a_s_payplan'] = "Network System";
$LNG['a_s_cmdrlist'] = "Additional direct referral commission list structure";
$LNG['a_s_cmlist'] = "Commission list structure";
$LNG['a_s_cmlistcare'] = "Additional commission list structure from Maintenance Fee - optional"; //new > v2.1.80214
$LNG['a_s_rwlist'] = "Full level reward list structure";
$LNG['a_s_randlist'] = "Randomized level list structure";
$LNG['a_s_maxwidth'] = "Level Width (0 = Unilevel)"; //update
$LNG['a_s_maxdeep'] = "Level Deep";
$LNG['a_s_limitref'] = "Limit member's direct referral (0 = disable)"; //update > v2.2.80401
$LNG['a_s_spillover1'] = "Spread Evenly"; //update > v2.3.450
$LNG['a_s_spillover2'] = "First In, First Filled";
$LNG['a_s_minref2splovr'] = "Minimum direct referral to get spillover"; //new > v2.2.80612
$LNG['a_s_cmdayhold'] = "Commissions \"cooling off\" period (in days, 0 = disable)"; //new > v2.2.80612
$LNG['a_s_cmdayhold_t'] = "Commission onhold for %s days since generated (cooling off period)."; //new > v2.2.80612
$LNG['a_s_spill4ver'] = "Continue spillover although members matrix is full"; //new > v2.2.80612
$LNG['a_s_matrixfulfill'] = "Auto Fulfill Matrix Structure with Random Members"; //new
$LNG['a_s_compression'] = "Enable Matrix Compression"; //update > v2.2.80401
$LNG['a_s_cmpair'] = "Pairing commission";
$LNG['a_s_cmatch'] = "Fast start commission";
$LNG['a_s_minref2getcm'] = "Minimum direct referral to start earning commissions"; //new > v2.2.80612
$LNG['a_s_minref2getcm_fail'] = "<b>Note:</b> Minimum direct referral unaccomplished"; //new > v2.2.80612
$LNG['a_s_min2payout'] = "Minimum total active commissions before payout"; //new > v2.2.80612

$LNG['a_s_active_rewards'] = "Member Rewards";
$LNG['a_s_active_ref_daily'] = "Minimum daily new direct referrals list"; //update > v2.1.80214
$LNG['a_s_active_rewards_daily'] = "Daily reward list (empty = disabled)"; //update > v2.1.80214
$LNG['a_s_active_ref_weekly'] = "Minimum weekly new direct referrals list"; //new > v2.1.80214
$LNG['a_s_active_rewards_weekly'] = "Weekly reward list (empty = disabled)"; //new > v2.1.80214
$LNG['a_s_active_ref_monthly'] = "Minimum monthly new direct referrals list"; //update > v2.1.80214
$LNG['a_s_active_rewards_monthly'] = "Monthly reward list (empty = disabled)"; //update > v2.1.80214

$LNG['a_s_matrix_recycling'] = "Matrix Recycling and Reentry"; //update > v2.2.80612
$LNG['a_s_matrix_isrecycling'] = "Enable Recycling"; //new > v2.2.80612
$LNG['a_s_matrix_recycling_on'] = "Enable Reentry"; //update > v2.2.80612
$LNG['a_s_matrix_recycling_self'] = "(Self Reentry)"; //update > v2.2.80612
$LNG['a_s_matrix_recycling_to'] = "Reentry to"; //new > v2.2.91730
$LNG['a_s_matrix_recycling_2ref'] = "Reentry to the sponsor (if possible)"; //update > v2.2.91730
$LNG['a_s_matrix_recycling_max'] = "Reentry account created when cycle"; //new > v2.2.91730
$LNG['a_s_matrix_recycling_limit'] = "Phase Limit"; //new
$LNG['a_s_matrix_recycling_cmlist'] = "Recycling rewards list"; //new
$LNG['a_s_matrix_recycling_wider'] = "level width wider"; //new > v2.2.91212
$LNG['a_s_matrix_recycling_payplan'] = "update membership"; //new > v2.2.91730
$LNG['a_s_matrix_recycling_pplanto'] = "Update (register) membership to"; //new > v2.2.91730
$LNG['a_s_matrix_recycling_selfpplan'] = "(Self Cycle)"; //new > v2.2.91730
$LNG['a_s_matrix_recycling_followspr'] = "follow the sponsor"; //new > v2.2.91212
$LNG['a_s_matrix_recycling_followme'] = "follow me"; //new > v2.2.91212
$LNG['a_s_matrix_recycling_autoactive'] = "Auto member status activation"; //new > v2.2.91730

$LNG['a_s_xupsys'] = "X-Up System";
$LNG['a_s_1up_on'] = "Enable 1-Up System";
$LNG['a_s_xup_on'] = "Enable X-Up System";
$LNG['a_s_xup_vertical'] = "vertical pass up";
$LNG['a_s_xuplevel'] = "X-Up Level (Qualifying Users)";
$LNG['a_s_cm1up'] = "1-Up commission belong to sponsor (flat value or percentage)"; //update > v2.2.80612
$LNG['a_s_cmxup'] = "X-Up commission list belong to sponsors"; //update
$LNG['a_s_idlistxup'] = "Referral # list for pass up, optional"; //new > v2.2.80612

$LNG['a_s_cashgifting'] = "Cash Gifting System"; //new > v2.2.80612
$LNG['a_s_cashgifting_level'] = "Level"; //new > v2.2.80612
$LNG['a_s_cashgifting_cglevels'] = "Cash Gifting Levels"; //new > v2.2.80612
$LNG['a_s_cashgifting_cgsendmtd'] = "Cash Sending Method"; //new > v2.2.80612
$LNG['a_s_cashgifting_cglevels_manage'] = "(<a href='index.php?a=admin&amp;b=fields'>manage</a>)"; //new > v2.2.80612
$LNG['a_s_cashgifting_cglevels_disable'] = "--- Disable ---"; //new > v2.2.80612
$LNG['a_s_cashgifting_cgupgraderule'] = "Level Upgrade Rule"; //new > v2.2.80612
$LNG['a_s_cashgifting_cgupgraderule_diff'] = "Pay difference between levels"; //new > v2.2.80612
$LNG['a_s_cashgifting_cgupgraderule_full'] = "Pay full for new level"; //new > v2.2.80612
$LNG['a_s_cashgifting_cgupgradepay'] = "If new receiving line level higher than the inviter level, new receiving line should"; //new > v2.2.80612
$LNG['a_s_cashgifting_cgupgradepay_direct'] = "Directly split the payment to the inviter and their host"; //new > v2.2.80612
$LNG['a_s_cashgifting_cgupgradepay_passed'] = "Pay full to the inviter and inviter needs to pass up the difference level fee to their host"; //new > v2.2.80612

$LNG['a_s_vlmbased'] = "Volume Based PayOut (eStore)"; //new > v2.2.80401
$LNG['a_s_vlmbased_pv'] = "Personal Volume"; //new > v2.2.80401
$LNG['a_s_vlmbased_bv'] = "Business (Group) Volume"; //new > v2.2.80401

$LNG['a_s_bnarysys'] = "Binary System";
$LNG['a_s_bnarysys_0'] = "Disabled";
$LNG['a_s_bnarysys_1'] = "Automatic by system";
$LNG['a_s_bnarysys_2'] = "Manual by member (with left and right option)";
$LNG['a_s_bnarysys_3'] = "Manual by member (include auto position option)";
$LNG['a_s_bnarysys_pos'] = "Binary position preference";
$LNG['a_s_bnarysys_left'] = "Left Leg";
$LNG['a_s_bnarysys_right'] = "Right Leg";
$LNG['a_s_bnarysys_auto'] = "Auto position by system";
$LNG['a_s_bnarysys_pair_cm'] = "Binary system pairing commission";
$LNG['a_s_bnarysys_pair_limit'] = "Pairing commission limit"; //new > v2.3.450

//new > v2.2.91730
$LNG['a_s_cyclingitme'] = "<font color=#999999>ReCycling this member in the next system task</font>";
$LNG['a_s_cyclingfromid'] = "<font color=#999999>ReCycling triggered from member register</font>";
$LNG['a_s_other_autoregplan'] = "Members also registered automatically to payplan";
$LNG['a_s_other_autoregnote'] = "<font color='#CC0000'>&rarr; <a href='javascript:;' id='unselectme'>Unselect</a> all to disable this feature (press Ctrl + Click to select or deselect).</font>";

//new > v2.3.443
$LNG['a_s_other_customplan'] = "Custom payplan variables";

//new > v2.2.80612
$LNG['a_s_other_isbenefactor'] = "Allow members to setup their benefactor value";
$LNG['a_s_other_directpay'] = "Enable direct payment (member to member payment)";
$LNG['a_s_other_ctrldownline'] = " (and allow members to control their referrals status)";

$LNG['a_s_merchants'] = "Payment Settings";
$LNG['a_s_egold'] = "E-Gold Account";
$LNG['a_s_egoldon'] = "Accept payment using E-gold";
$LNG['a_s_curl_not_installed'] = "<b>Warning:</b> cUrl are not available in your server!";
$LNG['a_s_amountsvg'] = "Amount of saving in % (to secure your profits)";
$LNG['a_s_egoldacc_svg'] = "E-gold Account (optional, for saving purpose only)";
$LNG['a_s_egoldfee'] = "Additional E-gold fee"; //new
$LNG['a_s_egoldacc'] = "E-gold Account";
$LNG['a_s_egoldpass'] = "E-gold Passphrase (only required if auto payout is selected)"; //update
$LNG['a_s_egoldaltpass'] = "E-gold Alternate Passphrase";
$LNG['a_s_egold4usr'] = "Allow users to select E-gold when accept commissions";

// alertpay //new
$LNG['a_s_alertpay'] = "Payza Account";
$LNG['a_s_alertpayon'] = "Accept payment using Payza";
$LNG['a_s_alertpaysubs'] = "Enable subscription payment (if possible)"; //new > v2.2.80612
$LNG['a_s_alertpayfee'] = "Additional Payza fee";
$LNG['a_s_alertpayacc'] = "Payza Account (Email Address)";
$LNG['a_s_alertpayipn'] = "IPN Security Code"; //update > v2.2.91729
$LNG['a_s_alertpayapiuser'] = "Payza Pro or Business Account Email (required if auto payout is selected)"; //new > v2.2.80612
$LNG['a_s_alertpayapipass'] = "API Password (required if auto payout is selected)"; //new > v2.2.80612
$LNG['a_s_alertpayapitype'] = "Purchase Type (for auto payout)"; //new > v2.2.80612
$LNG['a_s_alertpayapitest'] = "Payza Payment Test Mode (for auto payout)"; //new > v2.2.80612
$LNG['a_s_alertpay4usr'] = "Allow users to select Payza when accept commissions";

// solidtrustpay //new > v3.2.429
$LNG['a_s_solidtrustpay'] = "SolidTrustPay Account";
$LNG['a_s_solidtruston'] = "Accept payment using SolidTrustPay";
$LNG['a_s_solidtrustsubs'] = "Enable subscription payment (if possible)";
$LNG['a_s_solidtrustfee'] = "Additional SolidTrustPay fee";
$LNG['a_s_solidtrustacc'] = "SolidTrustPay Account (username or email)";
$LNG['a_s_solidtrustpass'] = "SolidTrustPay Secondary Password";
$LNG['a_s_solidtrustitem'] = "Item ID";
$LNG['a_s_solidtrustapiuser'] = "API Name (required for auto payout)";
$LNG['a_s_solidtrustapipass'] = "API Password (required for auto payout)";
$LNG['a_s_solidtrustapitest'] = "SolidTrustPay Payment Test Mode";
$LNG['a_s_solidtrust4usr'] = "Allow users to choose SolidTrustPay when accept commissions";

// perfectmoney //new > v3.2.429
$LNG['a_s_perfectmoney'] = "PerfectMoney Account";
$LNG['a_s_perfectmoneyon'] = "Accept payment using PerfectMoney";
$LNG['a_s_perfectmoneyfee'] = "Additional PerfectMoney fee";
$LNG['a_s_perfectmoneyacc'] = "PerfectMoney Account";
$LNG['a_s_perfectmoneyname'] = "Account Name";
$LNG['a_s_perfectmoneyaltpass'] = "Alternate Passphrase";
$LNG['a_s_perfectmoneyid'] = "Member ID (required for auto payout)";
$LNG['a_s_perfectmoneypass'] = "Password (required for auto payout)";
$LNG['a_s_perfectmoney4usr'] = "Allow users to choose PerfectMoney when accept commissions";

$LNG['a_s_safepay'] = "SafePay Account";
$LNG['a_s_safepayon'] = "Accept payment using SafePay Solutions";
$LNG['a_s_safepayfee'] = "Additional SafePay fee";
$LNG['a_s_safepayacc'] = "SafePay Username";
$LNG['a_s_safepaypwd'] = "SafePay Password (optional)";
$LNG['a_s_safepayipn'] = "SafePay Secret Passphrase (IPN)";
$LNG['a_s_safepaytest'] = "SafePay Payment Test Mode";
$LNG['a_s_safepay4usr'] = "Allow users to select SafePay when accept commissions";

$LNG['a_s_mbookers'] = "MoneyBookers Account";
$LNG['a_s_mbookerson'] = "Accept payment using MoneyBookers";
$LNG['a_s_mbookersfee'] = "Additional MoneyBookers fee";
$LNG['a_s_mbookersacc'] = "MoneyBookers account email";
$LNG['a_s_mbookerspwd'] = "Secret Word";
$LNG['a_s_mbookerslng'] = "MoneyBookers' pages language";
$LNG['a_s_mbookersnote'] = "Confirmation note (optional)";
$LNG['a_s_mbookers4usr'] = "Allow users to select MoneyBookers when accept commissions";

$LNG['a_s_paypal'] = "PayPal Account";
$LNG['a_s_paypalon'] = "Accept payment using PayPal";
$LNG['a_s_paypalsubs'] = "Enable subscription payment (if possible)"; //new > v2.2.80612
$LNG['a_s_paypalfee'] = "Additional PayPal fee";
$LNG['a_s_paypalacc'] = "PayPal Account (Email Address)";
$LNG['a_s_paypalapiuser'] = "API Username (required if auto payout is selected)"; //new > v2.1.80214
$LNG['a_s_paypalapipass'] = "API Password (required if auto payout is selected)"; //new > v2.1.80214
$LNG['a_s_paypalapisign'] = "API Signature (required if auto payout is selected)"; //new > v2.1.80214
$LNG['a_s_paypalproxy'] = "API Proxy - [host:port] (optional, required if auto payout is selected)"; //new > v2.1.80214
$LNG['a_s_paypaltest'] = "PayPal Payment Test Mode";
$LNG['a_s_paypal4usr'] = "Allow users to select PayPal when accept commissions";

//libertyreserve new > v2.1.80214
$LNG['a_s_lreserve'] = "LibertyReserve Account";
$LNG['a_s_lreserveon'] = "Accept payment using LibertyReserve";
$LNG['a_s_lreservefee'] = "Additional LibertyReserve fee";
$LNG['a_s_lreserveacc'] = "LibertyReserve account";
$LNG['a_s_lreservesto'] = "Store name";
$LNG['a_s_lreservestopwd'] = "Store security word";
$LNG['a_s_lreserveapi'] = "API name (required if auto payout is selected)";
$LNG['a_s_lreserveapipwd'] = "API security word (required if auto payout is selected)";
$LNG['a_s_lreservepriv'] = "Payment level";
$LNG['a_s_lreserve4usr'] = "Allow users to select LibertyReserve when accept commissions";

//linkpoint new > v2.2.80401
$LNG['a_s_linkpoint'] = "LinkPoint Account";
$LNG['a_s_linkpointon'] = "Accept payment using LinkPoint";
$LNG['a_s_linkpointfee'] = "Additional LinkPoint fee";
$LNG['a_s_linkpointhostport'] = "LinkPoint Host:Port";
$LNG['a_s_linkpointkeyfile'] = "LinkPoint Key File Path";
$LNG['a_s_linkpointid'] = "LinkPoint User ID (store number)";
$LNG['a_s_linkpointname'] = "Merchant Name (CaSe SeNsItIvE)";
$LNG['a_s_linkpointemail'] = "Merchant Email Address (CaSe SeNsItIvE)";

$LNG['a_s_authorize'] = "AuthorizeNet Payment";
$LNG['a_s_authorizeon'] = "Accept payment using AuthorizeNet (SIM)";
$LNG['a_s_authorizetitle'] = "Payment Method Title (optional)";
$LNG['a_s_authorizefee'] = "Additional AuthorizeNet fee";
$LNG['a_s_authorizelogin'] = "API Login ID";
$LNG['a_s_authorizekey'] = "Transaction Key";
$LNG['a_s_authorizepwd'] = "Secret Word (optional)";
$LNG['a_s_authorizetest'] = "Test Mode Enabled";

$LNG['a_s_manualpay'] = "Manual Payment (optional)";
$LNG['a_s_manualpayon'] = "Enabled manual or offline payment";
$LNG['a_s_manualpaybtn'] = "Display manual or offline payment button"; //new >v2.3.450
$LNG['a_s_manualpayfee'] = "Additional processing fee";
$LNG['a_s_manualpayname'] = "Manual payment name";
$LNG['a_s_manualpayipn'] = "Details (instructions, query, html form code, etc)";
$LNG['a_s_manualpay4usr'] = "Allow users to insert their payment details (ie. bank account)";

$LNG['a_s_no_dirpay'] = "<font color='red' size=1><em>Do not support for member to member direct payment</em></font>"; //new >v2.2.80612
$LNG['a_s_more_merchants'] = "Other Payment Processors"; //new >v2.2.80401
$LNG['a_s_manage_more_merchants'] = "Manage Other Payment Processors"; //new >v2.2.80401

// Admin > Other Payment Processors //new >v2.2.80401
$LNG['a_s_prepaypro_header'] = "Manage Payment Processor";
$LNG['a_s_prepaypro_list'] = "Payment Processor List";
$LNG['a_s_prepaypro_new'] = "Create New Payment Processor";
$LNG['a_s_prepaypro_create'] = "Create";
$LNG['a_s_prepaypro_predelete'] = "Are you sure want to delete \\'%s\\' payment processor?";
$LNG['a_s_prepaypro_seldelete'] = "Are you sure want to delete these selected payment processors?";
$LNG['a_s_prepaypro_update'] = "Update Payment Processor";
$LNG['a_s_prepaypro_status'] = "Status";
$LNG['a_s_prepaypro_name'] = "Payment Processor Name";
$LNG['a_s_prepaypro_fee'] = "Payment Processor Additional Fee";
$LNG['a_s_prepaypro_content'] = "Payment Processor Content";
$LNG['a_s_prepaypro_content_estore'] = "Payment Processor Content (eStore)";
$LNG['a_s_prepaypro_logo'] = "Payment Processor Logo URL (101x41 pixels)";
$LNG['a_s_prepaypro_button'] = "Payment Processor Button Image URL (98x54 pixels)";
$LNG['a_s_prepaypro_created'] = "The new payment processor has been created.";
$LNG['a_s_prepaypro_updated'] = "The payment processor has been updated.";
$LNG['a_s_prepaypro_deleted'] = "The payment processor has been deleted.";
$LNG['a_s_prepaypro_preform'] = "Insert custom code or payment instructions here";
$LNG['a_s_prepaypro_ipnhandler'] = "<strong>Note:</strong><br /><br />You need to create IPN handler file to process postback (IPN) variables received from the payment processor. Generally this file defined in the <em>return</em> or <em>confirm</em> URL in the buynow button form.<br /><br />Since each payment processor is unique and have different method to process the payment, we do not provide tech support for it, but we offer integration service if needed. Please contact us for further details.";

// Admin > Other Administrator Access //new >v2.2.80401
$LNG['a_s_admaccs_header'] = "Manage Administrator";
$LNG['a_s_admaccs_list'] = "Administrator List";
$LNG['a_s_admaccs_linkadmin'] = "Click <a href='index.php?a=admin&amp;b=settings#{$LNG['a_s_admin_password']}'>here</a> to manage Super Administrator login details.";
$LNG['a_s_admaccs_new'] = "Create New Administrator";
$LNG['a_s_admaccs_create'] = "Create";
$LNG['a_s_admaccs_predelete'] = "Are you sure want to delete \\'%s\\' Administrator?";
$LNG['a_s_admaccs_seldelete'] = "Are you sure want to delete these selected Administrators?";
$LNG['a_s_admaccs_update'] = "Update Administrator";
$LNG['a_s_admaccs_status'] = "Status";
$LNG['a_s_admaccs_name'] = "Administrator Title";
$LNG['a_s_admaccs_user'] = "Administrator Username";
$LNG['a_s_admaccs_pass'] = "Administrator Password";
$LNG['a_s_admaccs_created'] = "The new Administrator has been created.";
$LNG['a_s_admaccs_failed'] = "<font color=red>The new Administrator not created.</font>"; //new >v2.2.80612
$LNG['a_s_admaccs_exist'] = "The new Administrator username already exist.";
$LNG['a_s_admaccs_updated'] = "The Administrator has been updated.";
$LNG['a_s_admaccs_deleted'] = "The Administrator has been deleted.";
$LNG['a_s_admaccs_privilege'] = "Administrator Privilege";
$LNG['a_s_admaccs_admbanpage'] = "Select <font color='#CC0000'>restricted pages</font> for this administrator:"; //new >v2.2.91730
$LNG['a_s_admaccs_banpage'] = "Restricted Pages:"; //new >v2.2.91730
$LNG['a_s_admaccs_loginas'] = "<strong>Login as:</strong> "; //new >v2.2.91730

$LNG['a_s_sql'] = "SQL Settings";
$LNG['a_s_sql_type'] = "Database Type";
$LNG['a_s_sql_host'] = "Database Host (default: localhost)";
$LNG['a_s_sql_database'] = "Database Name<br />(<font color=red>Warning! ALL existing tables will be overwrite!</font>)";
$LNG['a_s_sql_username'] = "Database Username";
$LNG['a_s_sql_password'] = "Database Password";

$LNG['a_i_options'] = "Install Options";
$LNG['a_i_freshinstall'] = "Fresh installation with new database and configuration";
$LNG['a_i_updateconfig'] = "Update the configuration file (settings_sql.php)";
$LNG['a_i_encryptcfile'] = "Encrypt the configuration file (settings_sql.php)";
$LNG['a_i_freshinstall_note'] = "<font color='#999999' size=1>Default option for the fresh installation. System will create new settings_sql.php file and also configure the database. ALL existing tables in the database will be overwriten!</font>";
$LNG['a_i_updateconfig_note'] = "<font color='#999999' size=1>This option will update the content of the settings_sql.php file only, commonly used when update the database details only or when move the installation to another server with different database details and installation path.</font>";
$LNG['a_i_encryptcfile_note'] = "<font color='#999999' size=1>If checked, the database details in the settings_sql.php file will be encrypted (recommended).</font>";

$LNG['a_s_sitedescr'] = "META Description";
$LNG['a_s_sitekeywrd'] = "META Keywords";

$LNG['a_s_compname'] = "Company Name";
$LNG['a_s_compaddress'] = "Company Address";
$LNG['a_s_sitefooter'] = "Site footer";

$LNG['a_s_ranking'] = "Ranking Settings";
$LNG['a_s_maxpage'] = "Number of data to list per page";
$LNG['a_s_dldir'] = "Directory path where you store your files to download (without trailing slash /)";
$LNG['a_s_num_list'] = "Number of members to list per page";
$LNG['a_s_ranking_period'] = "Ranking period";
$LNG['a_s_ranking_method'] = "Ranking method";
$LNG['a_s_ranking_average'] = "Rank by average or by just %s";
$LNG['a_s_featured_header'] = 'Show Featured Header';
$LNG['a_s_iscrontask'] = 'Run all tasks using Cron '; //new
$LNG['a_s_iscrontask_intv'] = 'Site tasks interval (in minutes)'; //new v2.3.442
$LNG['a_s_cronts'] = 'Latest Cron Job'; //new > v2.1.08214
$LNG['a_s_iscampstat'] = 'Enable member campaign tracking'; //new > v2.2.91730
$LNG['a_s_issitestats'] = 'Enable site stats '; //new > v2.1.08214
$LNG['a_s_purgestats'] = 'Purge site stats after'; //new > v2.2.08612
$LNG['a_s_top_skin_num'] = "Number of members to use the _top skin for";
$LNG['a_s_ad_breaks'] = "Show ads after these ranks";

$LNG['a_s_member'] = "Member Settings";
$LNG['a_s_genealogy'] = "Genealogy Settings";
$LNG['a_s_review'] = "Review Settings";
$LNG['a_s_messenger'] = "Members to members messaging system";
$LNG['a_s_active_default'] = "Require new members to be approved before being active";
$LNG['a_s_active_default_review'] = "Require new reviews to be approved before being listed";
$LNG['a_s_free_member'] = "Allow free member accounts";
$LNG['a_s_free_member_torefer'] = " (and allow free account to refer new signup or sales)"; //new > v2.2.80612
$LNG['a_s_delete_after'] = "Delete inactive or free members after this many days (type 0 to turn off)";
$LNG['a_s_delete_exp_after'] = "Delete expired members after this many days (type 0 to disable this feature)"; //new > v2.2.91730
$LNG['a_s_email2reg'] = "Allow member to register multiple accounts with one email address"; //new > v2.2.80612
$LNG['a_s_sprmanual'] = "Allow new members to enter their sponsor username manually when registering"; //new
$LNG['a_s_sprbypass'] = "Allow members to give away their new members to another member"; //new
$LNG['a_s_seembrleads'] = "Allow members to see their subscribers"; //new > v2.2.80401
$LNG['a_s_seembrleads_bulk'] = "(and enable import subscribers option)"; //new > v2.2.80612
$LNG['a_s_emailmbrleads'] = "Allow members to send emails to their subscribers"; //new > v2.2.80612
$LNG['a_s_emailmbrleads_batch'] = "Allow members to send emails to their subscribers"; //new > v2.2.80612
$LNG['a_s_emailmbrleads_send'] = "(broadcast emails only)"; //new > v2.2.80612
$LNG['a_s_emailmbrleads_auto'] = "(autoresponse emails only)"; //new > v2.2.80612
$LNG['a_s_emailmbrleads_both'] = "(both broadcast and autoresponse emails)"; //new > v2.2.80612
$LNG['a_s_emailmbrleads_limit'] = "Limit member broadcast per interval"; //new > v2.2.91730
$LNG['a_s_emailmbrleads_interval'] = "Member broadcast interval when limit reached (in days)"; //new > v2.2.91730
$LNG['a_s_emailmbrleads_batch'] = "Force members to sending or broadcast email in batch"; //new > v2.2.80612
$LNG['a_s_seembrspr'] = "Show sponsor details in member area"; //new
$LNG['a_s_seembrdl'] = "Show referral details in the member area"; //new > v2.2.80612
$LNG['a_s_seembrfull'] = "(complete details)"; //new > v2.2.80612

$LNG['a_s_email_admin_on_join'] = "Email admin when a new member register";
$LNG['a_s_email_admin_on_review'] = "Email admin when a new review is posted";
$LNG['a_s_max_image_width'] = "Maximum image width (type 0 to turn off and create proporsional image)";
$LNG['a_s_max_image_height'] = "Maximum image height (type 0 to turn off)";
$LNG['a_s_max_title_char'] = "Maximum site title characters (type 0 to maximum 255 chars)";
$LNG['a_s_max_descr_char'] = "Maximum site description characters (type 0 to maximum 255 chars)";
$LNG['a_s_doubleoptin_user'] = "Double Opt-In for new registration (available for free registration only)"; //new > v2.2.80401
$LNG['a_s_doubleoptin_lead'] = "Double Opt-In for new subscriber"; //new > v2.2.80401
$LNG['a_s_current_user_defaultphoto'] = "Current default image for members who do not supply one"; //new
$LNG['a_s_user_defaultphoto'] = "Default image for members who do not supply one (optional)";
$LNG['a_s_random_referrer'] = "Get random referrer if register without referrer or sponsor";
$LNG['a_s_default_referrer'] = "Default random referrer (type member username and separate with commas)";
$LNG['a_s_valid_referrer'] = "Prevent visitors to visit your site without referrer";
$LNG['a_s_valid_reff_all'] = "(all pages except login page)";
$LNG['a_s_valid_reff_join'] = "(registration page only)";
$LNG['a_s_valid_reff_lead'] = "(all pages except login page and activate subscription form)";
$LNG['a_s_valid_reff_rand'] = "(all pages except login page and display random referrer button)"; //new > v2.3.429

//new > v2.2.91730
$LNG['a_s_isewallet'] = "Enable E-Wallet";
$LNG['a_s_ewallet_transfer'] = "Enable transfer fund between members";
$LNG['a_s_ewallet_transferfee'] = "Transfer fee (deducted from the amount to transfer)";
$LNG['a_s_ewallet_withdrawfee'] = "Withdraw fee (will deducted from the amount to withdraw)";
$LNG['a_s_ewallet_join'] = "Allow pay the membership fee using E-Wallet balance";
$LNG['a_s_ewallet_buy'] = "Allow purchase the products (in the ezyCart plug-in) using E-Wallet balance";

$LNG['a_s_reffandspr'] = "Referrer and Sponsor"; //new > v2.2.91730
$LNG['a_s_imagasite'] = "Image and Site"; //new > v2.2.91730

$LNG['a_s_button'] = "Button Settings";
$LNG['a_s_ranks_on_buttons'] = "Ranks on buttons";
$LNG['a_s_stat_buttons'] = "Stat Buttons";
$LNG['a_s_button_url'] = "If Yes - URL to the default image you want to appear on members' sites";
$LNG['a_s_button_dir'] = "If Yes - URL to the directory the buttons are in";
$LNG['a_s_button_ext'] = "If Yes - Extension of the buttons (gif, png, jpg, etc.)";
$LNG['a_s_button_num'] = "If Yes - Number of buttons you have made";
$LNG['a_s_gateway'] = "Gateway page to deter cheating for hits in (Top Sites only)";

$LNG['a_s_genealogy_status'] = "Genealogy status";
$LNG['a_s_genealogy_opt1'] = "(limit to the maximum payplan members structure)"; //new > v2.2.91730
$LNG['a_s_genealogy_opt2'] = "(no limitation, members can see other members structure)"; //new > v2.2.91730
$LNG['a_s_genealogy_view'] = "Genealogy view in User CP"; //new > v2.2.91730
$LNG['a_s_genealogy_view_tree'] = "Tree view"; //new > v2.2.91730
$LNG['a_s_genealogy_view_board'] = "Board view"; //new > v2.2.91730
$LNG['a_s_genealogy_maxwidth'] = "Maximum genealogy tree width (0 = disabled, based on {$LNG['a_s_maxwidth']})";
$LNG['a_s_genealogy_maxdeep'] = "Maximum genealogy tree deep (0 = disabled, based on {$LNG['a_s_maxdeep']})";

$LNG['a_s_emailandcaptcha'] = "CAPTCHA and Email Settings";
$LNG['a_s_captcha_login'] = "Word verification (CAPTCHA) on login and registration form";
$LNG['a_s_captcha_login_reg'] = "Registration form (if no membership fee or free registration option is enable)"; //new > v2.3.443
$LNG['a_s_captcha_login_usr'] = "User CP login form"; //new > v2.3.443
$LNG['a_s_captcha_login_adm'] = "Admin CP login form"; //new > v2.3.443
$LNG['a_s_captcha_subscriber'] = "Word verification (CAPTCHA) on subscriber form";
$LNG['a_s_captcha_contact'] = "Word verification (CAPTCHA) on contact form";
$LNG['a_s_captcha_rc'] = "Using reCAPTCHA"; //new > v2.2.91730
$LNG['a_s_captcha_rc_publickey'] = "reCAPTCHA Public Key"; //new > v2.2.91730
$LNG['a_s_captcha_rc_privatekey'] = "reCAPTCHA Private Key"; //new > v2.2.91730

//new > v2.2.91730
$LNG['a_s_giftpass'] = "Enable GiftPass (Free Registration Coupon Code)";
$LNG['a_s_giftpass_syntax'] = "Default GiftPass Code Syntax";
$LNG['a_s_giftpass4user'] = "Allow Members to Purchase GiftPass from Their User CP";

$LNG['a_s_email'] = "Email Settings"; //new > v2.1.80214
$LNG['a_s_email_agent'] = "Mail Transfer Agent"; //new > v2.1.80214
$LNG['a_s_email_smtphost'] = "SMTP Hostname (ie. mail.hostname.com:25;mail.myhostname.com)"; //new > v2.1.80214
$LNG['a_s_email_smtpuser'] = "SMTP Username"; //new > v2.1.80214
$LNG['a_s_email_smtppass'] = "SMTP Password"; //new > v2.1.80214
$LNG['a_s_email_xmailer'] = "Return-Path Email (optional, not all server support for it)"; //new > v2.1.80214
$LNG['a_s_email_limit'] = "Sending email limit (per hours)";

$LNG['a_s_email_test_email'] = "<em>Test Email Address</em>"; //new > v2.2.80612
$LNG['a_s_email_test_email_invalid'] = "Please enter a valid email address!"; //new > v2.2.80612
$LNG['a_s_email_test_btn'] = "Send"; //new > v2.2.80612
$LNG['a_s_email_test_success'] = "<font face='tahoma' color=green>Test Email Sent</font>"; //new > v2.2.80612
$LNG['a_s_email_test_failed'] = "<font face='tahoma' color=red>Sending Test Email Failed!</font>"; //new > v2.2.80612

//new > v2.2.80612
$LNG['a_s_smser_test_sms'] = "<em>Test send SMS messages to</em>";
$LNG['a_s_smser_test_sms_invalid'] = "Please enter a valid phone number!";
$LNG['a_s_smser_test_btn'] = "Send";
$LNG['a_s_smser_disbtn_note'] = "<br /><br /><em><font size=1 color=red>Enable this button by setup User Custom Field for the member's phone.</font></em>"; //new > v2.3.450
$LNG['a_s_smser_test_success'] = "<font face='tahoma' color=green>Test SMS Sent</font>";
$LNG['a_s_smser_test_failed'] = "<font face='tahoma' color=red>Sending Test SMS Failed!</font>";

$LNG['a_s_other'] = "Other Settings";
$LNG['a_s_ezyalert'] = "<a href='http://www.ezygold.com/ezyalert' title='EzyAlert - EzyGold Notifier' target='_blank'>EzyAlert</a> integration";
$LNG['a_s_ezyalert0'] = "Disabled";
$LNG['a_s_ezyalert1'] = "Enabled";
$LNG['a_s_ezyalert2'] = "Based on Member Setting";
$LNG['a_s_search'] = "Search";
$LNG['a_s_time_offset'] = "Time offset from your server (in hours)";
$LNG['a_s_time_server'] = "Server Date Time";
$LNG['a_s_trackingway'] = "Referrer Tracking ID rule"; //new > v2.2.80612
$LNG['a_s_reflinkformat'] = "Referral Link format"; //new > v2.2.91730
$LNG['a_s_reflinkformat_def'] = "Default referral link"; //new > v2.2.91730
$LNG['a_s_reflinkformat_sub'] = "Sub-domain referral link"; //new > v2.2.91730
$LNG['a_s_trackingwayold'] = "Keep using old referrer Tracking ID (if available)"; //new > v2.2.80612
$LNG['a_s_trackingwaynew'] = "Always using new referrer Tracking ID"; //new > v2.2.80612
$LNG['a_s_trafficshare'] = "Traffic share to other members (in percentage, 0 or empty to disable)"; //new > v2.3.406
$LNG['a_s_cookie_days'] = "Cookie Timer (in days)"; //new
$LNG['a_s_se_friendly_links'] = "SE-friendly links";
$LNG['a_s_logdlfile'] = "Download log file name (empty = disabled)";

$LNG['a_s_unbanlist'] = "Reserved Username list";
$LNG['a_s_ipbanlist'] = "Blocked IP list";
$LNG['a_s_bademail'] = "Invalid e-mail list";
$LNG['a_s_badlicense_key'] = "Invalid License Key";
$LNG['a_s_valid_key'] = "Your current License Key is <b>valid</b>";
$LNG['a_s_pro_version'] = "(Pro! version)";

//new > v2.2.91730
$LNG['a_s_sms_gateway'] = "SMS Gateway";
$LNG['a_s_sms_username'] = "Account Username";
$LNG['a_s_sms_password'] = "Account Password";
$LNG['a_s_sms_apiid'] = "API ID";
$LNG['a_s_sms_apiurl'] = "API Url";
$LNG['a_s_sms_phonefield'] = "Member Cell Phone <a href='index.php?a=admin&b=fields'>Custom Field</a>";
$LNG['a_s_sms_from'] = "Sender ID (Send From)";

$LNG['a_s_join_status'] = "Enable New Registration"; //new > v2.2.90809
$LNG['a_s_site_status'] = "Website Status";
$LNG['a_s_offline_note'] = "Offline Note (will shown when {$LNG['a_s_site_status']} <b>{$LNG['g_offline']}</b>)";

$LNG['a_s_enable'] = "Enabled";
$LNG['a_s_disable'] = "Disabled";
$LNG['a_s_on'] = "On";
$LNG['a_s_off'] = "Off";
$LNG['a_s_days'] = "Days";
$LNG['a_s_months'] = "Months";
$LNG['a_s_weeks'] = "Weeks";
$LNG['a_s_yes'] = "Yes";
$LNG['a_s_no'] = "No";
$LNG['a_s_autopaid'] = "On - Auto Payout";
$LNG['a_s_withdraw'] = "On - Auto Withdraw";

$LNG['a_s_updated'] = "%s have been updated.";

// Admin > Members Campaign // new > v2.2.91730
$LNG['a_usrcampaign_header'] = "Members Campaign Tracking";
$LNG['a_usrcampaign_text'] = "Tracking";
$LNG['a_usrcampaign_ref'] = "Refferer";
$LNG['a_usrcampaign_hits'] = "Hits";
$LNG['a_usrcampaign_id'] = "#";
$LNG['a_usrcampaign_tothits'] = "Total Hits";
$LNG['a_usrcampaign_email'] = "Email";
$LNG['a_usrcampaign_username'] = "Username";

$LNG['a_usrcampaign_del_header'] = "Delete Member Campaign Tracking";
$LNG['a_usrcampaign_del_errorid'] = "Invalid Member Campaign ID";
$LNG['a_usrcampaign_del_warn'] = "Member Campaign Tracking: <strong>%s</strong><br />Are you sure you want to delete this campaign tracking?";
$LNG['a_usrcampaign_del_done'] = "Delete Member Campaign Tracking Done.";

// Admin > Downline Builder
$LNG['a_dlbuilder_header'] = "Manage Downline Builder Site";
$LNG['a_dlbuilder_option'] = "Downline Builder System (%s)"; //update
$LNG['a_dlbuilder_optionadm'] = "(setup by Administrator only)"; //new > v2.2.91730
$LNG['a_dlbuilder_optionusr'] = "(setup by Administrator and Members)"; //new > v2.2.91730
$LNG['a_dlbuilder_usrsitelimit'] = "Limit Member Downline Builder Site"; //new > v2.2.91730
$LNG['a_dlbuilder_usr4user'] = "Allow Referrals to Insert Their Site Username or ID"; //new > v2.2.91730
$LNG['a_dlbuilder_dlbfromref'] = "Always Follow the Username from Referrer"; //new > v2.3.429
$LNG['a_dlbuilder_title_form'] = "Create/Update Downline Builder Site";
$LNG['a_dlbuilder_title'] = "Site Title";
$LNG['a_dlbuilder_url'] = "Site URL (include http://)";
$LNG['a_dlbuilder_urltag'] = "<font color=#779955>Site URL Tag:</font>"; //new > v2.2.91729
$LNG['a_dlbuilder_title_error'] = "Please enter site title or use another site title";
$LNG['a_dlbuilder_url_error'] = "Please enter valid URL address";
$LNG['a_dlbuilder_username'] = "Your Site Username or ID";
$LNG['a_dlbuilder_info'] = "Site Descriptions";
$LNG['a_dlbuilder_imgurl'] = "Site Image or Logo Location (url or path)";
$LNG['a_dlbuilder_cloack'] = "Hide Referral Link (cloacking)";
$LNG['a_dlbuilder_4user'] = "Enable Members to Insert Their Site Username or ID";
$LNG['a_dlbuilder_avalfor'] = "Available for";
$LNG['a_dlbuilder_avalfor_0'] = "Disable";
$LNG['a_dlbuilder_avalfor_1'] = "All";
$LNG['a_dlbuilder_avalfor_2'] = "Members Only";
$LNG['a_dlbuilder_avalfor_3'] = "Public Only";
$LNG['a_dlbuilder_order'] = "Site List Order";
$LNG['a_dlbuilder_tb_order'] = "Sorting Order";
$LNG['a_dlbuilder_btn_order'] = "Update Site Order"; //new > v2.2.91730
$LNG['a_dlbuilder_new_button'] = "Insert New Site";

$LNG['a_dlbuilder_del_header'] = "Delete Downline Builder Site";
$LNG['a_dlbuilder_del_errorid'] = "Invalid Downline Builder Site ID";
$LNG['a_dlbuilder_del_warn'] = "Downline Builder Site Title: <strong>%s</strong><br />Are you sure you want to delete this site (and all user data belong to this site)?";
$LNG['a_dlbuilder_del_done'] = "Delete Downline Builder Site Done.";

// Admin > Manage Addons //new
$LNG['a_addons_header'] = "Manage Site Addons";
$LNG['a_addons_folder'] = "Folder";
$LNG['a_addons_name'] = "Name";
$LNG['a_addons_group'] = "Group";
$LNG['a_addons_install'] = "Install Add-on";
$LNG['a_addons_uninstall'] = "Uninstall Add-on";
$LNG['a_addons_nodesc'] = "<em>Addon description not found!</em>";
$LNG['a_addons_updatecfg'] = "Update Addon configuration file";
$LNG['a_addons_cfgfile'] = "Update configuration file for <strong>%s</strong> add-on";
$LNG['a_addons_editconfirm'] = "Are you sure want to update this configuration file?";
$LNG['a_addons_savebtn'] = "Save file";

// Admin > manage AdsGroup 2.3.450
$LNG['a_adsgroup_header'] = "Manage Ads Group";
$LNG['a_adsgroup_agtitle'] = "Group Title";
$LNG['a_adsgroup_agnote'] = "Descriptions";
$LNG['a_adsgroup_status'] = "Status";
$LNG['a_adsgroup_manage'] = "Create/Update Ads Group";
$LNG['a_adsgroup_create'] = "Create New Ads Group";

$LNG['a_adsgroup_agtype'] = "Ads Type";
$LNG['a_adsgroup_agbase'] = "Ads Credit Based";
$LNG['a_adsgroup_agcredits'] = "Default Ads Credit";
$LNG['a_usersads_agcredits_time'] = "(in days)";
$LNG['a_adsgroup_agmaxchars'] = "Ads Characters Limit (text type ads)";
$LNG['a_adsgroup_charsmax'] = "chars max.";
$LNG['a_adsgroup_agsizew'] = "Ads Size - Wide";
$LNG['a_adsgroup_agsizeh'] = "Ads Size - Height";
$LNG['a_adsgroup_agplanids'] = "Available Payplan";
$LNG['a_adsgroup_instore'] = "Available in eStore";

$LNG['a_adsgroup_updatestatus'] = "Update Ads Group Status";
$LNG['a_adsgroup_optupdated'] = "Ads Group Updated.";
$LNG['a_adsgroup_statusupdated'] = "Ads Group Status Updated.";
$LNG['a_adsgroup_displaycode'] = "Ads Group Code";

$LNG['a_adsgroup_del_header'] = "Delete Ads Group";
$LNG['a_adsgroup_del_ads'] = "Also <font color=red>remove all ads</font> belong to this group";
$LNG['a_adsgroup_del_adswarn'] = "Are you sure want to delete all member ads belong to this groups?";
$LNG['a_adsgroup_del_warn'] = "Ads Group <strong>%s</strong><br />Are you sure you want to delete this Ads Group?";
$LNG['a_adsgroup_del_warns'] = "Ads Groups <strong>%s</strong><br />Are you sure you want to delete these Ads Groups?";
$LNG['a_adsgroup_del_invalid'] = "Invalid Ads Group ID. Please try again.";
$LNG['a_adsgroup_del_button'] = "Confirm Deletion";
$LNG['a_adsgroup_del_done'] = "Ads Group has been deleted.";

$LNG['a_adsgroup_titledefault'] = "-- Your Ad --";
$LNG['a_adsgroup_adsdefault'] = "Advertise Your Business Here";

// Admin > manage UsersAds 2.3.450
$LNG['a_usersads_header'] = "Manage Member Ads";
$LNG['a_usersads_uatitle'] = "Ads Title";
$LNG['a_usersads_uaurl'] = "Ads URL";
$LNG['a_usersads_uacontent'] = "Ads Content";
$LNG['a_usersads_uacontent_banner'] = "Banner URL";
$LNG['a_usersads_status'] = "Status";
$LNG['a_usersads_status_0waiting'] = "Waiting";
$LNG['a_usersads_status_1running'] = "Running";
$LNG['a_usersads_status_2pause'] = "&rarr; Pause";
$LNG['a_usersads_status_3review'] = "&rarr; Review";
$LNG['a_usersads_status_4finish'] = "&rarr; Finish";
$LNG['a_usersads_status_9hold'] = "&rarr; Hold";
$LNG['a_usersads_manage'] = "Create/Update Member Ads";
$LNG['a_usersads_create'] = "Create New Member Ads";
$LNG['a_usersads_uakeywords'] = "Keywords (separated by comma)";
$LNG['a_usersads_uainfo'] = "Member Ads Note (displayed on Admin CP only)";

$LNG['a_usersads_isbanner'] = "<strong>Banner Image:</strong>";
$LNG['a_usersads_nobanner'] = "No banner file available";
$LNG['a_usersads_agtype'] = "Ads Type";
$LNG['a_usersads_agbase'] = "Ads Credit Based";
$LNG['a_usersads_uambrid'] = "Member ID";
$LNG['a_usersads_uastartdate'] = "Start Date";
$LNG['a_usersads_uaenddate'] = "End Date";
$LNG['a_usersads_uaruntime'] = "Last Runtime";
$LNG['a_usersads_uagid'] = "Ads Group";
$LNG['a_usersads_uaitemid'] = "Items on eStore";
$LNG['a_usersads_uacredits'] = "Ads Credit";
$LNG['a_usersads_uacredits_note'] = "(0 = unlimited - if ads based on time interval)";
$LNG['a_usersads_uahits'] = "Impression";
$LNG['a_usersads_uaclicks'] = "Clicks";

$LNG['a_usersads_updatestatus'] = "Update Member Ads Status";
$LNG['a_usersads_optupdated'] = "Member Ads Updated.";
$LNG['a_usersads_statusupdated'] = "Member Ads Status Updated.";
$LNG['a_usersads_uainfonote'] = "Renew: ";

$LNG['a_usersads_del_header'] = "Delete Member Ads";
$LNG['a_usersads_del_warn'] = "Member Ads <strong>%s</strong><br />Are you sure you want to delete this Member Ads?";
$LNG['a_usersads_del_warns'] = "Member Ads <strong>%s</strong><br />Are you sure you want to delete these Member Ads?";
$LNG['a_usersads_del_invalid'] = "Invalid Member Ads ID. Please try again. ";
$LNG['a_usersads_del_button'] = "Confirm Deletion";
$LNG['a_usersads_del_done'] = "Member Ads has been deleted. ";

// Admin > Itemized Categories
$LNG['a_itemized_header'] = "Manage Item Categories";
$LNG['a_itemized_categories_done'] = "The category status have been set.";
$LNG['a_itemized_new_category_done'] = "The new category has been created.";
$LNG['a_itemized_delete_done'] = "The category has been deleted.";
$LNG['a_itemized_delete_error'] = "The category cannot be deleted because you must have at least one category.";
$LNG['a_itemized_edit_done'] = "The category has been edited.";
$LNG['a_itemized_category'] = "Category";
$LNG['a_itemized_parent'] = "Parent Category";
$LNG['a_itemized_name'] = "Category Name";
$LNG['a_itemized_descr'] = "Descriptions";
$LNG['a_itemized_status'] = "Status";
$LNG['a_itemized_status0'] = "Hidden";
$LNG['a_itemized_status1'] = "Available";
$LNG['a_itemized_status2'] = "Private (available after login)";
$LNG['a_itemized_keywords'] = "Keywords";
$LNG['a_itemized_sort'] = "Category Order";
$LNG['a_itemized_items'] = "items";
$LNG['a_itemized_new_category'] = "Create New Category";
$LNG['a_itemized_no_action'] = "Invalid Category. No action taken!";
$LNG['a_itemized_set_status'] = "Set Category Status";
$LNG['a_itemized_edit_category'] = "Edit Category";
$LNG['a_itemized_diff_status'] = "If you want different status for different categories, select them below.";
$LNG['a_itemized_delcat_warn'] = "Are you sure want to delete \\'%s\\' category?";
$LNG['a_itemized_cmlist'] = "Default Affiliate Commission List"; //new > v2.2.80612

// Admin > Product Items
$LNG['a_items_header'] = "Manage Products";
$LNG['a_items_products_done'] = "The product status have been set.";
$LNG['a_items_new_product_done'] = "The new product has been created.";
$LNG['a_items_delete_done'] = "The product has been deleted.";
$LNG['a_items_delete_error'] = "The product cannot be deleted because you must have at least one product.";
$LNG['a_items_edit_done'] = "The product has been edited.";
$LNG['a_items_product'] = "Product";
$LNG['a_items_date'] = "Date";
$LNG['a_items_category'] = "Category";
$LNG['a_items_parentitem'] = "Parent Item";
$LNG['a_items_file'] = "File Download";
$LNG['a_items_name'] = "Product Name";
$LNG['a_items_subname'] = "Product Info (optional, additional information of {$LNG['a_items_name']})"; //new > v2.2.80401
$LNG['a_items_id'] = "Product ID";
$LNG['a_items_pict'] = "Item Image Tumbnail"; //update > v2.3.442
$LNG['a_items_pict_remove'] = "Remove existing image"; //new > v2.2.91730
$LNG['a_items_sort'] = "Item Order"; //new > v2.2.91730
$LNG['a_items_note'] = "Note (sort descriptions)";
$LNG['a_items_note_r'] = "Info";
$LNG['a_items_descr'] = "Descriptions";
$LNG['a_items_price'] = "Product Price";
$LNG['a_items_actprice'] = "Product Price (special price for Active members, optional)";
$LNG['a_items_bulkprice'] = "Renewal Price (optional)";
$LNG['a_items_oldprice'] = "Old Price (optional)";
$LNG['a_items_cmlist'] = "Affiliate Commission List";
$LNG['a_items_affdis'] = " (<font color=red><em>Affiliate program currently disable, <a href='index.php?a=admin&b=store'>click here</a> to enable this feature.</em></font>)"; //new > v2.3.450
$LNG['a_items_buyercm'] = "Buyer Commission"; //new > v2.2.90809
$LNG['a_items_isxup'] = "X-Up System Commission"; //new > v2.2.90809
$LNG['a_items_xupcm'] = "X-Up Level # and Commission List"; //new > v2.2.90809
$LNG['a_items_type'] = "Product Type"; //new > v2.2.91730
$LNG['a_items_type1'] = "Downloadable"; //new > v2.2.91730
$LNG['a_items_type2'] = "Service"; //new > v2.2.91730
$LNG['a_items_type3'] = "Other"; //new > v2.2.91730
$LNG['a_items_status'] = "Status";
$LNG['a_items_status0'] = "Hidden";
$LNG['a_items_status1'] = "Available";
$LNG['a_items_status2'] = "Private (available after login)";
$LNG['a_items_status3'] = "Private (available for OTO page)"; //new > v2.2.80612
$LNG['a_items_usercplist'] = "Listed in the User CP ({$LNG['client_affiliate']} page)";
$LNG['a_items_usekey'] = "License Key";
$LNG['a_items_keyfile'] = "File Name (to generate License Key)";
$LNG['a_items_weight'] = "Item Weight (optional)";

$LNG['a_items_service_type'] = "Service Type"; //new > v2.3.450
$LNG['a_items_service_giftpass'] = "Generate GiftPass Code"; //new > v2.3.450
$LNG['a_items_service_ewallet'] = "Add E-Wallet Fund"; //new > v2.3.450
$LNG['a_items_service_ads'] = "Add Ads Credits"; //new > v2.3.450
$LNG['a_items_service_other'] = "Other"; //new > v2.3.450
$LNG['a_items_ewallet_amount'] = "E-Walled Amount (fixed or pecentage from the <em>{$LNG['a_items_price']}</em>)"; //new > v2.3.450
$LNG['a_items_giftpass_syntax'] = "GiftPass Syntax Format"; //new > v2.3.450
$LNG['a_items_giftpass_validfor'] = "Valid for (in days, empty to disable)"; //new > v2.3.450
$LNG['a_items_giftpass_qty'] = "GiftPass Code Quantity"; //new > v2.3.450
$LNG['a_items_giftpass_value'] = "GiftPass Value"; //new > v2.3.450
$LNG['a_items_ads_group'] = "Ads Group"; //new > v2.3.450
$LNG['a_items_ads_value'] = "Ads Credits (will use <em>Renew Period</em> value if Ads Group based on time interval)"; //new > v2.3.450

$LNG['a_items_dlgroup'] = "Members Download Group"; //new > v2.1.80214
$LNG['a_items_argroup'] = "Responder Group"; //new > v2.2.90809
$LNG['a_items_usekey0'] = "No";
$LNG['a_items_usekey1'] = "Yes";
$LNG['a_items_stock'] = "Product Available (in stock, 0 = unlimited)";
$LNG['a_items_stock_r'] = "In stock";
$LNG['a_items_dayexp'] = "Renew Period (days, optional, 0 = disabled)"; //update > v2.2.91730
$LNG['a_items_autoship'] = "Enable Subscription Payments"; //new > v2.2.91730
$LNG['a_items_subscription'] = "<font color='#009933'><em>Subscription</em></font>"; //new > v2.2.91730
$LNG['a_items_dayexp_r'] = "Expired";
$LNG['a_items_dayexp_rdays'] = "days";
$LNG['a_items_keywords'] = "Keywords";
$LNG['a_items_unlimited'] = "Unlimited";
$LNG['a_items_new_product'] = "Create New Product";
$LNG['a_items_no_action'] = "Invalid Product ID. No action taken!";
$LNG['a_items_set_status'] = "Set Product Status";
$LNG['a_items_edit_product'] = "Edit Product";
$LNG['a_items_diff_status'] = "If you want different status for different products, select them below.";
$LNG['a_items_clone_warn'] = "Are you sure want to duplicate product: %s?"; //new
$LNG['a_items_delcat_warn'] = "Are you sure want to delete product: %s?";
$LNG['a_items_details'] = "details"; //new

// Admin > Ads Categories //new > v2.1.80214
$LNG['a_adscats_header'] = "Manage Ads Categories";
$LNG['a_adscats_menu'] = "Ads Category";
$LNG['a_adscats_menu_manage'] = "(<a href='index.php?a=admin&amp;b=adscats'>manage</a>)"; //new > v2.1.80214
$LNG['a_adscats_categories_done'] = "The category ads have been set.";
$LNG['a_adscats_new_category_done'] = "The new category has been created.";
$LNG['a_adscats_delete_done'] = "The category has been deleted.";
$LNG['a_adscats_delete_error'] = "The category cannot be deleted because you must have at least one category.";
$LNG['a_adscats_edit_done'] = "The category has been edited.";
$LNG['a_adscats_categories'] = "Ads Categories";
$LNG['a_adscats_new_category'] = "Create New Category";
$LNG['a_adscats_edit_category'] = "Edit Category";
$LNG['a_adscats_category_name'] = "Category Name";
$LNG['a_adscats_available'] = "Available Categories:";
$LNG['a_adscats_delcat_warn'] = "Are you sure want to delete \\'%s\\' category?";

// Admin > File Categories //new > v2.1.80214 //update > v2.2.91730
$LNG['a_filecats_header'] = "Manage File and Video Categories";
$LNG['a_filecats_categories_done'] = "The category have been set.";
$LNG['a_filecats_new_category_done'] = "The new category has been created.";
$LNG['a_filecats_delete_done'] = "The category has been deleted.";
$LNG['a_filecats_delete_error'] = "The category cannot be deleted because you must have at least one category.";
$LNG['a_filecats_edit_done'] = "The category has been edited.";
$LNG['a_filecats_categories'] = "File or Video Categories";
$LNG['a_filecats_new_category'] = "Create New Category";
$LNG['a_filecats_edit_category'] = "Edit Category";
$LNG['a_filecats_category_name'] = "Category Name";
$LNG['a_filecats_available'] = "Available Categories:";
$LNG['a_filecats_delcat_warn'] = "Are you sure want to delete \\'%s\\' category?";
$LNG['a_filecats_category_tdf'] = "Downloadable File"; //new > v2.2.91730
$LNG['a_filecats_category_tov'] = "Online Video"; //new > v2.2.91730

// Admin > Site Themes and Categories
$LNG['a_themes_header'] = "Manage Site Themes";
$LNG['a_themes_categories_header'] = "Manage User Site Categories and Themes";
$LNG['a_themes_default'] = "Default Theme";
$LNG['a_themes_set_default'] = "Set Default Theme";
$LNG['a_themes_anon'] = "Anonymous";
$LNG['a_themes_default_done'] = "The default theme has been set.";
$LNG['a_themes_categories_done'] = "The category theme have been set.";
$LNG['a_themes_new_category_done'] = "The new category has been created.";
$LNG['a_themes_delete_done'] = "The category has been deleted.";
$LNG['a_themes_delete_error'] = "The category cannot be deleted because you must have at least one category.";
$LNG['a_themes_edit_done'] = "The category has been edited.";
$LNG['a_themes_invalid_skin'] = "Invalid theme: %s.  Please try again.";
$LNG['a_themes_categories'] = "Site Categories";
$LNG['a_themes_new_category'] = "Create New Category";
$LNG['a_themes_set_themes'] = "Set Category Themes";
$LNG['a_themes_edit_category'] = "Edit Category";
$LNG['a_themes_category_name'] = "Category Name";
$LNG['a_themes_diff_themes'] = "If you want different theme for different categories, select them below.";
$LNG['a_themes_delcat_warn'] = "Are you sure want to delete \\'%s\\' category?";
$LNG['a_themes_incompatible'] = "<br /><font color=red size=1><em><b>Warning:</b> This theme <b>may not be compatible</b> with your current EzyGold version. <b>Do not use on live site!</b></em></font>"; //new > v2.2.80612
$LNG['a_themes_incompatiblever'] = "<br /><font color='#C10D31' size=1><em><b>Note:</b> This theme <b>may not be compatible</b> with your current EzyGold version. <b>Do not use on live site!</b></em></font>"; //new > v2.3.450

// Admin > Email Templates //new > v2.2.80401
$LNG['a_emailtpl_contact_email_admin'] = "Contact Email Form (Send To Administrator)";
$LNG['a_emailtpl_join_email'] = "Registration Welcome Email (Send To Member)";
$LNG['a_emailtpl_join_email_admin'] = "New Member Email Notification (Send To Administrator)";
$LNG['a_emailtpl_join_email_confirm'] = "Registration Confirmation Email (Send To Member)";
$LNG['a_emailtpl_join_email_free'] = "Free Registration Welcome Email (Send To Member)";
$LNG['a_emailtpl_lost_pw_email'] = "Lost Password Email (Send To Member)";
$LNG['a_emailtpl_notify_account_expired'] = "Account Expired Email Notification (Send To Member)";
$LNG['a_emailtpl_notify_account_will_expired'] = "Account Will be Expired Email Notification (Send To Member)";
$LNG['a_emailtpl_notify_commission_paid'] = "New Commission Email Notification (Send To Member)";
$LNG['a_emailtpl_notify_new_register'] = "New Downline Email Notification (Send To Member)"; //new > v2.3.450
$LNG['a_emailtpl_notify_new_referral'] = "New Referral Email Notification (Send To Member - Free registration)";
$LNG['a_emailtpl_order_email'] = "Order Email Details (Send To Member)";
$LNG['a_emailtpl_rate_email_admin'] = "Rating Email Details (Send To Administrator)";
$LNG['a_emailtpl_renew_email'] = "Renewal Email Notification (Send To Member)";
$LNG['a_emailtpl_renew_email_admin'] = "Renewal Member Email Notification (Send To Administrator)";
$LNG['a_emailtpl_updateplan_email'] = "Update Membership Email Notification (Send To Member)"; //new > v2.2.91730
$LNG['a_emailtpl_updateplan_email_admin'] = "Update Membership Email Notification (Send To Administrator)"; //new > v2.2.91730
$LNG['a_emailtpl_subscriber_activation_email'] = "Activation Email (Send To Subscriber)";
$LNG['a_emailtpl_subscriber_welcome_email'] = "Welcome Email (Send To Subscriber)";
$LNG['a_emailtpl_subscriber_notify_email'] = "New Subscriber Email Notification (Send To Member)";
$LNG['a_emailtpl_send_email'] = "Enable Sending Email";
$LNG['a_emailtpl_send_sms'] = "Enable Sending SMS";

// Stats // new > v2.3.442
$LNG['stats_account'] = "Account Stats";
$LNG['stats_country'] = "Country Stats";
$LNG['stats_product'] = "Product Stats";
$LNG['stats_daily'] = "Daily Stats";
$LNG['stats_daily_subscriber'] = "Subscribers";
$LNG['stats_daily_member'] = "Members Signup";
$LNG['stats_daily_day'] = "Day";
$LNG['stats_dailysales'] = "Daily Sales Stats";
$LNG['stats_dailysales_sales'] = "Sales";
$LNG['stats_dailysales_day'] = "Day";

// eStore
$LNG['estore_header'] = "Online Store";
$LNG['estore_cart_items'] = "Items";
$LNG['estore_cart_total'] = "Total";
$LNG['estore_cart_subtotal'] = "Subtotal"; // new > v2.3.442
$LNG['estore_menu_order'] = "Products";
$LNG['estore_addto_cart'] = "Add To Cart";
$LNG['estore_ord_qty'] = "Quantity";
$LNG['estore_ord_oldprice'] = "Price";
$LNG['estore_ord_price'] = "Promo";
$LNG['estore_continue_shopping'] = "Continue Shopping";
$LNG['estore_update'] = "Update";
$LNG['estore_checkout'] = "Checkout";
$LNG['estore_mycart'] = "My Cart";
$LNG['estore_order'] = "Order"; // new > v2.2.91730
$LNG['estore_item_soldout'] = " / <font color=red>Out of Stock</font>"; // new > v2.2.91730
$LNG['estore_item_instock'] = ""; // new > v2.2.91730
$LNG['estore_delete_alert'] = "Are you sure want to delete this item";
$LNG['estore_delete'] = "Delete item";
$LNG['estore_tb_item'] = "Items";
$LNG['estore_tb_price'] = "Price";
$LNG['estore_tb_qty'] = "Qty";
$LNG['estore_tb_subtotal'] = "Subtotal";
$LNG['estore_noitem'] = "No item selected, please at least add one item";
$LNG['estore_urlredir'] = "<font face='tahoma' size='1' color='#666666'>Please <a href='%s'>click here</a> if your browser does not automatically redirect you.</font>"; // new > v2.2.80612
$LNG['order_receipt'] = "your order receipt";
$LNG['estore_mycart_tax'] = "Tax"; // new > v2.3.442
$LNG['estore_mycart_snh'] = "Shipping &amp; handling"; // new > v2.3.442
$LNG['estore_mycart_noitem'] = "There is no item in your shopping cart";
$LNG['estore_mycart_note1'] = "If you changed the quantity (Qty), please update your cart by clicking <strong>{$LNG['estore_update']}</strong> button.";
$LNG['estore_mycart_note2'] = "To proccess <strong>{$LNG['estore_checkout']}</strong>, please <a href='%s' title='Login with your username and password'>login</a> or <a href='%s' title='Register'>register</a>.";
$LNG['estore_mycart_note3'] = "After login or register, all items in your cart above will be automatically move to your account and you can continue checkout proccess.";
$LNG['estore_mycart_note4'] = "When registered, you can manage your account, order history, download your products, etc.";
$LNG['estore_mycart_discount'] = "<br /><strong>Your discount is <font color=green size=2>%s</font></strong> (<em>from %s</em>)"; //new > v2.2.80612
$LNG['estore_failamountpay'] = "[UPDATE] Invalid payment amount, manual verification required."; // new > v2.2.91730
$LNG['estore_paywithewallet'] = "Please click E-Wallet button below to confim your payment:"; // new > v2.2.91730

// AutoPay Status
$LNG['paystatus_wait'] = "Waiting";
$LNG['paystatus_paid'] = "PAID";
$LNG['paystatus_try'] = "Trying";
$LNG['paystatus_pending'] = "Pending";
$LNG['paystatus_unpaid'] = "UNPAID"; // new > v2.2.80612
$LNG['paystatus_hold'] = "On Hold";

// IPN
$LNG['ipn_joinfee'] = "Membership Fee ::";
$LNG['ipn_carefee'] = "Maintenance Fee ::"; //new
$LNG['ipn_matchcom'] = "Fast Start Commission"; // update > v2.2.80401
$LNG['ipn_paircom'] = "Pairing Commission";
$LNG['ipn_saving'] = ":: Saving";
$LNG['ipn_dirrefcom'] = "Direct Referral Commission";
$LNG['ipn_dirrefcomx'] = "Level %s Direct Referral Commission";
$LNG['ipn_admcp_dirrefcom'] = "Ref Earning :: %s"; // new > v2.2.80612
$LNG['ipn_carecom'] = "Level %s Membership Commission"; //new > v2.1.0214
$LNG['ipn_qualcom'] = "Qualified %s Pass-Up Commission";
$LNG['ipn_xupcom'] = "%s-Up System Commission";
$LNG['ipn_levelcom'] = "Level %s Referral Commission";
$LNG['ipn_flevelrwd'] = "Full Level [%s] Reward";
$LNG['ipn_flevelrwdspr'] = "Matching Commission [%s]"; // new > v2.2.80401
$LNG['ipn_binpaircom'] = "Binary Pairing Commission";
$LNG['ipn_sprrefund'] = "Refund from sponsor (%s)";
$LNG['ipn_dailyrwd'] = "Daily Reward";
$LNG['ipn_weeklyrwd'] = "Weekly Reward"; //new > v2.1.0214
$LNG['ipn_monthlyrwd'] = "Monthly Reward";
$LNG['ipn_cyclingrwd'] = "Recycling %s Reward"; //new
$LNG['ipn_cyclingrwd4spr'] = "Referral Recycling %s Reward"; //new > v2.2.90809
$LNG['ipn_totalfee_details'] = "Membership fee %s, additional %s processing fee %s"; // new > v2.2.80612
$LNG['ipn_refracerwd'] = "Referrer Contest Reward #%s"; // new > v2.2.91730
$LNG['ipn_refracerwdspr'] = "Matching Referrer Contest Reward #%s [Level %s]"; // new > v2.2.91730
$LNG['ipn_updatefee'] = "Membership Update Fee ::"; // new > v2.2.91730

$LNG['ipn_store_order'] = "Order ::";
$LNG['ipn_store_affcom'] = "Affiliate Commission ::";
$LNG['ipn_store_buyercom'] = "Buyer Commission ::"; // new > v2.2.90809
$LNG['ipn_store_subaffcom'] = "Sub-affiliate (%s) Commission ::";
$LNG['ipn_store_saving'] = ":: Saving";
$LNG['ipn_store_totalpay'] = "This transaction amount including additional %s processing fee %s"; // new > v2.2.80612

// EzyGold REST API // new > v2.2.91730
$LNG['api_format_error'] = "Please use correct syntax or format.";
$LNG['api_user_not_found'] = "User could not be found.";
$LNG['api_user_added'] = "New user registered.";
$LNG['api_user_added_no'] = "Register failed.";
$LNG['api_user_planupdated'] = "User payplan updated.";
$LNG['api_user_updated'] = "User updated.";
$LNG['api_user_updated_no'] = "User failed to update.";
$LNG['api_user_invalidhash'] = "Invalid API hash.";
$LNG['api_user_unknownpostact'] = "Unknown API task.";
$LNG['api_sales_not_found'] = "Sales or history could not be found.";
$LNG['api_sales_updated'] = "Sales updated.";
$LNG['api_sales_added'] = "New sales added.";
$LNG['api_sales_added_no'] = "Sales not added.";

// EzyAlert
$LNG['ezyalert_unknown_error'] = "Unknown Error!";
$LNG['ezyalert_verify_failed'] = "Verification Failed";
$LNG['ezyalert_invalid_key'] = "Invalid license key, please contact system Administrator";
$LNG['ezyalert_admin_disabled'] = "System has disabled by Administrator";
$LNG['ezyalert_user_disabled'] = "User access was restricted by Administrator";

// Terms and Aggreements
$LNG['terms_i_agree'] = "I agree to the <a href='%s' target='_blank' title='Terms and Conditions'>terms and conditions</a>"; // new > v2.2.80401
$LNG['terms_content'] = "End User License Agreement (EULA):<br /><br />Place your terms, agreements, policy here"; // new > v2.2.80401

// ------- Help Hints ---------------------------------->>> //
$LNG['hint_ad_breaks'] = "You can display ads between the member's ads.<br /><br />There are two template files for ad breaks. <b>ad_break_top.html</b> is for when the ad break occurs while the _top templates are in use. <b>ad_break.html</b> is for the rest of the list.";

$LNG['hint_ranks_on_buttons'] = "<b>Static</b><br />Static buttons means that all your members display the same unchanging button on their website. To use static buttons, set 'Ranks on buttons' to No and set the URL to your button.<br /><br /><b>Ranks on Buttons</b><br />Ranks on buttons means that your members will get a button that dynamically displays their rank. You must create these buttons with ranks yourself. Name them 1.png, 2.png, 3.png, etc. up as high as you want. Upload the buttons to your server. Go to the settings section of the admin to set the directory you uploaded the buttons to, their file extension, and the number of buttons you made. Also set a default button that will be given to members who are low on your member list.<br /><br />This feature takes some server resources to put ranks on buttons. If you are having problems with performance, you may have to disable this feature.<br /><br /><b>Stat Buttons</b><br />Stat buttons give you the most control over how your buttons look, but it is also the most difficult to configure and uses the most server resources. Only use stat buttons if you have a powerful server and some PHP knowledge.<br /><br />To use stat buttons, first select it then edit settings_buttons.php to dynamically display stats on the button. You can put any stat that the script keeps on the button.";

$LNG['hint_se_friendly_links'] = "When this feature is enabled, your members will be given code that links directly to your site. Instead of http://www.domain.com/index.php?a=details, the links will look like http://www.domain.com/details. That way, your site becomes a powerful search engine marketing tool because of all those links.<br /><br />To enabled this feature, you need install this system on apache server with <b>mod_rewrite</b> enabled."; //update > v2.2.80612

$LNG['hint_featured_header'] = "You can show sub header on every page in your site. As default, this header will show member's site. You can <a href='index.php?a=admin&amp;b=tpl_pages&amp;f=ZmVhdHVyZWRfaGVhZGVyLmh0bWw%3D' title='Customize sub header page'>customize</a> this header by editing <b>featured_header.html</b> file in your templates directory."; //update > v2.2.80612

$LNG['hint_iscrontask'] = "If you select all task run by using cron, then you need run <b>cronexec.php</b> file using cron job (each 5 or 10 minutes) in your account hosting control panel (CPanel).<br /><br />Example:<br />cd /<font color=blue>_htdoc_dir_</font>/ && /<font color=blue>_php_dir_</font>/php cronexec.php >/dev/null<br /><br />Where:<br />/<font color=blue>_htdoc_dir_</font>/ = full path to the cronexec.php file directory<br />ie. /home/xxx/public_html/<br /><br />/<font color=blue>_php_dir_</font>/php = full path to the php directory<br />ie. /usr/local/bin/php<br /><br />%s<br /><br />Selecting {$LNG['g_yes']}++ will execute the cron task both from the site and cron job (not recommended if you have hight traffic)."; //new

$LNG['hint_purgestats'] = "This feature is used to limit the amount of site stats records.<br /><br />If you set this value to <b>0</b> (or empty) then the stas records will not deleted. If you want to purge all records every 2 weeks then set this value to 14.<br /><br />You can also set the purge date on calendar format,<br />ie. <b>2w</b> for 2 weeks, <b>4m</b> for 4 months, <b>1y</b> for 1 year, etc.<br /><br />Example: if you set this value to 2y, then all records older than 2 years will deleted."; //new > v2.2.80612

$LNG['hint_squeezer'] = "Select <strong>Yes</strong> to enable Squeeze Page feature.<br /><br />You can update or manage the squeeze page by editing <strong>squeezer.html</strong> and <strong>squeezer_form.html</strong> files inside templates/[theme name] folder or simply click <a href='index.php?a=admin&amp;b=tpl_pages'>here</a> to update this file directly inside Admin CP.<br /><br />You can select Test Mode if you want to check how your squeeze page work. Your squeeze page test url is <a href='{$CONF['site_url']}/squeezepage' target='_blank'>{$CONF['site_url']}/squeezepage</a>"; //update > v2.2.91730

$LNG['hint_free_user'] = "Select <b>{$LNG['a_s_enable']}</b> if you want to accept free member and then give them an option to upgrade their account later. When this option selected, new signup will placed under Administrator or active sponsor only, the actual member position will restructured when account upgraded to the paid or active member.<br /><br />Select <b>{$LNG['a_s_enable']}{$LNG['a_s_free_member_torefer']}</b> if you want to accept free member and allow them to refer (and get credit for) new signup or sales. These free members also have an option to upgrade their account later. <strong>Note:</strong> please do not select this option when x-Up payplan enable.<br /><br />You need to enable this option when you accept offline payment (ie. bank transfer, check, direct payment, etc)."; //update > v2.2.80612

$LNG['hint_manual_activation'] = "Set default to Off. It's recommended to keep this option to Off (disable) when you are using paid membership or payplan.<br /><br />Generally this feature used when there is no paid membership available. Unless you have specific reason to enable this feature, you can keep this option Off."; //new > v2.2.91730

$LNG['hint_delete_exp_after'] = "You can setup the expired members to be removed from the system automatically by enter the days here. System will remove the expired members in certain days after the members account expired.<br /><br /><font color=red>Please note</font>, the members removal process cannot be reversed."; //new > v2.2.91730

$LNG['hint_emailmbrinterval'] = "You can limit the member broadcast and set the interval. Example: you allow member to send broadcast email 2 times per 5 days, so you can enter 2 in the broadcast limit and 5 in the broadcast interal.<br /><br />You can leave these fields empty or set to 0 to disable these broadcast limitation features."; //new > v2.2.91730

$LNG['hint_emailmbrleads'] = "<font color=red><b>Warning!</b></font> be careful when enable this feature. Enable this feature only when you trust your members to avoid SPAM complaint.<br /><br />When your member's subscribers growing you will need more server cpu resource. Make sure your server capable to handle large broadcast emails per minute.<br /><br />You can force your members to sending email in batch to avoid high cpu resource usage, but in return your subscribers emails will delivered in batch (approx. %s emails per hour)."; //new > v2.2.80612

$LNG['hint_ewallet_withdrawfee'] = "You can setup the E-Wallet withdraw fee here.<br /><br />Enter flat or percentage value, example: 2% (it's mean 2% from the withdraw amount will be deducted for the withdraw fee). Default is 0 or empty (no withdraw fee).<br /><br />You can limit the maximum withdraw fee and also minimum amount to withdraw by using format as follow <b>x|y|z</b> where <b>x</b> is the flat or percentage withdraw fee, <b>y</b> is the maximum withdraw fee (flat or fixed value), and <b>z</b> is the minimum amount to withdraw (flat or fixed value).<br /><br />Example: <b>2.7%|2|15</b> it's mean the withdraw fee is 2.7% from the amount to withdraw and the fee cannot greather than $2, the withdraw amount should more than or equal to $15."; //new > v2.2.91730

$LNG['hint_ewallet_transferfee'] = "You can setup the transfer fee here. The fee will be deducted from the amount to transfer, between the members E-Wallet account.<br /><br />Enter flat or percentage value, example: 1.5% (it's mean 1.5% from the transfer amount will be deducted for the transfer fee).<br /><br />You can limit the minimum amount to transfer and also the transfer fee maximum by using format as follow <b>x|y|z</b> where <b>x</b> is the flat or percentage transfer fee, <b>y</b> is the maximum transfer fee in flat or fixed value, and <b>z</b> is the minimum amount to transfer in flat or fixed value.<br /><br />Example: <b>1.2%|1|5</b> it's mean the transfer fee is 1.2% from the amount to transfer between members E-Wallet account, where the transfer fee maximum is $1 and the minimum allowed to transfer is $5."; //new > v2.2.91730

$LNG['hint_genealogy_view'] = "Select the genealogy view displayed in the User CP (member area), you can select Tree View (recommended in most payplan settings) or Board View.<br /><br />Genealogy ree view example:<br /><img src='images/tree_view.gif' border='0'><br /><br />Genealogy board view example:<br /><img src='images/board_view.gif' border='0'><br /><br />It's recommended to use board view if you setup the payplan settings for small level width and deep, for example when you setup payplan for matrix 2x3, 5x5, 3x11, etc."; //new > v2.2.91730

$LNG['hint_reflink_format'] = "Select the referral link format do you prefer to use, the default format is http://www.domain.com/id/index.php?ref=USERNAME or<br /> http://www.domain.com/id/USERNAME for search engine friendly (using mod_rewrite). You can also modify this link format with some customization in the .htaccess file.<br /><br />Another referral link format you can use is using sub-domain, and the referral link format will look like http://USERNAME.domain.com where USERNAME is your members username.<br /><br />If you want to using sub-domain referral link format, you need to setup catch-all for your sub-domain in your CPanel hosting control panel and also update the url format in the Email Template contents."; //new > v2.2.91730

$LNG['hint_captcha_login'] = "When someone trying to login in or joining your site, they will be asked to copy a short series of letters and numbers from an image into a form field. This blocks the automated hackers or spammers that trying attacked your site.<br /><br />If the images do not work, then it is a problem with your server. You must have the GD library installed. If you do not, then you cannot use word verification. If word verification is not supported on your server, you can turn word verification off.";

$LNG['hint_captcha_subscriber'] = "When someone trying to subscribe in your site, they will be asked to copy a short series of letters and numbers from an image into a form field. This blocks the automated hackers or spammers that trying attacked your site.<br /><br />If the images do not work, then it is a problem with your server. You must have the GD library installed. If you do not, then you cannot use word verification. If word verification is not supported on your server, you can turn word verification off.";

$LNG['hint_captcha_contact'] = "When someone trying to contact you through your site contact form, they will be asked to copy a short series of letters and numbers from an image into a form field. This blocks the automated hackers or spammers that trying attacked your site.<br /><br />If the images do not work, then it is a problem with your server. You must have the GD library installed. If you do not, then you cannot use word verification. If word verification is not supported on your server, you can turn word verification off.";

$LNG['hint_isrecaptcha'] = "If enabled, system will use <a href=http://www.google.com/recaptcha target=_blank>reCAPTCHA</a> instead of EzyGold build-in CAPTCHA."; //new > v2.2.91730

$LNG['hint_test_sendemail'] = "Use this feature to test your email configuration to make sure it is working properly or not.<br /><br />Enter your email address and click Send button to process.<br /><br /><font color=red>Note:</font> If you select SMTP as {$LNG['a_s_email_agent']} and there is {$LNG['a_s_email_smtppass']} provided, the test email process will not encrypt your SMTP password."; //new > v2.2.80612

$LNG['hint_create_page_id'] = "This will be used in the URL, so it can contain only letters, numbers, and underscores, and the first chaharacter cannot using number.<br /><br />You can using name prefix for this value, ie. <strong>cpg_</strong>terms, <strong>cpg_</strong>privacy, etc. where <strong>cpg_</strong> is name prefix for terms and privacy pages.";

$LNG['hint_page_multiselect'] = "Press Ctrl and click on available option to select multiple options. Please select at least one option.";

$LNG['hint_page_otoval'] = "Set the interval for the One Time Offer page to show up.<br /><br />Examples: <b>5</b> for every 5 days, <b>2w</b> for every 2 weeks, <b>3m</b> for every 3 months, etc.<br /><br />You may leave the interval empty to make the One Time Offer page will show up once.";

$LNG['hint_page_ototags'] = "Use these tags to display the link to process the One Time Offer page, basic HTML codes knowledge required to use these tags.<br /><br /><b>Usage Example:</b><br /><font face='courier new'><<span>a</span> href='<font color=navy>%OTO_SKIPIT%</font>'>Skip This Offer<</<span>/a</span>></font>";

$LNG['hint_page_otoitemtags'] = "Use these tags to display the <b>Buy Now</b> button from one of your <em>OTO page</em> items in your <a href=index.php?a=admin&amp;b=items>eStore</a>.";

$LNG['hint_page_dripfeed'] = "You can automatically display or enable this page in certain date (according members payplan registration date) by enter the day(s) when the page will be available. This feature will enable when the page available for member only. Leave empty to disable.<br /><br />Example if member register at 12 Oct and you set the day to 5, the page will be available on 17'th Oct.<br /><br />To insert the drip feed page list in the User CP, use the <strong>{&#36;display_dripfeed_content}</strong> tag. The default drip feed content list will be displayed in the User CP main page.";

$LNG['hint_page_dripfeedexp'] = "You can also setup the expiration for the page. It's counted from the members payplan registration date. This feature will enable when the page available for member only. Leave empty to disable.<br /><br />Example if member register at 12 Oct and you set the expiration to 10 days, the page will be unavailable after 22'nd Oct.";

$LNG['hint_file_multiselect'] = "Press Ctrl and click on available option to select multiple options. Inactive or free members here mean member status not active (free, expired, pending, blocked, etc).";

$LNG['hint_protect_folder'] = "Make sure the folder you want to protect is writable. If you need the folder not writable, you can chmod the folder back after the .htaccess file created.<br /><br /><font face='courier new, courier'><font color=#555555>[-]</font> = .htaccess file not found<br /><font color=#660000>[?]</font> = other .htaccess file exist<br /><font color=#006600>[+]</font> = folder protected</font>"; //update v2.3.429

$LNG['hint_video_flvfile'] = "You can display the video file localy from your server by select the available video file from the drop down list.<br /><br />You need to upload your .flv video file to the %s folder, in your server."; //new v2.2.9173

$LNG['hint_video_embedcode'] = "Enter or copy-paste your video embed code here, you can paste the youtube, vimeo, or other video embed source code here.<br /><br />If you want to display your video using custom video player, you need also enter your video source code here."; //new v2.2.9173

$LNG['hint_video_splashimage'] = "Please select your video image or splash. This image will be used as the video splash image or video tumbnail.<br /></br />Recommended to use png transparent image.<br /></br />If this drop down list empty, please upload the images files in the %s folder."; //new v2.2.9173

$LNG['hint_groupdl_expired'] = "The expiration date counted from members <b>{$LNG['a_s_dlgroup_date']}</b>.<br /><br />Example:<br />- If you set the expired to <b>30</b> and your members '{$LNG['a_s_dlgroup_date']}' set to %s then your member <b>cannot</b> download any files belong to this group after %s.<br /><br />- If you set the expired to <b>30|15</b> and your members '{$LNG['a_s_dlgroup_date']}' set to %s then your member <b>cannot</b> download any files belong to this group before %s <b>and</b> after %s."; //new > v2.2.80401

$LNG['hint_groupdl_multiselect'] = "Press Ctrl and click on available option to select multiple options. <a href='index.php?a=admin&amp;b=download_group'>Click here</a> to manage Download Group.<br /><br />You need to assign the download group to the members in order to make the files available to download, you may also select the download group as <em>Default Download Group</em> in the PayPlan Settings page, by doing this the download group will assigned automatically to the new signup."; //update > v2.2.90809

$LNG['hint_groupar_multiselect'] = "Press Ctrl and click on available option to select multiple options. <a href='index.php?a=admin&amp;b=responder_group'>Click here</a> to manage Responders Group and <a href='index.php?a=admin&amp;b=responders'>click here</a> to manage your autoresponder messages."; //new > v2.2.90809

$LNG['hint_member_status'] = "<strong>{$LNG['a_edit_active']}:</strong> member who active or already make payment for the registration fee (paid member), if there is a registration fee on site.<p><strong>{$LNG['a_edit_inactive']}:</strong> free member or who do not make payment for the registration fee, if there is a registration fee on site. This status also used for trial membership (if the option enabled, in the {$LNG['a_s_header']} page).</p><p><strong>{$LNG['a_edit_blocked']}:</strong> members with this status cannot manage their membership. Usually it's applied for members who abuse the site or system.</p><p><strong>{$LNG['a_edit_expired']}:</strong> members status will change to {$LNG['a_edit_expired']} if they do not pay their renewal fee (from the registration fee value), if <em>recurring membership fee</em> enabled.</p><p><strong>{$LNG['a_edit_pending']}:</strong> members status will change to {$LNG['a_edit_pending']} if they do not pay their maintenance fee, if <em>recurring maintenance fee</em> enabled.</p>"; //new > v2.2.80612

$LNG['hint_planparentid'] = "If you want to use single membership plan then select default or none, if you want to use multiple membership where members allowed to choose or upgrade their membership plan, then select the parent or higher plan after this payplan.<br /><br />The current payplan membership fee is generally cheaper than the parent or higher payplan membership fee."; //new > v2.2.90809

$LNG['hint_landingpg'] = "You can customize the member referral link landing page by set this value to any remote or relatif url, the remote url always started with http:// or https:// and relatif url is a page within your site (ie. details).<br /><br />To disable this feature, leave the field empty and all of your referral link landing page will go directly to your site (EzyGold script installation) main page."; //new > v2.1.80214

$LNG['hint_expday'] = "If you enter 0 or empty this field, then member account will <b>never expired</b>.<br /><br />You can setup the expired on calendar format,<br />ie. <b>2w</b> for 2 weeks, <b>4m</b> for 4 months, <b>1y</b> for 1 year, etc.<br /><br />Please note, after your system running you may increase this value but decrease it is NOT recommended since old member's account expired date already set."; //update > v2.2.80612

$LNG['hint_carepayday'] = "If you set this value to <b>0</b> (or empty) then there is no maintenance fee period. If you want to ask your members to pay their maintenance fee in the next 2 weeks then set this value to 14.<br /><br />You can also set the maintenance fee on calendar format,<br />ie. <b>2w</b> for 2 weeks, <b>4m</b> for 4 months, <b>1y</b> for 1 year, etc."; //new > v2.2.80612

$LNG['hint_trialacc'] = "This feature only working when the subscription payment enable."; //new > v2.2.91730

$LNG['hint_trialperiod'] = "You can setup the expired on calendar format, ie. <b>5</b> for 5 days, <b>1w</b> for 1 week, <b>2m</b> for 2 months, <b>1y</b> for 1 year, etc."; //new > v2.2.91730

$LNG['hint_customplan'] = "Enter custom variables used by payplan here.<br /><br />Available Options:<br /><br /><strong>1. Passup Members:</strong><br />cpgo_rollup=<strong>A</strong>, <strong>B</strong>, <strong>C</strong>, ...<br />cpgo_rolluplast=<strong>D</strong><br />cpgo_rollupjump=<strong>E</strong><br /><br />Where <strong>A</strong>, <strong>B</strong>, <strong>C</strong> is the referrals# to passup, and (<em>optional</em>) <strong>D</strong> is the latest referrals to passup before passup every <strong>E</strong> new referrals signup.<br /><br /><strong>2. Multiple Commission According to the Member Direct Referrals:</strong><br />cpgo_cmlist[|payplan_id]=[direct_referrals]:[commission_list]<br /><br />Where <strong>[payplan_id]</strong> is optional, it's the payplan id setup in the payplan settings page. <strong>[direct_referrals]</strong> is the total member direct referrals for the specific commission list defined in the <strong>[commission_list]</strong>.<br /><br />You can define the <strong>[direct_referrals]</strong> value to 0, 1, 2 for 1, 2, or 3 direct referrals, 4-6 for 4, 5, and 6 direct referrals, and 7-* for 7 and onwards direct referrals. You can also <u>combine</u> these value options.<br /><br />Example:<br />cpgo_cmlist=0,1,2:100, 75, 50<br />cpgo_cmlist|2=3-5:150, 125, 75<br />cpgo_cmlist|2=6-*:300, 175, 150<br /><br /> All variables above must be separated with new line."; //new > v2.3.442

$LNG['hint_amountsvg'] = "You can save your <b>%</b> of profits automatically to your e-gold account below (account for saving) to secure your profits.<br /><br />This feature will active when you set <b>auto payment for e-gold</b> is On. Empty or set this value to <b>0</b> if you want to disabled this feature and set it to <b>100</b> (it's mean 100%) if you want to send ALL your profit from each new account to your e-gold saving account.";

$LNG['hint_maxwidth'] = "Enter here maximum level width before spillover take place (it's act as <b>Force Matrix system</b>).<br /><br />Type <b>0</b> to disabled Force Matrix and activate the <b>Unilevel system</b> (with unlimited level width).<br /><br />If you planning to use binary system, enter <b>2</b> in this form.<br /><br />If you already built your matrix system, do not alter or disable the matrix setting, or you will mess up your matrix system structure.";

$LNG['hint_limitref'] = "You can limit member's <u>direct</u> referral here, leave empty or set to 0 to disable this feature.<br /><br />When this feature enabled and members reach their direct referrals limit, their new direct referral will be passed to default random member set <a href='index.php?a=admin&amp;b=settings#{$LNG['a_s_member']}'>here</a> (if enabled) or if not exist, <em><font color=red>no referrer warning</font></em> will appear when visitor accessing registration page.<br /><br />If this feature is used together with x-up system, direct referral counted after members qualified."; //new > v2.2.80401

$LNG['hint_spillover'] = "If you are unsure how this system work, you can select <em>{$LNG['a_s_spillover1']}</em> as your {$LNG['g_spillover']}.<br /><br />This feature will disable when <b>{$LNG['a_s_maxwidth']}</b> and/or <b>{$LNG['a_s_maxdeep']}</b> is empty or 0.";

$LNG['hint_spill4ver'] = "If this option is enable then although members matrix is full and completed, members will continue to provide spillover to any member under their matrix. If this feature is disable then any spillover from completed matrix will belong to administrator (placed in new matrix without sponsor).";

$LNG['hint_recycling_limit'] = "Recycling limit (0 = unlimited), this value will be valid when <em>{$LNG['a_s_matrix_isrecycling']}</em> set to <strong>{$LNG['g_yes']} ({$LNG['a_s_matrix_recycling_wider']})</strong>"; //new

$LNG['hint_isbenefactor'] = "When this option is enable, members will have ability to setup their benefactor or refund value to their new referral or downline.<br /><br />Example: if members setup their benefactor value to 25% then every time new downline register, 25% from members commission will refunded to their new downline."; //new > v2.2.80612

$LNG['hint_ctrldownline'] = "You can enabled this option if you want your members paid their sponsors directly.<br /><br />If you enable this option {$LNG['a_s_other_ctrldownline']}, members will have an ability to activate or deactivate their downline status with single click from their member's back office.<br /><br /><b>Note:</b> When this feature is enable, some additional commissions or rewards features should disabled and the E-Wallet feature (the option available in the General Settings page) must be disabled."; //new > v2.2.80612

$LNG['hint_recycling_2ref'] = "Select <strong>Yes</strong> to enable re-entry and place the new entry under sponsor structure."; //new > v2.2.91730

$LNG['hint_recycling_cm'] = "This commission will be paid to member every time their matrix recycling.<br /><br />Example:<br />If you set {$LNG['a_s_matrix_recycling_limit']} (limit for matrix recycling time) to <strong>3</strong> and the commission list set to <strong>200, 500, 1000%</strong>, then when members complete their first matrix structure, they will earn 200, when complete second matrix structure, they will earn 500, and when complete their third matrix structure, they will earn 1000% from the registration fee.<br />If you set {$LNG['a_s_matrix_recycling_limit']} to <strong>0</strong> (unlimited recycling), then every time your members complete their matrix structure, they will earn <strong>200</strong> (only first value from <em>{$LNG['a_s_matrix_recycling_cmlist']}</em> will be used).<br /><br />If you want to enable matrix recycling feature but didn't want to pay commission for your members, then leave empty the {$LNG['a_s_matrix_recycling_cmlist']} form.<br /><br />It's flat or percentage value. For example: <strong>500</strong> or <strong>2500%</strong> (percentage from the registration or membership fee).<br /><br />If you want to rewards the sponsor when member cycling, use | character to separate the reward value between member and sponsors. For example: <strong>500|100</strong> or <strong>2500%|300%</strong> (this values mean, when cycling member will earn reward 500 or 2500% and member's sponsor will earn 100 or 300%, percentage from the registration or membership fee)."; //update > v2.2.90809

$LNG['hint_xupon'] = "The primary identifying characteristic of Up System (pass up) is their pass up referrals.<br /><br />For instance, example in a two up pay plan (2-Up System), your first two direct referrals would be passed upline to your enrolling sponsor. Your sponsor would then be the primary beneficiary from the sales efforts of the passed up referral.<br /><br />The different with <em>{$LNG['a_s_xup_vertical']}</em> is when you set it to two up plan (2-Up System), your first two direct referrals would be passed upline to your enrolling sponsor where 1st referral passed to your 1st level sponsor and your 2nd referral passed to your 2nd level sponsor. Your 1st dan 2nd level sponsor would then be the primary beneficiary from the sales efforts of the passed up referral (this options will be available when you set {$LNG['a_s_maxdeep']} to 2 or higher).";

$LNG['hint_cm1up'] = "1-Up System mean sponsor will earn commission from their member 1st referral. Member will give their 1st referral (and split commission in % is doable) to their sponsor, it's only happen for 1st referral only.<br /><br />Example value: <b>50%</b> mean 50% commission from 1st direct referral go to sponsor and 50% commission from 1st direct referral go to referrer (member), <b>100%</b> mean 100% commission from 1st direct referral go to sponsor.";

$LNG['hint_cmxup'] = "X-Up System or Pass Ups mean sponsor will earn commission from their member's first <b>X</b> referrals. Member will give their <b>X</b> referrals (and split commission in %) to their sponsor, it's only happen for first <b>X</b> referrals only.<br /><br />Example 1-Up:<br />Flat Value: <b>5</b> mean 5 from 1st direct referral commission go to sponsor, <b>10</b> mean 10 from 1st direct referral commission go to sponsor.<br />Percentage Value: <b>50%</b> mean 50% commission from 1st direct referral go to sponsor and 50% commission from 1st direct referral go to referrer (member), <b>100</b> mean 100% commission from 1st direct referral go to sponsor.<br /><br />Example 2-Up:<br />Flat Value: <b>5, 2</b> means 5 from 1st direct referral commission go to sponsor and 2 from 2nd direct referral commission go to sponsor.<br />Percentage Value: <b>50%, 25%</b> means 50% commission from 1st direct referral go to sponsor and 50% commission from 1st direct referral go to referrer (member), 25% commission from 2nd direct referral go to sponsor and 75% commission from 2nd direct referral go to referrer (member), <b>0, 80%</b> means no commission from 1st direct referral for sponsor, 80% commission from 2nd direct referral go to sponsor and 20% commission from 2nd direct referral go to referrer (member).<br /><br />If this value is set to <b>0</b> or <b>empty</b> then X-Up System will be disabled.";

$LNG['hint_idlistxup'] = "Enter here member's referrals # where need to pass up, usually it's use for reverse X-Up.<br /><br />Example 2-Up:<br />If you want <b>2nd</b> and <b>5th</b> referral will pass up to member's sponsor, then you need to set this value to <b>2, 5</b>.<br /><br />Example 3-Up:<br />If you want <b>1st</b>, <b>2nd</b> and <b>4th</b> referral will pass up to member's sponsor, then you need to set this value to <b>1, 2, 4</b>.<br /><br />When this value empty, X-Up System will work as default where member will pass up their referral from 1st referral."; //new > v2.2.80612

$LNG['hint_cmdrlist'] = "Additional direct referral commission list structure (optional, separate with commas), commission generated for the referrers.<br /><br /><font color=red>Valid value:</font> Flat value (exp. 2 or 2.5 or 0.5) and Percentage value (exp. 5% or 10.2% or 0.9%, this value is percentage from <b>{$LNG['a_s_joinfee']}</b> value).<br /><br />Example 1 (flat value): <b>10, 2</b> means member will earn <b>10</b> from their direct referrals, and <b>2</b> from their indirect referral (level 2).<br /><br />Example 2 (percentage value): <b>10%, 4%, 1%</b> means member will earn <b>10%</b> from {$LNG['a_s_joinfee']} when refer direct referrals, earn <b>4%</b> from {$LNG['a_s_joinfee']} when their indirect referral (level 2) refer direct referrals, and earn <b>1%</b> from {$LNG['a_s_joinfee']} when their indirect referral (level 3) refer direct referrals.<br /><br />Example 3 (mixed value): <b>10, 2%, 1</b> means member will earn <b>10</b> from their direct referral, earn <b>2%</b> from {$LNG['a_s_joinfee']} when their indirect referral refer direct referrals, earn <b>1</b> from their indirect referral (level 3) direct referrals.<br /><br />This commission list <b>isn't affected</b> by force matrix or spillover system. If this value is set to <b>0</b> or <b>empty</b> then no commission will be generated.";

$LNG['hint_cmlist'] = "Commission list structure based on <b>level deep</b> (separate with commas).<br /><br /><font color=red>Valid value:</font> Flat value (exp. 2 or 2.5 or 0.5) and Percentage value (exp. 5% or 10.2% or 0.9%, this value is percentage from <b>{$LNG['a_s_joinfee']}</b> value).<br /><br />Example 1 (level deep <b>5</b>, flat value): <b>10, 2, 1, 0, 2</b> means commission for level 1 is <b>10</b>, level 2 is <b>2</b>, level 3 is <b>1</b>, level 4 is <b>0</b> (no commission) and level 5 is <b>2</b>.<br /><br />Example 2 (level deep <b>3</b>, percentage value): <b>10%, 4%, 1%, 5%, 2%</b> means commission for level 1 is <b>10%</b> from {$LNG['a_s_joinfee']}, level 2 is <b>4%</b> from {$LNG['a_s_joinfee']}, level 3 is <b>1%</b> from {$LNG['a_s_joinfee']}.<br /><br />Example 3 (level deep <b>7</b>, mixed value): <b>10, 2%, 1, 2, 1%</b> means commission for level 1 is <b>10</b>, level 2 is <b>2%</b> from {$LNG['a_s_joinfee']}, level 3 is <b>1</b>, level 4 is <b>2</b>, level 5 is <b>1%</b> from {$LNG['a_s_joinfee']}, level 6 is <b>0</b> (no commission) and level 7 is <b>0</b> (no commission).<br /><br />If this value is set to <b>0</b> or <b>empty</b> then no commission will be generated.";

$LNG['hint_cmlistcare'] = "Commission list structure based on <b>level deep</b> (separate with commas) and calculated from Maintenance Fee.<br /><br /><font color=red>Valid value:</font> Flat value (exp. 2 or 2.5 or 0.5) and Percentage value (exp. 5% or 10.2% or 0.9%, this value is percentage from <b>{$LNG['client_carefee']}</b> value).<br /><br />Example 1 (level deep <b>5</b>, flat value): <b>10, 2, 1, 0, 2</b> means commission for level 1 is <b>10</b>, level 2 is <b>2</b>, level 3 is <b>1</b>, level 4 is <b>0</b> (no commission) and level 5 is <b>2</b>.<br /><br />Example 2 (level deep <b>3</b>, percentage value): <b>10%, 4%, 1%, 5%, 2%</b> means commission for level 1 is <b>10%</b> from {$LNG['client_carefee']}, level 2 is <b>4%</b> from {$LNG['client_carefee']}, level 3 is <b>1%</b> from {$LNG['client_carefee']}.<br /><br />Example 3 (level deep <b>7</b>, mixed value): <b>10, 2%, 1, 2, 1%</b> means commission for level 1 is <b>10</b>, level 2 is <b>2%</b> from {$LNG['client_carefee']}, level 3 is <b>1</b>, level 4 is <b>2</b>, level 5 is <b>1%</b> from {$LNG['client_carefee']}, level 6 is <b>0</b> (no commission) and level 7 is <b>0</b> (no commission).<br /><br />If this value is set to <b>0</b> or <b>empty</b> then no commission will be generated."; //new > v2.1.80214

$LNG['hint_rwlist'] = "Reward list structure based on <b>full level width</b> (separate with commas).<br /><br /><font color=red>Valid value:</font> Flat value (exp. 25 or 150.5 or 0.5) and Percentage value (exp. 25% or 200.2% or 0.5%, this value is percentage from <b>{$LNG['a_s_joinfee']}</b> value).<br /><br />Example 1 (flat value): <b>10, 200, 500, 0, 2000</b> means reward for level 1 is <b>10</b>, level 2 is <b>200</b>, level 3 is <b>500</b>, level 4 is <b>0</b> (no reward) and level 5 is <b>2000</b>.<br /><br />Example 2 (percentage value): <b>, 40%, 100%</b> means reward for level 1 is <b>0</b> (no reward), level 2 is <b>40%</b> from {$LNG['a_s_joinfee']} and level 3 is <b>100%</b> from {$LNG['a_s_joinfee']}.<br /><br />Example 3 (mixed value): <b>10%, , , 0, 1000</b> means reward for level 1 is <b>10%</b> from {$LNG['a_s_joinfee']}, level 2 to 4 is <b>0</b> (no reward), level 5 is <b>1000</b>, level 6 is <b>0</b> (no reward) and level 7 is <b>0</b> (no reward).<br /><br /><strong>Rewards for Sponsors (matching commission):</strong><br /> Example value: 20|10%|5, 50|35|2%<b></b> its mean when sponsor (let say <em>SPR</em>) earn a reward, then <em>SPR</em> sponsor's will also earn rewards (separated with <b>|</b> character).<br /><br />Based on example above, <em>SPR</em> will earn reward 20 when his 2nd level is fulfilled (2nd level = <em>SPR</em> direct referral's level), at the same time <em>SPR</em> sponsor on position 1 will earn 10% from registration fee and <em>SPR</em> sponsor on position 2 will earn 5, this condition also occurs when <em>SPR</em> complete his third level, and so on.<br /><br />You will need setup this value if you want to provide commissions or reward when member cycling or complete their matrix.<br /><br />If this value is set to <b>0</b> or <b>empty</b> then no reward will be generated."; //update > v2.2.91730

$LNG['hint_cmpair'] = "Pairing commission based on <b>level width</b> (available for referrer).<br /><br />Example value: if you set level width: <b>2</b>, then your members will earn pairing commission <b>every time</b> they get 2 <u>direct</u> <u>referrals</u>. If your member have 5 <u>direct</u> <u>referrals</u>, then they will earn pairing commission two times (5 direct referrals, 2 pair and 1 direct referrals waiting for next pairing).<br /><br />If this value is set to <b>0</b> or <b>empty</b> then no pairing commission will be generated. This commission will generated only when a new <b>direct referral</b> account added (not recurring).<br /><br />It's flat or percentage value. For example: <strong>5</strong> or <strong>2.5%</strong> (percentage from the registration or membership fee)"; //update

$LNG['hint_cmatch'] = "Fast Start Commission based on <b>level width</b> (available for referrer).<br /><br />Example value: if you set level width: <b>5</b>, then your members will earn fast start commission when they get first 5 <u>direct</u> <u>referrals</u>. This commission only paid once.<br /><br />If this value is set to <b>0</b> or <b>empty</b> then no fast start commission will be generated. This commission will generated only when a new <b>direct referral</b> account added (not recurring).<br /><br />It's flat or percentage value. For example: <strong>10.5</strong> or <strong>25%</strong> (percentage from the registration or membership fee)"; //update

$LNG['hint_rewards_daily'] = "This active reward will be paid to member every time they have new direct referrals (count daily) that more or equal than minimum daily direct referrals (to earn daily active reward).<br /><br />It's flat or percentage value. For example: <strong>5</strong> or <strong>2.5%</strong> (percentage from the registration or membership fee).<br /><br /><b>Example:</b> If you set the minimum daily referral to <b>2, 10</b> and daily active rewards to <b>5, 20%</b> then every time your member get 2 new referral within certain day they will earn active reward 5 and when they get minimum 10 new referral in same day they will also earn active reward 20% (calculated from registration or membership fee), in total they will earn active reward 5 and 20%."; //update > v2.1.80214

$LNG['hint_rewards_weekly'] = "This active reward will be paid to member every time they have new direct referrals (count weekly) that more or equal than minimum weekly direct referrals (to earn weekly active reward).<br /><br />It's flat or percentage value. For example: <strong>5</strong> or <strong>2.5%</strong> (percentage from the registration or membership fee).<br /><br /><b>Example:</b> If you set the minimum weekly referral to <b>50, 100</b> and weekly active rewards to <b>30%, 50%</b> then every time your member get 50 new referral within certain day they will earn active reward 30% (calculated from registration or membership fee) and when they get minimum 100 new referral in same week they will also earn active reward 50% (calculated from registration or membership fee), in total they will earn active reward 80%."; //new > v2.1.80214

$LNG['hint_rewards_monthly'] = "This active reward will be paid to member every time they have new direct referrals (count monthly) that more or equal than minimum monthly direct referrals (to earn monthly active reward).<br /><br />It's flat or percentage value. For example: <strong>75</strong> or <strong>33%</strong> (percentage from the registration or membership fee).<br /><br /><b>Example:</b> If you set the minimum monthly referral to <b>200, 500</b> and monthly active rewards to <b>1000, 2500</b> then every time your member get 200 new referral within certain day they will earn active reward 1000 and when they get minimum 500 new referral in same month they will also earn active reward 2500, in total they will earn active reward 3500."; //update > v2.1.80214

$LNG['hint_bnarysys'] = "Binary plans are so named because they are built on a matrix of two. Member can sign only two people onto their first level. Everyone else goes beneath those people.<br /><br />The advantage of this system over the standard matrix is that member can easily benefit from new referrals that occur many levels away from.<br /><br />If you want to enabled this Binary System feature, set the <b>level width</b> value to <b>2</b>. You can turn this feature off by disabled it.";

$LNG['hint_bnarysys_cmpair'] = "Pairing commission based on member's <b>pair referrals</b> in their left and right direct referrals.<br /><br />Every time your member get new referrals and their left and right referrals is equal, then your members will earn binary pairing commission. If your member have 5 total referrals (3 in left and 2 in right), then they will earn binary pairing commission two times, 5 referrals (from spillover or not), 2 pair (left and right positions) and 1 referrals waiting for next pairing.<br /><br />You can define the pairing value for left and right position by adding | character after the commission value.<br />Example: 50|3, it's mean member will earn 50 pairing commission everytime pairing occurs, 3 left and 3 right.<br /><br />If this value is set to <b>0</b> or <b>empty</b> then no binary pairing commission will be generated. This commission will generated only when a new account added (not recurring).<br /><br />It's flat or percentage value. For example: <strong>15</strong> or <strong>20%</strong> (percentage from the registration or membership fee)";

$LNG['hint_bnarysys_cmpairlimit'] = "Maximum pairing commission generated for each member. Empty or set to 0 to disable this feature."; //new > v2.3.450

$LNG['hint_randlist'] = "List of level will randomized (separate with commas).<br /><br />Example value: <b>3, 5, 6</b> mean system will generate and select random member id for level 3, 5 and 6.<br /><br />If this value is set to <b>0</b> or <b>1</b> or <b>empty</b> then this feature will be disabled. If you want to set randomized for level 1 then you can turn <em>{$LNG['a_s_random_referrer']}</em> option in <a href='index.php?a=admin&amp;b=settings'>{$LNG['a_menu_settings']}</a> page to <b>On</b>.<br /><br />It's <b>not recommended</b> to using this feature when Binary System is Enable.<br /><br />Please be aware, if this feature is enabled then the genealogy structure will seem <font color=red>overlapping</font> and <font color=red>messy</font>.";

$LNG['hint_minref2getcm'] = "If this value is greather than 0, then member will not earn commission until their direct referral or downline is equal or greather than this value. During this period, any available commissions belong to this members will on hold.<br /><br /><strong>Advanced Value:</strong> You may limit members maximum level based on their total direct referrals, example if you set this value to <b>0, 0, 1, 2, 4, 10</b> then member will earn any commission in level <b>1</b> and <b>2</b> without need to refer people. Members will earn commission from level <b>3</b> if they get at least 1 direct referral, and will earn from level <b>4</b> if members have 2 direct referrals, they will also earn from level <b>5</b> if members have at least 4 direct referrals, and they will earn any commissions from level <b>6</b> if members have 10 direct referrals or more.<br /><br />Set this value to 0 to disable this feature."; //new > v2.2.80612

$LNG['hint_cmdayhold'] = "Pending the commissions for certain period (in days) before available for payout."; //new > v2.2.80612

$LNG['hint_egold'] = "Select <b>'{$LNG['a_s_autopaid']}'</b> if you want to make payment for all commissions and rewards running automatically, select <b>'{$LNG['a_s_withdraw']}'</b> if you want your member be able to <b>auto withdraw</b> their commission or rewards.<br /><br />Please note, to enabled this feature, you must provide your e-gold account details (account number, passphrase and alternate passphrase), curl must be installed on your server and set Automation Access on e-gold AccSent protection to enable (for security reason, it's recommended to set it based on your server ip address).<br /><br />%s<br /><br /><img src='images/asnt_egold1.gif' border='0'><br /><img src='images/asnt_egold2.gif' border='0'>.<br /><br />SECURITY ADVISORY: Autopayment requires very sensitive information about your e-gold account. While all the passwords and other sensitive information is encrypted on the database and next to impossible to decrypt, you should still be careful and make sure your hosting provider is a serious, honest company, specially if using shared hosting.";

$LNG['hint_alertpay'] = "<strong>Note:</strong> You need submit your website to Payza Website Review (<em>in your Payza account</em>) in order to use this payment option. If your website is approved, you will then be able to use Payza on your site.<br /><br /><img src='images/snap_ap1.gif' border='0'><br /><br /><img src='images/snap_ap2.gif' border='0'><br><br />Premium or Secured Payza account type is required to enable this processor.<br /><br />If you enabled this payment processor, then you need to set the Payza IPN.<br /><br />Login to your Payza account and enable the IPN feature.<br /><br />If you do not have any business profile, then create one for your EzyGold powered site by clicking the Add button.<br /><br /><img src='images/snap_ap3.gif' border='0'><br /><br />After completing your business profile details, click My Payza Account menu then IPN Advance Integration.<br/><br/><img src='images/snap_ap4.gif' border='0'><br /><br />In the IPN Setup page, enable your <b>IPN Status</b> and enter <strong>%s</strong> in the Alert URL text box.<br /><br /><img src='images/snap_ap5.gif' border='0'><br /><br />Generate new IPN security code and enter it in your <strong>IPN Security Code</strong> in the <em>{$LNG['a_s_alertpayipn']}</em> form below."; //update > v2.2.91729

$LNG['hint_alertpayapi'] = "Enter here your Personal Pro or Business account's <strong>primary</strong> email address.<br /><br />If you enabled auto payment, then you need to setup your Payza API.<br /><br />Login to your Payza account and enable the API feature.<br /><br /><img src='images/snap_ap6.gif' border='0'><br /><br />Enable the API status and the generate the API password, copy this password to API password form.<br /><br />There is also Test Mode option available, you may enable this option to see your alertpay API already setup correctly or not, bofore use it in real auto payment.<br /><br /><img src='images/snap_ap7.gif' border='0'><br />%s<br /><br />For security reasons you may enable IP restriction, enter your server IP address in this form. If restrict by IP is Enabled, the Payza API will only allow transactions performed from the IP Addresses you entered."; //new > v2.2.80612

$LNG['hint_lreserve'] = "Select <b>'{$LNG['a_s_autopaid']}'</b> if you want to make payment for all commissions and rewards running automatically, select <b>'{$LNG['a_s_withdraw']}'</b> if you want your member be able to <b>auto withdraw</b> their commission or rewards.<br /><br />Please note, to enabled this feature, you must provide your libertyreserve account details (libertyreserve account, store name and password), domxml, hash and curl must be enable in your server.<br /><br /><img src='images/snap_lr1.gif' border='0'><br /><img src='images/snap_lr2.gif' border='0'><br /><img src='images/snap_lr3.gif' border='0'><br /><br />If you plan to use auto payment then you must provide your libertyreserve API and password (for security reason, it's recommended to set IP address that you authorize to make requests from the system).<br /><br />%s<br /><br /><img src='images/snap_lr4.gif' border='0'><br /><img src='images/snap_lr5.gif' border='0'>.<br /><br />SECURITY ADVISORY: Autopayment requires very sensitive information about your libertyreserve account. While all the passwords and other sensitive information is encrypted on the database and next to impossible to decrypt, you should still be careful and make sure your hosting provider is a serious, honest company, specially if using shared hosting."; //new > v2.1.80214

$LNG['hint_lreserve_client'] = "Select <b>'{$LNG['a_s_autopaid']}'</b> if you want to make payment for all commissions and rewards running automatically, select <b>'{$LNG['a_s_withdraw']}'</b> if you want your member be able to <b>auto withdraw</b> their commission or rewards.<br /><br />Please note, to enabled this feature, you must provide your libertyreserve account details (libertyreserve account, store name and password), domxml, hash and curl must be enable in your server.<br /><br /><img src='images/snap_lr1.gif' border='0'><br /><img src='images/snap_lr2.gif' border='0'><br /><img src='images/snap_lr3.gif' border='0'>"; //new > v2.2.80612

$LNG['hint_safepay'] = "For extra security, you may use the '<b>Allowed domains for your Buy Now buttons:</b>' option, found under the 'Seller Tools' -> 'Settings' menu in your SafePay Solutions back office. This will provide you with extra security against hackers.<br /><br /><img src='images/snap_sps4.gif' border='0'><br /><br />Post back loss prevention:<br />Enabling the SafePay multiple post back option<br /><br />Within the 'Seller Tools' -> 'Settings' -> 'IPN Security Settings' section of your SafePay back office, you will find a '<b>Resend Payment Notification:</b>' option. Simply use the drop-down box to turn this On, if desired.<br /><br />From this point On, the SafePay Solutions system will now make sure that each signal has been received by the merchant's side (within EzyGold system).<br /><br />If any problems occur (IE: your server is down, one of the internet backbones is down causing data loss, etc) SafePay Solutions system will re-send the payment notification until it IS confirmed as recieved!<br /><br />The SafePay Solutions system will be certain that EzyGold system has recieved the payment notification, because it is EzyGold system that is REQUIRED to respond to each payment notification signal.<br /><br /><img src='images/snap_sps1.gif' border='0'><br /><img src='images/snap_sps2.gif' border='0'><br /><img src='images/snap_sps3.gif' border='0'>";

$LNG['hint_mbookers'] = "Select <b>{$LNG['a_s_on']}</b> to activate MoneyBookers gateway.<br /><br />The <em>{$LNG['a_s_mbookerspwd']}</em> form is optional. You can set this {$LNG['a_s_mbookerspwd']} value by login to your MoneyBookers account and go to Merchant Tools page (please contact merchantservices at moneybookers.com directly if this page not available in your MoneyBookers account).";

$LNG['hint_paypal'] = "Select <b>'{$LNG['a_s_autopaid']}'</b> if you want to make payment for all commissions and rewards running automatically, select <b>'{$LNG['a_s_withdraw']}'</b> if you want your member be able to <b>auto withdraw</b> their commission or rewards.<br /><br />Please note, to enabled this feature, you must provide your PayPal email account. This system using socket connection to verify PayPal payment.<br /><br />If you plan to use auto payment then you must provide your PayPal API details (API username, password and signature), curl v4.4.2 or greater enable in your server and PHP must be installed under Apache HTTP server.<br /><br />%s<br /><br /><a href='https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_NVPAPIBasics' target='_blank'>Click here</a> for details how to requesting API signature.<br /><br />Select {$LNG['a_s_on']} in {$LNG['a_s_paypaltest']} option to activate TEST mode (<a href='https://developer.paypal.com' target='_blank'>PayPal SandBox</a>)."; //update > v2.1.80214

$LNG['hint_solidtrustpay'] = "Select <strong>{$LNG['a_s_on']}</strong> to enable this payment option, select <b>'{$LNG['a_s_autopaid']}'</b> if you want to make payment for all commissions and rewards running automatically, select <b>'{$LNG['a_s_withdraw']}'</b> if you want your member be able to <b>auto withdraw</b> their commission or rewards.<br /><br />Please note, to enabled this feature, you must provide your SolidTrusPay account and secondary password.<br /><br />If you plan to use auto payment then you must provide your SolidTrusPay API details (API username and password), curl v4.4.2 or greater enable in your server and PHP must be installed under Apache HTTP server. You will need also add new API in your SolidTrustPay account. To do so, login to your SolidTrustPay account, go to the Merchant Zone menu and click the <em>'Create Remote Payment API'</em> link.<br /><br /><img src='images/snap_stp1b.gif' border='0'><br /><br /><img src='images/snap_stp1a.gif' border='0'><br /><br />%s"; //new > v2.3.429

$LNG['hint_solidtrustpaysubs'] = "If you want to setup subscription payment, you need to Create Merchant Subscriptions in your SolidTrustPay account to get the Item ID.<br /><br />Here are the steps you need to follow:<br />1. Login to your SolidTrust Pay account.<br />2. Click on <em>'Create Subscriptions'</em> in the <strong>Merchant Zone</strong> menu.<br /><br /><img src='images/snap_stp2.gif' border='0'><br /><br />3. Click on <em>'Create and/or Edit Your Merchant Subscriptions'</em> link.<br />4. Click on <em>'Add New Subscription'</em>.<br /><br /><img src='images/snap_stp3.gif' border='0'><br /><br />5. Fill in the details for the subscription.<br />6. Click on the <em>'Add'</em> button. The subscription item will be added and you will see it in the list of your added Subscriptions.<br /><br />7. In this newly created list, click on <em>'Generate Form'</em> next to the subscription item you need to get the Item ID.<br /><br /><img src='images/snap_stp4.gif' border='0'><br /><br />When you get the Item ID for your subscription, enter it in the Item ID field.<br /><br />Please note that the subscription amount and interval for the recurring billing will follow the SolidTrustPay subscription settings (will override the membership fee and payment interval in the payplan settings page)."; //new > v2.3.429

$LNG['hint_perfectmoney'] = "Select <strong>{$LNG['a_s_on']}</strong> to enable this payment option, select <b>'{$LNG['a_s_autopaid']}'</b> if you want to make payment for all commissions and rewards running automatically, select <b>'{$LNG['a_s_withdraw']}'</b> if you want your member be able to <b>auto withdraw</b> their commission or rewards.<br /><br />Please note, to enabled this feature, you must provide your PerfectMoney account and alternate passphrase.<br /><br />Your PerfectMoney account is Uxxxxxxx for USD or Exxxxxxx for EUR and must correspond to currency you use in the Payplan Settings page. You can setup your Alternate Passphrase in your PerfectMoney account (Profile Edit page).<br /><br /><img src='images/snap_pm1.gif' border='0'><br /><br /><img src='images/snap_pm2.gif' border='0'><br /><br />If you plan to use auto payment then you must provide your PerfectMoney Member ID and password, curl v4.4.2 or greater enable in your server and PHP must be installed under Apache HTTP server.<br /><br />You need also enable API (in your PerfectMoney account, Account Security page).<br /><br /><img src='images/snap_pm3.gif' border='0'><br /><br />%s<br />"; //new > v2.3.429

$LNG['hint_linkpoint'] = "Select <b>{$LNG['a_s_on']}</b> to activate credit cards gateway using LinkPoint. The <em>{$LNG['a_s_linkpointname']}</em> form is optional.<br /><br /><a href=http://www.linkpoint.com/support/lpconnect_intguide/lpconnect_help.html target=_blank>Click here</a> for LinkPoint Connect details"; //new > v2.2.80401

$LNG['hint_linkpointkeyfile'] = "Supply the complete local path to the default key file (e.g. /home/user/public_html/xxx.pem). For security reason, it is recommended you store this file outside your web directory.<br /><br />The key file is generated by LinkPoint and emailed directly to you as a file with your initial configuration information (e.g. xxx<b>.pem</b>, where xxx usually matches your storename)."; //new > v2.2.80612

$LNG['hint_authorize'] = "When enabled this option, you need to setup API Login ID and Transaction Key, please <a href='http://www.authorize.net/support/CP/helpfiles/Account/Settings/Security_Settings/General_Settings/API_Login_ID_and_Transaction_Key.htm' target='_blank'>click here</a> for details.";

$LNG['hint_authorizekey'] = "To obtain the Transaction Key from the Merchant Interface (only users with the appropriate permissions will be able to access this setting):<br /><br />1. Log into the Merchant Interface<br />2. Select <b>Settings</b> from the Main Menu<br /><br /><img src='images/snap_an1.gif' border='0'><br /><br />3. Click on the <b>API Login ID and Transaction Key</b> in the Security section<br /><br /><img src='images/snap_an2.gif' border='0'><br /><br />4. Type in the answer to your secret question (The secret question and answer is setup during account activation. It is required to authenticate the merchant before the transaction key is generated.)<br />5. Click <b>Submit</b>. The transaction key is returned by the Merchant Interface<br /><br /><img src='images/snap_an3.gif' border='0'><br /><br />6. (Optional Setting) Go to <b>MD5 Hash</b> menu. Set secret word to desired values<br /><br /><img src='images/snap_an4.gif' border='0'><br />";

$LNG['hint_manualpay'] = "This option is for members payment only (that paid to you as administrator), you can make payment for all member's commission and rewards manually or through your active payment processors (based on your payment processors settings above).<br /><br />You can set detail manual or offline payment instructions (in text or HTML) in the <b>{$LNG['a_s_manualpayipn']}</b> form below.<br /><br />If this an offline payment (ie. bank transfer, check, direct transfer, etc), then you need to <u>{$LNG['a_s_enable']}</u> '<b>{$LNG['a_s_free_member']}</b>' option in the <b><a href='index.php?a=admin&amp;b=settings#{$LNG['a_s_member']}' title='{$LNG['a_s_free_member']} option'>{$LNG['a_s_header']}</a> page</b>."; //update > v2.2.80612

$LNG['hint_addpayfee'] = "You can fill this field with flat or percentage value (%). Empty or enter <b>0</b> to disabled this feature.<br /><br />Flat value example: <b>2.5</b> or <b>5</b> or <b>10</b>.<br />Percentage value example: <b>2.5%</b> or <b>5%</b> or <b>10%</b>.";

$LNG['hint_unbanlist'] = "To ban some username from being used by new member, you can type reserved username separate with commas.<br /><br />Example of banning username: <b>index, admin, www</b> this means that anyone can't join your site using username <b>index</b>, <b>admin</b> or <b>www</b>.";

$LNG['hint_ipbanlist'] = "To ban some ip address from being access your site you can type ip address separate with commas, you also can add ip address using wildcards.<br /><br />An example of banning IP be <b>81.16.20.*, 121.50.9.12</b> this means that anyone using an IP between 81.16.20.0 and 81.16.20.255 and IP 121.50.9.12 would be unable to access your website.<br /><br />Please note it can be dangerous banning entire IP Address ranges as you can lock out alot of visitors to your website, however sometimes it may be required.";

$LNG['hint_bademail'] = "If for some reasons your members have an invalid email registered in their account, you can notify them by showing notify or warning about their invalid email straight on main page of their member area. Enter all your members invalid email here, separate with commas.<br /><br />In Example:<br /><b>name@email.com, you@sitename.com, somekind@domain.net</b>";

$LNG['hint_sms_gateway'] = "In order to use the sms sender feature, you will need SMS Gateway account, this system using Clickatell service to send the sms.<br /><br />If you do not have one, <a href='http://www.clickatell.com/pricing/pricing_wizard.php' target='_blank'>click here</a> to register, you will be given a <b>username</b>, <b>password</b> and <b>api id</b>. Once your registration has been activated you will receive 10 free credits with which to test the sms gateway."; //new > v2.2.91730

$LNG['hint_sms_from'] = "As per SMS regulations you are allowed to set a Sender ID (originator) on your sms message of either <b>11 alphanumeric</b> characters, or <b>15 numeric</b> characters. Please do not exceed these limitations as your message may be affected.<br /><br />If you wish to set a mobile number as the sender so recipients may be able to reply or identify you, please always set the sender in the International format <u>excluding</u> the leading + character. Try not to use punctuation and special characters on your sender as many of these are not supported.<br /><br />The Sender ID may need registered within your Clickatell account and approved by Clickatell before you can use it."; //new > v2.2.91730

$LNG['hint_isallfee'] = "If payment subscription mode is enable (in the {$LNG['a_s_merchants']} page) and both <em>account expiration</em> and <em>maintenance phase</em> are setup, then only <b>one</b> fee (registration OR maintenenance fee) will be setup for recurring payment"; //new > v2.2.80612

$LNG['hint_license'] = "This License Key is based on your domain name (<b>%s</b>). You may install single valid license key on single domain name. If you want to install this script on other domain name, please purchase additional license.<br /><br />We do not allow anyone to resell our scripts or redistribute it in any way. If you found someone resell our scripts, please report to us.<br /><br />Visit <a href='http://www.ezygold.com' target='_blank' title='http://www.ezygold.com'>http://www.ezygold.com</a> to obtain a valid License Key.";

$LNG['hint_pllkey'] = "This Private Label License Key is based on your domain name (<b>%s</b>).<br /><br />To remove <em><strong>'Powered by ...'</strong></em> text from any pages in your site, you need a valid private label license key. The <em>'Powered by ...'</em> text is generated from <em><strong>&#123;&#36;site_powered_by&#125;</strong></em> tag within the template files (template.html, template_user.html and template_admin.html).<br /><br />Please note, remove or make the <em>'Powered by ...'</em> text invisible or not readable without purchasing the Private Label License Key is strictly prohibited and cause the available free upgrade and support are voided."; //new

$LNG['hint_ipaccess'] = "Admin CP IP Access empowers you to restrict browser access to your Admin CP back office to a single IP address (or comma separated of IP addresses).<br /><br />Your IP number is your numeric address on the Internet. If you access the Internet via a dial-up connection your ISP may assign you a different IP number every time you connect. If you access the Internet via cable, DSL, or a network connection, typically your IP number remains the same for a much longer period of time.<br /><br />{$LNG['a_s_admin_ipaccess_empty']}."; //new > v2.1.80214

$LNG['hint_crawl_files'] = "This feature allow you to index all files in entered directory (it's must <b>already exist</b> and <b>writable</b>) to database for manage files to download.<br /><br />Before start indexing, make sure you already upload all files using your favorite ftp to entered directory.<br /><br />You may edit default download directory (full path) through <a href='index.php?a=admin&amp;b=settings'>settings</a> page."; //update > v2.2.80612

$LNG['hint_upload_maxi'] = "Maximum file size allowed to upload is %s, to increase it you can modify your php.ini file (<em>upload_max_filesize</em>) or contact your web hosting provider for details."; //new > v2.2.80612

$LNG['hint_file_planselect'] = "Optional, if one or more payplans selected, file settings will override the payplan default download group rules."; //new > v2.2.91730

$LNG['hint_crawl_banners'] = "This feature allow you to load all the banner files in the <b>banners</b> directory (it' must <b>already exist</b> and <b>writable</b>) to database for banner management.<br /><br />Before start loading, make sure you already upload all the banner files using your favorite ftp (recommended in 468 pixels maximum width).<br /><br />Crawling button will be <b>disabled</b> when directory is not exist or writable.";

$LNG['hint_email_templates'] = "This feature allow you to customize your emails. You can use both Plain Text and HTML format for your email templates.";

$LNG['hint_page_templates'] = "This feature allow you to customize your pages and template style. {$LNG['a_tpl_page_load']} button will be <b>disabled</b> when directory is not exist or writable.";

$LNG['hint_send_toid'] = "Insert your member's id number here (it's not your member's username nor email).";

$LNG['hint_datesend'] = "Start sending this message at certain date in the future, <strong>empty</strong> the date or enter <strong>0000-00-00</strong> to disable this feature.<br /><br />When this feature used, message will be send in batch, approx. %s message per hour since the date of message executed."; //update > v2.2.90809

$LNG['hint_payout_egold'] = "Press <b>{$LNG['payout_btn_pay']}</b> button (available when you select '{$LNG['a_s_autopaid']}' option only) to start paying your members. This process is automatically and may take a few minutes. It's recommended to select not more than 5 members (depend from your member's commission records).<br /><br />Or you can pay your members manually, once you've paid your members, click the <b>{$LNG['payout_paid']}</b> button. This will automatically mark all unpaid as paid as well.";

$LNG['hint_payout_alertpay'] = "Start making Payza payments by following these simple steps:<br /><br />Copy the payees list in the text area shown below by clicking <b>{$LNG['payout_btn_select']}</b> button and press Ctrl+C.<br /><br />Login to your Payza account.<br /><br />To make a payment, click <b>Send Money</b>. Next, click <b>Mass Pay</b> menu, paste the payees list in the Transfer listbox and follow the instructions.<br /><br />Once you've paid members, click the <b>{$LNG['payout_paid']}</b> button. This will automatically mark all unpaid as paid as well."; //new

$LNG['hint_payout_paypal'] = "Press <b>{$LNG['payout_btn_pay']}</b> button (available when you select '{$LNG['a_s_autopaid']}' option only) to start paying your members. This process is automatically and may take a few minutes. It's recommended to select not more than 5 members (depend from your member's commission records).<br /><br />Or you can pay your members manually by following these simple steps:<br /><br />Download the file of payees by clicking <b>{$LNG['payout_btn_download']}</b> button.<br /><br />Login to your Premier or Business PayPal account (if you don't have one, create it or upgrade from your existing Personal account).<br /><br /><font color=red>Note:</font> You must confirm your email address and checking account before using Mass Payment.<br /><br />To make a payment, click <b>Mass Pay</b> at the bottom of any PayPal webpage. Next, click <b>Make a Mass Payment</b> on the Overview page. Locate and upload your Mass Payment file when prompted and customize the email your recipients will receive. Click <b>Continue</b> and then <b>Send Money</b> to instantly process the Mass Payment.<br /><br />Once you've paid members, click the <b>{$LNG['payout_paid']}</b> button. This will automatically mark all unpaid as paid as well."; //update > v2.1.80214

$LNG['hint_payout_perfectmoney'] = "Press <b>{$LNG['payout_btn_pay']}</b> button (available when you select '{$LNG['a_s_autopaid']}' option only) to start paying your members. This process is automatically and may take a few minutes. It's recommended to select not more than 5 members (depend from your member's commission records).<br /><br />Or you can pay your members manually from your PerfectMoney account, once you've paid your members, click the <b>{$LNG['payout_paid']}</b> button. This will automatically mark all unpaid as paid as well."; //new > v2.3.442

$LNG['hint_payout_solidtrustpay'] = "Press <b>{$LNG['payout_btn_pay']}</b> button (available when you select '{$LNG['a_s_autopaid']}' option only) to start paying your members. This process is automatically and may take a few minutes. It's recommended to select not more than 5 members (depend from your member's commission records).<br /><br />Or you can pay your members manually by following these simple steps:<br /><br />Download the file of payees by clicking <b>{$LNG['payout_btn_download']}</b> button.<br /><br />Login to your business SolidTrustPay account (if you don't have one, create it or upgrade from your existing Personal account).<br /><br />To make a payment, click <b>Send Mass Payment</b> in the Merchant Zone menu. Next, locate and upload your mass payment file when prompted, click <b>Transfer</b> button to instantly process the mass payment.<br /><br />Once you've paid members, click the <b>{$LNG['payout_paid']}</b> button. This will automatically mark all unpaid as paid as well."; //new > v2.3.442

$LNG['hint_payout_safepay'] = "Start making SafePay Solutions payments by following these simple steps:<br /><br />Download the file of payees by clicking <b>{$LNG['payout_btn_download']}</b> button.<br /><br />Login to your SafePay Solution account.<br /><br />To make a payment, click <b>Send Money</b>. Next, click <b>Batch Pay</b> and follow the instructions.<br /><br />Once you've paid members, click the <b>{$LNG['payout_paid']}</b> button. This will automatically mark all unpaid as paid as well.";

$LNG['hint_payout_moneybookers'] = "Start making MoneyBookers payments by following these simple steps:<br /><br />Download the file of payees by clicking <b>{$LNG['payout_btn_download']}</b> button.<br /><br />Login to your MoneyBookers account.<br /><br /><font color=red>Note:</font> Mass pay filenames MUST be unique for each mass payment.<br /><br />To make a payment, click <b>Send Money</b>. Next, click <b>Make a Mass Payment</b> and follow the instructions.<br /><br />Once you've paid members, click the <b>{$LNG['payout_paid']}</b> button. This will automatically mark all unpaid as paid as well.";

$LNG['hint_payout_libertyreserve'] = "Press <b>{$LNG['payout_btn_pay']}</b> button (available when you select '{$LNG['a_s_autopaid']}' option only) to start paying your members. This process is automatically and may take a few minutes. It's recommended to select not more than 5 members (depend from your member's commission records).<br /><br />Or you can pay your members manually, once you've paid your members, click the <b>{$LNG['payout_paid']}</b> button. This will automatically mark all unpaid as paid as well."; //new > v2.1.80214

$LNG['hint_payout_manual'] = "Download the file of payees by clicking <b>{$LNG['payout_btn_download']}</b> button and make manual payment to your members.<br /><br />Once you've paid members, click the <b>{$LNG['payout_paid']}</b> button. This will automatically mark all unpaid as paid as well.";

$LNG['hint_fields_name'] = "Use only lowercase alphanumeric (a-z, 0-9) and underscore (_), cannot contains space or symbol.<br /><br />It's recommended to using field name prefix.<br />Example: cf_ssn, cf_age, cf_company, etc.";

$LNG['hint_fields_options'] = "Use one option each line. The value and text are separated by | sign.<br /><br /><strong>Example 1:</strong><br />No|First Option<br />Yes|Second Option<br />etc.<br /><br /><strong>Example 2:</strong><br />1|First Option Text<br />2|Second Option Text<br />etc.<br /><br />Option details: <b>No</b> or <b>1</b> is a value and <b>First Option</b> or <b>First Option Text</b> is a text string displayed in the option list.";

$LNG['hint_refrace_rrmaxref'] = "Enter the maximum direct referrals to win the contest, type 0 for unlimited.<br /><br />If this feature enable, system will end the contest when the maximum direct referrals reached by member."; //new > v2.2.91730

$LNG['hint_refrace_rrrwdlist'] = "Enter the reward for the contest here (flat value only), separated with comma (,). The reward will ordered by the position of the winners.<br /><br />Example: <strong>100, 50, 20</strong><br />Reward 100 for the winner position #1, reward 50 for the winner position #2, and reward 20 for the winner position #3<br /><br /><strong>Advanced Setting:</strong><br />You can also setup the matching reward for the winner's sponsors by add tab (|) character in the winners reward value.<br /><br />Example: <strong>100|25, 50|5, 20</strong><br />Reward 100 for the winner position #1, <em>reward 25 for the winner position #1's sponsor</em>, reward 50 for the winner position #2, <em>reward 5 for the winner position #2's sponsor</em>, and reward 20 for the winner position #3. The member's sponsors level is based on the PayPlan Settings."; //new > v2.2.91730

$LNG['hint_refrace_rrpplan'] = "Select PayPlan you want to enable this Contest, press Ctrl + click to select multiple PayPlan.<br /><br />If multiple PayPlans selected, the total referrals will counted from all of the selected PayPlans (that registered by members, if available)."; //new > v2.2.91730

$LNG['hint_refrace_rrbanuname'] = "You can <strong>restrict</strong> certain members to take a part from the contest by entering the member username here, separated with comma."; //new > v2.2.91730

$LNG['hint_refrace_rrcmgen'] = "If this option enabled, when the contest ended system will generate the reward (transaction history) for the winners and close the contest.<br /><br />If you planning  to extend the contest before contest ended or want to manually reward the winners, you can leave this option to disable."; //new > v2.2.91730

$LNG['hint_giftpass_codeformat'] = "You can define how the GiftPass code will generated by using syntax below:<br /> <strong>?</strong> = generate any alpha-numeric character<br /> <strong>@</strong> = generate any alpha character<br /> <strong>#</strong> = generate any numeric character<br /><br /><i>Example:</i><br />????-<font color='#CC00FF'>CODE</font>????-<font color='#4E9900'>@@</font><font color='#0081FD'>##</font> syntax will generate code with example result:<br />189C-<font color='#CC00FF'>CODE</font>TZNS-<font color='#4E9900'>BJ</font><font color='#0081FD'>15</font><br />4YGZ-<font color='#CC00FF'>CODE</font>FQ2R-<font color='#4E9900'>MX</font><font color='#0081FD'>42</font><br />13HR-<font color='#CC00FF'>CODE</font>6THI-<font color='#4E9900'>DB</font><font color='#0081FD'>27</font><br />QRLX-<font color='#CC00FF'>CODE</font>EGT1-<font color='#4E9900'>NJ</font><font color='#0081FD'>28</font><br />..."; //new > v2.2.91730

$LNG['hint_giftpass_gpusemax'] = "Maximum <strong>one</strong> GiftPass code available to use. If you enter 1 for this value, when GiftPass code used by member, the code will expired and no longer available again."; //new > v2.2.91730

$LNG['hint_giftpass_gpidref'] = "You can define the default <strong>Referrer Id</strong> to forced when using this GiftPass code. This Id will override any existing referrer id when this code used in the <strong>registration form</strong>. Useful when used by members to register new member manually or offline."; //new > v2.2.91730

$LNG['hint_giftpass_gpvalue'] = "Enter the value for the GiftPass code here, you can setup in percentage or flat value. If flat value used and the value greater than amount need to pay, the value will considered as 100%."; //new > v2.2.91730

$LNG['hint_dlbuilder_url'] = "Insert %IDUSERNAME% tag instead of member Username or Id within url address.<br /><br /><strong>Example:</strong><br />If the site referral url is http://www.sitename.com/id/<strong>xyz123</strong> where <strong>xyz123</strong> is a member's username then enter http://www.sitename.com/id/<strong>%IDUSERNAME%</strong> as the site url.<br /><br />Member's Username or Id will replace %IDUSERNAME% tag within the url address.";

$LNG['hint_dbex_qrywhere'] = "Enter the query condition here, syntax: <strong>field</strong> = '<strong>value</strong>'<br /><br />Example:<br />- country = 'us' OR country = 'id'<br />- edompet > '100'<br />- email LIKE '%@mail.tld'<br />- etc"; //new > v2.3.450

$LNG['hint_vlmbased_pv'] = "When this Personal Volume is set, members will earn their commission if their total active commission (and rewards) are equal or greater than this PV value (commission from sales made by members).<br /><br />Leave the value empty or set it to 0 to disable this feature."; //new > v2.2.80401

$LNG['hint_vlmbased_bv'] = "When this Business or Group Volume is set, members will earn their commission if their total active commission (and rewards) are equal or greater than this BV or GV value (commission from sales made by members and their downlines).<br /><br />Leave the value empty or set it to 0 to disable this feature."; //new > v2.2.80401

$LNG['hint_itemized_defcm'] = "Optional field, use it if required, separated with commas.<br /><br />If the commission list for any items under this category not set or empty, then the system will collect the commission list from this field value.<br /><br /> The value support for percentage or flat, empty to disable this feature.";

$LNG['hint_merchant_list'] = "<strong>Available merchant:</strong><br />Payza, PayPal, LibertyReserve, SolidTrustPay, PerfectMoney, E-Gold, AuthorizeNet, Manual, Other, MoneyBookers, LinkPoint, SafePay"; //update > v2.3.442

$LNG['hint_adsgroup_adssize'] = "Below is the most typical ad sizes used for online advertising purposes. The ad size for a web site will vary depending on their standards.<br /><br /><img src='images/adsizes.jpg' border='0'>"; //new > v2.3.450

$LNG['hint_adsgroup_agplanids'] = "If certain payplan selected, system will generate ads credits for member who register to the related payplan.<br /><br />If available in eStore, you can setup an item for ads credits where member be able to purchase it directly from their User CP or order page."; //new > v2.3.450

$LNG['hint_itemized_status'] = "If <b>{$LNG['a_itemized_status0']}</b> or <b>{$LNG['a_itemized_status2']}</b> selected, then it will override <a href='index.php?a=admin&amp;b=items'>items status</a>.";

$LNG['hint_itemized_list'] = "Here you can select a category for your product. If there's no category (empty) then you need to add category for your product through <a href='index.php?a=admin&amp;b=itemized'>{$LNG['a_itemized_header']}</a>";

$LNG['hint_parent_item'] = "Here you can select parent product item for your new product (if your new product as embeded item that is not required to be purchased or one package with another item that purchased is required).<br /><br />If you select a parent item for your current product then everytime people purchase your parent product item they will also have ability to access your current product item.<br /><br />Usage example: if your parent product items is a downloadable software then you can add new item like software manual as embeded item.<br /><br />If it's standalone item (that you sell) then you should not select parent product item.";

$LNG['hint_items_file'] = "Here you can select file for your product. If this value is empty then you need upload your product file through <a href='index.php?a=admin&amp;b=manage_files'>{$LNG['a_menu_manage_files']}</a>.<br /><br />If you select minus (<strong>-</strong>), then the product act like service or offline product (and there is no file will be available for download).<br /><br />If you select a file (a product file), then you need update the file status to <b>{$LNG['a_man_files_type_3']}</b>.";

$LNG['hint_items_tax'] = "Separated with comma each option. The value and text are separated by | sign.<br /><br /><strong>Example:</strong><br />US|10%, BR|5, ID|7%, AU|12%, etc.<br /><br />Option details: <b>US</b> or <b>ID</b> is a country code and <b>10%</b> or <b>5</b> is a percentage (from the total item price) or fixed tax or vat. This value will be calculated and added in the total payment."; //new > v2.3.442

$LNG['hint_items_snh'] = "Separated with comma each option. The value and text are separated by | sign.<br /><br /><strong>Example:</strong><br />US|0-5|2%, US|6-10|3%, ID|0-20|2%, AU|11-50|5%, etc.<br /><br />Option details: <b>US</b> or <b>ID</b> is a country code, <b>0-n</b> or <b>n-m</b> is the total weight, and <b>2%</b> or <b>10</b> is a percentage (from the total item price) or fixed shipping and handling. This value will be calculated and added in the total payment."; //new > v2.3.442

$LNG['hint_items_id'] = "Here, you can enter your product unique id. It's for internal use only (ie. integration with third party gateway). The value for {$LNG['a_items_id']} should not exceed than 32 character length.";

$LNG['hint_items_oldprice'] = "This price set for promotional only, for example if you set {$LNG['a_items_price']} = 10 and {$LNG['a_items_oldprice']} = 15 then it's will show your product price is <s>15</s> <strong>10</strong>.";

$LNG['hint_items_bulkprice'] = "This price will use in extended order. For example it's used when your customer want to extended their purchased product that already expired.<br /><br /><b>Note:</b> If this value isn't set or this value is set to <b>0</b> or this value is same as <b>{$LNG['a_items_price']}</b> value, then extended order will be <b>disabled</b>.";

$LNG['hint_items_cmlist'] = "Affiliate commission list structure based on <b>level deep</b> in <a href='index.php?a=admin&amp;b=payplans'>{$LNG['a_s_payment']}</a> (separate with commas).<br /><br /><font color=red>Valid value:</font> Flat value (exp. 2 or 2.5 or 0.5) and Percentage value (exp. 5% or 10.2% or 0.9%, this value is percentage from the active <b>{$LNG['a_items_price']}</b> value).<br /><br />Example 1 (level deep <b>5</b>, flat value): <b>10, 15, 1, 0, 2</b> means commission for affiliate is <b>10</b>, sub-affiliate level 2 is <b>15</b>, sub-affiliate level 3 is <b>1</b>, sub-affiliate level 4 is <b>0</b> (no commission) and sub-affiliate level 5 is <b>2</b>.<br /><br />Example 2 (level deep <b>3</b>, percentage value): <b>10%, 12%, 1%, 5%, 2%</b> means commission for affiliate is <b>10%</b> from the {$LNG['a_items_price']}, sub-affiliate level 2 is <b>12%</b> from the {$LNG['a_items_price']}, sub-affiliate level 3 is <b>1%</b> from the {$LNG['a_items_price']}.<br /><br />Example 3 (level deep <b>7</b>, mixed value): <b>10%, 2, 1, 2%, 1</b> means commission for affiliate is <b>10%</b> from the {$LNG['a_items_price']}, sub-affiliate level 2 is <b>2</b>, sub-affiliate level 3 is <b>1</b>, sub-affiliate level 4 is <b>2%</b> from the {$LNG['a_items_price']}, sub-affiliate level 5 is <b>1</b>, sub-affiliate level 6 is <b>0</b> (no commission) and sub-affiliate level 7 is <b>0</b> (no commission).<br /><br />If this value is set to <b>0</b> or <b>empty</b> then no affiliate commission will be generated.";

$LNG['hint_items_buyercm'] = "Enter the commission for buyer here, it's flat or percentage value (calculated from the active product price value). Leave empty to disable, default value is empty."; //new > v2.2.90809

$LNG['hint_items_xupcm'] = "Affiliate commission list structure based on <b>x-up system</b>, (separate with commas).<br /><br />Valid commission value: Flat value (exp. 2 or 2.5 or 0.5) and Percentage value (exp. 5% or 10.2% or 0.9%, this value is calculated from the <b>{$LNG['a_items_cmlist']}</b> above).<br /><br /><font color=red>Value Example:</font> 2|20, 4|25%, <strong>x</strong>|<strong>y</strong>, etc. where <strong>x</strong> is x-up position and <strong>y</strong> is the referrer's sponsor commission value.<br /><br />Example 1 (1-up, flat value, commission list set to <b>15</b>): <b>1|10</b>, this value means commission for affiliate is <b>5</b> (15-10), affiliate's sponsor is 10, this commission wil generate for 1st sale only. The 2nd sales onwards will generate 15 commission for affiliate.<br /><br />Example 2 (2-up, percentage value, commission list set to <b>18</b>, 12): <b>1|75%, 2|50%</b>, this value means commission for affiliate is <b>25%</b> from 18, affiliate's sponsor is 75% from 18 for <b>1st</b> sale and commission for affiliate is <b>50%</b> from 18, affiliate's sponsor also 50% from 18 for <b>2nd</b> sale, this commission wil generate for 1st and 2nd sales only. The 3rd sales onwards will generate 18 commission for affiliate and 12 for affiliate's sponsor.<br /><br />Example 3 (reverse 2-up, mixed value, commission list set to <b>18</b>, 12): <b>2|75%, 4|15</b>, this value means commission for affiliate is <b>25%</b> from 18, affiliate's sponsor is 75% from 18 for <b>2nd</b> sale and commission for affiliate is <b>3</b> (from 18-15), affiliate's sponsor is 15 for <b>4th</b> sale, this commission will generate for 2nd and 4th sales only, the other sales will generated 18 commission for affiliate and 12 for affiliate's sponsor."; //new > v2.2.90809

$LNG['hint_items_key'] = "If your product using registration or license key, then select this value to <b>Yes</b> and place your license key generator file (written using php language) at <b>includes/misc/</b> directory.<br /><br />You should pass <b>\$product_license</b> and <b>\$customer_domain</b> variables from your keygen.<br /><br />Please refer to estore plugin manual for details about this license key generator file or simply contact our support desk for help or example.";

// ================================//
// include additional languge file //
// ================================//
if ($CONF['default_skin'] && $CONF['default_language']) @include_once("./templates/{$CONF['default_skin']}/lng_{$CONF['default_language']}.php");
?>