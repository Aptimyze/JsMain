<?php
/*****************************************************************************************************
Filename    : survery_community.php
Description : Display 2-D matrix of Community & Religion wise response numbers of surver [2326]
Created On  : 9 October 2007
Created By  : Sadaf Alam
*****************************************************************************************************/
include("connect.inc");

$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{
	$sql="SELECT PROFILEID FROM MIS.SURVEY WHERE QUES1!='' OR QUES2!='' OR QUES3!='' OR QUES4!=''";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	while($row=mysql_fetch_assoc($res))
	{
		$sqldata="SELECT RELIGION,MTONGUE FROM newjs.JPROFILE WHERE PROFILEID='$row[PROFILEID]'";
		$resdata=mysql_query_decide($sqldata,$db) or die("$sqldata".mysql_error_js($db));
		$rowdata=mysql_fetch_assoc($resdata);
		$religion=$rowdata["RELIGION"];
		$mtongue=$rowdata["MTONGUE"];
		$table[$mtongue][$religion]++;
		$table["mtongue"][$mtongue]["total"]++;
		$table["rel"][$religion]["total"]++;
		$table["grandtotal"]++;
	}
	$sql="SELECT DISTINCT(VALUE) AS VALUE,LABEL FROM newjs.MTONGUE";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	while($row=mysql_fetch_assoc($res))
	{
		$value=$row["VALUE"];
		$label=$row["LABEL"];
		$comm[$value]=$label;
		if($table[$value])
		{
			foreach($table[$value] as $key=>$val)
			{
				$table[$value][$key]="<a href=\"$SITE_URL/mis/survey_response.php?comm=$value&&rel=$key&cid=$cid\">$val</a>";
			}
		}
		if($table["mtongue"][$value]["total"])
		$table["mtongue"][$value]["total"]="<a href=\"$SITE_URL/mis/survey_response.php?comm=$value&cid=$cid\">".$table["mtongue"][$value]["total"]."</a>";
	}
	$sql="SELECT DISTINCT(VALUE) AS VALUE,LABEL FROM newjs.RELIGION";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	while($row=mysql_fetch_assoc($res))
	{
		$value=$row["VALUE"];
		$label=$row["LABEL"];
		$rel[$value]=$label;
		if($table["rel"][$value]["total"])
		$table["rel"][$value]["total"]="<a href=\"$SITE_URL/mis/survey_response.php?rel=$value&cid=$cid\">".$table["rel"][$value]["total"]."</a>";
	}
	$table["grandtotal"]="<a href=\"$SITE_URL/mis/survey_response.php?cid=$cid\">".$table["grandtotal"]."</a>";
	$smarty->assign("comm",$comm);
	$smarty->assign("rel",$rel);
	$smarty->assign("table",$table);
	$smarty->assign("CHECKSUM",$cid);
	$smarty->display("survey_community.htm");
}
else
{
	$smarty->assign("user",$user);
	$smarty->display("jsconnectError.tpl");
}
?>
