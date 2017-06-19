<?php
/************************************************************************************************************************
*    FILENAME           : js_search_recomend.php
*    DESCRIPTION        : Simmilar contacts will be shown to user when user search give him < 10 results.
*    CREATED BY         : lavesh
***********************************************************************************************************************/
include_once "connect.inc";
$db=connect_db();
include_once "search.inc";

$smarty->assign("j",$j);
$smarty->assign("my_scriptname",'simcontacts_search.php');

$PAGELEN = 6;

$PAGELEN_QCACHE=120;
$j_QCACHE=floor($j/120);
$j_QCACHE=120*$j_QCACHE;

if(!$j )
	$j = 0;
$sno=$j+1;

if($crmback)
{
	$jssql="SELECT GENDER FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$profileid'";
	$jsres=mysql_query_decide($jssql);
	$jsrow=mysql_fetch_array($jsres);
	$G=$jsrow['GENDER'];
	$id=$profileid;

}
else
{
	if($data)
	{
		$id=$data["PROFILEID"];
		$G=$data["GENDER"];
	}
	else
	{
		$id=$_COOKIE["ISEARCH"];

		if(is_numeric($id))
		{
			$jssql="SELECT GENDER FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$id'";
			$jsres=mysql_query_decide($jssql);
			$jsrow=mysql_fetch_array($jsres);
			$G=$jsrow['GENDER'];
		}
	}
	if($Gender==$G || !$id)
		return;	
}

$jscontactedby=$id;

//Sharding On Contacts done by Lavesh Rawat
$contactResult=getResultSet("RECEIVER",$jscontactedby);
if(is_array($contactResult))
{
	foreach($contactResult as $key=>$value)
	{
		$jsreceivers_list.=$contactResult[$key]["RECEIVER"].",";
	}
}

$contactResult=getResultSet("SENDER","","",$jscontactedby,'',"'A'");
if(is_array($contactResult))
{
	foreach($contactResult as $key=>$value)
	{
		$jsreceivers_list.=$contactResult[$key]["SENDER"].",";
	}
}
//Sharding On Contacts done by Lavesh Rawat



$jsreceivers_list=rtrim($jsreceivers_list,',');
	
if($jsreceivers_list!="")
{
	//mysql_close($db);
	unset($db);
	$db2=connect_db4();
	$jssql="SELECT SENDER FROM CONTACTS_SEARCH WHERE RECEIVER IN($jsreceivers_list) and RECEIVER<>'$jscontactedby'";
	$jsres=mysql_query_decide($jssql,$db2) or logError("Error while retrieving data from newjs.CONTACTS_SEARCH",$jssql,"ShowErrTemplate");
	while($jsrow=mysql_fetch_array($jsres))
	{
		$jssender_list.=$jsrow['SENDER'].",";
	}
	
	$jssender_list=rtrim($jssender_list,',');
	//mysql_close($db2);
}
	
if($jssender_list!="")
{
	$db2=connect_db4();
	$jssql="SELECT SQL_CACHE SQL_CALC_FOUND_ROWS RECEIVER,COUNT(*) AS CNT FROM newjs.CONTACTS_SEARCH WHERE SENDER IN ($jssender_list) AND RECEIVER NOT IN ($jsreceivers_list) GROUP BY RECEIVER ORDER BY CNT DESC,RECEIVER DESC LIMIT $j_QCACHE,$PAGELEN_QCACHE";
	$jsres=mysql_query_decide($jssql,$db2) or logError("Error while retrieving data from newjs.CONTACTS_SEARCH",$jssql,"ShowErrTemplate");
	$csql = "Select FOUND_ROWS()";
	$cres = mysql_query_decide($csql,$db2) or logError("Error while fetching count",$jssql,"ShowErrTemplate");
	//mysql_close($db2);
	$db=connect_db();
	$crow = mysql_fetch_row($cres);
	$jsTOTALREC = $crow[0];

	if($jsTOTALREC>20)
	{
		if ($j)
			$cPage = ($j/$PAGELEN) + 1;
		else
			$cPage = 1;

		if(mysql_num_rows($jsres)>0)
		{
			displayresults($jsres,$j,"/profile/simcontacts_search.php",$jsTOTALREC,"","1","",$moreurl,"","",12,"","","","","","");
			if($query_track==1)
			{
				$today=date("Y-m-d");
				$sql_track="UPDATE newjs.ZERORESULTS SET RECOMENDS=RECOMENDS+1 where ENTRY_DT='$today' AND STYPE='$STYPE'";
				mysql_query_decide($sql_track) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_track,"ShowErrTemplate");
				if(!mysql_affected_rows_js())
				{
					$sql_track="INSERT INTO newjs.ZERORESULTS VALUES ('$STYPE','$today','','','','',1,'')";
					mysql_query_decide($sql_track) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_track,"ShowErrTemplate");
				}
			}

		}
		else
		{
			$smarty->assign("JSRECORDCOUNT","0");
			$smarty->assign("JSNORESULTS","1");
			$smarty->assign("JSNO_OF_PAGES","0");
			$smarty->assign("JSCURPAGE","0");
		}
		$isearch_new = $_COOKIE["ISEARCH"];
		$logincheck_new = $_COOKIE["AUTHN"];
		$smarty->assign("JS_REC",$smarty->fetch("js_search_recomend.htm"));
	}
}
if(!$db)
        $db=connect_db();
?>
