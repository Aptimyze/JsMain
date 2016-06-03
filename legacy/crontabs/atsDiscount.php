<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


/***************************************************************************************************************
* FILE NAME     : ats_discount.php 
* DESCRIPTION   : Cron script, daily scheduled , calculates the ATS discount and record in billing.VARIABLE_DISCOUNT and MIS.ATS_DISCOUNT tables
		: 3 defined sites considered here: Bharat Matrimony(BM), Shaadi(SH), Simply Marry(SM)
*****************************************************************************************************************/

$flag_using_php5=1;
include_once("connect.inc");
$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path."/classes/Membership.class.php");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");

$db = connect_db();
$db_slave = connect_slave();

// Config values for ATS discount Start
$todaysDate 		=date("Y-m-d");
$atsDiscountPeriod 	='7';	// validity period for ATS discount
$atsCooloffPeriod	='30';	// cool period considered after ATS discount period expired during which the discount is not to be given. 
$atsPoolPeriod 		=$atsDiscountPeriod+$atsCooloffPeriod;
//Config values Ends


// Get the profiles who visited the defined sites.
$visitedProfilesArr =getVisitedProfiles($db_slave,$atsPoolPeriod);
$totalProfiles =count($visitedProfilesArr);
$membership =new Membership;

// loop to execute for each profile whot visited the defined sites and compute the ATS discount 
foreach($visitedProfilesArr as $key=>$profileid)
{
	// Check the profile is everPaid
	$sql 	="SELECT PROFILEID FROM billing.PAYMENT_DETAIL WHERE PROFILEID='$profileid' AND STATUS='DONE'";
	$res 	=mysql_query($sql,$db_slave) or logError("Due to a temporary problem your request could not be processed.");
	$row 	=mysql_fetch_array($res);
	$paidProfile	=$row["PROFILEID"];	
	if(!$paidProfile)
	{
		// Check the profile is renewable	 
		$isRenewable =$membership->isRenewable($profileid);
		if(!$isRenewable)
		{
			// Check if valid discount exits for the profile
			$sql 	="SELECT PROFILEID FROM billing.VARIABLE_DISCOUNT WHERE PROFILEID='$profileid' AND EDATE>='$todaysDate'";
			$res 	=mysql_query($sql,$db_slave) or logError("Due to a temporary problem your request could not be processed.");
			$row 	=mysql_fetch_array($res);
			$profileDiscounttExist=$row["PROFILEID"];
			if(!$profileDiscounttExist)	
			{
				// Check if cool-off period exist for the profile 
				$poolPeriodDate =date("Y-m-d",strtotime("$todaysDate -$atsPoolPeriod days"));
				$sql 	="SELECT PROFILEID FROM MIS.ATS_DISCOUNT WHERE PROFILEID='$profileid' AND ENTRY_DT>'$poolPeriodDate'";
				$res    =mysql_query($sql,$db_slave) or logError("Due to a temporary problem your request could not be processed.");
				$row    =mysql_fetch_array($res);
				$cooloffPeriodExist =$row["PROFILEID"];
				if(!$cooloffPeriodExist)
				{
					// ATS discount computation; Gets the ats dicount value
					$atsDiscount =getATS_Discount($profileid,$db_slave);	
					if($atsDiscount)
					{	
						// Update the variable discount table
						$atsDiscountValidPeriod =$atsDiscountPeriod-1;
						$eDate =date("Y-m-d",strtotime("$todaysDate +$atsDiscountValidPeriod days"));
        					$sql="INSERT IGNORE INTO billing.VARIABLE_DISCOUNT(`PROFILEID`,`DISCOUNT`,`SDATE`,`EDATE`,`ENTRY_DT`,`SENT`) VALUES('$profileid','$atsDiscount','$todaysDate','$eDate','$todaysDate','N')";
        					mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed.");

						$sqlN ="insert into billing.VARIABLE_DISCOUNT_OFFER_DURATION(`PROFILEID`,`3`,`6`,`12`,`L`) VALUES('$profileid','$atsDiscount','$atsDiscount','$atsDiscount','$atsDiscount')";
						mysql_query($sqlN,$db) or logError("Due to a temporary problem your request could not be processed.");
						
						// Update the ats discount table
        					$sql="INSERT INTO MIS.ATS_DISCOUNT(`PROFILEID`,`DISCOUNT`,`ENTRY_DT`) VALUES('$profileid','$atsDiscount','$todaysDate')";
        					mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed.");
					}
				}
			}
		}	
	}
}

