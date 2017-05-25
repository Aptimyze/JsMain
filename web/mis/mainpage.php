<?php
include("connect.inc");
$site_Url =JsConstants::$siteUrl;
header("Location: $site_Url/operations.php/crmMis/misMainpage?public='N'");
die();

/* Old code to be removed later */
/*
$db=connect_misdb();
$db2=connect_master();
$data=authenticated($cid);

if(isset($data))
{
	$privilage=getprivilage($cid);
	$priv=explode("+",$privilage);
	$misname=getname($cid);
	$center=getcenter_for_operator($misname);

	if(in_array("MGR",$priv))
	{
		$linkarr[]=array("main"=>"<a href=\"ap_mis_profile_dis.php?cid=$cid&role=DIS&MGR=1\">Profile-wise Dispatch report</a>","jump"=>"<a href=\"ap_mis_profile_dis.php?cid=$cid&role=DIS&outside=Y&MGR=1\">Jump</a>");
		$linkarr[]=array("main"=>"<a href=\"ap_queue_report.php?cid=$cid\">Queue Report</a>");
	}
	if(in_array("OA",$priv))
	{
		$linkarr[]=array("main"=>"<a href=\"offline_collection_status.php?cid=$cid&user=$user\">Offline Transaction Wise MIS</a>");

	}
	if(in_array("MC1",$priv))
	{
		$linkarr[]["main"]="<a href=\"va_profile_sent.php?checksum=$cid&user=$user\">Visitor Alert Record</a>";
		$linkarr[]=array("main"=>"<a href=\"collectionmis.php?checksum=$cid&user=$user\">Total Collection MIS</a>",
			"jump"=>"<a href=\"collectionmis.php?checksum=$cid&user=$user&outside=Y\">Jump</a>");
		$linkarr[]=array("main"=>"<a href=\"delete_profiles.php?checksum=$cid&user=$user\">Delete Profile Pipeline MIS</a>" );
		$linkarr[]=array("main"=>"<a href=\"dailySearchCount.php?checksum=$cid&user=$user\">Contact Search Flow Details</a>","jump"=>"<a href=\"dailySearchCount.php?checksum=$cid&user=$user&outside=Y\">Jump</a>");
		$linkarr[]=array("main"=>"<a href=\"mobile_verification_count.php?checksum=$cid&user=$user\">Mobile Verification Details</a>","jump"=>"<a href=\"mobile_verification_count.php?checksum=$cid&user=$user&outside=Y\">Jump</a>");
		$linkarr[]["main"]="<a href=\"cheque_details.php?cid=$cid&user=$user\">Cheque Details MIS</a>";
		if(in_array("MA",$priv) || in_array("MB",$priv))
		{
			$linkarr[]["main"]="<a href=\"refund_details.php?checksum=$cid&user=$user\">Refund Details</a>";
			$linkarr[]["main"]="<a href=\"refund_user_details.php?checksum=$cid&user=$user\">Refund User Details</a>";
		}
		$linkarr[]["main"]="<a href=\"gateway_wise.php?checksum=$cid&user=$user\">Gateway Wise (Collection)</a>";
		$linkarr[]=array("main"=>"<a href=\"servicesmis.php?cid=$cid&user=$user\">Ticket Distribution Service-wise</a>","jump"=>"<a href=\"servicesmis.php?cid=$cid&user=$user&CMDGo=1&type=D&reportType=S&profileType=&branch=\">Jump</a>");
		$linkarr[]["main"]="<a href=\"view_for_mis_main.php?cid=$cid&user=$user\">Track Views MIS</a>";
		$linkarr[]["main"]="<a href=\"paynext.php?checksum=$cid&user=$user\">Payment Wise MIS</a>";

		$linkarr[]["main"]="<a href=\"month_record_acc.php?checksum=$cid&user=$user\">Total Month Record (Collection)</a>";
		$linkarr[]["main"]="<a href=\"cheque_dd_list.php?cid=$cid&user=$user\">Bank Deposit Slips</a>";
		$linkarr[]["main"]="<a href=\"unique_paid_members.php?cid=$cid&user=$user\">Unique Paid User</a>";
		$linkarr[]=array("main"=>"<a href=\"responseCount.php?checksum=$cid&user=$user\">Response Details</a>","jump"=>"<a href=\"responseCount.php?checksum=$cid&user=$user&outside=Y\">Jump</a>");
	}

	if(in_array("BA",$priv) || in_array("BU",$priv) )
	{
		$linkarr[]["main"]="<a href=\"special_discount_check.php?cid=$cid&user=$user\">Search for Users eligible for 40 % discount  </a>";
	}
	if(in_array("ACCU",$priv) || in_array("ACCA",$priv))
	{
		$linkarr[]["main"]="<a href=\"rev_billing_mis.php?cid=$cid&user=$user\">Misc Revenue Billing MIS</a>";
		$linkarr[]["main"]="<a href=\"rev_dueamount_mis.php?cid=$cid&user=$user\">Misc Outstanding MIS</a>";
		$linkarr[]["main"]="<a href=\"collection_status.php?cid=$cid&user=$user\">Payment Collection Status (For Accounts)</a>";
		$linkarr[]["main"]="<a href=\"deferred_monthwise.php?cid=$cid&user=$user\">Deferral MIS monthwise</a>";
	}
	if(in_array("MC2",$priv))
	{
		$linkarr[]["main"]="<a href=\"source_brief_conv.php?checksum=$cid&user=$user\">Source Wise conversion/relevant / Members MIS</a>";
		$linkarr[]["main"]="<a href=\"sourcehits.php?checksum=$cid&user=$user\">Source Wise Hits / Members MIS</a>";
		$linkarr[]["main"]="<a href=\"sourcehitspercent.php?cid=$cid&user=$user\">Source Wise Hits / Members Percentage MIS</a>";
		$linkarr[]["main"]="<a href=\"ageloc_sourcehits.php?cid=$cid&user=$user\">Source-Age-Location Wise Members MIS</a>";
		$linkarr[]["main"]="<a href=\"irrelevant_profiles_mis.php?cid=$cid&user=$user\">Irrelevant Profiles MIS</a>";
		$linkarr[]["main"]="<a href=\"contactsmis.php?checksum=$cid&user=$user\">Contact MIS</a>";
		$linkarr[]=array("main"=>"<a href=\"daily_contact_count.php?cid=$cid&user=$user\">Contact MIS (Detail View)</a>","jump"=>"<a href=\"daily_contact_count.php?cid=$cid&user=$user&outside=Y \">Jump</a>");
		$linkarr[]=array("main"=>"<a href=\"directCallMis.php?cid=$cid&user=$user\">Direct Call MIS</a>","jump"=>"<a href=\"directCallMis.php?cid=$cid&user=$user&outside=Y \">Jump</a>");
		$linkarr[]["main"]="<a href=\"source_members.php?cid=$cid&user=$user\">Source Wise Paid Members MIS</a>";
		$linkarr[]["main"]="<a href=\"photomis.php?checksum=$cid&user=$user\">Registered Photo MIS</a>";
		$linkarr[]["main"]="<a href=\"registered_members.php?checksum=$cid&user=$user\">Registered Members MIS (City-wise/Country-wise)</a>";
		$linkarr[]=array("main"=>"<a href=\"paid_n_percentages.php?cid=$cid&user=$user\">Tickets and Sales distribution - Gender, Posted By, Currency, City, Country-wise</a>","jump"=>"<a href=\"paid_n_percentages.php?cid=$cid&user=$user&outside=Y\">Jump</a>");
		$linkarr[]=array("main"=>"<a href=\"daily_login_count.php?cid=$cid&user=$user\">Daily Login Count</a>","jump"=>"<a href=\"daily_login_count.php?cid=$cid&user=$user&outside=Y\">Jump</a>");
		$linkarr[]["main"]="<a href=\"search_query.php?cid=$cid&user=$user\">Search Query Results</a>";
		$linkarr[]["main"]="<a href=\"male_female_count.php?cid=$cid&user=$user\">Male/Female Count MIS</a>";
		$linkarr[]["main"]="<a href=\"community_count.php?cid=$cid&user=$user\">Community Count MIS</a>";
		$linkarr[]["main"]="<a href=\"payments_mainpage.php?cid=$cid&user=$user\">Age-Gender/Community MIS Category</a>";
		$linkarr[]["main"]="<a href=\"regpay_mainpage.php?cid=$cid&user=$user\">Registered/Paid Percentage MIS Category</a>";
		$linkarr[]["main"]="<a href=\"faqmis.php?cid=$cid&user=$user\">FAQ MIS</a>";
		$linkarr[]=array("main"=>"<a href=\"horoscopemis.php?cid=$cid&user=$user\">Detailed Horoscope Stats</a>","jump"=>"<a href=\"horoscopemis.php?cid=$cid&user=$user&outside=Y\">Jump</a>");
		$linkarr[]["main"]="<a href=\"resubscribe_status.php?cid=$cid&user=$user\">Membership Renewal MIS</a>";
		$linkarr[]["main"]="<a href=\"incomplete_reg.php?cid=$cid&user=$user\">Incomplete to Complete Conversion MIS</a>";
		$linkarr[]["main"]="<a href=\"indicator_index.php?checksum=$cid&user=$user\">JS Indicators</a>";
		$linkarr[]=array("main"=>"<a href=\"requesthoroscopemis.php?cid=$cid&user=$user\">Horoscope Request Count</a>","jump"=>"<a href=\"requesthoroscopemis.php?cid=$cid&user=$user&outside=Y\">Jump</a>");
		$linkarr[]=array("main"=>"<a href=\"astro_compatibility_mis.php?cid=$cid&user=$user\">Astro Compatibility Revenue MIS</a>","jump"=>"<a href=\"astro_compatibility_mis.php?cid=$cid&user=$user&outside=Y\">Jump</a>");
		$linkarr[]["main"]="<a href=\"profile_acqstn_source_revenue.php?cid=$cid&user=$user\">Cost Per Paid Profile MIS</a>";
		$linkarr[]["main"]="<a href=\"3d_trends.php?cid=$cid&user=$user\">3D Trends</a>";
		$linkarr[]=array("main"=>"<a href=\"sms_mis.php?cid=$cid&user=$user\">SMS mis</a>","jump"=>"<a href=\"sms_mis.php?cid=$cid&user=$user&outside=Y\">Jump</a>");
		$linkarr[]=array("main"=>"<a href=\"search_matrix.php?cid=$cid&user=$user\">Search Results And Type </a>");
		$linkarr[]=array("main"=>"<a href=\"success_story_count.php?cid=$cid&user=$user\">Success Stories</a>");
		$linkarr[]=array("main"=>"<a href=\"feedback_mis.php?cid=$cid&user=$user\">Feedback MIS</a>");
		$linkarr[]=array("main"=>"<a href=\"contact_breakdown.php?cid=$cid&user=$user\">Contact Breakdown MIS</a>");
	}
	if(in_array("ExcFld",$priv) || in_array("SupFld",$priv) || in_array("MgrFld",$priv) || in_array("SLHDO",$priv) || in_array("TRNG",$priv) || in_array("P",$priv) || in_array("MG",$priv))
	{
		$linkarr[]=array("main"=>"<a href=\"/operations.php/crmMis/fieldSalesExecutivePerformanceMis?cid=$cid\">Field Sales Executive Performance MIS</a>","jump"=>"<a href=\"/operations.php/crmMis/fieldSalesExecutivePerformanceMis?cid=$cid&outside=Y\">Jump</a>");
		$linkarr[]=array("main"=>"<a href=\"/operations.php/crmMis/fieldSalesExecutiveEfficiencyMis?cid=$cid\">Field Sales Executive Efficiency MIS</a>","jump"=>"<a href=\"/operations.php/crmMis/fieldSalesExecutiveEfficiencyMis?cid=$cid&outside=Y\">Jump</a>");
	}
	if(in_array("MC2",$priv) || in_array("MC4",$priv))
	{
		$linkarr[]=array("main"=>"<a href=\"matri_profile_mis.php?cid=$cid&user=$user\">Matri Profile MIS</a>","jump"=>"<a href=\"matri_profile_mis.php?cid=$cid&user=$user&outside=Y\">Jump</a>");
		$linkarr[]=array("main"=>"<a href=\"delete_profile_count.php?cid=$cid&user=$user\">Deleted Profiles</a>");
	}

	if(in_array("MC2",$priv) || in_array("MC3",$priv))
	{
		$linkarr[]=array("main"=>"<a href=\"contact_limit_hit.php?cid=$cid&user=$user\">CONTACT LIMIT EXCEED USERS LIST</a>");
	}
	if(in_array("MC2",$priv) || in_array("MC3",$priv))
	{
		$linkarr[]=array("main"=>"<a href=\"chat_mis.php?cid=$cid&user=$user\">Chat related MIS</a>");
	}

	if(in_array("ExcSl",$priv) || in_array("SLMNTR",$priv) || in_array("SLSUP",$priv) || in_array("SLMGR",$priv) || in_array("SLSMGR",$priv) || in_array("SLHD",$priv) || in_array("SLHDO",$priv) || in_array("TRNG",$priv) || in_array("P",$priv) || in_array("MG",$priv))
		$linkarr[]=array("main"=>"<a href=\"/operations.php/crmMis/crmHandledRevenueMis?cid=$cid\">CRM Handled Revenue MIS</a>","jump"=>"<a href=\"/operations.php/crmMis/crmHandledRevenueMis?cid=$cid&outside=Y\">Jump</a>");

        if(in_array("ExcSl",$priv) || in_array("SLMNTR",$priv) || in_array("SLSUP",$priv) || in_array("SLMGR",$priv) || in_array("SLSMGR",$priv) || in_array("SLHD",$priv) || in_array("SLHDO",$priv) || in_array("TRNG",$priv) || in_array("P",$priv) || in_array("MG",$priv))
                $linkarr[]=array("main"=>"<a href=\"/operations.php/crmMis/fieldSalesFollowUpStatusMis?cid=$cid\">Sales Follow-up Status MIS</a>");

        if(in_array("ExcSl",$priv) || in_array("SLMNTR",$priv) || in_array("SLSUP",$priv) || in_array("SLMGR",$priv) || in_array("SLSMGR",$priv) || in_array("SLHD",$priv) || in_array("SLHDO",$priv) || in_array("TRNG",$priv) || in_array("P",$priv) || in_array("MG",$priv))
                $linkarr[]=array("main"=>"<a href=\"/operations.php/crmMis/renewalFollowUpStatusMis?cid=$cid\">Renewal Follow-up Status MIS</a>");

	if(in_array("MC3",$priv))
	{
		if(in_array("MA",$priv) || in_array("MB",$priv) || (in_array("MC",$priv) && $center!="MUMBAI"))
		{
			$linkarr[]["main"]="<a href=\"crm_daily_work_mis.php?cid=$cid\">CRM Users Daily Work MIS</a>";
		}
		$linkarr[]=array("main"=>"<a href=\"ni_handled_incentive_calc.php?cid=$cid&user=$user\">Executive-wise Revenue for Incentive Calculation</a>","jump"=>"<a href=\"ni_handled_incentive_calc.php?cid=$cid&user=$user&outside=Y\">Jump</a>");
		$linkarr[]["main"]="<a href=\"crm_monthly_revenue_mis.php?cid=$cid&user=$user\">CRM DailyWork / Revenue / Conversion MIS</a>";
		$linkarr[]["main"]="<a href=\"crm_conversion_community_wise.php?cid=$cid&user=$user\">CRM Community wise Conversion MIS</a>";

		if($center=="NOIDA" && in_array("IA",$priv))
		{
			$linkarr[]["main"]="<a href=\"faq_noanswer.php?cid=$cid&user=$user\">Unanswered FAQ Count</a>";
		}
	}

	if(in_array("MG",$priv) || in_array("TRNG",$priv) || in_array("P",$priv) || in_array("ExcFTA",$priv) || in_array("FTASup",$priv))
		$linkarr[]=array("main"=>"<a href=\"/operations.php/crmMis/ftaRegular?cid=$cid&user=$misname\">FTA Executive Efficiency MIS</a>","jump"=>"<a href=\"/operations.php/crmMis/ftaRegular?cid=$cid&user=$misname&outside=Y\">Jump</a>");

	if(in_array("TRNG",$priv) || in_array("MG",$priv) || in_array("P",$priv) || in_array("IUO",$priv) || in_array("IUI",$priv) || in_array("SLMNTR",$priv) || in_array("SLSUP",$priv) || in_array("SLHD",$priv))
		$linkarr[]["main"]="<a href=\"crm_sales_allocation.php?cid=$cid&user=$user\">Executive Sales Allocation MIS</a>";

	if(in_array("OPM",$priv) || in_array("OSM",$priv) || in_array("OFSM",$priv) || in_array("MgrFld",$priv) || in_array("SLMGR",$priv) || in_array("SLSMGR",$priv) || in_array("SUP",$priv) || in_array("OPS",$priv) || in_array("OSS",$priv) || in_array("OFSS",$priv) || in_array("SLSUP",$priv) || in_array("LTFSUP",$priv) || in_array("FPSUP",$priv) || in_array("INBSUP",$priv) || in_array("SUPPRM",$priv) || in_array("OPSSUP",$priv) || in_array("UpSSup",$priv) || in_array("RnwSup",$priv) || in_array("PDSSUP",$priv) || in_array("FTASup",$priv) || in_array("CSSUP",$priv) || in_array("LTFVSp",$priv) || in_array("SupFld",$priv) || in_array("SupVDS",$priv) || in_array("SLSMGR",$priv) || in_array("SLHD",$priv) || in_array("LTFHD",$priv) || in_array("OPSHD",$priv) || in_array("SLHDO",$priv))
		$linkarr[]=array("main"=>"<a href=\"/operations.php/crmMis/discountHeadsMis?cid=$cid\">Discount Heads MIS</a>","jump"=>"<a href=\"/operations.php/crmMis/discountHeadsMis?cid=$cid&outside=Y\">Jump</a>");

	if(in_array("MC4",$priv))
	{
		$linkarr[]["main"]="<a href=\"adminusers.php?checksum=$cid&user=$user\">View Operators Work MIS</a>";
		$linkarr[]["main"]="<a href=\"zone.php?checksum=$cid&user=$user\">Operators Work ZONE</a>";
		$linkarr[]["main"]="<a href=\"workzone.php?checksum=$cid&user=$user\">Operators Work ZONE - New</a>";
		$linkarr[]["main"]="<a href=\"new_edit_mis.php?checksum=$cid&user=$user\">Operators Work ZONE MIS - New/Edit</a>";
		$linkarr[]=array("main"=>"<a href=\"deleted_profiles_count.php?checksum=$cid&user=$user\">Deleted Profiles Count Details</a>","jump"=>"<a href=\"deleted_profiles_count.php?checksum=$cid&user=$user&outside=Y\">Jump</a>");
		$linkarr[]["main"]="<a href=\"aff_np_records_count.php?checksum=$cid&user=$user\">MIS for Newspaper feeding</a>";
		$linkarr[]["main"]="<a href=\"qc_grades_mis.php?checksum=$cid&user=$user\">QC Grades Mis</a>";
		$linkarr[]["main"]="<a href=\"avg_screen_time.php?cid=$cid&user=$user\">Avg Screen Time MIS</a>";

                $linkarr[]=array("main"=>"<a href=\"live_profile_count.php?cid=$cid&user=$user&type=new\">Profile Screening Efficiency Mis</a>","jump"=>"<a href=\"live_profile_count.php?cid=$cid&user=$user&outside=Y\">Jump</a>");
		$linkarr[]=array("main"=>"<a href=\"photo_screen_eff.php?cid=$cid&user=$user\">Photo Screening Efficiency MIS</a>",
                                "jump"=>"<a href=\"photo_screen_eff.php?cid=$cid&user=$user&outside=Y\">Jump</a>");
		$linkarr[]=array("main"=>"<a href=\"photo_screen_stats.php?checksum=$cid&user=$user\">Photo Screening MIS</a>",
			"jump"=>"<a href=\"photo_screen_stats.php?checksum=$cid&user=$user&outside=Y\">Jump</a>");
	}
	if(in_array("IUI",$priv) && $center == "NOIDA")
	{
		$linkarr[]=array("main"=>"<a href=\"ni_handled_incentive_calc.php?cid=$cid&user=$user\">Executive-wise Revenue for Incentive Calculation</a>","jump"=>"<a href=\"ni_handled_incentive_calc.php?cid=$cid&user=$user&outside=Y\">Jump</a>");
	}

	if(in_array("SLSUP",$priv) || in_array("FPSUP",$priv) || in_array("SLHD",$priv) || in_array("P",$priv) || in_array("MG",$priv))
	{
		$linkarr[]=array("main"=>"<a href=\"order_conversion.php?checksum=$cid&user=$user\">Order Conversion MIS</a>","jump"=>"<a href=\"order_conversion.php?checksum=$cid&user=$user&outside=Y\">Jump</a>");
		$linkarr[]=array("main"=>"<a href=\"order_conversion_leads.php?checksum=$cid&user=$user\">Failed Payment Leads</a>");
	}

	if(in_array("ASTR",$priv))
	{
		$linkarr[]=array("main"=>"<a href=\"astro_compatibility_mis.php?cid=$cid&user=$user\">Astro Compatibility Revenue MIS</a>","jump"=>"<a href=\"astro_compatibility_mis.php?cid=$cid&user=$user&outside=Y\">Jump</a>");
	}
	if(in_array("OPSUP",$priv) || in_array("OPHD",$priv) || in_array("LTFSUP",$priv) || in_array("LTFHD",$priv) || in_array("P",$priv) || in_array("MG",$priv))
		$linkarr[]=array("main"=>"<a href=\"sugarDataFlowMIS.php?cid=$cid&user=$misname\">Lead Entry Monitoring MIS</a>");
	if(in_array("MG",$priv) || in_array("TRNG",$priv) || in_array("P",$priv))
		$linkarr[]=array("main"=>"<a href=\"sugarcrm_LTF_report.php?outside='Y'&cid=$cid\">LTF MIS Report</a>");
	elseif(in_array("SLTF",$priv) || in_array("LTFExc",$priv) || in_array("LTFVnd",$priv) || in_array("LTFSUP",$priv) || in_array("LTFHD ",$priv))
		$linkarr[]=array("main"=>"<a href=\"sugarcrm_LTF_report.php?cid=$cid\">LTF MIS Report</a>");

        if(in_array("MG",$priv) || in_array("TRNG",$priv) || in_array("P",$priv))
                $linkarr[]=array("main"=>"<a href=\"disposition_report.php?outside='Y'&cid=$cid\">Disposition report</a>");
	elseif(in_array("SLMNTR",$priv) || in_array("SLHD",$priv) || in_array("SLSUP",$priv))
                $linkarr[]=array("main"=>"<a href=\"disposition_report.php?cid=$cid\">Disposition report</a>");

			if(in_array("MG",$priv) || in_array("TRNG",$priv) || in_array("P",$priv))
				$linkarr[]=array("main"=>"<a href=\"disposition_report.php?outside='Y'&cid=$cid\">Disposition report</a>");
			elseif(in_array("SLMNTR",$priv) || in_array("SLHD",$priv) || in_array("SLSUP",$priv))
				$linkarr[]=array("main"=>"<a href=\"disposition_report.php?cid=$cid\">Disposition report</a>");

			if(in_array("SLHD",$priv) || in_array("SLSUP",$priv) || in_array("MG",$priv) || in_array("P",$priv) || in_array("TRNG",$priv))
				$linkarr[]["main"]="<a href=\"allot_mis.php?cid=$cid&user=$user\">Alloted MIS</a>";

	if(in_array('PDSSUP',$priv) || in_array('OPSHD',$priv) || in_array('P',$priv) || in_array('MG',$priv) || in_array('TRNGOP',$priv))
	{
	 	$linkarr[]=array("main"=>"<a href=\"$SITE_URL/operations.php/duplicateScreening/Mis?name=$misname&cid=$cid&flag=IE_exec\">PD Identification Efficiency MIS of Executives</a>","jump"=>"<a href=\"$SITE_URL/operations.php/duplicateScreening/Mis?name=$misname&cid=$cid&flag=IE_exec&outside=Y\">Jump</a>");
		$linkarr[]=array("main"=>"<a href=\"$SITE_URL/operations.php/duplicateScreening/Mis?name=$misname&cid=$cid&flag=IE_sup\">PD Identification Efficiency MIS of Supervisor</a>","jump"=>"<a href=\"$SITE_URL/operations.php/duplicateScreening/Mis?name=$misname&cid=$cid&flag=IE_sup&outside=Y\">Jump</a>");
 		$linkarr[]=array("main"=>"<a href=\"$SITE_URL/operations.php/duplicateScreening/Mis?name=$misname&cid=$cid&flag=SE_exec\">PD Screening Executive Efficiency MIS</a>","jump"=>"<a href=\"$SITE_URL/operations.php/duplicateScreening/Mis?name=$misname&cid=$cid&flag=SE_exec&outside=Y\">Jump</a>");
		$linkarr[]=array("main"=>"<a href=\"screeningStatusReport.php?name=$misname&cid=$cid\">Probable Duplicate Screening pending queue length</a>");
	}

	$smarty->assign("linkarr",$linkarr);
	$smarty->assign("jumparr",$jumparr);
	$smarty->assign("USER",$user);
	$smarty->assign("CID",$cid);

	$smarty->display("mainpage.htm");
}
else
{
	$smarty->assign("username",$user);
	$smarty->display("jsconnectError.tpl");
}
*/
?>
