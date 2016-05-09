<?php
include ("connect.inc");

$data=authenticated($cid);

if(isset($data))//successful login
{
//echo "cid : $cid<br>";
	$privilage = getprivilage($cid);
   	$priv = explode("+",$privilage);

	//echo "<br> nm : ".$misname=getname($cid);

/*	if(in_array('PA',$priv))
	{
		$user="n";
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/showprofilestoassign_new.php?name=$name&user=$user&cid=$cid\">Assign Photo Profiles</a>";
	}
*/

/*Photo Screening Privileges Start*/

	if(in_array('PU',$priv))
	{
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/showphotostoscreen_new.php?name=$user&cid=$cid&val=new\">Screen New Photos</a>";
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/showphotostoscreen_new.php?name=$user&cid=$cid&val=edit\">Screen Edit Photos</a>";
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/screen_photos_from_mail.php?name=$user&cid=$cid\">Screen Photos From Mail</a>";
	}

	if(in_array('PA',$priv))
	{
		$user="n";
		 $linkarr[]="<a href=\"$SITE_URL/jsadmin/view_photo_profile_count.php?name=$user&cid=$cid&val=new\">View Photo Screening Stats</a>";
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/add_photo_login.php?username=$user&cid=$cid&val=new\">Master Photo Edit</a>";
	}
/*Photo Screening Privileges End*/

/*Horoscope Privileges Start*/
        if(in_array('HU',$priv))
        {
                $linkarr[]="<a href=\"$SITE_URL/jsadmin/showhoroscopetoscreen.php?name=$user&cid=$cid\">View assigned horoscope profiles</a>";
        }
        if(in_array('HA',$priv))
        {
                $user="n";
                $linkarr[]="<a href=\"$SITE_URL/jsadmin/showhoroscopetoassign.php?name=$name&user=$user&cid=$cid\">Assign Horoscope Profiles</a>";
        }
	if(in_array('OH',$priv))
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/add_offline_horoscope.php?name=$user&cid=$cid&val=new\">Add Offline Horoscopes</a>";
/*Horoscope Privileges End*/

/*MatriProfile Privileges Start*/
	if(in_array('MPU',$priv))
	{
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/show_matri_allot.php?checksum=$cid&user=$user\">Allotted Matri profiles</a>";
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/search_matri_profile.php?checksum=$cid&user=$user&show_image=1\">Search Matri-Profile member</a>";
	}
	if(in_array('MPA',$priv))
	{
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/matri_reply_manage.php?checksum=$cid&user=$user\">Matri-Profile Reply Manage</a>";
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/show_matriprofile.php?checksum=$cid&user=$user\">View / Allot Matri-Profile Members</a>";
	}
/*MatriProfile Privileges End*/

/*Profile Screening Privileges Start*/
	if(in_array('NU',$priv))
	{
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/userview.php?name=$user&cid=$cid\">View assigned profiles</a>";
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/screen_new.php?name=$user&cid=$cid&val=new\">Screen New Profiles</a>";
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/screen_new.php?name=$user&cid=$cid&val=edit\">Screen Edit Profiles</a>";
	}
	if(in_array('NU1',$priv))
	{
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/userview_1min.php?name=$user&cid=$cid\">View assigned profiles - 1 Min</a>";
	}
	
	if(in_array('A',$priv))
	{
		$linkarr[]="<a href=\"$SITE_URL/mis/matriprofile_todownload.php?checksum=$cid&user=$user\">Mark uploaded Matri-Profiles</a>";
		//$linkarr[]="<a href=\"$SITE_URL/jsadmin/alternate.php?name=$user&cid=$cid&val=new\">Assign Profiles</a>";
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/view_profile_count.php?name=$user&cid=$cid&val=new\">View Screening Stats</a>";
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/delete_a_photo.php?name=$user&cid=$cid\">Delete Photo</a>";
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/newusername.php?name=$user&cid=$cid\">Screen New Username</a>";
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/show_user_record.php?name=$user&cid=$cid\">Search for a record</a>";
	}
	if(in_array('QC',$priv))
	{
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/qc_screening.php?user=$user&checksum=$cid\"> QC Screening</a>";
	}
	if(in_array('OPP',$priv))
	{
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/add_edit_fields.php?name=$user&cid=$cid&val=new\">Master Profile Edit</a>";
	}
	if(in_array('NA1',$priv))
	{
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/alternate_1min.php?name=$user&cid=$cid&val=new\">Assign Profiles - 1 Min</a>";
	}

/*Profile Screening Privileges End*/

	if(in_array('S',$priv))
        {
                 $linkarr[]="<a href=\"$SITE_URL/jsadmin/searchpage.php?user=$name&cid=$cid\">Search Profile</a>";
        }
	if(in_array('R',$priv))
        {
                 $linkarr[]="<a href=\"$SITE_URL/jsadmin/retrievepage.php?user=$name&cid=$cid\">Retrieve Profile</a>";
        }
        if(in_array('TA',$priv))//thumbnail administrator
        {
                $linkarr[]="<a href=\"$SITE_URL/jsadmin/show_thumbnails_to_assign.php?name=$name&cid=$cid\">Assign Thumbnails</a>";
        }
        if(in_array('TU',$priv))//thumbnail operator
        {
                $linkarr[]="<a href=\"$SITE_URL/jsadmin/show_thumbnails_to_screen.php?username=$name&cid=$cid\">View Assigned Thumbnails</a>";
        }
	if(in_array('F',$priv)) //Feedback operator
        {
                 $linkarr[]="<a href=\"$SITE_URL/jsadmin/feedback_check.php?user=$name&cid=$cid\">Feedback Check</a>";
                 $linkarr[]="<a href=\"$SITE_URL/jsadmin/faq_admin_main.php?user=$name&cid=$cid\">FAQ Admin</a>";
        }
	if(in_array('MS',$priv))
        {
                 $linkarr[]="<a href=\"$SITE_URL/jsadmin/mobcheckuser.php?user=$name&cid=$cid\">Search user based on Mobile Number</a>";
        }
	$linkarr[]="<a href=\"$SITE_URL/jsadmin/diwali_discount_list.php?user=$name&cid=$cid\">Diwali Discount List</a>";
	if(in_array('BU',$priv)) //billing entry operator
        {
                 $linkarr[]="<a href=\"$SITE_URL/billing/billing.php?user=$name&cid=$cid\">Billing</a>";
                 $linkarr[]="<a href=\"$SITE_URL/billing/dueamountlist.php?user=$name&cid=$cid\">List for Due Amount</a>";
		 $linkarr[]="<a href=\"$SITE_URL/billing/search_rev_user.php?user=$name&cid=$cid\">Misc Revenue Billing</a>";
        }
	if(in_array('MBU',$priv)) //Misc-Revenue billing entry operator
	{
		$linkarr[]="<a href=\"$SITE_URL/billing/search_rev_user.php?user=$name&cid=$cid\">Misc Revenue Billing</a>";
	}
	if(in_array('BA',$priv)) //billing admin
        {
                $linkarr[]="<a href=\"$SITE_URL/billing/billing.php?user=$name&cid=$cid\">Billing</a>";
		$linkarr[]="<a href=\"$SITE_URL/billing/bank_main.php?user=$name&cid=$cid\">View/Add/Modify Bank Records</a>";
		$linkarr[]="<a href=\"$SITE_URL/billing/dueamountlist.php?user=$name&cid=$cid\">List for Due Amount</a>";
		$linkarr[]="<a href=\"$SITE_URL/billing/search_rev_user.php?user=$name&cid=$cid\">Misc Revenue Billing</a>";
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/charge_back_stats.php?name=$user&cid=$cid\">Search for Charge Back Users</a>";
        }
	if(in_array('OR',$priv))  // 'OR' privilage for top admin viewing order records
        {
                $linkarr[]="<a href=\"$SITE_URL/billing/order_records.php?name=$user&cid=$cid\">View Order Records</a>";
        }
	if(in_array('BTR',$priv))  // 'OR' privilage for top admin viewing order records
        {
		$linkarr[]="<a href=\"$SITE_URL/billing/start_service.php?name=$user&cid=$cid&CMDSearch=1&login=1\"> Bank Transfer records</a>";
        }

	if(in_array('RA',$priv))//resources admin
        {
                $linkarr[]="<a href=\"$SITE_URL/jsadmin/manage_banner_sources.php?username=$username&cid=$cid&val=add\">Manage Banner Sources</a>";
                $linkarr[]="<a href=\"$SITE_URL/resources/resources_admin_cat.php?username=$username&cid=$cid\">Resources Admin</a>";
        }

//	if($misname=="shiv")
	if(in_array('MC1',$priv) || in_array('MC2',$priv) || in_array('MC3',$priv) || in_array('MC4',$priv))
	{
		$linkarr[]="<a href=\"$SITE_URL/mis/mainpage.php?name=$misname&cid=$cid\">View MIS</a>";
	}

	if(in_array('MP',$priv)) //manage homepage photo
	{
		//$linkarr[]="<a href=\"$SITE_URL/jsadmin/manage_homepage_photo.php?name=$username&cid=$cid\">Manage HomePage Photo</a>";
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/manage_homepage.php?name=$username&cid=$cid\">Manage HomePage Profile</a>";
	}

	if(in_array('LC',$priv)) //manage live chat status
	{
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/livechat_status_manage.php?name=$username&cid=$cid\">Live Chat Status</a>";
	}

	if(in_array('ES',$priv))
        {
                $linkarr[]="<a href=\"$SITE_URL/jsadmin/mailid_view.php?cid=$cid\">View improper profiles</a>";
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/mailid_list.php?cid=$cid\">Search improper profiles</a>";
        }

/*CRM Privileges Start*/
	if(in_array('IA',$priv)) 
	{
//		$linkarr[]="<a href=\"$SITE_URL/crm/uploadfile.php?name=$username&cid=$cid\">Upload Chat file in CSV format</a>";
//		$linkarr[]="<a href=\"$SITE_URL/crm/pullback.php?name=$username&cid=$cid\">Pullback Records</a>";
		$linkarr[]="<a href=\"$SITE_URL/crm/get_history.php?name=$username&cid=$cid\">View history of a particular user</a>";
		$operator=getname($cid);

                $center=getcenter_for_walkin($operator);
		if(strtoupper($center)=='NOIDA')
		{
			//$linkarr[]="<a href=\"$SITE_URL/crm/crm_data_csv.php?name=$operator&cid=$cid\">Generate list of profiles for followup</a>";
			$linkarr[]="<a href=\"$SITE_URL/crm/parse_for_times_tried.php?name=$operator&cid=$cid\">Profiles with failed followup</a>";
			$linkarr[]="<a href=\"$SITE_URL/crm/invalid_phone_list.php?name=$operator&cid=$cid\">Mark profiles with invalid/wrong phone no.s</a>";
			 $linkarr[]="<a href=\"$SITE_URL/crm/telec_voice_log.php?name=$operator&cid=$cid\">View Tele-callers Voice Log</a>";
			$linkarr[]="<a href=\"$SITE_URL/mis/performance_track_upload.php?cid=$cid\">Performance Track MIS - Upload CSV</a>";
			$linkarr[]="<a href=\"$SITE_URL/mis/performance_track_edit.php?cid=$cid\">Performance Track MIS - Edit CSV</a>";

		}
		$linkarr[]="<a href=\"$SITE_URL/crm/calling_details.php?name=$operator&cid=$cid\">Get complete telecalling details of a profile</a>";
		$linkarr[]="<a href=\"$SITE_URL/crm/update_relaxdays.php?name=$operator&cid=$cid\">Profiles for extension of followup</a>";

	}
	if(in_array('CU',$priv))
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/contact_us.php?name=$username&cid=$cid\">Update MatchAlert/Contacts-Us page details page</a>";
        if(in_array("IUI",$priv) || in_array("IUO",$priv))
		$linkarr[]="<a href=\"$SITE_URL/mis/performance_track_mis.php?cid=$cid\">Performance Track MIS</a>";

	if(in_array('CMA',$priv))
	{
		$linkarr[]="<a href=\"$SITE_URL/crm/assign_newprofile.php?name=$username&cid=$cid\">Manually allot user to CRM telecaller</a>";
		$linkarr[]="<a href=\"$SITE_URL/crm/upload_crm.php?name=$username&cid=$cid\">CSV file based allotment of users to CRM telecallers</a>";
	}
	$operator=getname($cid);
	$center=getcenter_for_walkin($operator);
        $Unalloted_Free_Online_Search=array('princy.gulati','milan.sharma','anamika.singh','bharat.vaswani','swaleha.khan','aparna.karmakar','renuka.k','jstech');
        if(in_array($operator,$Unalloted_Free_Online_Search))
                 $linkarr[]="<a href=\"$SITE_URL/crm/inbound_online.php?name=$operator&cid=$cid\">Unalloted free online search</a>";

	if(in_array('IUO',$priv)) //CRM outbound users
	{
		$linkarr[]="<a href=\"$SITE_URL/crm/outbound.php?name=$username&cid=$cid\">Outbound Calls User</a>";
		$linkarr[]="<a href=\"$SITE_URL/crm/get_history.php?name=$username&cid=$cid\">View history of a particular user</a>";

		//if(strtoupper($center)=='MUMBAI' || strtoupper($center)=='BHOPAL' || strtoupper($center)=='AHMEDABAD')
			$linkarr[]="<a href=\"/mis/crm_users_revenue_new.php?cid=$cid&user=$user\">CRM Handled Revenue MIS</a>";
		//if(strtoupper($center)=='NOIDA')
		//{
			$linkarr[]="<a href=\"$SITE_URL/mis/crm_monthly_revenue_mis.php?name=$username&cid=$cid\">CRM DailyWork / Revenue / Conversion MIS</a>";
			$linkarr[]="<a href=\"$SITE_URL/crm/daily_handled_list.php?cid=$cid\">Daily Handled List</a>";
		//}
		//$linkarr[]="<a href=\"$SITE_URL/crm/crm_data_csv.php?name=$username&cid=$cid\">Generate list of profiles for followup</a>";
	}
	elseif(in_array('IUI',$priv) && strtoupper($center) == "NOIDA")
		$linkarr[]="<a href=\"$SITE_URL/crm/outbound.php?name=$username&cid=$cid\">Inbound Calls User</a>";

	if(in_array('IUI',$priv)) //CRM inbound users
	{
//		$linkarr[]="<a href=\"$SITE_URL/crm/inbound_walkin.php?name=$username&cid=$cid&mode=I\">Inbound Calls User</a>";
		if(strtoupper($center)!='PUNE')
			$linkarr[]="<a href=\"$SITE_URL/crm/get_history.php?name=$username&cid=$cid\">View history of a particular user</a>";
	}

	if(in_array('IUW',$priv)) //CRM walkin users
	{
//		$linkarr[]="<a href=\"$SITE_URL/crm/inbound_walkin.php?name=$username&cid=$cid&mode=W\">Walkin User</a>";
	}
/*CRM Privileges End*/

	if(in_array('IUP',$priv))
        {
                $linkarr[]="<a href=\"$SITE_URL/jsadmin/searchpage.php?user=$name&cid=$cid\">Payment Collection Entry</a>";
		$linkarr[]="<a href=\"$SITE_URL/crm/status_track.php?user=$name&cid=$cid\">Track status for pickup request</a>";
        }
        if(in_array('IUU',$priv))
        {
                 $linkarr[]="<a href=\"$SITE_URL/crm/confirmclient.php?user=$name&cid=$cid\">Confirm Client</a>";
        }
//      if(in_array('IUA',$priv) && strtoupper($centre)=='HO')
        if(in_array('IUA',$priv))
        {
                $operator=getname($cid);
                $center=getcenter_for_walkin($operator);
                $linkarr[]="<a href=\"$SITE_URL/crm/clientinvoice.php?user=$name&cid=$cid\">Invoice Generation and Pickups Handling</a>";
                if(strtoupper($center)=='NOIDA' || strtoupper($center)=='HO')
		{
                        $linkarr[]="<a href=\"$SITE_URL/crm/mail_for_skypak.php?user=$name&cid=$cid\">Mail for Skypak</a>";
                        $linkarr[]="<a href=\"$SITE_URL/crm/resend_mail_for_skypak.php?user=$name&cid=$cid\">Resend Mail for Skypak</a>";
		}
        }

        if(in_array('IS',$priv))
        {
                 $linkarr[]="<a href=\"$SITE_URL/jsadmin/invoice_mis.php?user=$name&cid=$cid\">Skypak Mail Archive</a>";
        }

//      if(in_array('IUA',$priv) && strtoupper($centre)=='HO')
        if(in_array('IUS',$priv))
        {
                 $linkarr[]="<a href=\"$SITE_URL/crm/status_activation.php?user=$name&cid=$cid\">Service Status And Activation</a>";
        }
//      if(in_array('IB',$priv) && strtoupper($centre)=='HO')
        if(in_array('IB',$priv))
        {
                 $linkarr[]="<a href=\"$SITE_URL/crm/billentry.php?user=$name&cid=$cid\">Enter Billing Details for PickUps</a>";
        }
        if(in_array('TI',$priv))
        {
                $linkarr[]="<a href=\"$SITE_URL/jsadmin/tieups_get_login.php?user=$name&cid=$cid\">Create Tie-ups Login</a>";
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/tieups_viewinfo.php?user=$name&cid=$cid\">Manage Tie-ups logins</a>";

        }
        if(in_array('OM',$priv))
        {
                $linkarr[]="<a href=\"$SITE_URL/jsadmin/check_obscene_msg.php?user=$name&cid=$cid\">Check obscenes msg</a>";
        }
	if(in_array('IUI',$priv) || in_array('IUO',$priv) || in_array('IUW',$priv))
	{
		//$linkarr[]="<a href=\"$SITE_URL/crm/ncr_individual_operator_record_new.php?user=$name&cid=$cid\">Your Track Record</a>";
		$linkarr[]="<a href=\"#\" onClick=\"MM_openBrWindow(this,'/crm/ncr_individual_operator_record_new.php?user=$name&cid=$cid','mywindow','width=700,height=600,scrollbars=yes');return false;\">Your Track Record</a>";
	}
	if(in_array('BL',$priv))
	{
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/managebackend.php?name=$user&cid=$cid\">Manage Backend Login</a>";
	}
			    /*********************************************************\
                                MODIFIED BY :   SHOBHA
                                ON          :   12.05.05
                                REASON      :   FOR ADDITION OF LINK FOR THE PURPOSE OF
                                                EDITING THE COMMUNITY PAGE ACCORDING
                                                TO THE PRIVILEGE "SEO"
                            \**********************************************************/
                                                                                                                             
        if(in_array('SEO',$priv))
        {
                 $linkarr[]="<a href=\"$SITE_URL/jsadmin/seo_matrimonialdisplay.php?name=$user&cid=$cid&mode=N\">Modify Matrimonial records </a>";

		// Added by Rahul Tara on 30 May,2005
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/form_index_seo.php?user=$name&cid=$cid\">Title,keyword and description entry for homepage </a>";

                $linkarr[]="<a href=\"$SITE_URL/jsadmin/manage_google_keywords.php?user=$name&cid=$cid\">Manage google adsense keywords </a>";                                                           
        }
                            /**********MODIFICATION ENDS HERE*********************/
                                                                                                                             
/* Added By : Gaurav Arora
           Date of Addtion : 11 May 2005
           Reason for Addition : To add new link to manage input profile creative(privilage name: BT)
       */
        if(in_array('BT',$priv))
                {
                        $linkarr[]="<a href=\"$SITE_URL/jsadmin/manage_template_input.php?name=$user&cid=$cid\">Manage Input Profile Creative</a>";
                }
/* Added By: kush Asthana
	Date of Addition June03,2005
	Reason: TO manage Payment Gateway
*/	
        if(in_array('PGA',$priv))
                {
                        $linkarr[]="<a href=\"$SITE_URL/jsadmin/manage_payment_gateway.php?name=$user&cid=$cid\">Manage Payment Gateway</a>";
                }
        if(in_array('SA',$priv) || in_array('EPR',$priv))
        {
                $linkarr[]="<a href=\"$SITE_URL/jsadmin/show_editprofile_request.php?name=$user&cid=$cid\">Edit profile requests</a>";
                //$linkarr[]="<a href=\"$SITE_URL/jsadmin/admin_inc_bang.php?name=$user&cid=$cid\">Reallot alloted profiles of a particular branch</a>";
        }
/**********************************************************************************************************************
Changed By	: Shakti Srivastava
Change Date	: 9 August, 2005
Reason		: Grant privilege to add banners and modify affilaite records to Darshan
**********************************************************************************************************************/
        if(in_array('BS',$priv))
        {
                $linkarr[]="<a href=\"$SITE_URL/jsadmin/maingate.php?name=$user&cid=$cid\">Manage Affiliate Records</a>";
        }

        if(in_array('UB',$priv))
        {
                $linkarr[]="<a href=\"$SITE_URL/jsadmin/banners.php?name=$user&cid=$cid\">Upload/Edit Banners for BusinessSathi</a>";
        }

        if(in_array("ECA",$priv))
        {
                $linkarr[]="<a href=\"$SITE_URL/mis/view_service.php?cid=$cid&user=$user\">Verify e-Classifed/Horo/Kundali Services MIS</a>";
        }
        if(in_array('WD',$priv))
	{
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/mainAds.php?name=$user&cid=$cid\">Manage Wedding Gallery Listings</a>";
	}
	if(in_array('BCR',$priv))
		$linkarr[]="<a href=\"$SITE_URL/billing/bounced_cheque_mis.php?name=$user&cid=$cid\">Bounced Cheque Reminder Module</a>";
    
	if(in_array('MR',$priv))
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/mbureau.php?cid=$cid\">Manage Marriage Bureau</a>";

	if(in_array('IVPM',$priv))
	{
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/infovision_username_list.php?cid=$cid\">Mail to Profiles Registered via Infovision</a>";
	}
	if(in_array('WM',$priv))
	{
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/mmm_module.php?cid=$cid&new=1\">Manage MMMJS Branch Details</a>";
	}	

	if(in_array('VA',$priv))
	{
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/voucher_issued_detail.php?name=$user&cid=$cid\">View Issued Voucher Numbers detail</a>";    
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/voucher_resend.php?name=$user&cid=$cid\">Resend Vouchers</a>";    
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/voucher_success_issued.php?name=$user&cid=$cid\">Success Story Printed Vouchers</a>";
	}

	if(in_array('VU',$priv) || in_array('VA',$priv))
	{
                $linkarr[]="<a href=\"$SITE_URL/jsadmin/voucher_issued.php?name=$user&cid=$cid\">View Vouchers detail to be issued</a>";
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/voucher_successstory.php?name=$user&cid=$cid\">Success Story e-Vouchers</a>";
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/screen_success_story.php?user=$user&cid=$cid\">Screen & Upload Success Stories</a>";
	}
	if(in_array('VS',$priv))
	{
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/voucher_backend_sales.php?name=$user&cid=$cid&new=1\">Upload New Deal / Edit Existing Deal</a>";
	}
	if(in_array('VD',$priv))
	{
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/voucher_backend_design.php?name=$user&cid=$cid\">Design Images for New Deals</a>";
	}


	if(in_array('EBC',$priv))
		$linkarr[]="<a href=\"$SITE_URL/jsadmin/easybill_uploadcsv.php?name=$user&cid=$cid\">Upload Easy Bill CSV</a>";    

	$linkarr[]="<a href=\"$SITE_URL/jsadmin/change_passwd.php?name=$user&cid=$cid\">Change your password</a>";

	$smarty->assign("linkarr",$linkarr);
	$smarty->assign("CID",$cid);
	$smarty->display("mainpage.htm");
}
else//login failed
{
	$smarty->assign("username","$name");
	$smarty->display("jsconnectError.tpl");
}
?>
