<?php
$curFilePath = dirname(__FILE__)."/";
 include_once("/usr/local/scripts/DocRoot.php");

chdir("$_SERVER[DOCUMENT_ROOT]/profile");
include("connect.inc");
include($_SERVER['DOCUMENT_ROOT']."/jsadmin/ap_common.php");
include($_SERVER['DOCUMENT_ROOT']."/jsadmin/ap_dpp_common.php");

$db=connect_db();

if($argv[1] == 'insertMissing')
    $sql="SELECT T.* FROM Assisted_Product.AP_TEMP_DPP T LEFT JOIN Assisted_Product.AP_DPP_FILTER_ARCHIVE F ON T.PROFILEID = F.PROFILEID WHERE F.PROFILEID IS NULL";
else
    $sql="SELECT * FROM Assisted_Product.AP_TEMP_DPP WHERE CREATED_BY='ONLINE'";
$res=mysql_query_decide($sql);
if(mysql_num_rows($res))
{
	while($row=mysql_fetch_assoc($res))
	{
		$create=1;
		$profileid=$row["PROFILEID"];
		$new=isProfileNew($profileid);
		//Changes for bug 53375 : dpp version created online has status RQA even if profile is new - Sadaf
		/*$sqlNew="SELECT COUNT(*) AS COUNT FROM Assisted_Product.AP_DPP_FILTER_CHANGE_LOG WHERE PROFILEID='$profileid' AND NEW_STATUS='LIVE'";
		$resNew=mysql_query_decide($sqlNew);
		$rowNew=mysql_fetch_assoc($resNew);
		if($rowNew["COUNT"])*/
		if($new)
			$status="NQA";
		else
			$status="RQA";
		$sqlDPP="SELECT DPP_ID,ONLINE,STATUS,CREATED_BY,ROLE,DATE FROM Assisted_Product.AP_DPP_FILTER_ARCHIVE WHERE PROFILEID='$profileid' AND STATUS NOT IN('LIVE','OBS')";
		$resDPP=mysql_query_decide($sqlDPP);
		if(mysql_num_rows($resDPP))
		{
			while($rowDPP=mysql_fetch_assoc($resDPP))
			{
				unset($editRow);
				$flag=1;
				if($rowDPP["ONLINE"]=="Y" && ($rowDPP["STATUS"]=="RQA" || $rowDPP["STATUS"]=="NQA") && $rowDPP["CREATED_BY"]=="ONLINE" && $rowDPP["ROLE"]=="ONLINE")
				{
					if(!checkAssigned($profileid,$status,'','QA'))
					{
						$flag=0;
						$editRow["GENDER"]=$row["GENDER"];
						$editRow["CHILDREN"]=$row["CHILDREN"];
						$editRow["LAGE"]=$row["LAGE"];
						$editRow["HAGE"]=$row["HAGE"];
						$editRow["LHEIGHT"]=$row["LHEIGHT"];
						$editRow["HHEIGHT"]=$row["HHEIGHT"];
						$editRow["HANDICAPPED"]=$row["HANDICAPPED"];
						$editRow["CASTE_MTONGUE"]=$row["CASTE_MTONGUE"];
						$editRow["PARTNER_BTYPE"]=$row["PARTNER_BTYPE"];
						$editRow["PARTNER_CASTE"]=$row["PARTNER_CASTE"];
						$editRow["PARTNER_CITYRES"]=$row["PARTNER_CITYRES"];
						$editRow["PARTNER_COUNTRYRES"]=$row["PARTNER_COUNTRYRES"];
						$editRow["PARTNER_DIET"]=$row["PARTNER_DIET"];
						$editRow["PARTNER_DRINK"]=$row["PARTNER_DRINK"];
						$editRow["PARTNER_ELEVEL_NEW"]=$row["PARTNER_ELEVEL_NEW"];
						$editRow["PARTNER_INCOME"]=$row["PARTNER_INCOME"];
						$editRow["PARTNER_MANGLIK"]=$row["PARTNER_MANGLIK"];
						$editRow["PARTNER_MSTATUS"]=$row["PARTNER_MSTATUS"];
						$editRow["PARTNER_MTONGUE"]=$row["PARTNER_MTONGUE"];
						$editRow["PARTNER_NRI_COSMO"]=$row["PARTNER_NRI_COSMO"];
						$editRow["PARTNER_OCC"]=$row["PARTNER_OCC"];
						$editRow["PARTNER_RELATION"]=$row["PARTNER_RELATION"];
						$editRow["PARTNER_RES_STATUS"]=$row["PARTNER_RES_STATUS"];
						$editRow["PARTNER_SMOKE"]=$row["PARTNER_SMOKE"];
						$editRow["PARTNER_COMP"]=$row["PARTNER_COMP"];
						$editRow["PARTNER_RELIGION"]=$row["PARTNER_RELIGION"];
						$editRow["PARTNER_NAKSHATRA"]=$row["PARTNER_NAKSHATRA"];
						$editRow["NHANDICAPPED"]=$row["NHANDICAPPED"];
						$editRow["AGE_FILTER"]=$row["AGE_FILTER"];
						$editRow["MSTATUS_FILTER"]=$row["MSTATUS_FILTER"];
						$editRow["RELIGION_FILTER"]=$row["RELIGION_FILTER"];
						$editRow["CASTE_FILTER"]=$row["CASTE_FILTER"];
						$editRow["COUNTRY_RES_FILTER"]=$row["COUNTRY_RES_FILTER"];
						$editRow["CITY_RES_FILTER"]=$row["CITY_RES_FILTER"];
						$editRow["MTONGUE_FILTER"]=$row["MTONGUE_FILTER"];
						$editRow["INCOME_FILTER"]=$row["INCOME_FILTER"];
						$editRow["LINCOME"]=$row["LINCOME"];
						$editRow["HINCOME"]=$row["HINCOME"];
						$editRow["LINCOME_DOL"]=$row["LINCOME_DOL"];
						$editRow["HINCOME_DOL"]=$row["HINCOME_DOL"];
						editDPP($profileid,$rowDPP["DPP_ID"],$editRow);
						$create=0;
					}
				}
				if($flag)
					changeDPPStatus($profileid,"ONLINE",$rowDPP["DPP_ID"],$rowDPP["STATUS"],"OBS",$rowDPP["ONLINE"],$rowDPP["CREATED_BY"]);
			}
		}
		if($create)
		{
			$sqlActed="SELECT STATUS,ONLINE,CREATED_BY,DPP_ID FROM Assisted_Product.AP_DPP_FILTER_ARCHIVE WHERE DPP_ID='$row[ACTED_ON_ID]'";
			$resActed=mysql_query_decide($sqlActed);
			$rowActed=mysql_fetch_assoc($resActed);
			if($rowActed["STATUS"]!="LIVE" && $rowActed["STATUS"]!="OBS")
				$rowActed["NEW_STATUS"]="OBS";
			createDPP($row,$profileid,"ONLINE","ONLINE",$status,$rowActed["DPP_ID"],$rowActed["STATUS"],$rowActed["NEW_STATUS"],$rowActed["ONLINE"],$rowActed["CREATED_BY"],'',"Y");
		}
		deleteTemporaryDPP($profileid,"ONLINE");
	}
}
?>
