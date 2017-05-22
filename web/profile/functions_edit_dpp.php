<?php
include_once(JsConstants::$docRoot."/commonFiles/incomeCommonFunctions.inc");
function offlineBillingUpdate($profileid)
{
                                               //mark a flag whenever the off line customer changes his/her desired partner profile.
   $sql="SELECT BILLID FROM jsadmin.OFFLINE_BILLING WHERE PROFILEID='$data[PROFILEID]' ORDER BY ENTRY_DATE DESC LIMIT 1";
   $res=mysql_query_decide($sql) or logError("1 Due to a temporary problem, your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
   $row=mysql_fetch_assoc($res);
   $sql="update jsadmin.OFFLINE_BILLING set CHANGE_DPP='Y' where BILLID='$row[BILLID]' AND PROFILEID='$data[PROFILEID]'";
   mysql_query_decide($sql) or logError("1 Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
}
function deleteTempIfAPEditId($profileid,$APeditID,$myDb_ap,$mysqlObj)
{
	$partnerWhrCond=" AND STATUS='LIVE' AND DPP_ID>'$APeditID'";
	$jpartnerObj_ap=new Jpartner("Assisted_Product.AP_DPP_FILTER_ARCHIVE");
	$jpartnerObj_ap->setPartnerDetails($profileid,$myDb_ap,$mysqlObj,"*",$partnerWhrCond);
	if($jpartnerObj_ap->isPartnerProfileExist($myDb_ap,$mysqlObj)){
		deleteTemporaryDPP($profileid,"ONLINE");
		return $jpartnerObj_ap;
	}
	return false;

}
function incomeMapping($rsLIncome,$rsHIncome,$doLIncome,$doHIncome)
{
			$resultArr["doLIncome"]=$doLIncome;
			$resultArr["doHIncome"]=$doHIncome;
			$resultArr["rsLIncome"]=$rsLIncome;
			$resultArr["rsHIncome"]=$rsHIncome;
			if($rsLIncome || $rsLIncome =='0')
			{
				$cur_sort_arr["minIR"]=intval($rsLIncome);
				$rsIncomeMentioned=1;
			}
			if($rsHIncome || $rsHIncome=='0')
				$cur_sort_arr["maxIR"]=intval($rsHIncome);
			if($doLIncome || $doLIncome=='0')
			{
				$cur_sort_arr["minID"]=intval($doLIncome);
				$doIncomeMentioned=1;
			}
			if($doHIncome || $doHIncome =='0')
				 $cur_sort_arr["maxID"]=intval($doHIncome);
			if($rsIncomeMentioned && $doIncomeMentioned)
				$cur_sort_arr["currency"]='both';
			elseif($rsIncomeMentioned)
				$cur_sort_arr["currency"]='rupees';
			else
				$cur_sort_arr["currency"]='dollar';		
			
include_once(JsConstants::$docRoot."/commonFiles/incomeCommonFunctions.inc");
			$db=connect_db();	
			if(!($rsIncomeMentioned && $doIncomeMentioned))
			{       $arrMapped=get_mapped_values($cur_sort_arr,$db);
				if($rsIncomeMentioned)
				{
					$cur_sort_arr["minID"]=$arrMapped["minID"];
					$cur_sort_arr["maxID"]=$arrMapped["maxID"];
				}
				else
				{
					$cur_sort_arr["minIR"]=$arrMapped["minIR"];
					$cur_sort_arr["maxIR"]=$arrMapped["maxIR"];
				}
				$cur_sort_arr["currency"]='both';
			}
			$resultArr["istr"]=get_pincome_str($cur_sort_arr,$db,$return);
			if(!$resultArr["istr"])
				$resultArr["istr"]='';

			if($rsLIncome!='' && $rsHIncome!=''){
				$resultArr["doLIncome"]=$cur_sort_arr["minID"];
				$resultArr["doHIncome"]=$cur_sort_arr["maxID"];
			}
			else if($doLIncome!='' && $doHIncome!=''){
				$resultArr["rsLIncome"]=$cur_sort_arr["minIR"];
				$resultArr["rsHIncome"]=$cur_sort_arr["maxIR"];
			}
return $resultArr;
}
function mapped_value($partner_education_arr)
{
	for($i=0;$i<count($partner_education_arr);$i++)
	{
		$pedu = $partner_education_arr[$i];
		$sql_map="select VALUE from EDUCATION_LEVEL_NEW WHERE OLD_VALUE='$pedu'";
                $result_map=mysql_query_decide($sql_map) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_map,"ShowErrTemplate");
                while($row_map=mysql_fetch_array($result_map))
                {
        	        $pedu_str.="'".$row_map['VALUE']."',";
                }	
	}
	$len = strlen($pedu_str)-1;
	$pedu_str = substr($pedu_str,0,$len);
	return $pedu_str;
}
