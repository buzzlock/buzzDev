<?php
/*
# ------------------------------------------------------------------------ #
# Software Name: EzyGold - The Ultimate Network Marketing Suite            #
# Written by: C. Era Setiawan                                              #
# Website: http://www.ezygold.com                                          #
# Copyright ©2007-2010 Aratisa Corporation. All Rights Reserved.           #
# ------------------------------------------------------------------------ #
# COPYRIGHT AND LICENSE AGREEMENT.                                         #
# REDISTRIBUTION OF THIS SCRIPT OR ANY MODIFICATIONS                       #
# OF THIS SCRIPT IN ANY FORM IS STRICTLY PROHIBITED!                       #
#                                                                          #
# There are no warranties expressed or implied of any kind, and by using   #
# this code you agree to indemnify Era Setiawan and Aratisa Corporation,   #
# from any and all liability that might arise from it's use.               #
# ------------------------------------------------------------------------ #
# ONLY EDIT LINES BELOW IN ADVANCE!                                        #
# ------------------------------------------------------------------------ #
*/

/*
# ------------------------------------------------------------------------ #
# NOTE: This file is executed when member payment success                  #
# ------------------------------------------------------------------------ #
# Valid Variables:                                                         #
#       $CONF  = Settings from setting and payplan tables (type: array);   #
#       $ETCS  = Site settings from etcs table (type: array);              #
#       $PAYM  = Payment settings from merchant table (type: array);       #
#       $LNG   = Language template from languages file (type: array);      #
#       $TMPL['username']  = New member username;                          #
#       $TMPL['fullname']  = New member fullname;                          #
#       $TMPL['email']     = New member email address;                     #
#                                                                          #
# ------------------------------------------------------------------------ #
*/

if (!defined('EZYGOLD') || defined('EZYGOLDNETCOM') != 'ARATiSA iNC.') {
	die("<title>EzyGold - Network Marketing Software</title>This file cannot be accessed directly.");
}

if ($is_renewal != 1) {
    // insert your code here (first time payment success)
} else {
    // insert your code here (renewal success)
}
?>