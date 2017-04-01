<?php
include_once "connect.inc";
include_once "ap_common.php";
$data = authenticated($cid);
if (isset($data)) //successful login
{ 
    $privilage = getprivilage($cid);
    $priv      = explode("+", $privilage);
    if ($data['name']) {
        $name = $username = $data['name'];
    } else {
        $name = $username = getname($cid);
    }
    $name = preg_replace('/[^A-Za-z0-9\. -_]/', '', $name);	
    $username = preg_replace('/[^A-Za-z0-9\. -_]/', '', $username);
    $center = getcenter_for_walkin($name);

    if (JsConstants::$whichMachine == 'prod' && JsConstants::$siteUrl == 'http://crm.jeevansathi.com') {
        if (in_array('S', $priv) || in_array('FTA', $priv) || in_array('M', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/searchpage.php?user=$name&cid=$cid\">Search Profile</a>";
        }

        // Outbound Call User Link accessibility
        if (in_array("FTAFTO", $priv) || in_array("FTASup", $priv) || in_array("ExcUpS", $priv) || in_array("UpSSup", $priv) || in_array("ExcRnw", $priv) || in_array("RnwSup", $priv) || in_array("ExcFP", $priv) || in_array("FPSUP", $priv) || in_array("ExcDIb", $priv) || in_array("INBSUP", $priv) || in_array("ExcDOb", $priv) || in_array("ExcBSD", $priv) || in_array("ExcBID", $priv) || in_array("ExcFSD", $priv) || in_array("ExcFID", $priv) || in_array("ExcPrm", $priv) || in_array("ExPrmO", $priv) || in_array("ExPrmI", $priv) || in_array("ExcWFH", $priv) || in_array("SLMNTR", $priv) || in_array("SLSUP", $priv) || in_array("SLHD", $priv) || in_array("P", $priv) || in_array("MG", $priv) || in_array("TRNG", $priv) || in_array("PreAll", $priv) || in_array("ExcWL", $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmAllocation/outboundProcessList?name=$username&cid=$cid\">Outbound Calls User</a>";
        }

        if (in_array('ExcDIb', $priv) || in_array('INBSUP', $priv) || in_array('P', $priv) || in_array('MG', $priv) || in_array('TRNG', $priv) || in_array('IA', $priv) || in_array('IUO', $priv) || in_array('FTAFTO', $priv) || in_array('FTASup', $priv) || in_array('CSEXEC', $priv) || in_array('CSSUP', $priv)) {
            //CRM inbound users
            $linkarr[] = "<a href=\"$SITE_URL/crm/get_history.php?name=$username&cid=$cid\">View history of a particular user</a>";
        }

        if (in_array('IUU', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/crm/confirmclient.php?user=$name&cid=$cid\">Confirm Client</a>";
        }

        if (in_array('IUI', $priv) || in_array('IUO', $priv) || in_array('IUW', $priv)) {
            $linkarr[] = "<a href=\"#\" onClick=\"MM_openBrWindow(this,'/crm/ncr_individual_operator_record_new.php?user=$name&cid=$cid','mywindow','width=700,height=600,scrollbars=yes');return false;\">Your Track Record</a>";
        }

        if (in_array("UpSSup", $priv) || in_array("FPSUP", $priv) || in_array("INBSUP", $priv) || in_array("ExcPrm", $priv) || in_array("SLMNTR", $priv) || in_array("SLSUP", $priv) || in_array("SLHD", $priv) || in_array("P", $priv) || in_array("MG", $priv) || in_array("TRNG", $priv) || in_array("ExcFld", $priv) || in_array("SupFld", $priv) || in_array("RSP", $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmDeallocation/ReleaseProfile\">Release Single Profile</a>";
        }

        if (in_array('MnAllc', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmAllocation/manualExtAllocation\">Manual Allocation/Extension</a>";
        }

        $linkarr[] = "<a href=\"$SITE_URL/jsadmin/change_passwd.php?name=$user&cid=$cid\">Change your password</a>";

        if (in_array('RC', $priv)) {
            //$name=getname($cid);
            $linkarr[] = "<a target='_blank' href=\"$SITE_URL/P/inputprofile.php?source=onoffreg&operator=$name\">Register an offline user</a>";
        }
        if (in_array('SE', $priv)) {
            //$name=getname($cid);
            $linkarr[] = "<a target='_blank' href=\"$SITE_URL/P/inputprofile.php?source=onoffreg&operator=$name\">Register an offline user</a>";
        }
        // Added by Reshu for profile document verification trac#3626 and trac#3632
        /*if(in_array("ExcFld",$priv) || in_array("SupFld",$priv) || in_array("MgrFld",$priv) || in_array("SLHDO",$priv) || in_array("MG",$priv) || in_array("P",$priv) || in_array("TRNG",$priv))
        $linkarr[]="<a href=\"$SITE_URL/operations.php/profileVerification/profileDocumentsUpload\">Upload Profile Verification Documents</a>";
         */

	if(in_array("SupFld",$priv))
		$linkarr[]="<a href=\"$SITE_URL/operations.php/profileVerification/profileDocumentsUpload\">Upload Profile Verification Documents</a>";

        if (in_array('CSEXEC', $priv) || in_array('CSSUP', $priv) || in_array('LTFSUP', $priv) || in_array('TRNGOP', $priv) || in_array('OPSHD', $priv) || in_array('TRNG', $priv) || in_array('P', $priv) || in_array('MG', $priv) || in_array('SLSUP', $priv) || in_array('SLHD', $priv) || in_array('ExcFld', $priv) || in_array('SupFld', $priv) || in_array('MgrFld', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/notduplicate/index\">Mark profile pair as not duplicate</a>";
        }

        if (in_array('IUO', $priv) || in_array('IUI', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/crm/only_service.php?name=$username&cid=$cid&mode=W\">Disposing call without alloting profile</a>";
        }

        if (in_array('MS', $priv) || in_array("AdSlEx", $priv) || in_array("P", $priv) || in_array("MG", $priv) || in_array('NTSP', $priv) || in_array("AdSlEx", $priv) || in_array("P", $priv) || in_array("MG", $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/mobcheckuser.php?user=$name&cid=$cid\">Search user based on Phone Number</a>";
        }

        if (in_array('IUO', $priv)) {
            //CRM outbound users
            $linkarr[] = "<a href=\"/mis/ni_handled_incentive_calc.php?cid=$cid&user=$user\">Executive-wise Revenue for Incentive Calculation</a>";
            $linkarr[] = "<a href=\"$SITE_URL/crm/daily_handled_list.php?cid=$cid\">Daily Handled List</a>";
        }

        if (in_array('BU', $priv) || in_array('BA', $priv) || in_array('SE', $priv)) {
            //billing entry operator
            $linkarr[] = "<a href=\"$SITE_URL/billing/billing.php?user=$name&cid=$cid\">Billing</a>";
        }
        if (in_array('CRMTEC', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmInterface/billingManagementInterface?user=$name&cid=$cid\">Billing Management Interface</a>";
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmInterface/changeActiveServicesInterface?user=$name&cid=$cid\">Change Active Services Interface</a>";
        }
        if (in_array("CRMTEC", $priv) || in_array("DA", $priv) || in_array("MG", $priv) || in_array("SLHDO", $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmInterface/manageVdOffer\">Manage Variable Discount Offer </a>";
        }
        if (in_array('MBU', $priv) || in_array('BU', $priv) || in_array('BA', $priv)) //Misc-Revenue billing entry operator
        {
            $linkarr[] = "<a href=\"$SITE_URL/billing/search_rev_user.php?user=$name&cid=$cid\">Misc Revenue Billing</a>";
        }

        if (in_array("IUI", $priv) || in_array("IUO", $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/mis/performance_track_mis.php?cid=$cid\">Performance Track MIS</a>";
        }

        if (in_array('LTFVnd', $priv) || in_array('LTFHD', $priv) || in_array('P', $priv) || in_array('MG', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/register_lead.php?name=$username&cid=$cid\">Register a Lead</a>";
        }
        if (in_array('CRMTEC', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/commoninterface/selectGateway\">Select Gateway Redirection</a>";
        }

    } else {
        //    if(in_array('MA',$priv)||in_array("MC",$priv)||in_array("CSEXEC",$priv)||in_array("CSSUP",$priv)||in_array("LTFSUP",$priv)||in_array("LTFHD",$priv)||in_array("SLSUP",$priv)||in_array("SLHD",$priv)||in_array("SLMGR",$priv)||in_array("SLSMGR",$priv)||in_array("SLHDO",$priv)||in_array("AUTLOG",$priv) || in_array("SupFld",$priv) || in_array("MgrFld",$priv) || in_array("PA",$priv))
        if (in_array("LTFHD", $priv) || in_array("SLSUP", $priv) || in_array("SLHD", $priv) || in_array("SLHDO", $priv) || in_array("AUTLOG", $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/commoninterface/generateAutologinLink\">Generate Autologin</a>";
        }
	if(in_array("LTFSUP",$priv)|| in_array("MG",$priv)||in_array("P",$priv))
	{
            $linkarr[] = "<a href=\"$SITE_URL/operations.php//sugarcrm/csvToSugar\">Upload Sugarcrm CSV</a>";
	}
        if (in_array('ANT', $priv)) {
            //$linkarr[]="<a href=\"$SITE_URL/operations.php/commoninterface/uploadVD\">Upload variable special discount data</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/upload_offer_discount.php?name=$user&cid=$cid\">Upload Offer Discount csv</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/upload_bluedart_airway.php?name=$user&cid=$cid\">Upload Bluedart Airway csv</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/csv_for_bms.php?name=$user&cid=$cid&bmscsv=1\">Upload variable special discount csv for BMS </a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/csv_for_bms.php?name=$user&cid=$cid&bmscsv=2\">Upload contacts stats csv for BMS</a>";
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/csvUpload/uploadNotificationCsv\">Upload Notification CSV</a>";
        }

        if (in_array('BDTA', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/bluedart_request.php?name=$user&cid=$cid\">Send BlueDart COD Request</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/bluedart_print_bill.php?name=$user&cid=$cid\">Print Bluedart COD Request Bill</a>";
        }

        if (in_array('P', $priv) || in_array('IJS', $priv) || in_array('SJS', $priv) || in_array('TSJS', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/switch_match_alert_algo.php?name=$user&cid=$cid\">Switch Match Alert Algorithm</a>";
            $linkarr[] = "<a href=\"$SITE_URL//operations.php/feedback/reportInvalid?name=$user&cid=$cid\">Invalid Reported Contacts</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/allot_contact.php?name=$user&cid=$cid\"> Allot contacts View to Paid members. </a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/check_profiles_score.php?cid=$cid\">Check forward and reverse score for two profiles</a>";
        } elseif (in_array('QC', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/invalid_phone_status.php?name=$user&cid=$cid\">Invalid Reported Contacts </a>";
        }
        if (in_array('DA', $priv))
        //$linkarr[]="<a href=\"$SITE_URL/jsadmin/change_festive.php?user=$name&cid=$cid\">Activate/Deactivate Festive offer</a>";

        /*Operator Admin Privileges Start*/ {
            if (in_array('OA', $priv)) {
                if (in_array('OB', $priv));
                else{
                    $linkarr[] = "<a href=# onClick=\"window.open('$SITE_URL/jsadmin/login_customer.php?cid=$cid','','fullscreen=1,resizable=1,scrollbars=1');\">Offline user login</a>";
                }

                $linkarr[] = "<a href=\"$SITE_URL/jsadmin/change_passwd_op.php?name=$user&cid=$cid\">Change operator password</a>";
                $linkarr[] = "<a href=\"$SITE_URL/jsadmin/mod_acceptances.php?name=$user&cid=$cid\">Modify Acceptance Limit</a>";
                $linkarr[] = "<a href=\"$SITE_URL/jsadmin/operator_statistics.php?name=$user&cid=$cid\">Users registerd by an operator</a>";
                $linkarr[] = "<a href=\"$SITE_URL/jsadmin/addnew_user.php?name=$user&cid=$cid\"> Add New operator or admin</a>";
                $timein    = 1;
            }
        }

        /*Operator Admin Privilages End*/
        /*Operator Privileges Start*/

        if (in_array('OB', $priv)) {
            //$name = getname($cid);
            $smarty->assign("username", $name);
            $linkarr[] = "<a href=# onClick=\"window.open('$SITE_URL/jsadmin/login_customer.php?cid=$cid','','fullscreen=1,resizable=1,scrollbars=1');\">Offline user login</a>";
            $linkarr[] = "<a href=\"$SITE_URL/billing/billing_start.php?user=$name&cid=$cid&offline_billing=1\">Bill an offline user</a>";
            $linkarr[] = "<a href=#  onClick=\"window.open('$SITE_URL/jsadmin/customers_list.php?name=$name&cid=$cid','','fullscreen=1,resizable=1,scrollbars=1');\">Users registered by me</a>";
            $linkarr[] = "<a href=#  onClick=\"window.open('$SITE_URL/jsadmin/customers_list_tobeserviced.php?name=$name&cid=$cid','','fullscreen=1,resizable=1,scrollbars=1');\">Clients to be serviced today</a>";
            // $linkarr[]="<a href=#  onClick=\"window.open('$SITE_URL/jsadmin/offline_renewal_clients.php?name=$name&cid=$cid','','fullscreen=1,resizable=1,scrollbars=1');\">Clients to follow up for renewals</a>";
            $linkarr[] = "<a target='_blank' href=\"$SITE_URL/P/inputprofile.php?source=101&operator=$name\">Register 101 members</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/registered_by_me_101.php?cid=$cid&name=$name\">101 members registered by me</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/client_feedback.php?cid=$cid\">Client Feedback Form</a>";
            $timein    = 1;
        }
        if (in_array('OA', $priv)) {
            // $linkarr[]="<a href=# onClick=\"window.open('$SITE_URL/jsadmin/duplicate_verify_user.php?name=$name&cid=$cid','','fullscreen=1,resizable=1,scrollbars=1');\">Override Duplicate Phone Numbers</a>";
        }
        if (in_array('VRFYPH', $priv)) {
            $linkarr[] = "<a href=# onClick=\"window.open('$SITE_URL/jsadmin/offline_verify_user.php?name=$name&cid=$cid','','fullscreen=1,resizable=1,scrollbars=1');\">Verify/Unverify Users</a>";
        }
        if ($timein) {
            $date = date("Y-m-d");
            $link = "Mark Login Time";
            $ll   = "in";
            $sql  = "SELECT LOGOUT,LOGIN,ID FROM jsadmin.LOGIN_DETAILS WHERE OPERATOR='$name' AND DATE='$date' ORDER BY ID DESC LIMIT 1";
            $res  = mysql_query_decide($sql) or die(mysql_error());
            if (mysql_num_rows($res)) {
                $row = mysql_fetch_assoc($res);
                if ($row['LOGOUT'] == '00:00:00') {
                    $link = "Mark Logout Time";
                    $ll   = "out";
                    $id   = $row['ID'];
                    $mess = "Your login time is " . $row['LOGIN'] . ". <br>";
                }
            }

            $Timein = "<tr align=\"center\" class=\"formhead\"><td >$mess<a href=\"../jsadmin/timein.php?name=$name&cid=$cid&link=$ll&id=$id\">$link</a></td></tr>";
            $smarty->assign("Timein", $Timein);
        }
        /*Operator Privilages End*/

        /*Photo Screening Privileges Start*/
        if (in_array('PU', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/photoScreening/screen?source=new\">Photo Screen Accept/ Reject - New Photos</a>";
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/photoScreening/screen?source=edit\">Photo Screen Accept/ Reject- Edit Photos</a>";
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/photoScreening/processInterface?source=new\">Photo Screen Process -New Photos</a>";
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/photoScreening/processInterface?source=edit\">Photo Screen Process- Edit Photos</a>";
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/photoScreening/screenPhotosFromMail?source=mail\">Screen Photos From Mail</a>";
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/photoScreening/getUserDeletedPlusOriginalPhotos?source=new\">Show Deleted and Existing Photos</a>";
        }

        //OPS Screening//
        if (in_array('PDSEXC', $priv) || in_array('PDSSUP', $priv) || in_array('OPSHD', $priv) || in_array('P', $priv) || in_array('MG', $priv) || in_array('TRNGOP', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/duplicateScreening/Screening?pid=$profileid\">Screen probable duplicates</a>";
        }

        if (in_array('PDSSUP', $priv) || in_array('OPSHD', $priv) || in_array('P', $priv) || in_array('MG', $priv) || in_array('TRNGOP', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/duplicateScreening/Examine?pid=$profileid\">Re-examine probable duplicates</a>";
        }

        //end//
        if (in_array('CSEXEC', $priv) || in_array('CSSUP', $priv) || in_array('LTFSUP', $priv) || in_array('TRNGOP', $priv) || in_array('OPSHD', $priv) || in_array('TRNG', $priv) || in_array('P', $priv) || in_array('MG', $priv) || in_array('SLSUP', $priv) || in_array('SLHD', $priv) || in_array('ExcFld', $priv) || in_array('SupFld', $priv) || in_array('MgrFld', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/notduplicate/index\">Mark profile pair as not duplicate</a>";
        }

        if (in_array('PA', $priv)) {
            $user      = "n";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/view_photo_profile_count.php?name=$user&cid=$cid&val=new\">View Photo Screening Stats</a>";
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/photoScreening/masterPhotoEdit\">Master Photo Edit</a>";
        }
        /*Photo Screening Privileges End*/

        /*Horoscope Privileges Start*/
        if (in_array('HU', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/showhoroscopetoscreen.php?name=$user&cid=$cid\">View assigned horoscope profiles</a>";
        }
        if (in_array('HA', $priv)) {
            $user      = "n";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/showhoroscopetoassign.php?name=$name&user=$user&cid=$cid\">Assign Horoscope Profiles</a>";
        }
        if (in_array('OH', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/add_offline_horoscope.php?name=$user&cid=$cid&val=new\">Add Offline Horoscopes</a>";
        }

        /*Horoscope Privileges End*/

        /*MatriProfile Privileges Start*/
        if (in_array('MPU', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/show_matri_allot.php?checksum=$cid&user=$user\">Allotted Matri profiles</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/search_matri_profile.php?checksum=$cid&user=$user&show_image=1\">Search Matri-Profile member</a>";
        }
        if (in_array('MPA', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/matri_reply_manage.php?checksum=$cid&user=$user\">Matri-Profile Reply Manage</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/show_matriprofile.php?checksum=$cid&user=$user\">View / Allot Matri-Profile Members</a>";
        }
        /*MatriProfile Privileges End*/

        /*Profile Screening Privileges Start*/
        if (in_array('NU', $priv)) {
            //$name = getname($cid);
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/userview.php?name=$user&cid=$cid\">View assigned profiles</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/screen_new.php?name=$user&cid=$cid&val=new\">Screen New Profiles</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/screen_new.php?name=$user&cid=$cid&val=edit\">Screen Edit Profiles</a>";
        }
	if(in_array('DP', $priv) || in_array('MG', $priv) || in_array('P', $priv) || in_array('SLHDO', $priv)) {
		$linkarr[] = "<a href=\"$SITE_URL/jsadmin/del_csl_profile.php?name=$name&cid=$cid\">Delete comma-seperated list of profiles</a>";
	}
        if (in_array('NU1', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/userview_1min.php?name=$user&cid=$cid\">View assigned profiles - 1 Min</a>";
        }

        if (in_array('A', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/mis/matriprofile_todownload.php?checksum=$cid&user=$user\">Mark uploaded Matri-Profiles</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/view_profile_count.php?name=$user&cid=$cid&val=new\">View Screening Stats</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/newusername.php?name=$user&cid=$cid\">Screen New Username</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/show_user_record.php?name=$user&cid=$cid\">Search for a record</a>";
        }
        if (in_array('QC', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/qc_screening.php?user=$user&checksum=$cid\"> QC Screening</a>";
        }
        if (in_array('OPP', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/add_edit_fields.php?name=$user&cid=$cid&val=new\">Master Profile Edit</a>";
        }

        /*Profile Screening Privileges End*/

        if (in_array('S', $priv) || in_array('FTA', $priv) || in_array('M', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/searchpage.php?user=$name&cid=$cid\">Search Profile</a>";
        }
        if (in_array('IUO', $priv) || in_array('IUI', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/special_var_discount.php?cid=$cid\">Special Discount User</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/special_discount.php?cid=$cid\">40% DISCOUNT CHECK</a>";
            $linkarr[] = "<a href=\"$SITE_URL/crm/only_service.php?name=$username&cid=$cid&mode=W\">Disposing call without alloting profile</a>";
        }
        if (in_array('R', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/retrievepage.php?user=$name&cid=$cid\">Retrieve Profile</a>";
        }
        if (in_array('TA', $priv)) //thumbnail administrator
        {
            // $linkarr[]="<a href=\"$SITE_URL/jsadmin/show_thumbnails_to_assign.php?name=$name&cid=$cid\">Assign Thumbnails</a>";
        }
        if (in_array('TU', $priv)) //thumbnail operator
        {
            // $linkarr[]="<a href=\"$SITE_URL/jsadmin/show_thumbnails_to_screen.php?username=$name&cid=$cid\">View Assigned Thumbnails</a>";
        }
        if (in_array('F', $priv)) //Feedback operator
        {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/feedback_check.php?user=$name&cid=$cid\">Feedback Check</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/faq_admin_main.php?user=$name&cid=$cid\">FAQ Admin</a>";
        }
        if (in_array('MS', $priv) || in_array("AdSlEx", $priv) || in_array("P", $priv) || in_array("MG", $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/mobcheckuser.php?user=$name&cid=$cid\">Search user based on Phone Number</a>";
        }
        if (in_array('SUP', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/manageOnlineDialing.php?user=$name&cid=$cid\">Manage Online Dialing</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/online_call_divert.php?user=$name&cid=$cid\">Online Call Divert</a>";
        }
        if (in_array('BU', $priv)) //billing entry operator
        {
            $linkarr[] = "<a href=\"$SITE_URL/billing/billing.php?user=$name&cid=$cid\">Billing</a>";
            $linkarr[] = "<a href=\"$SITE_URL/billing/dueamountlist.php?user=$name&cid=$cid\">List for Due Amount</a>";
            $linkarr[] = "<a href=\"$SITE_URL/billing/search_rev_user.php?user=$name&cid=$cid\">Misc Revenue Billing</a>";
        }
        if (in_array('CRMTEC', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmInterface/billingManagementInterface?user=$name&cid=$cid\">Billing Management Interface</a>";
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmInterface/changeActiveServicesInterface?user=$name&cid=$cid\">Change Active Services Interface</a>";
        }
        if (in_array('MBU', $priv)) //Misc-Revenue billing entry operator
        {
            $linkarr[] = "<a href=\"$SITE_URL/billing/search_rev_user.php?user=$name&cid=$cid\">Misc Revenue Billing</a>";
        }
        if (in_array('BA', $priv)) //billing admin
        {
            $linkarr[] = "<a href=\"$SITE_URL/billing/billing.php?user=$name&cid=$cid\">Billing</a>";
            $linkarr[] = "<a href=\"$SITE_URL/billing/order_check.php?user=$name&cid=$cid\">Start online orders Billing</a>";
            $linkarr[] = "<a href=\"$SITE_URL/billing/bank_main.php?user=$name&cid=$cid\">View/Add/Modify Bank Records</a>";
            $linkarr[] = "<a href=\"$SITE_URL/billing/dueamountlist.php?user=$name&cid=$cid\">List for Due Amount</a>";
            $linkarr[] = "<a href=\"$SITE_URL/billing/search_rev_user.php?user=$name&cid=$cid\">Misc Revenue Billing</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/charge_back_stats.php?name=$user&cid=$cid\">Search for Charge Back Users</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/stats_for_allUsers.php?name=$user&cid=$cid\">Search stats - for all Users</a>";
            $linkarr[] = "<a href=\"$SITE_URL/billing/search_ivr_users.php?name=$user&cid=$cid\">Search for IVR Users</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/discount_new_entry.php?user=$name&cid=$cid\">Enter Discount Details</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/retrievepage.php?user=$name&cid=$cid\">Retrieve Profile</a>";
        }
        if (in_array('OR', $priv)) // 'OR' privilage for top admin viewing order records
        {
            $linkarr[] = "<a href=\"$SITE_URL/billing/order_records.php?name=$user&cid=$cid\">View Order Records</a>";
        }
        if (in_array('BTR', $priv)) // 'OR' privilage for top admin viewing order records
        {
            $linkarr[] = "<a href=\"$SITE_URL/billing/start_service.php?name=$user&cid=$cid&CMDSearch=1&login=1\"> Bank Transfer records</a>";
        }

        if (in_array('RA', $priv) || in_array('M', $priv)) //resources admin
        {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/manage_banner_sources.php?username=$username&cid=$cid&val=add\">Manage Banner Sources</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/pixel_code_insertion.php?username=$username&cid=$cid\">Pixelcode insertion</a>";
            $linkarr[] = "<a href=\"$SITE_URL/resources/resources_admin_cat.php?username=$username&cid=$cid\">Resources Admin</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/sem.php?menu=Y&username=$username&cid=$cid\">SEM Registration Pages Customization</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/add_source_entry_in_custom_mini_reg.php?username=$username&cid=$cid&val=add\">Add source in customize mini reg</a>";
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/sem\">Custom Registration Page</a>";
        }

        if (in_array('MC1', $priv) || in_array('MC2', $priv) || in_array('MC3', $priv) || in_array('MC4', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/mis/mainpage.php?name=$misname&cid=$cid\">View MIS</a>";
        }

        if (in_array('MP', $priv)) //manage homepage photo
        {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/manage_homepage.php?name=$username&cid=$cid\">Manage HomePage Profile</a>";
        }

        if (in_array('LC', $priv)) //manage live chat status
        {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/livechat_status_manage.php?name=$username&cid=$cid\">Live Chat Status</a>";
        }

        if (in_array('ES', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/mailid_view.php?cid=$cid\">View improper profiles</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/mailid_list.php?cid=$cid\">Search improper profiles</a>";
        }

        /*CRM Privileges Start*/
        if (in_array('IA', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/crm/get_history.php?name=$username&cid=$cid\">View history of a particular user</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/yes_no_mail.php?name=$username&cid=$cid\">On Demand Yes-No mailer</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/ondemand_sms.php?name=$username&cid=$cid\">On Demand SMS</a>";

            $operator = $name; //getname($cid);

            //$center=getcenter_for_walkin($operator);
            if (strtoupper($center) == 'NOIDA') {
                $linkarr[] = "<a href=\"$SITE_URL/mis/performance_track_upload.php?cid=$cid\">Performance Track MIS - Upload CSV</a>";
                $linkarr[] = "<a href=\"$SITE_URL/mis/performance_track_edit.php?cid=$cid\">Performance Track MIS - Edit CSV</a>";

            }
            $linkarr[] = "<a href=\"$SITE_URL/crm/calling_details.php?name=$operator&cid=$cid\">Get complete telecalling details of a profile</a>";
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmAllocation/manualExtDaysAllocation\">Profile allocation extension - for my team</a>";

        }
        if (in_array('CU', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/contact_us.php?name=$username&cid=$cid\">Update MatchAlert/Contacts-Us page details page</a>";
        }

        if (in_array("IUI", $priv) || in_array("IUO", $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/mis/performance_track_mis.php?cid=$cid\">Performance Track MIS</a>";
        }
        if (in_array('CMA', $priv) || in_array('SLHD', $priv) || in_array('P', $priv) || in_array('MG', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmAllocation/manualAllocation\">Manually allot user to CRM telecaller</a>";
        }
        $operator = $name; //getname($cid);
        //$center=getcenter_for_walkin($operator);

        if (in_array("UFOS", $priv) || in_array("P", $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/crm/inbound_online.php?name=$operator&cid=$cid\">Unalloted free online search</a>";
        }

        // Outbound Call User Link accessibility
        if (in_array("FTAFTO", $priv) || in_array("FTASup", $priv) || in_array("ExcUpS", $priv) || in_array("UpSSup", $priv) || in_array("ExcRnw", $priv) || in_array("RnwSup", $priv) || in_array("ExcFP", $priv) || in_array("FPSUP", $priv) || in_array("ExcDIb", $priv) || in_array("INBSUP", $priv) || in_array("ExcDOb", $priv) || in_array("ExcBSD", $priv) || in_array("ExcBID", $priv) || in_array("ExcFSD", $priv) || in_array("ExcFID", $priv) || in_array("ExcPrm", $priv) || in_array("ExPrmO", $priv) || in_array("ExPrmI", $priv) || in_array("ExcWFH", $priv) || in_array("SLMNTR", $priv) || in_array("SLSUP", $priv) || in_array("SLHD", $priv) || in_array("P", $priv) || in_array("MG", $priv) || in_array("TRNG", $priv) || in_array("PreAll", $priv) || in_array("ExcWL", $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmAllocation/outboundProcessList\">Outbound Calls User</a>";
        }

        if (in_array("ExcSl", $priv) || in_array("SLMNTR", $priv) || in_array("SLSUP", $priv) || in_array("SLMGR", $priv) || in_array("SLSMGR", $priv) || in_array("SLHD", $priv) || in_array("SLHDO", $priv) || in_array("TRNG", $priv) || in_array("P", $priv) || in_array("MG", $priv) || in_array("FNC", $priv)) {
            $linkarr[] = "<a href=\"/operations.php/crmMis/crmHandledRevenueMis\">CRM Handled Revenue MIS</a>";
        }

        if (in_array('IUO', $priv)) //CRM outbound users
        {
            $linkarr[] = "<a href=\"$SITE_URL/crm/get_history.php?name=$username&cid=$cid\">View history of a particular user</a>";
            $linkarr[] = "<a href=\"/mis/ni_handled_incentive_calc.php?cid=$cid&user=$user\">Executive-wise Revenue for Incentive Calculation</a>";
            $linkarr[] = "<a href=\"$SITE_URL/mis/crm_monthly_revenue_mis.php?name=$username&cid=$cid\">CRM DailyWork / Revenue / Conversion MIS</a>";
            $linkarr[] = "<a href=\"$SITE_URL/crm/daily_handled_list.php?cid=$cid\">Daily Handled List</a>";
        } elseif ((in_array('ExcDIb', $priv) || in_array('INBSUP', $priv)) && strtoupper($center) == "NOIDA") {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmAllocation/outboundProcessList\">Inbound Calls User</a>";
        }

        if (in_array('ExcDIb', $priv) || in_array('INBSUP', $priv) || in_array('P', $priv) || in_array('MG', $priv) || in_array('TRNG', $priv)) //CRM inbound users
        {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmAllocation/inboundAllocation?mode=I\">Inbound Calls User</a>";
            if (strtoupper($center) != 'PUNE') {
                $linkarr[] = "<a href=\"$SITE_URL/crm/get_history.php?name=$username&cid=$cid\">View history of a particular user</a>";
            }

        }
        if (in_array('FTAFTO', $priv) || in_array('FTASup', $priv) || in_array('CSEXEC', $priv) || in_array('CSSUP', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/crm/get_history.php?name=$username&cid=$cid\">View history of a particular user</a>";
        }

        /*CRM Privileges End*/

        if (in_array('IUP', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/searchpage.php?user=$name&cid=$cid\">Payment Collection Entry</a>";
            $linkarr[] = "<a href=\"$SITE_URL/crm/status_track.php?user=$name&cid=$cid\">Track status for pickup request</a>";
        }
        if (in_array('IUU', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/crm/confirmclient.php?user=$name&cid=$cid\">Confirm Client</a>";
        }
        if (in_array('IUA', $priv)) {
            $operator = $name; //getname($cid);
            //$center=getcenter_for_walkin($operator);
            $linkarr[] = "<a href=\"$SITE_URL/crm/clientinvoice.php?user=$name&cid=$cid\">Invoice Generation and Pickups Handling</a>";
            if (strtoupper($center) == 'NOIDA' || strtoupper($center) == 'HO') {
                $linkarr[] = "<a href=\"$SITE_URL/crm/mail_for_skypak.php?user=$name&cid=$cid\">Mail for Skypak</a>";
                $linkarr[] = "<a href=\"$SITE_URL/crm/resend_mail_for_skypak.php?user=$name&cid=$cid\">Resend Mail for Skypak</a>";
            }
        }

        if (in_array('BMD', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/bounceMail/bounceMailDetection\">Bounce Mail Detection</a>";
        }

        if (in_array('IS', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/invoice_mis.php?user=$name&cid=$cid\">Skypak Mail Archive</a>";
        }

        if (in_array('IUS', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/crm/status_activation.php?user=$name&cid=$cid\">Service Status And Activation</a>";
        }
        if (in_array('IB', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/crm/billentry.php?user=$name&cid=$cid\">Enter Billing Details for PickUps</a>";
        }
        if (in_array('TI', $priv) || in_array('M', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/tieups_get_login.php?user=$name&cid=$cid\">Create Tie-ups Login</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/tieups_viewinfo.php?user=$name&cid=$cid\">Manage Tie-ups logins</a>";

        }
        if (in_array('OM', $priv)) {
            // $linkarr[]="<a href=\"$SITE_URL/jsadmin/check_obscene_msg.php?user=$name&cid=$cid\">Check obscenes msg</a>";
        }
        if (in_array('IUI', $priv) || in_array('IUO', $priv) || in_array('IUW', $priv)) {
            $linkarr[] = "<a href=\"#\" onClick=\"MM_openBrWindow(this,'/crm/ncr_individual_operator_record_new.php?user=$name&cid=$cid','mywindow','width=700,height=600,scrollbars=yes');return false;\">Your Track Record</a>";
        }
        if (in_array("UpSSup", $priv) || in_array("FPSUP", $priv) || in_array("INBSUP", $priv) || in_array("ExcPrm", $priv) || in_array("SLMNTR", $priv) || in_array("SLSUP", $priv) || in_array("SLHD", $priv) || in_array("P", $priv) || in_array("MG", $priv) || in_array("TRNG", $priv) || in_array("ExcFld", $priv) || in_array("SupFld", $priv) || in_array("RSP", $priv)) {
            $user      = $name; //getname($cid);
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmDeallocation/ReleaseProfile\">Release Single Profile</a>";
        }
        if (in_array('SLHD', $priv) || in_array("P", $priv) || in_array("MG", $priv)) {
            $user      = $name; //getname($cid);
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmDeallocation/NoLongerWorking\">Release Resource</a>";
        }
        if (in_array("SLSUP", $priv) || in_array("FPSUP", $priv) || in_array("INBSUP", $priv) || in_array("SUPPRM", $priv) || in_array("UpSSup", $priv) || in_array("RnwSup", $priv) || in_array("SupFld", $priv)) {
            $user      = $name; //getname($cid);
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmDeallocation/ReleaseProfileForMyTeam\">Release Profile (for my team)</a>";
        }
        if (in_array('BL', $priv)) {
            $user      = $name; //getname($cid);
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/managebackend.php?name=$user&cid=$cid\">Manage Backend Login</a>";
        }
        if (in_array('SLHD', $priv) || in_array("P", $priv) || in_array('MG', $priv) || in_array('TFP', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmAllocation/transferProfiles?subMethod=FRESH\">Transfer Fresh Profiles</a>";
        }

        // Transfer profile links
        if (in_array('UpSSup', $priv) || in_array("P", $priv) || in_array('MG', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmAllocation/transferProfiles?subMethod=UPSELL\">Transfer Upsell profiles</a>";
        }

        if (in_array('RnwSup', $priv) || in_array("P", $priv) || in_array('MG', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmAllocation/transferProfiles?subMethod=RENEWAL\">Transfer Renewal profiles</a>";
        }

        if (in_array('FPSUP', $priv) || in_array("P", $priv) || in_array('MG', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmAllocation/transferProfiles?subMethod=NEW_FAILED_PAYMENT\">Transfer Failed Payment Profiles</a>";
        }

        // ends

        if (in_array('SupFld', $priv) || in_array("P", $priv) || in_array('MG', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmAllocation/transferProfiles?subMethod=FIELD_SALES\">Transfer Field Sales Profiles</a>";
        }

        if (in_array('SupFld', $priv) || in_array("P", $priv) || in_array('MG', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmAllocation/transferProfiles?subMethod=FIELD_SALES&singleProfile=1\">Transfer Single Field Sales Profile</a>";
        }

        if (in_array('MgrFld', $priv) || in_array("SLHD", $priv) || in_array("P", $priv) || in_array('MG', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmAllocation/fieldSalesAllocationLimit\">Set Field Sales Allocation Limit</a>";
        }

        if (in_array('MgrFld', $priv) || in_array("SLHD", $priv) || in_array("P", $priv) || in_array('MG', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmAllocation/preAllocationLimit\">Set Pre-Allocation Limit</a>";
        }

        if (in_array("SLHD", $priv) || in_array("SLHDO", $priv) || in_array("P", $priv) || in_array("MG", $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmAllocation/setSalesTarget\">Sales Target Setting</a>";
        }

        if (in_array('MnAllc', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmAllocation/manualExtAllocation\">Manual Allocation/Extension</a>";

        }

        if (in_array('SEO', $priv) || in_array('M', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/seo_matrimonialdisplay.php?name=$user&cid=$cid&mode=N\">Modify Matrimonial records </a>";

            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/form_index_seo.php?user=$name&cid=$cid\">Title,keyword and description entry for homepage </a>";

            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/manage_google_keywords.php?user=$name&cid=$cid\">Manage google adsense keywords </a>";
        }
        /**********MODIFICATION ENDS HERE*********************/

        if (in_array('BT', $priv) || in_array('M', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/manage_template_input.php?name=$user&cid=$cid\">Manage Input Profile Creative</a>";
        }
        if (in_array('PGA', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/manage_payment_gateway.php?name=$user&cid=$cid\">Manage Payment Gateway</a>";
        }
        if (in_array('SA', $priv) || in_array('EPR', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/show_editprofile_request.php?name=$user&cid=$cid\">Edit profile requests</a>";
        }
        if (in_array('BS', $priv) || in_array('M', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/maingate.php?name=$user&cid=$cid\">Manage Affiliate Records</a>";
        }

        if (in_array('UB', $priv) || in_array('M', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/banners.php?name=$user&cid=$cid\">Upload/Edit Banners for BusinessSathi</a>";
        }

        if (in_array("ECA", $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/mis/view_service.php?cid=$cid&user=$user\">Verify e-Classifed/Horo/Kundali Services MIS</a>";
        }
        if (in_array('WD', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/mainAds.php?name=$user&cid=$cid\">Manage Wedding Gallery Listings</a>";
        }
        if (in_array('BCR', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/billing/bounced_cheque_mis.php?name=$user&cid=$cid\">Bounced Cheque Reminder Module</a>";
        }

        if (in_array('MR', $priv))
        // $linkarr[]="<a href=\"$SITE_URL/jsadmin/mbureau.php?cid=$cid\">Manage Marriage Bureau</a>";
        {
            if (in_array('IVPM', $priv)) {
                $linkarr[] = "<a href=\"$SITE_URL/jsadmin/infovision_username_list.php?cid=$cid\">Mail to Profiles Registered via Infovision</a>";
            }
        }

        if (in_array('WM', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/mmm_module.php?cid=$cid&new=1\">Manage MMMJS Branch Details</a>";
        }

        if (in_array('VA', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/voucher_issued_detail.php?name=$user&cid=$cid\">View Issued Voucher Numbers detail</a>";
            // $linkarr[]="<a href=\"$SITE_URL/jsadmin/voucher_resend.php?name=$user&cid=$cid\">Resend Vouchers</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/voucher_success_issued.php?name=$user&cid=$cid\">Success Story Gift</a>";
        }

        if (in_array('VU', $priv) || in_array('VA', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/voucher_issued.php?name=$user&cid=$cid\">View Vouchers detail to be issued</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/voucher_successstory.php?name=$user&cid=$cid\">Success Story e-Vouchers</a>";
        }
        if (in_array('VS', $priv)) {
            // $linkarr[]="<a href=\"$SITE_URL/jsadmin/voucher_backend_sales.php?name=$user&cid=$cid&new=1\">Upload New Deal / Edit Existing Deal</a>";
        }
        if (in_array('VD', $priv)) {
            // $linkarr[]="<a href=\"$SITE_URL/jsadmin/voucher_backend_design.php?name=$user&cid=$cid\">Design Images for New Deals</a>";
        }

        if (in_array('EBC', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/easybill_uploadcsv.php?name=$user&cid=$cid\">Upload Easy Bill CSV</a>";
        }

        $username = $name; //getname($cid);

        if (in_array('PU', $priv) || in_array('NU', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/storyScreening/index\">Screen & Upload Success Stories</a>";
        }

        if (in_array('SA', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/pool_success.php?user=$user&cid=$cid\">Add Success Story Pool for Home Page</a>";
        }

        $linkarr[] = "<a href=\"$SITE_URL/jsadmin/change_passwd.php?name=$user&cid=$cid\">Change your password</a>";

        if (in_array('NTSP', $priv) || in_array("AdSlEx", $priv) || in_array("P", $priv) || in_array("MG", $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/mobcheckuser.php?user=$name&cid=$cid\">Search user based on Phone Number</a>";
        }

        if (in_array('NTSP', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/yes_no_mail.php?name=$username&cid=$cid\">On Demand Yes-No mailer</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/ondemand_sms.php?name=$username&cid=$cid\">On Demand SMS</a>";
            $linkarr[] = "<a href=\"$SITE_URL/crm/calling_details.php?name=$operator&cid=$cid\">Get complete telecalling details of a profile</a>";
        }
        if (in_array('RC', $priv)) {
            //$name=getname($cid);
            $linkarr[] = "<a target='_blank' href=\"$SITE_URL/P/inputprofile.php?source=onoffreg&operator=$name\">Register an offline user</a>";
        }
        //Links for Assisted Product Users
        if (in_array('SE', $priv)) {
            //$name=getname($cid);
            $linkarr[] = "<a target='_blank' href=\"$SITE_URL/P/inputprofile.php?source=onoffreg&operator=$name\">Register an offline user</a>";
            $linkarr[] = "<a href=\"$SITE_URL/billing/billing.php?user=$name&cid=$cid\">Billing</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/ap_dashboard.php?cid=$cid&pagination=1&active=1\">My Dashboard</a>";
        }

        if (in_array('QA', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/ap_dpp.php?cid=$cid&new=1\">QA new profiles</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/ap_dpp.php?cid=$cid\">QA review profiles</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/ap_pull_profile.php?cid=$cid\">Audit profile out of queue</a>";
        }

        if (in_array('DIS', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/ap_list.php?cid=$cid&list=TBD\">Open Dispatch Queue</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/ap_pull_profile.php?cid=$cid\">Service profile out of queue</a>";
        }

        if (in_array('TC', $priv)) {
            $uscan_count    = getTcQuerry('uscan', '3', '', '1');
            $fresh_count    = getTcQuerry('fresh', '3', '', '1');
            $nonfresh_count = getTcQuerry('nonfresh', '3', '', '1');
            $linkarr[]      = "<a href=\"$SITE_URL/jsadmin/ap_viewprofile.php?cid=$cid&page=MYPROFILE&list=PULL&qtype=uscan\" >Pull the next profile in the US/Canada queue <strong>($uscan_count)</strong></a>";
            $linkarr[]      = "<a href=\"$SITE_URL/jsadmin/ap_viewprofile.php?cid=$cid&page=MYPROFILE&list=PULL&qtype=fresh\" >Pull the next profile in the Fresh queue <strong>($fresh_count)</strong></a>";
            $linkarr[]      = "<a href=\"$SITE_URL/jsadmin/ap_viewprofile.php?cid=$cid&page=MYPROFILE&list=PULL&qtype=nonfresh\" >Pull the next profile in the Non Fresh queue <strong>($nonfresh_count)</strong></a>";
            $linkarr[]      = "<a href=\"$SITE_URL/jsadmin/ap_tbc_profile.php?cid=$cid&page=MYPROFILE&list=PULL&qtype=userid\" >Pull the profile by Username/Email</a>";
        }

        if (in_array('MGR', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/ap_reassign_profile.php?cid=$cid\">Reassign Assisted Product Profile</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/ap_delete_se.php?cid=$cid\">Delete SE</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/ap_dispatcher_cities.php?cid=$cid\">Assign cities to Dispatcher</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/ap_increase_credits.php?cid=$cid\">Increase call credits</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/ap_dashboard_oh.php?cid=$cid&pagination=1\">Operation head's dashboard</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/ap_toggle_auto_apply.php?cid=$cid\">Toggle Auto Apply Services</a>";
        }
        if ($operator == 'trasferoffline') {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/assignOflse.php?name=$user&cid=$cid\">Assign onoffreg profiles to offline SE</a>";
        }

        if (in_array('NEGLST', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/crm/negativeProfileSearch.php?cid=$cid\">Negative Individual/ Company</a>";
        }
        if(in_array('NEGLST',$priv))
	        $linkarr[]="<a href=\"$SITE_URL/operations.php/commoninterface/negativeTreatment?cid=$cid\">Delete and Mark profiles in Negative List</a>";
	if(in_array('NEGLST',$priv))
		$linkarr[]="<a href=\"$SITE_URL/operations.php/commoninterface/negativeHandler?cid=$cid&actionType=D\">Remove from Negative List</a>";
	$linkarr[]="<a href=\"$SITE_URL/operations.php/commoninterface/negativeHandler?cid=$cid&actionType=F\">Fetch Negative Profile</a>";

        if (in_array('MG', $priv) || in_array('P', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/go_to_large_file.php?name=$user&cid=$cid\">Configure Large File</a>";
            $linkarr[] = "<a href=\"$SITE_URL/crm/show_IM.php?cid=$cid\">Show/Hide Incentive Multiplier</a>";
        }
        // link for search sugarcrm LTF profiles
        if (in_array('LTFSUP', $priv) || in_array('TRNG', $priv) || in_array('P', $priv) || in_array('MG', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/sugarcrm_LTF_search.php?cid=$cid\">Search details of LTF registered profile</a>";
        }

        if (in_array('OPS', $priv) || in_array('OPM', $priv) || in_array('OPSSUP', $priv) || in_array('OPSHD', $priv) || in_array('TRNGOP', $priv) || in_array('P', $priv) || in_array('MG', $priv))
        //     $linkarr[]="<a href=\"$SITE_URL/jsadmin/unverified_users.php?cid=$cid\">Verify profiles who denied registration on Verification Call or SMS</a>";

        {
            $linkarr[] = "<a href=\"$SITE_URL/crm/dncNumberCheck.php?cid=$cid\">Check DNC Status</a>";
        }

        if (in_array('MG', $priv) || in_array('P', $priv) || in_array('SLHD', $priv) || in_array('TRNG', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/crm/tele_calling_details.php?name=$operator&cid=$cid\">Tele-calling history of a Profile</a>";
        }

        if (in_array('BK', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/profiledata/index\">User backend Information[For Legal].</a>";
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/mobcheckuser.php?user=$name&cid=$cid\">Search user based on Phone Number</a>";
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/legal/NameLocationAgeSearch\">Name Age Location Search[For Legal].</a>";
        }
        if (in_array('LTFVnd', $priv) || in_array('LTFHD', $priv) || in_array('P', $priv) || in_array('MG', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/jsadmin/register_lead.php?name=$username&cid=$cid\">Register a Lead</a>";
        }

        // if(in_array("FTAReg",$priv) || in_array("TRNG",$priv) || in_array("P",$priv) || in_array("MG",$priv))
        // $linkarr[]="<a href=\"$SITE_URL/jsadmin/photoFollowup.php?cid=$cid\">New Registration Photo Follow-up</a>";
        if (in_array("ExcPrm", $priv) || in_array("P", $priv) || in_array("MG", $priv) || in_array("TRNG", $priv) || in_array("ExPmSr", $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/commoninterface/premiumUser\">Mark dummy profile of JS Exclusive client</a>";
        }

        // Added by Reshu for profile document verification trac#3626 and trac#3632
        /*
        if(in_array("ExcFld",$priv) || in_array("SupFld",$priv) || in_array("MgrFld",$priv) || in_array("SLHDO",$priv) || in_array("MG",$priv) || in_array("P",$priv) || in_array("TRNG",$priv)){
        if(JsConstants::$whichMachine == 'prod' && JsConstants::$siteUrl == 'http://www.jeevansathi.com'){
        $linkarr[]="<a href=\"http://crm.jeevansathi.com/operations.php/profileVerification/profileDocumentsUpload\">Upload Profile Verification Documents</a>";
        }
        else {
        $linkarr[]="<a href=\"$SITE_URL/operations.php/profileVerification/profileDocumentsUpload\">Upload Profile Verification Documents</a>";
        }
        }*/

        if (in_array("ExcVDS", $priv) || in_array("SupVDS", $priv) || in_array("OPSHD", $priv) || in_array("TRNGOP", $priv) || in_array("MG", $priv) || in_array("P", $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/profileVerification/screen\">Screen Profile Verification Documents</a>";
        }

        // Added by Akash for FSO removal hence Verification Seal removal trac#4392
        if (in_array("SLHDO", $priv) || in_array("SLHD", $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/profileVerification/fsoRemoval\">FSO Visit Removal for verification Seal</a>";
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmInterface/splitSalesInterface\">Split Sales Interface </a>";
        }
        if (in_array("SLHDO", $priv) || in_array("P", $priv) || in_array("MG", $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmInterface/couponInterface\">Generate Coupon Code</a>";
        }
        if (in_array("CRMTEC", $priv) || in_array("DA", $priv) || in_array("MG", $priv) || in_array("SLHDO", $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmInterface/manageVdOffer\">Manage Variable Discount Offer </a>";
        }
        if (in_array("CRMTEC", $priv) || in_array("DA", $priv)) {

            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmInterface/manageCashDiscountOffer\">Manage Cash Discount Offer </a>";
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmInterface/manageFestiveOffer\">Manage Festive Offer Discount/Durations </a>";
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmInterface/manageCommissions\">Manage Apple/Franchisee Commissions Interface </a>";
        }
        if (in_array('TRAN', $priv)) {
            $linkarr[] = "<a href=\"/operations.php/bms/trackTransactionDetails\">Track Transaction by ID</a>";
        }
        if (in_array('P', $priv) || in_array("MG", $priv) || in_array('SLHDO', $priv) || in_array('SuPmSr', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmAllocation/getExclusiveMembers?EX_STATUS=ASSIGNED&user=$user&cid=$cid\">Exclusive Customer Service Onboarding</a>";
        }
        $linkarr[] = "<a href=\"$SITE_URL/operations.php/profileVerification/requestFieldSalesVisit\">Field Visit Request Interface</a>";
        if (in_array('PFV', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/profileVerification/showPendingFSVisits\">Pending Field Visits Interface</a>";
        }
        if (in_array('P', $priv) || in_array("MG", $priv) || in_array('SLHDO', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/commoninterface/selectGateway\">Select Gateway Redirection</a>";
        }
        if (in_array('MG', $priv) || in_array('P', $priv) || in_array('SLHDO', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmInterface/slaveLagStatus\">Get Server Lag Status</a>";
        }

        if (in_array('CSEXEC', $priv) || in_array('CSSUP', $priv) || in_array("P", $priv) || in_array("MG", $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/feedback/reportAbuse\">Fetch Report Abuse Data</a>";
        }
        if (in_array('CSSUP', $priv) || in_array("P", $priv) || in_array("MG", $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/profileVerification/InappropriateUsers\">Fetch Inappropriate Users Data</a>";
        }

        if (in_array('SLHDO', $priv) || in_array('P', $priv) || in_array('SLHD', $priv) || in_array('MG', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmInterface/helpBackend\">Help Questions</a>";
        }
        if(in_array("SupFld",$priv))
                $linkarr[]="<a href=\"$SITE_URL/operations.php/profileVerification/profileDocumentsUpload\">Upload Profile Verification Documents</a>";
        //exclusive servicing phase II platform
        if (in_array('ExPmSr', $priv) || in_array('SupPmS', $priv)) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/crmAllocation/exclusiveServicingII\">Send profiles to customer</a>";
        }
        if (in_array('CSSUP', $priv) || in_array('MG', $priv) || in_array('P', $priv) ) {
            $linkarr[] = "<a href=\"$SITE_URL/operations.php/feedback/reportInvalidContactsQC\">Invalid reported Contacts QC</a>";
        }
        if(in_array('MG', $priv) || in_array('P', $priv) || in_array('CSEXEC', $priv) || in_array('CSSUP', $priv))
            $linkarr[]="<a href=\"$SITE_URL/operations.php/feedback/reportAbuseForUser\">Report Abuse For User</a>";
          if(in_array('MG', $priv) || in_array('P', $priv) || in_array('CSEXEC', $priv) || in_array('CSSUP', $priv))
            $linkarr[]="<a href=\"$SITE_URL/operations.php/profileVerification/fetchAbuseInvalidData\">Fetch Abuse and Invalid Report</a>";

        if(in_array('MG', $priv) || in_array('P', $priv) || in_array('CSEXEC', $priv) || in_array('CSSUP', $priv))
            $linkarr[]="<a href=\"$SITE_URL/operations.php/feedback/deleteRequestForUser\">Request user to delete profile</a>";
    }

    $smarty->assign("linkarr", $linkarr);
    $smarty->assign("CID", $cid);
    $smarty->assign("SITE_URL", $SITE_URL);
    $smarty->display("../jsadmin/mainpage.htm");
} else //login failed
{
    $smarty->assign("username", "$name");
    $smarty->display("jsconnectError.tpl");
}
