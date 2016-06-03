<?php
header("Location: http://www.jeevansathi.com/membership/jspc");
die();
//print_r($_GET);
//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt && !$dont_zip_now && $dont_zip_more!=1)
	ob_start("ob_gzhandler");
header("Cache-Control: no-cache, must-revalidate");
include_once("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Services.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");
/*include_once("all_elements/membership_elements.php");
check_all_ele("SEARCH_DATA");*/

$smarty->assign("CHECKSUM",$checksum);
$smarty->assign("con_chk",'4');
$smarty->assign("cb3",'0');
$smarty->assign("cb4",'0');
$smarty->assign("cb5",'0');

$offer = 0;

$db=connect_db();                                                       
$data=authenticated($checksum);

$one="<i class=\"sprte grn_tck\"></i>";
$zero="<i class=\"sprte rd_crs\"></i>";
$benefits_arr[]=array("Create Profile, Create Album, Define Partner Profile, Search and<br />Express Interest",$one,$one,$one);
$benefits_arr[]=array("Contact Members",$one,$one,$zero);
$benefits_arr[]=array("View Contact Details of accepted members",$one,$one,$zero);
$benefits_arr[]=array("View contact details instantly with direct calls<sup class=\"red\">New</sup>",$one,$one,$zero);
$benefits_arr[]=array("Send Messages along with your Contact Details",$one,$one,$zero);
$benefits_arr[]=array("Start Online Chat",$one,$one,$zero);
$benefits_arr[]=array("Let others see your contact details",$one,$zero,$zero);
//$benefits_arr[]=array("Feature in searches for members, with contact details visible",$one,$zero,$zero);

$smarty->assign("benefits",$benefits_arr);
$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));

if($data)
{	
	global $renew_discount_rate,$user_disc;
	$smarty->assign('PARA',$renew_discount_rate);
	$profileid = $data['PROFILEID'];

	$sql_order = "SELECT COUNTRY_RES,INCOMPLETE FROM newjs.JPROFILE WHERE PROFILEID = $profileid and activatedKey=1";
	$result = mysql_query_decide($sql_order) or logError_sums($sql_order,1);
	$row = mysql_fetch_assoc($result);
	if ($row[COUNTRY_RES] == '51')
		$cur_type='RS';
	else
		$cur_type='DOL';

	$memObj = new Membership;

       //Getting key that will fetch data from memcache.

	$sql_VD = "SELECT PROFILEID FROM billing.VARIABLE_DISCOUNT WHERE PROFILEID =$profileid AND EDATE>=NOW()";
	$result_VD = mysql_query_decide($sql_VD) or logError_sums($sql_VD,1);
	$row_VD = mysql_fetch_assoc($result_VD);

        $sql_PPU = "SELECT PROFILEID FROM billing.OFFER_DISCOUNT WHERE PROFILEID =$profileid AND EXPIRY_DT>=NOW() AND SERVICEID='P1'";
        $result_PPU = mysql_query_decide($sql_PPU) or logError_sums($sql_PPU,1);
        $row_PPU = mysql_fetch_assoc($result_PPU);
        if($row_PPU!='' && !$memObj->isRenewable($profileid) && !$row_VD)
                $offer = 1;

	$disc=$memObj->isRenewable($profileid);
	if(!$disc)
	{
                if($row[INCOMPLETE] == 'Y')
                {
                        $smarty->assign('INC',1);
                        $myprofilechecksum = md5($profileid)."i".($profileid);
                        $smarty->assign('flink',"viewprofile.php?checksum=$checksum&profilechecksum=$myprofilechecksum&EditWhatNew=incompletProfile");
                }
                $disc ='N'; 
		$Spec_arr=$memObj->getSpecialDiscount($profileid);
		$Spec=$Spec_arr['DISCOUNT'];
		if($Spec)
		{
			list($yy,$mm,$dd)= explode("-",$Spec_arr['EDATE']);
			$timestamp= mktime(0,0,0,$mm,$dd,$yy);
			$SpecDate=date('d M Y',$timestamp);
			$smarty->assign("SpecDate",$SpecDate);
			$smarty->assign('Spec',$Spec);
			$renew_discount_rate=$Spec;
		}
	}
	else if($disc=='1')
	{
		$disc='Y';
	}
	else
	{
		$smarty->assign('Ex_dt',$disc);
		$Life_not_exist=1;
		$smarty->assign('Life_not_exist',$Life_not_exist);
		$disc='Y';
		
	}
	if($disc=='Y')
		$user_disc=1;
	elseif($Spec)
		$user_disc=2;
	else
		$user_disc=0;	
	$smarty->assign('DISC',$disc);
	$smarty->assign('user_disc',$user_disc);

	$sub_arr=$memObj-> getSubscriptionStatus($profileid);
	if(!($sub_arr))
	{
		$sub_arr=-1;
		if($Life_not_exist==1)
			$smarty->assign('cou_nter',1);

	}
	else
	{
		for($i = 0;$i<count($sub_arr);$i++)
		{	if($sub_arr[$i]['LINK']=='B')
			{
				$smarty->assign('Life_exist','1');
				if(!$var)
					$var="A9";
			}				
		}
	}
	$smarty->assign('EXPIRE',$sub_arr);
	$ftoStateArray = SymfonyFTOFunctions::getFTOStateArray($profileid);
	if($ftoStateArray['STATE']==FTOStateTypes::FTO_ELIGIBLE)
	{
		$limit_text = convert($ftoStateArray['INBOUND_LIMIT'],1);
		$smarty->assign('INBOUND_LIMIT',$limit_text);
                $smarty->assign('SHOW_FTO','1');
	}
}
else
{
	$smarty->assign('EXPIRE',-1);
	$cur_type='RS';
}
$condy=0;
if(!$var)
	$var="CL,B12,M,T12,PL";