// function computes the ATS discount
function getATS_Discount($profileid,$db_slave)
{
	// Config details
	$setDaysOld 	        ='60'; // defined to get 60 days old records
	$threeMemPageDis        ='31';
	$twoMemPageDis          ='26';
	$oneMemPageDis          ='21';
	$otherPageDis           ='11';
	$memPagesArr		=array("1"=>"SHP","2"=>"SMP","3"=>"BMP");
	$otherPagesArr		=array("1"=>"SH","2"=>"SM","3"=>"BM");
	$visitedSitesArr	=array();
	$visitedSitesStr	='';
        $membershipPage         =0;;
        $otherPage              =0;
	$discount		=0;
	$todaysDate		=date("Y-m-d");

        $setDateOld =date("Y-m-d",strtotime("$todaysDate -$setDaysOld days"));
        $sql_ats="SELECT VISITED_SITE FROM MIS.ATS WHERE ENTRY_DATE >'$setDateOld' AND VISITED_SITE!='' AND PROFILEID='$profileid'";
        $res_ats=mysql_query($sql_ats,$db_slave) or logError("Due to a temporary problem your request could not be processed.");
        while($myrow_ats=mysql_fetch_array($res_ats))
                $visitedSitesStr .=$myrow_ats["VISITED_SITE"]."-";

	if($visitedSitesStr)
		$visitedSitesArr 	=explode("-",$visitedSitesStr);

	// Check for the paid membership pages
	if(in_array("$memPagesArr[1]",$visitedSitesArr))
		$membershipPage++;		
	if(in_array("$memPagesArr[2]",$visitedSitesArr))
		$membershipPage++;
	if(in_array("$memPagesArr[3]",$visitedSitesArr))
		$membershipPage++;
		
	// Check for the other pags (other than membership pages)
         if(in_array("$otherPagesArr[1]",$visitedSitesArr))
		$otherPage++;
         if(in_array("$otherPagesArr[2]",$visitedSitesArr))
		$otherPage++;
         if(in_array("$otherPagesArr[3]",$visitedSitesArr))
		$otherPage++;

	if($membershipPage=='3')
		$discount =$threeMemPageDis;
	else if($membershipPage=='2')	
		$discount =$twoMemPageDis;
	else if($membershipPage=='1')
		$discount =$oneMemPageDis;
	else if($otherPage=='3'){
		$discount =$otherPageDis;
	}
	unset($visitedSitesArr);
	return $discount;
}

/* Get the profiles who visited the 3 defined sites
 * param: $days: no of days    
 * return array
*/
function getVisitedProfiles($db_slave,$setDaysOld2)
{
	$todaysDate =date("Y-m-d");
	$setDaysOld1 ='60'; // set 60 days for the first time and then set to 1 day

	$oldDate =date("Y-m-d",strtotime("$todaysDate -$setDaysOld1 days"));
	$atsExpDt=date("Y-m-d",strtotime("$todaysDate -$setDaysOld2 days"));
	$profileidArr1 =array();
	$profileidArr2 =array();

	$sql_ats="SELECT distinct PROFILEID FROM MIS.ATS WHERE ENTRY_DATE >'$oldDate' AND VISITED_SITE!=''";
	$res_ats=mysql_query($sql_ats,$db_slave) or logError("Due to a temporary problem your request could not be processed.");
	while($myrow_ats=mysql_fetch_array($res_ats))
		$profileidArr1[]=$myrow_ats["PROFILEID"];

	$sql="SELECT distinct PROFILEID FROM MIS.ATS_DISCOUNT WHERE ENTRY_DT='$atsExpDt'";
	$res=mysql_query($sql,$db_slave) or logError("Due to a temporary problem your request could not be processed.");
        while($myrow=mysql_fetch_array($res))
                $profileidArr2[]=$myrow["PROFILEID"];
	
	return array_merge($profileidArr1,$profileidArr2);
}

?>