if($var)
{
	$arr =explode(",",$var);
	if(is_array($arr))
	{
		for($i = 0;$i<count($arr);$i++)
		{
			$var=$arr[$i];
			if(strstr($var,'P'))
			{
				$condy=1;
				$var=CheckNotListed($var,'P');
				$smarty->assign("main_service",$var);
			}
			if(strstr($var,'C'))
			{
				$condy=1;
				$var=CheckNotListed($var,'C');
				$smarty->assign("main_service",$var);
			}
			if(strstr($var,'B'))
			{
				$smarty->assign("cb3",'1');
				$smarty->assign("bold",$var);
			}
			if(strstr($var,'T'))
			{
				$smarty->assign("cb2",'1');
				$smarty->assign("tonly",$var);

			}
			if(strstr($var,'A'))
			{
				$smarty->assign("cb4",'1');
				$smarty->assign("aonly",$var);

			}
			if(strstr($var,'M'))
			{
				$smarty->assign("cb5",'1');
			}

		}
		if($condy == 0)
		{
			$smarty->assign("main_service",1);
		}
	}
}
	savehits_payment($profileid,"1");
	if($from_source)
	{
		sourcetracking_payment($profileid,'1',$from_source);
		$smarty->assign('from_source',$from_source);
	}
	$smarty->assign('CURRENCY',$cur_type);
        $serObj = new Services;
	if($serObj->getFestive())
		$smarty->assign('Fest',1);
	else
		$smarty->assign('Fest',0);
	
	if(!$offer)
	{
		$key="$cur_type"."MEMBERSHIP";
		$all_data=unserialize(memcache_call($key));
		if($all_data)
	                $addon =$all_data[0];
		else{
			$all_data[0]=$addon =$serObj->getAddOnInfo($cur_type);//For addons
		        //Storing data into memcache for 1 day.
			memcache_call($key,serialize($all_data),86400);	
		}
	}
	else
		$all_data[0]=$addon =$serObj->getAddOnInfo($cur_type,$offer);//For addons
	$rishta =$serObj->getServiceInfo('P',$cur_type,$offer);//For e -Rishta
	$value =$serObj->getServiceInfo('C',$cur_type,$offer);//For e -Value
	//print_r($addon);
	$smarty->assign("addon",$addon);
	if($cur_type=='DOL')
	{
		$smarty->assign('M','12');	
		$smarty->assign('BOLD_VALUE','14 $');
	}
	else
	{
		$smarty->assign('M','395');	
		$smarty->assign('BOLD_VALUE','Rs 395');	
	}
	$smarty->assign('ASTRO',$astro);
	$smarty->assign('MATRO',$matro);
	$smarty->assign('RISHTA',$rishta);
	$smarty->assign('VALUE',$value);
	$smarty->assign('BLIST',$bold_list);
        /*************************************Portion of Code added for display of Banners*******************************/
        $smarty->assign("data",$data["PROFILEID"]);
        $smarty->assign("bms_topright",18);
        $smarty->assign("bms_right",28);
        $smarty->assign("bms_bottom",19);
        $smarty->assign("bms_left",24);
        $smarty->assign("bms_new_win",32);
	$smarty->assign("bms_membership",1);
        /************************************************End of Portion of Code*****************************************/
        include_once("sphinx_search_function.php");//to be tested later
        savesearch_onsubheader($data["PROFILEID"]);//to be tested later
	$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
	//$smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));
	$smarty->assign("FOOT",$smarty->fetch("footer.htm"));//Added for revamp
	if($flag)
		$smarty->assign("flag",$flag);
	if($isMobile)
	{
		if($_GET[NAVIGATOR])
		{
			include_once($_SERVER[DOCUMENT_ROOT]."/../lib/model/lib/Navigator.class.php");
			$naviObj=new Navigator();
			
			$naviObj->navigation("DP","","");
			$smarty->assign("BREADCRUMB",$naviObj->onlyBackBreadCrumb);
			
		}
		if($data[PROFILEID])
			$smarty->assign("LOGGEDIN", 1);
		$smarty->assign("HEADER",$smarty->fetch("mobilejs/jsmb_header.html"));
                $smarty->assign("FOOTER",$smarty->fetch("mobilejs/jsmb_footer.html"));
		$smarty->display("mobilejs/mem_comparison.htm");
	}
	else
	        $smarty->display("mem_comparison.htm");
        if($zipIt)
                ob_end_flush();

/** Main Services which are not avaialble online are mapped to their respective unlimited service
**  to avoid javascript error*/
function CheckNotListed($str,$main)
{
	$d=substr($str,1);
	$listed=array('3','4','6','9','12','L');
	if(in_array($d,$listed))
		return $str;
	else
		return $main.'L';
}
?>
